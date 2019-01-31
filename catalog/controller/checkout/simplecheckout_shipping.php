<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutShipping extends Controller {
    static $updated = false;

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('shipping');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $address = $this->simplecheckout->getShippingAddress();

        $this->data['address_empty'] = $this->simplecheckout->isShippingAddressEmpty();
        
        $quote_data = array();

        if ($stubs = $this->simplecheckout->getShippingStubs()) {
            foreach ($stubs as $stub) {
                $quote_data[$stub['code']] = $stub;
            }
        }
        
        $this->load->model('setting/extension');
        
        $results = $this->model_setting_extension->getExtensions('shipping');

        foreach ($results as $result) {            
            $display = true;
            if ($this->data['address_empty']) {
                $display = $this->simplecheckout->displayShippingMethodForEmptyAddress($result['code']);
            }

            if ($this->config->get($result['code'] . '_status') && $display) {
                $this->load->model('shipping/' . $result['code']);
                
                $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address); 
    
                if ($quote) {
                    $this->simplecheckout->exportShippingMethods($quote);
                    $quote = $this->simplecheckout->prepareShippingMethods($quote);
                    if (!empty($quote)) {
                        $quote_data[$result['code']] = $quote;
                    }
                }
            }
        }

        $sort_order = array();
      
        foreach ($quote_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $quote_data);
        
        $this->data['shipping_methods']   = $quote_data;
        $this->data['shipping_method']    = null;
        $this->data['error_shipping']     = $this->language->get('error_shipping');
        $this->data['has_error_shipping'] = false;

        $this->data['code'] = '';
        $this->data['checked_code'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['shipping_method_checked'])) {
            $shipping = explode('.', $this->request->post['shipping_method_checked']);
            
            if (isset($shipping[1]) && isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->data['checked_code'] = $this->request->post['shipping_method_checked'];
            }
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);
            
            if (isset($shipping[1]) && isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->data['shipping_method'] = $this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
               
                if (isset($this->request->post['shipping_method_current']) && $this->request->post['shipping_method_current'] != $this->request->post['shipping_method']) {
                    $this->data['checked_code'] = $this->request->post['shipping_method'];
                }
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['shipping_method'])) {
            $user_checked = false;
            if (isset($this->session->data['shipping_method'])) {
                $shipping = explode('.', $this->session->data['shipping_method']['code']);
                $user_checked = true;
            }
            
            if (isset($shipping[1]) && isset($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->data['shipping_method'] = $this->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                if ($user_checked) {
                    $this->data['checked_code'] = $this->session->data['shipping_method']['code'];
                }
            }
        }

        $selectFirst = $this->simplecheckout->getSettingValue('selectFirst');
        $hide = $this->simplecheckout->isBlockHidden();
        
        if ($hide) {
            $selectFirst = true;
        }
        
        if (!empty($this->data['shipping_methods']) && ($hide || ($selectFirst && $this->data['checked_code'] == ''))) {
            $first = false;
            foreach ($this->data['shipping_methods'] as $method) {
                if (!empty($method['quote'])) {
                    $first_method = reset($method['quote']);

                    if (!empty($first_method) && empty($first_method['dummy'])) {
                        $this->data['shipping_method'] = $first_method;
                        break;
                    }
                }
            }
        }
        
        if ($this->validate()) {
            $this->simplecheckout->setShippingMethod($this->data['shipping_method']);
            $this->data['code'] = $this->data['shipping_method']['code'];
        }
        
        $this->data['rows'] = $this->simplecheckout->getRows();

        $this->validateFields();

        $this->saveToSession();

        $this->data['display_header']        = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['display_error']         = $this->simplecheckout->displayError();
        $this->data['display_address_empty'] = $this->simplecheckout->getSettingValue('displayAddressEmpty');
        $this->data['has_error']             = $this->simplecheckout->hasError();
        $this->data['hide']                  = $this->simplecheckout->isBlockHidden();
        
        $this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $this->data['text_shipping_address']         = $this->language->get('text_shipping_address');
        $this->data['error_no_shipping']             = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
                        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_shipping.tpl';
        }

        $this->response->setOutput($this->render());        
        $this->simplecheckout->resetCurrentBlock();
    }

    public function update() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        self::$updated = true;

        $this->init();

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }
    
    private function saveToSession() {
        $this->session->data['shipping_methods'] = $this->data['shipping_methods'];
        $this->session->data['shipping_method'] = $this->data['shipping_method'];
        
        if (empty($this->session->data['shipping_methods'])) {
            unset($this->session->data['shipping_method']);
        }
    }
    
    private function validate() {
        $error = false;
        
        if (empty($this->data['shipping_method']['code'])) {
            $this->data['has_error_shipping'] = true;
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