<?php
class shopSitemapItemsModel {
	protected waModel $model;

	protected array $categoryItems;
	protected array $menuItems;

	public function __construct () {
		$this->model = new waModel();

		$this->categoryItems = $this->model->query ('
			SELECT * FROM shop_category WHERE status = 1 ORDER BY left_key ASC
		')->fetchAll ();

		$this->menuItems = $this->model->query ('
			SELECT
				mi.left_key, mi.status, mi.type, mi.id, mi.parent_id, mi.depth, mi.name name, mip.name param_name, mip.value frontend_url
			FROM menu_item mi JOIN menu_item_params mip ON mi.id = mip.item_id OR mi.type != "link" HAVING mip.name = "url" AND mi.status = "1" ORDER BY left_key ASC
		')->fetchAll ();

		$this->menuItems = $this->uniqueMultidimArray ($this->menuItems, 'id');
	}

	public function getAll (): array {
		return array_merge (
			$this->prepareMenuRecursive (0),
			$this->prepareCategoriesRecursive (0)
		);
	}

	/**
	 * Create multidimensional array unique for any single key index.
	 * e.g I want to create multi dimentional unique array for specific code.
	 * You can make it unique for any field like id, name or num. 
	 */
	public function uniqueMultidimArray(array $array, string $key): array {
		$temp_array = array();
		$i = 0;
		$key_array = array();
	   
		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	}

	/**
	 * Осторожно! Рекурсия!
	 */
	protected function prepareCategoriesRecursive (int $id): array {
		$items = [];

		foreach ($this->categoryItems as $cat) {
			if ((int)$cat['parent_id'] === $id) {
				$items[] = $this->setItemValues ([
					'name' => $cat['name'],

					'depth' => $cat['depth'],
					'meta_title' => $cat['meta_title'],
					'meta_keywords' => $cat['meta_keywords'],
					'meta_description' => $cat['meta_description'],

					'frontend_url' => wa()->getRouting()->getUrl('shop/frontend/category', array('category_url' => $cat['full_url'])),

					'is_link' => true,

					'childs' => $this->prepareCategoriesRecursive ($cat['id']),
				]);
			}
		}
		return $items;
	}

	/**
	 * Осторожно! Рекурсия!
	 */
	protected function prepareMenuRecursive (int $id): array {
		$items = [];

		foreach ($this->menuItems as $cat) {
			if ((int)$cat['parent_id'] === $id) {
				$items[] = $this->setItemValues ([
					'name' => $cat['name'],

					'depth' => $cat['depth'],

					'meta_title' => '',
					'meta_keywords' => '',
					'meta_description' => '',

					'frontend_url' => $cat['frontend_url'],

					'is_link' => $cat['type'] === 'link',

					'childs' => $this->prepareMenuRecursive ($cat['id']),
				]);
			}
		}
		return $items;
	}

	protected function setItemValues (array $params): array {
		return [
			'name' => $params['name'],

			'depth' => $params['depth'],

			'meta_title' => $params['meta_title'],
			'meta_keywords' => $params['meta_keywords'],
			'meta_description' => $params['meta_description'],

			'frontend_url' => $params['frontend_url'],

			'is_link' => $params['is_link'],

			'childs' => $params['childs'],
		];
	}
}