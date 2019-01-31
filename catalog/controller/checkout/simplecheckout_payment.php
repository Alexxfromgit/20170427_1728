<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutPayment extends Controller {
    static $updated = false;

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('payment');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $address = $this->simplecheckout->getPaymentAddress();

        $this->data['address_empty'] = $this->simplecheckout->isPaymentAddressEmpty();

        $total_data = array();                    
        $total = 0;
        $taxes = $this->cart->getTaxes();
        
        $this->load->model('setting/extension');
        
        $sort_order = array(); 
        
        $results = $this->model_setting_extension->getExtensions('total');
        
        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }
        
        array_multisort($sort_order, SORT_ASC, $results);
        
        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);
                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $method_data = array();

        if ($stubs = $this->simplecheckout->getPaymentStubs()) {
            foreach ($stubs as $stub) {
                $method_data[$stub['code']] = $stub;
            }
        }

        $version = $this->simplecheckout->getOpencartVersion();

        $cartHasReccuringProducts = 0;

        if ($version >= 156) {
            $cartHasReccuringProducts = $this->cart->hasRecurringProducts();
        }
        
        $this->load->model('setting/extension');
        
        $results = $this->model_setting_extension->getExtensions('payment');

        foreach ($results as $result) {    
            $display = true;
            if ($this->data['address_empty']) {
                $display = $this->simplecheckout->displayPaymentMethodForEmptyAddress($result['code']);
            }

            if ($this->config->get($result['code'] . '_status') && $display) {
                $this->load->model('payment/' . $result['code']);
                
                $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $total); 
                
                if ($method) {
                    if (!$cartHasReccuringProducts || ($cartHasReccuringProducts > 0 && method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments() == true)) {
                        if (!empty($method['quote']) && is_array($method['quote'])) {
                            foreach ($method['quote'] as $quote) {
                                $this->simplecheckout->exportPaymentMethod($quote);
                                $quote = $this->simplecheckout->preparePaymentMethod($quote);
                                if (!empty($quote)) {
                                    $method_data[$quote['code']] = $quote;
                                }
                            }
                        } else {
                            $this->simplecheckout->exportPaymentMethod($method);
                            $method = $this->simplecheckout->preparePaymentMethod($method);
                            if (!empty($method)) {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }
            }
        }

        $sort_order = array();
      
        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);
        
        $this->data['payment_methods']   = $method_data;
        $this->data['payment_method']    = null;
        $this->data['error_payment']     = $this->language->get('error_payment');
        $this->data['has_error_payment'] = false;
            
        $this->data['code'] = '';
        $this->data['checked_code'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['payment_method_checked']) && !empty($this->data['payment_methods'][$this->request->post['payment_method_checked']]) && empty($this->data['payment_methods'][$this->request->post['payment_method_checked']]['dummy'])) {
            $this->data['checked_code'] = $this->request->post['payment_method_checked'];
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['payment_method']) && !empty($this->data['payment_methods'][$this->request->post['payment_method']]) && empty($this->data['payment_methods'][$this->request->post['payment_method_checked']]['dummy'])) {
            $this->data['payment_method'] = $this->data['payment_methods'][$this->request->post['payment_method']];
            
            if (isset($this->request->post['payment_method_current']) && $this->request->post['payment_method_current'] != $this->request->post['payment_method']) {
                $this->data['checked_code'] = $this->request->post['payment_method'];
            }
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['payment_method'])) { 
            $user_checked = false;
            if (!empty($this->session->data['payment_method']['code'])) {
                $payment_code = $this->session->data['payment_method']['code'];
                $user_checked = true;
            }
            
            if (isset($this->data['payment_methods'][$payment_code]) && empty($this->data['payment_methods'][$payment_code]['dummy'])) {
                $this->data['payment_method'] = $this->data['payment_methods'][$payment_code];
                if ($user_checked) {
                    $this->data['checked_code'] = $this->session->data['payment_method']['code'];
                }
            }
        }

        $selectFirst = $this->simplecheckout->getSettingValue('selectFirst');
        $hide = $this->simplecheckout->isBlockHidden();
        
        if ($hide) {
            $selectFirst = true;
        }
        
        if (!empty($this->data['payment_methods']) && ($hide || ($selectFirst && $this->data['checked_code'] == ''))) {
            foreach ($this->data['payment_methods'] as $method) {
                if (empty($method['dummy'])) {
                    $this->data['payment_method'] = $method;
                    break;
                }
            }
            
        }
        
        if ($this->validate()) {
            $this->simplecheckout->setPaymentMethod($this->data['payment_method']);
            $this->data['code'] = $this->data['payment_method']['code'];
        }

        $this->data['rows'] = $this->simplecheckout->getRows();

        $this->validateFields();
        
        $this->saveToSession();

        $this->data['display_header']        = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['display_error']         = $this->simplecheckout->displayError();
        $this->data['display_address_empty'] = $this->simplecheckout->getSettingValue('displayAddressEmpty');
        $this->data['has_error']             = $this->simplecheckout->hasError();
        $this->data['hide']                  = $this->simplecheckout->isBlockHidden();
        
        $this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $this->data['text_payment_address']         = $this->language->get('text_payment_address');
        $this->data['error_no_payment']             = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
                        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_payment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_payment.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_payment.tpl';
        }

        $this->response->setOutput($this->render());     
        $this->simplecheckout->resetCurrentBlock();   
    }

    public function update() {
        self::$updated = true;

        $this->init();

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }
    
    private function saveToSession() {
        $this->session->data['payment_methods'] = $this->data['payment_methods'];
        $this->session->data['payment_method'] = $this->data['payment_method'];
        
        if (empty($this->session->data['payment_methods'])) {
            unset($this->session->data['payment_method']);
        }
    }
    
    private function validate() {
        $error = false;
        
        if (empty($this->data['payment_method']['code'])) {
            $this->data['has_error_payment'] = true;
            $error = true;
        } 

        if ($error) {
            $this->simplecheckout->addError();
        }
        
    	return !$error;
    }

    private function validateFields() {
        $error = false;
        
        if (!$this->simplecheckout->validateFields()) {
            $error = true;
        }
        
        if ($error) {
            $this->simplecheckout->addError();
        }
        
        return !$error;
    }
}
