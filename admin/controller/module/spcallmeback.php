<?php
class ControllerModuleSpcallmeback extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load->language('module/spcallmeback');
        $this->load->model('spcommon/language');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

        if (isset($this->request->post['button_caption'])) {
            foreach(array('name_required', 'phone_required', 'time_required', 'email_required', 'comments_required', 'additional1_required', 'additional2_required', 'use_captcha') as $field)
            {
                $this->request->post[$field] = $_REQUEST[$field] = $_POST[$field] = isset($this->request->post[$field]) ? 'on' : 'off';
            }
        }
		
		$reset = isset($this->request->post['reset']) && $this->request->post['reset'] == 1;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if ($reset)
				$this->request->post = array();
			$this->model_setting_setting->editSetting('spcallmeback', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (!$reset && $this->request->post['close'])
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if ($reset)
			$this->session->data['success'] = $this->language->get('settings_reset');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
        
        $this->data['button_positions'] = array(array('id' => 1, 'name' => $this->language->get('text_button_position1')),
            array('id' => 2, 'name' => $this->language->get('text_button_position2')),
            array('id' => 3, 'name' => $this->language->get('text_button_position3')),
            array('id' => 4, 'name' => $this->language->get('text_button_position4')),
        );
		
        // вытащим из языкового ресурса
        $this->data = array_merge($this->data, $this->model_spcommon_language->getByIds(
            array('heading_title', 'text_name_name', 'text_name_phone', 'text_name_comments', 
            'text_button_position', 'text_button_caption', 'text_form_caption', 'text_form_subcaption', 'text_error_missing_value',
            'text_name_email', 'text_name_time', 'text_time_from_label', 'text_time_to_label',
            'text_form_button_caption', 'text_name_additional', 'text_email',
            'text_order', 'text_required', 'text_button_color', 'text_button_background', 
            'text_form_fields', 'text_hiding_tips', 'text_name_use_captcha'
            )));
            
            
        
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_reset'] = $this->language->get('button_reset');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
        // поля, для которых есть ошибки
        foreach(array('warning', 'name_name', 'name_phone', 'button_caption', 'form_caption', 'form_subcaption', 'form_button_caption', 'button_position', 
            'button_color', 'button_background', 'error_missing_value',
            'name_additional1', 'name_additional2', 'name_email', 'email', 'name_time', 'time_order',
            'name_order', 'phone_order', 'email_order', 'comments_order', 'additional1_order', 'additional2_order', 'name_comments') as $field)
        {
             if (isset($this->error[$field]) && !$reset) {
                $this->data['error_'.$field] = $this->error[$field];
            } else {
                $this->data['error_'.$field] = '';
            }
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
			'href'      => $this->url->link('module/spcallmeback', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		
   		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
   		}

   		if ($this->error && !$reset) {
   			if (isset($this->error['warning']))
				$this->data['error_warning'] = $this->error['warning'];
			else
				$this->data['error_warning'] = $this->language->get('error_warning');
   		}
		
		$this->data['action'] = $this->url->link('module/spcallmeback', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
        // поля - в форму
        foreach(array('name_name', 'name_phone', 'name_time', 'time_from_label', 'time_to_label', 'name_email', 'name_comments', 'button_caption', 'form_caption', 'form_subcaption', 'button_position', 
            'button_color', 'button_background', 'error_missing_value',
            'form_button_caption', 'name_additional1', 'name_additional2', 'email') as $field)
        {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } else {
                $this->data[$field] = $this->config->get($field) && !$reset ? $this->config->get($field) : 
                    $this->language->get($field.'_default');
            }
        }
        foreach(array('name_order', 'phone_order', 'time_order', 'email_order', 'comments_order', 'additional1_order', 'additional2_order') as $field)
        {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } else {
                $this->data[$field] = $this->config->get($field) !== NULL && !$reset ? $this->config->get($field) : (strpos($field, 'additional') === false ? 1 : 0); 
            }
        }
        
        $index = 1;
        foreach(array('name_required', 'phone_required', 'time_required', 'email_required', 'comments_required', 'additional1_required', 'additional2_required', 'use_captcha') as $field)
        {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } else {
                $this->data[$field] = $this->config->get($field) !== NULL  && !$reset ? $this->config->get($field) : ($index < 3 ? 'on' : 'off');
            }
            $index++;
        }
		
		//echo '';var_dump($this->data['use_captcha']);
		
		$this->data['token'] = $this->session->data['token'];

		$this->data['modules'] = array();
		
		if (isset($this->request->post['spcallmeback_module'])) {
			$this->data['modules'] = $this->request->post['spcallmeback_module'];
		} elseif ($this->config->get('spcallmeback_module')) { 
			$this->data['modules'] = $this->config->get('spcallmeback_module');
		}	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/spcallmeback.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
        if (file_exists(DIR_APPLICATION.'view/stylesheet/admin-spcallmeback.css')) {
            $this->document->addStyle('view/stylesheet/admin-spcallmeback.css');
        }
    			
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/spcallmeback')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
        foreach(array('name_name', 'name_phone', 'name_time', 'name_comments', 'button_caption', 'form_caption', 'form_subcaption', 'form_button_caption',
            'button_color', 'button_background', 'error_missing_value',
            'name_additional1', 'name_additional2', 'name_email', 'email',
            'name_order', 'phone_order', 'comments_order', 'additional1_order', 'additional2_order') as $field)
        {
		    if (trim($this->request->post[$field]) == '') {
			    $this->error[$field] = $this->language->get('error_missing_value');
		    }
        }

        foreach(array('name_order', 'phone_order', 'time_order', 'email_order', 'comments_order', 'additional1_order', 'additional2_order') as $field)
        {
            if (!ctype_digit ($this->request->post[$field])) {
                $this->error[$field] = $this->language->get('error_number_required');
            }
        }
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>