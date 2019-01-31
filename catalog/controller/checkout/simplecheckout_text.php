<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutText extends Controller { 
    public function index($args = null) {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $type = !empty($args['type']) ? $args['type'] : 'text';
        
        $this->simplecheckout->setCurrentBlock($type);

        $this->load->model('catalog/information');

        $this->data['text_id']      = !empty($args['id']) ? $args['id'] : (!empty($this->request->get['id']) ? $this->request->get['id'] : 0);
        $this->data['text_title']   = '';
        $this->data['text_content'] = '';
        $this->data['text_type']    = $type;

        $this->data['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
      
        $information = $this->model_catalog_information->getInformation($this->data['text_id']);
        
        if ($information) {
            $this->data['text_title'] = $information['title'];
            $this->data['text_content'] = html_entity_decode($information['description'], ENT_QUOTES, 'UTF-8');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_text.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_text.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_text.tpl';
        }

        $this->response->setOutput($this->render());
        $this->simplecheckout->resetCurrentBlock(); 
    }
}


?>