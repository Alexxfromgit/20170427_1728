<?php  
class ControllerModuleSpcallmeback extends Controller {
	protected function index($setting) {
		$this->language->load('module/spcallmeback');
		
        $this->load->model('spcallmeback/form');
                
        $this->load->model('spcommon/language');
                
        $form = $this->model_spcallmeback_form->create();
                        
        $this->data = array_merge($this->data, $this->model_spcommon_language->getByIds(array('heading_title',
        	'text_manual_button_position', 'text_anonym')));
                
        $this->document->addScript('catalog/view/javascript/module-spcallmeback.js');
        $this->document->addScript('catalog/view/javascript/zebra_form/zebra_form.src.js');
        /*$this->document->addScript('catalog/view/javascript/fancybox/jquery.mousewheel-3.0.4.pack.js');*/
        $this->document->addScript('catalog/view/javascript/fancybox/jquery.fancybox-1.3.4.js');
        $this->document->addStyle('catalog/view/javascript/fancybox/jquery.fancybox-1.3.4.css');

        
    	//$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
    	
		//$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        
        $this->data['form'] = html_entity_decode($form/*$this->config->get('google_talk_code')*/);

        $this->data['button_position'] = $this->model_spcallmeback_form->getFieldName('button_position');
        if ($this->data['button_position'] == 1)
          $this->data['btn_class'] = 'spcallmeback_sidebutton spcallmeback_sidebutton_left';
        else if ($this->data['button_position'] == 2)
          $this->data['btn_class'] = 'spcallmeback_sidebutton spcallmeback_sidebutton_right';
        else
          $this->data['btn_class'] = 'spcallmeback_sidebutton_inside';
                
        $this->data['button_caption'] = $this->model_spcallmeback_form->getFieldName('button_caption');
        $this->data['button_color'] = $this->model_spcallmeback_form->getFieldName('button_color');
        $this->data['button_background'] = $this->model_spcallmeback_form->getFieldName('button_background');
        
        $this->data['form_caption'] = html_entity_decode($this->model_spcallmeback_form->getFieldName('form_caption'));
        $this->data['form_subcaption'] = nl2br(html_entity_decode($this->model_spcallmeback_form->getFieldName('form_subcaption')));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/spcallmeback.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/spcallmeback.tpl';
		} else {
			$this->template = 'default/template/module/spcallmeback.tpl';
		}

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').'/stylesheet/zebra_form.css')) {
            $this->document->addStyle('catalog/view/theme/'.
                $this->config->get('config_template').'/stylesheet/zebra_form.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/zebra_form.css');
        }      

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').'/stylesheet/module-spcallmeback.css')) {
            $this->document->addStyle('catalog/view/theme/'.
                $this->config->get('config_template').'/stylesheet/module-spcallmeback.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/module-spcallmeback.css');
        }      
		
		$this->render();
	}
}
?>