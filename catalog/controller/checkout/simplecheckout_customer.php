<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutCustomer extends Controller {
    static $error = array();
    static $updated = false;

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('customer');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->data['text_checkout_customer']       = $this->language->get('text_checkout_customer');
        $this->data['text_checkout_customer_login'] = $this->language->get('text_checkout_customer_login');
        $this->data['text_you_will_be_registered']  = $this->language->get('text_you_will_be_registered');
        $this->data['text_account_created']         = $this->language->get('text_account_created');
        $this->data['entry_address_same']           = $this->language->get('entry_address_same');
        
        $this->data['display_login']               = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayLogin');
        $this->data['display_registered']          = !empty($this->session->data['simple']['registered']) ? true : false;
        
        $this->data['rows'] = $this->simplecheckout->getRows();

        $this->validate();

        unset($this->session->data['simple']['registered']);

        $this->data['display_header']              = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['display_you_will_registered'] = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayYouWillRegistered') && $this->simplecheckout->registerCustomer() && !$this->simplecheckout->isFieldUsed('customer', 'register');
        $this->data['display_error']               = $this->simplecheckout->displayError();
        $this->data['has_error']                   = $this->simplecheckout->hasError();
        $this->data['hide']                        = $this->simplecheckout->isBlockHidden();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_customer.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_customer.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_customer.tpl';
        }
        
        $this->response->setOutput($this->render());         
        $this->simplecheckout->resetCurrentBlock();
    }

    public function update() {
        self::$updated = true;

        $this->init();

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }

    private function validate() {
        $error = false;

        if (!$this->simplecheckout->validateFields()) {
            $error = true;
        }
        
        if ($error) {
            $this->simplecheckout->addError();
        }

        self::$error = $error;
        
        return !$error;
    }
}
?>