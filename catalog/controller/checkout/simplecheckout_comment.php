<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutComment extends Controller { 
    public function index() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('comment');

        $label       = '';
        $placeholder = '';
        $comment     = '';

        $tmp = $this->simplecheckout->getSettingValue('label');
        if (!empty($tmp[$this->simplecheckout->getCurrentLanguageCode()])) {
            $label = $tmp[$this->simplecheckout->getCurrentLanguageCode()];
        }

        $tmp = $this->simplecheckout->getSettingValue('placeholder');
        if (!empty($tmp[$this->simplecheckout->getCurrentLanguageCode()])) {
            $placeholder = $tmp[$this->simplecheckout->getCurrentLanguageCode()];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $comment = !empty($this->request->post['comment']) ? $this->request->post['comment'] : '';
            $this->session->data['simple']['comment'] = $comment;
        } elseif (!empty($this->session->data['simple']['comment'])) {
            $comment = $this->session->data['simple']['comment'];
        }

        $this->data['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['label'] = $label;
        $this->data['placeholder'] = $placeholder;
        $this->data['comment'] = $comment;

        $this->simplecheckout->setComment($comment);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_comment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_comment.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_comment.tpl';
        }

        $this->response->setOutput($this->render());
        $this->simplecheckout->resetCurrentBlock(); 
    }
}


?>