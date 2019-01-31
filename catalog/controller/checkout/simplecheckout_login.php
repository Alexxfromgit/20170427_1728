<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutLogin extends Controller {
    public function index() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('login');

        if ($this->customer->isLogged()) {
            if ($this->simplecheckout->isAjaxRequest()) {
                return;
            } else {
                $this->redirect($this->url->link('checkout/simplecheckout','','SSL'));
                return;
            }
        }

        $this->language->load('checkout/simplecheckout');
        
        $this->data['error_login'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!empty($this->request->post['email']) && !empty($this->request->post['password']) && $this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                unset($this->session->data['guest']);
            } else {
                $this->data['error_login'] = $this->language->get('error_login');
                $this->simplecheckout->addError();
            }
        }
        
        $this->data['text_checkout_customer']        = $this->language->get('text_checkout_customer');
        $this->data['text_checkout_customer_login']  = $this->language->get('text_checkout_customer_login');
        $this->data['text_checkout_customer_cancel'] = $this->language->get('text_checkout_customer_cancel');
        $this->data['text_forgotten']                = $this->language->get('text_forgotten');
        $this->data['entry_email']                   = $this->language->get('entry_email');
        $this->data['entry_password']                = $this->language->get('entry_password');
        $this->data['button_login']                  = $this->language->get('button_login');

        $this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
        
        if (isset($this->request->post['email'])) {
            $this->data['email'] = trim($this->request->post['email']);
        } else {
            $this->data['email'] = '';
        }

        $this->data['additional_path'] = $this->simplecheckout->getAdditionalPath();
        $this->data['has_error'] = $this->simplecheckout->hasError();
          
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_login.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_login.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_login.tpl';
        }
            
        $this->response->setOutput($this->render());
    }
}
?>