<?php
// News Module for Opencart v1.5.5, modified by villagedefrance (contact@villagedefrance.net)

class ControllerModuleNews extends Controller {
	private $error = array();
	private $_name = 'news';
	private $_version = '1.5.5'; 

	public function index() {
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->data[$this->_name . '_version'] = $this->_version;
	
		$this->load->model('catalog/news');
		
		$this->model_catalog_news->checkNews();
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('setting/setting');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting($this->_name, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if ($this->request->post['buttonForm'] == 'apply') {
				$this->redirect($this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
	
		$this->getModule();
	}

	public function insert() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_news->addNews($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getForm();
	}

	public function update() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_news->editNews($this->request->get['news_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getForm();
	}
	
	public function delete() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $news_id) {
				$this->model_catalog_news->deleteNews($news_id);
			}
		
			$this->session->data['success'] = $this->language->get('text_success');
		
			$this->redirect($this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getList();
	}

	public function listing() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->getList();
	}

	private function getModule() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_module_settings'] = $this->language->get('text_module_settings');
		$this->data['text_chars'] = $this->language->get('text_chars');
	
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
	
		$this->data['entry_customtitle'] = $this->language->get('entry_customtitle');
		$this->data['entry_header'] = $this->language->get('entry_header'); 
		$this->data['entry_icon'] = $this->language->get('entry_icon');
		$this->data['entry_box'] = $this->language->get('entry_box');
	
		$this->data['entry_template'] = $this->language->get('entry_template');
	
		$this->data['entry_headline_module'] = $this->language->get('entry_headline_module');
		$this->data['entry_newspage_thumb'] = $this->language->get('entry_newspage_thumb');
		$this->data['entry_newspage_popup'] = $this->language->get('entry_newspage_popup');
		$this->data['entry_newspage_addthis'] = $this->language->get('entry_newspage_addthis');
		$this->data['entry_headline_chars'] = $this->language->get('entry_headline_chars');
	
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_headline'] = $this->language->get('entry_headline');
		$this->data['entry_numchars'] = $this->language->get('entry_numchars');	
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
	
		$this->data['button_news'] = $this->language->get('button_news');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
	
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
 		if (isset($this->error['numchars'])) {
			$this->data['error_numchars'] = $this->error['numchars'];
		} else {
			$this->data['error_numchars'] = '';
		}
	
		if (isset($this->error['newspage_thumb'])) {
			$this->data['error_newspage_thumb'] = $this->error['newspage_thumb'];
		} else {
			$this->data['error_newspage_thumb'] = '';
		}
	
		if (isset($this->error['newspage_popup'])) {
			$this->data['error_newspage_popup'] = $this->error['newspage_popup'];
		} else {
			$this->data['error_newspage_popup'] = '';
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
			'href'      => $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
	
		$this->data['news'] = $this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL');
	
		$this->data['action'] = $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL');
	
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
	
		$this->data['templates'] = array();
	
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
	
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
	
		if (isset($this->request->post['config_template'])) {
			$this->data['config_template'] = $this->request->post['config_template'];
		} else {
			$this->data['config_template'] = $this->config->get('config_template');			
		}
	
		$this->load->model('localisation/language');
	
		$languages = $this->model_localisation_language->getLanguages();
	
		foreach ($languages as $language) {
			if (isset($this->request->post[$this->_name . '_customtitle' . $language['language_id']])) {
				$this->data[$this->_name . '_customtitle' . $language['language_id']] = $this->request->post[$this->_name . '_customtitle' . $language['language_id']];
			} else {
				$this->data[$this->_name . '_customtitle' . $language['language_id']] = $this->config->get($this->_name . '_customtitle' . $language['language_id']);
			}
		}
	
		$this->data['languages'] = $languages;
	
		if (isset($this->request->post[$this->_name . '_header'])) { 
			$this->data[$this->_name . '_header'] = $this->request->post[$this->_name . '_header']; 
		} else { 
			$this->data[$this->_name . '_header'] = $this->config->get($this->_name . '_header'); 
		}
		if (isset($this->request->post[$this->_name . '_icon'])) { 
			$this->data[$this->_name . '_icon'] = $this->request->post[$this->_name . '_icon']; 
		} else { 
			$this->data[$this->_name . '_icon'] = $this->config->get($this->_name . '_icon'); 
		}
		if (isset($this->request->post[$this->_name . '_box'])) { 
			$this->data[$this->_name . '_box'] = $this->request->post[$this->_name . '_box']; 
		} else { 
			$this->data[$this->_name . '_box'] = $this->config->get($this->_name . '_box'); 
		}
	
		if (isset($this->request->post[$this->_name . '_template'])) {
			$this->data[$this->_name . '_template'] = $this->request->post[$this->_name . '_template'];
		} else {
			$this->data[$this->_name . '_template'] = $this->config->get($this->_name . '_template');
		}
	
		if (isset($this->request->post[$this->_name . '_headline_module'])) {
			$this->data[$this->_name . '_headline_module'] = $this->request->post[$this->_name . '_headline_module'];
		} else {
			$this->data[$this->_name . '_headline_module'] = $this->config->get($this->_name . '_headline_module');
		}
	
		if (isset($this->request->post[$this->_name . '_thumb_width'])) {
			$this->data[$this->_name . '_thumb_width'] = $this->request->post[$this->_name . '_thumb_width'];
		} else {
			$this->data[$this->_name . '_thumb_width'] = $this->config->get($this->_name . '_thumb_width');
		}
		if (isset($this->request->post[$this->_name . '_thumb_height'])) {
			$this->data[$this->_name . '_thumb_height'] = $this->request->post[$this->_name . '_thumb_height'];
		} else {
			$this->data[$this->_name . '_thumb_height'] = $this->config->get($this->_name . '_thumb_height');
		}
		if (isset($this->request->post[$this->_name . '_popup_width'])) {
			$this->data[$this->_name . '_popup_width'] = $this->request->post[$this->_name . '_popup_width'];
		} else {
			$this->data[$this->_name . '_popup_width'] = $this->config->get($this->_name . '_popup_width');
		}
		if (isset($this->request->post[$this->_name . '_popup_height'])) {
			$this->data[$this->_name . '_popup_height'] = $this->request->post[$this->_name . '_popup_height'];
		} else {
			$this->data[$this->_name . '_popup_height'] = $this->config->get($this->_name . '_popup_height');
		}
		if (isset($this->request->post[$this->_name . '_newspage_addthis'])) {
			$this->data[$this->_name . '_newspage_addthis'] = $this->request->post[$this->_name . '_newspage_addthis'];
		} else {
			$this->data[$this->_name . '_newspage_addthis'] = $this->config->get($this->_name . '_newspage_addthis');
		}
	
		if (isset($this->request->post[$this->_name . '_headline_chars'])) {
			$this->data[$this->_name . '_headline_chars'] = $this->request->post[$this->_name . '_headline_chars'];
		} else {
			$this->data[$this->_name . '_headline_chars'] = $this->config->get($this->_name . '_headline_chars');
		}
	
		$this->data['modules'] = array();
	
		if (isset($this->request->post[$this->_name . '_module'])) {
			$this->data['modules'] = $this->request->post[$this->_name . '_module'];
		} elseif ($this->config->get($this->_name . '_module')) { 
			$this->data['modules'] = $this->config->get($this->_name . '_module');
		}
	
		$this->load->model('design/layout');
	
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
	
		$this->template = 'module/news.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function getList() {
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_viewed'] = $this->language->get('column_viewed');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
	
		$this->data['button_module'] = $this->language->get('button_module');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
	
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		$this->data['module'] = $this->url->link('module/news', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('module/news/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('module/news/delete', 'token=' . $this->session->data['token'], 'SSL');
	
		$this->data['totalnews'] = $this->model_catalog_news->getTotalNews();
	
		$this->load->model('tool/image');
	
		$this->data['news'] = array();
	
		$results = $this->model_catalog_news->getNews();
	
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/news/update', 'token=' . $this->session->data['token'] . '&news_id=' . $result['news_id'], 'SSL')
			);
		
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
		
			$this->data['news'][] = array(
				'news_id'     	=> $result['news_id'],
				'title'       		=> $result['title'],
				'image'      		=> $image,
				'date_added'  	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'viewed'			=> $result['viewed'],
				'status'     		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['news_id'], $this->request->post['selected']),
				'action'      		=> $action
			);
		}
	
		$this->template = 'module/news/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function getForm() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/news');
	
		$this->data['heading_title'] = $this->language->get('heading_title');
	
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
	
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
	
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_status'] = $this->language->get('entry_status');
	
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
	
		$this->data['tab_language'] = $this->language->get('tab_language');
		$this->data['tab_setting'] = $this->language->get('tab_setting');
	
		$this->data['token'] = $this->session->data['token'];
	
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
	
		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		if (!isset($this->request->get['news_id'])) {
			$this->data['action'] = $this->url->link('module/news/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/news/update', 'token=' . $this->session->data['token'] . '&news_id=' . $this->request->get['news_id'], 'SSL');
		}
	
		$this->data['cancel'] = $this->url->link('module/news/listing', 'token=' . $this->session->data['token'], 'SSL');
	
		if ((isset($this->request->get['news_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$news_info = $this->model_catalog_news->getNewsStory($this->request->get['news_id']);
		}
		$this->load->model('localisation/language');
	
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['news_description'])) {
			$this->data['news_description'] = $this->request->post['news_description'];
		} elseif (isset($this->request->get['news_id'])) {
			$this->data['news_description'] = $this->model_catalog_news->getNewsDescriptions($this->request->get['news_id']);
		} else {
			$this->data['news_description'] = array();
		}
		
		if (isset($this->request->post['meta_keyword'])) {
			$this->data['meta_keyword'] = $this->request->post['meta_keyword'];
		} elseif (isset($this->request->get['news_id'])) {
			$this->data['meta_keyword'] = $this->model_catalog_news->getNewsDescriptions($this->request->get['news_id']);
		} else {
			$this->data['meta_keyword'] = array();
		}

		if (isset($this->request->post['date_added'])) {
       		$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($news_info['date_added'])) {
			$this->data['date_added'] = $news_info['date_added'];
		} else {
			$this->data['date_added'] = date('Y-m-d', time() - 86400 % 7);
		}
	
		$this->load->model('setting/store');
	
		$this->data['stores'] = $this->model_setting_store->getStores();
	
		if (isset($this->request->post['news_store'])) {
			$this->data['news_store'] = $this->request->post['news_store'];
		} elseif (isset($news_info)) {
			$this->data['news_store'] = $this->model_catalog_news->getNewsStores($this->request->get['news_id']);
		} else {
			$this->data['news_store'] = array(0);
		}
	
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($news_info)) {
			$this->data['keyword'] = $news_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($news_info)) {
			$this->data['status'] = $news_info['status'];
		} else {
			$this->data['status'] = '';
		}
	
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($news_info)) {
			$this->data['image'] = $news_info['image'];
		} else {
			$this->data['image'] = '';
		}
	
		$this->load->model('tool/image');
	
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($news_info) && $news_info['image'] && file_exists(DIR_IMAGE . $news_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
	
		$this->template = 'module/news/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->request->post['news_headline_chars']) {
			$this->error['numchars'] = $this->language->get('error_numchars');
		}
	
		if (!$this->request->post['news_thumb_width'] || !$this->request->post['news_thumb_height']) {
			$this->error['newspage_thumb'] = $this->language->get('error_newspage_thumb');
		}
	
		if (!$this->request->post['news_popup_width'] || !$this->request->post['news_popup_height']) {
			$this->error['newspage_popup'] = $this->language->get('error_newspage_popup');
		}
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		foreach ($this->request->post['news_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 250)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function install() { 
		//create news table
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news` (`news_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image_size` int(1) NOT NULL default '0', `date_added` date default NULL, `viewed` int(5) NOT NULL DEFAULT '0', PRIMARY KEY (`news_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		//create news description table
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news_description`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_description` (`news_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`news_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		//create news store table
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news_to_store`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_to_store` (`news_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`news_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

	public function uninstall() { 
		$this->cache->delete('news');
	
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news_description`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "news_to_store`");
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` LIKE 'news_id=%'");
    }
}
?>