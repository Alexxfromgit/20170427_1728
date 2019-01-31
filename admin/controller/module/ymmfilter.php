<?php
class ControllerModuleYmmFilter extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->data = array_merge($this->data, $this->load->language('module/ymmfilter'));

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ymmfilter', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['text_enabled']      = $this->language->get('text_enabled');
		$this->data['text_disabled']     = $this->language->get('text_disabled');
		
		$this->data['button_save']       = $this->language->get('button_save');
		$this->data['button_cancel']     = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove']     = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ymmfilter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/ymmfilter', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['ymmfilter_module'])) {
			$this->data['modules'] = $this->request->post['ymmfilter_module'];
		} elseif ($this->config->get('ymmfilter_module')) { 
			$this->data['modules'] = $this->config->get('ymmfilter_module');
		}
		
		if (isset($this->request->post['ymmfilter_remember'])){
			$this->data['ymmfilter_remember'] = $this->request->post['ymmfilter_remember'];
		} elseif ($this->config->get('ymmfilter_remember')){
			$this->data['ymmfilter_remember'] = $this->config->get('ymmfilter_remember');
		} else {
			$this->data['ymmfilter_remember'] = '';
		}

		if (isset($this->request->post['ymmfilter_model'])){
			$this->data['ymmfilter_model'] = $this->request->post['ymmfilter_model'];
		} elseif ($this->config->get('ymmfilter_model')){
			$this->data['ymmfilter_model'] = $this->config->get('ymmfilter_model');
		} else {
			$this->data['ymmfilter_model'] = '';
		}

		if (isset($this->request->post['ymmfilter_engine'])){
			$this->data['ymmfilter_engine'] = $this->request->post['ymmfilter_engine'];
		} elseif ($this->config->get('ymmfilter_engine')){
			$this->data['ymmfilter_engine'] = $this->config->get('ymmfilter_engine');
		} else {
			$this->data['ymmfilter_engine'] = '';
		}

		if (isset($this->request->post['ymmfilter_year'])){
			$this->data['ymmfilter_year'] = $this->request->post['ymmfilter_year'];
		} elseif ($this->config->get('ymmfilter_year')){
			$this->data['ymmfilter_year'] = $this->config->get('ymmfilter_year');
		} else {
			$this->data['ymmfilter_year'] = '';
		}

		if (isset($this->request->post['ymmfilter_search_universal'])){
			$this->data['ymmfilter_search_universal'] = $this->request->post['ymmfilter_search_universal'];
		} elseif ($this->config->get('ymmfilter_search_universal')){
			$this->data['ymmfilter_search_universal'] = $this->config->get('ymmfilter_search_universal');
		} else {
			$this->data['ymmfilter_search_universal'] = '';
		}

		if (isset($this->request->post['ymmfilter_uni_default'])){
			$this->data['ymmfilter_uni_default'] = $this->request->post['ymmfilter_uni_default'];
		} elseif ($this->config->get('ymmfilter_uni_default')){
			$this->data['ymmfilter_uni_default'] = $this->config->get('ymmfilter_uni_default');
		} else {
			$this->data['ymmfilter_uni_default'] = '';
		}

		if (isset($this->request->post['ymmfilter_year_sort'])){
			$this->data['ymmfilter_year_sort'] = $this->request->post['ymmfilter_year_sort'];
		} elseif ($this->config->get('ymmfilter_year_sort')){
			$this->data['ymmfilter_year_sort'] = $this->config->get('ymmfilter_year_sort');
		} else {
			$this->data['ymmfilter_year_sort'] = 'asc';
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/ymmfilter.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ymmfilter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "make` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "engine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_ymm` (
  `product_id` int(11) NOT NULL,
  `make_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL DEFAULT '0',
  `begin_year` int(4) NOT NULL DEFAULT '0',
  `end_year` int(4) NOT NULL DEFAULT '0',
  `engine_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`make_id`,`model_id`,`begin_year`,`end_year`,`engine_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	
		if(!$this->columnExistsInTable('product', 'universal')) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD universal BOOLEAN DEFAULT 0");
		
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD INDEX (universal)");
		}
	}

	private function columnExistsInTable($table, $column) {
        $query = $this->db->query("DESC `" . DB_PREFIX . $table . "`;");
        foreach($query->rows as $row) {
            if($row['Field'] == $column) {
                return true;
            }
        }
        return false;
    }
}
?>