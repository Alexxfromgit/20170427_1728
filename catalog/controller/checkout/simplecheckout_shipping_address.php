<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckoutShippingAddress extends Controller {
    static $error = array();
    static $updated = false;

    private function init() {
        $this->load->library('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('shipping_address');

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

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
        $this->data['text_select']                    = $this->language->get('text_select');
        $this->data['text_add_new']                   = $this->language->get('text_add_new');
        $this->data['text_select_address']            = $this->language->get('text_select_address');
        
        $this->data['rows']                           = $this->simplecheckout->getRows();
        $this->data['hidden_rows']                    = $this->simplecheckout->getHiddenAddressRows();

        if (!$this->simplecheckout->isAddressSame()) {
            $this->validate();
        }

        $this->data['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['display_error']  = $this->simplecheckout->displayError();
        $this->data['has_error']      = $this->simplecheckout->hasError();
        $this->data['hide']           = $this->simplecheckout->isAddressSame() ? true : $this->simplecheckout->isBlockHidden();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping_address.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_shipping_address.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_shipping_address.tpl';
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

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->simplecheckout->updateFields();

        $this->saveToSession();

        $this->simplecheckout->resetCurrentBlock();
    }

    private function saveToSession() {
        $version = $this->simplecheckout->getOpencartVersion();
        
        $address = $this->simplecheckout->getShippingAddress();
        
        if (!$this->customer->isLogged() && !self::$error) {
            $this->session->data['guest']['shipping'] = $address;
        }
        
        unset($this->session->data['shipping_address_id']); 
        unset($this->session->data['shipping_country_id']); 
        unset($this->session->data['shipping_zone_id']);    
        unset($this->session->data['shipping_postcode']);
                
        if (!empty($address['address_id'])) {
            $this->session->data['shipping_address_id'] = $address['address_id'];
        } 
        
        if (!empty($address['country_id'])) {
            $this->session->data['shipping_country_id'] = $address['country_id'];
        } else {
            $this->session->data['shipping_country_id'] = 0;
        }
        
        if (!empty($address['zone_id'])) {
            $this->session->data['shipping_zone_id'] = $address['zone_id'];
        } else {
            $this->session->data['shipping_zone_id'] = 0;
        }
        
        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
        }
        
        if ($version == 152 && !empty($this->session->data['guest']['shipping']) && is_array($this->session->data['guest']['shipping'])) {
            $clear = true;
            foreach ($this->session->data['guest']['shipping'] as $key => $value) {
                if ($value) {
                    $clear = false;
                    break;
                }
            }
            if ($clear) {
                unset($this->session->data['guest']['shipping']);
            }
        }
       
        if ($this->session->data['shipping_country_id'] || $this->session->data['shipping_zone_id']) {
            if ($version > 151) {
                $this->tax->setShippingAddress($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
            } else {
                $this->tax->setZone($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
                
                $this->session->data['country_id'] = $this->session->data['shipping_country_id'];
                $this->session->data['zone_id'] = $this->session->data['shipping_zone_id'];

                if (isset($this->session->data['shipping_postcode'])) {
                    $this->session->data['postcode'] = $this->session->data['shipping_postcode'];
                }
            }
        } else {
            unset($this->session->data['shipping_country_id']);
            unset($this->session->data['shipping_zone_id']);
            
            if ($version > 151) {
                $this->tax->setShippingAddress(0, 0);
            } else {
                $this->tax->setZone(0, 0);
            }
                
            if (!$this->customer->isLogged() && $this->config->get('config_tax_default') == 'shipping') {
                if ($version > 151) {
                    $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                } else {
                    $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                }
            }
        }
        
        if (!empty($address['postcode'])) {
            $this->session->data['shipping_postcode'] = $address['postcode'];
        }
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