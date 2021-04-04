<?php
class shopSitemapPluginFrontendSitemapAction extends shopFrontendAction {
	public function execute () {
		$model = new shopSitemapItemsModel ();

		$this->getResponse()->setTitle ('Карта сайта');
		$this->view->assign ('pages', $model->getAll ());
		$this->setThemeTemplate ('sitemap.html');
	}
}
