<?php
class shopSitemapItemsModel {
	protected $model;

	protected $categoryItems;
	protected $menuItems;

	public function __construct () {
		$this->model = new waModel();

		$this->categoryItems = $this->model->query ('
			SELECT * FROM shop_category WHERE status = 1 ORDER BY left_key ASC
		')->fetchAll ();

		$this->menuItems = $this->model->query ('
			SELECT
				mi.left_key,
				mi.status,
				mi.type,
				mi.id,
				mi.parent_id,
				mi.depth,
				mi.name name,
				mip.name param_name,
				mip.value frontend_url
			FROM menu_item mi
			JOIN menu_item_params mip ON mi.id = mip.item_id OR mi.type != "link"
			HAVING mip.name = "url" AND mi.status = "1"ORDER BY left_key ASC
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
	 * uniqueMultidimArray
	 * 
	 * Create multidimensional array unique for any single key index.
	 * e.g I want to create multi dimentional unique array for specific code.
	 * You can make it unique for any field like id, name or num. 
	 *
	 *
	 * @param  array $array
	 * @param  string $key
	 * @return array
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
	 * prepareCategoriesRecursive
	 *
	 * @param  int $id
	 * @return array
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
	 * prepareMenuRecursive
	 *
	 * @param  int $id
	 * @return array
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
		
	/**
	 * setItemValues
	 *
	 * @throws \Exception
	 * @param  array $params
	 * @return array
	 */
	protected function setItemValues (array $params): array {
		$result = [
			'name' => $params['name'] ?? null,

			'depth' => $params['depth'] ?? null,

			'meta_title' => $params['meta_title'] ?? null,
			'meta_keywords' => $params['meta_keywords'] ?? null,
			'meta_description' => $params['meta_description'] ?? null,

			'frontend_url' => $params['frontend_url'] ?? null,

			'is_link' => $params['is_link'] ?? null,

			'childs' => $params['childs'] ?? null,
		];

		foreach ($result as $key => $value) {
			if ($value === null) {
				throw new \Exception ("One of the elements was not passed to the array of elements: \"$key\" is required");
			}
		}

		return $result;
	}
}