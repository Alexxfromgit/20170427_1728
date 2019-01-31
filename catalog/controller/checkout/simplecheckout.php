<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerCheckoutSimpleCheckout extends Controller { 
    public function index($args = null) {
        $this->load->library('simple/simplecheckout');

        $settingsGroup = !empty($args['group']) ? $args['group'] : (!empty($this->request->get['group']) ? $this->request->get['group'] : 0);

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry, $settingsGroup);
        
        if (!$this->customer->isLogged() && $this->simplecheckout->isGuestCheckoutDisabled()) {
            $this->session->data['redirect'] = $this->url->link('checkout/simplecheckout', '', 'SSL');
            $this->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('checkout/checkout');
        $this->language->load('checkout/simplecheckout');
        
        $this->document->setTitle($this->language->get('heading_title')); 

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        ); 
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('checkout/simplecheckout', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['action'] = 'index.php?'.$this->simplecheckout->getAdditionalParams().'route=checkout/simplecheckout&group='.$settingsGroup;

        $this->data['heading_title'] = $this->language->get('heading_title');
        
        $this->simplecheckout->clearPreventDeleteFlag();
        $this->simplecheckout->clearSimpleSession();

        $this->data['error_warning'] = '';
            
        if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
            $this->getChild('checkout/simplecheckout_customer/update');
            $this->getChild('checkout/simplecheckout_payment_address/update');
            $this->getChild('checkout/simplecheckout_shipping_address/update');
            $this->getChild('checkout/simplecheckout_shipping/update');
            $this->getChild('checkout/simplecheckout_payment/update');
            $this->getChild('checkout/simplecheckout_cart/update');

            if ($redirect = $this->simplecheckout->getRedirectUrl()) {
                if (!$this->simplecheckout->isAjaxRequest()) {
                    $this->redirect($redirect);                
                } else {
                    $this->response->setOutput('<script type="text/javascript">location="'.$redirect.'";</script>');
                    return;    
                }
            }

            $this->data['simple_blocks'] = array(
                'customer'         => '',
                'payment_address'  => '',
                'shipping_address' => '',
                'cart'             => '',
                'shipping'         => '',
                'payment'          => '',
                'agreement'        => '',
                'help'             => '',
                'summary'          => '',
                'comment'          => '',
                'payment_form'     => ''
            );
            
            if ($this->simplecheckout->isPaymentBeforeShipping()) {
                $this->data['simple_blocks']['payment']  = $this->getChild('checkout/simplecheckout_payment');
                $this->data['simple_blocks']['shipping'] = $this->getChild('checkout/simplecheckout_shipping');
            } else {
                $this->data['simple_blocks']['shipping'] = $this->getChild('checkout/simplecheckout_shipping');
                $this->data['simple_blocks']['payment']  = $this->getChild('checkout/simplecheckout_payment');
            }
            
            $this->data['simple_blocks']['cart']             = $this->getChild('checkout/simplecheckout_cart');
            $this->data['simple_blocks']['customer']         = $this->getChild('checkout/simplecheckout_customer');
            $this->data['simple_blocks']['payment_address']  = $this->getChild('checkout/simplecheckout_payment_address');
            $this->data['simple_blocks']['shipping_address'] = $this->getChild('checkout/simplecheckout_shipping_address');

            if ($this->simplecheckout->hasBlock('agreement') && $this->simplecheckout->getSettingValue('agreementId')) {
                $this->data['simple_blocks']['agreement'] = $this->getChild('checkout/simplecheckout_text', array('type' => 'agreement', 'id' => $this->simplecheckout->getSettingValue('agreementId')));
            }

            if ($this->simplecheckout->hasBlock('help') && $this->simplecheckout->getSettingValue('helpId')) {
                $this->data['simple_blocks']['help'] = $this->getChild('checkout/simplecheckout_text', array('type' => 'help', 'id' => $this->simplecheckout->getSettingValue('helpId')));
            }

            if ($this->simplecheckout->hasBlock('comment')) {
                $this->data['simple_blocks']['comment'] = $this->getChild('checkout/simplecheckout_comment');
            }

            $modules = $this->simplecheckout->getModules();

            foreach ($modules as $m) {
                if ($m != 'payment_simple' && file_exists(DIR_APPLICATION . 'controller/module/' . $m . '.php')) {
                    $this->simplecheckout->setCurrentBlock($m);

                    $defaultSettings = array('limit' => 5, 'width' => 100, 'height' => 100, 'banner_id' => 6, 'position' => 'top', 'layout_id' => 0);

                    $allSettings = $this->config->get($m . '_module');

                    $this->load->model('design/layout');
                    $currentLayoutId = $this->model_design_layout->getLayout('checkout/simplecheckout');

                    if (!empty($allSettings) && is_array($allSettings)) {
                        $found = false;
                        foreach ($allSettings as $s) {
                            if ($s['layout_id'] == $currentLayoutId) {
                                $defaultSettings = $s;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $defaultSettings = reset($allSettings);
                        }
                    }
                    
                    $this->data['simple_blocks'][$m] = $this->getChild('module/'.$m, $defaultSettings);
                    $this->simplecheckout->resetCurrentBlock();
                } elseif ($m == 'payment_simple') {
                    $payment_method = $this->simplecheckout->getPaymentMethod();
                    if (!empty($payment_method['code']) && file_exists(DIR_APPLICATION . 'controller/module/' . $payment_method['code'] . '.php')) {
                        $this->simplecheckout->setCurrentBlock($payment_method['code']);
                        $this->data['simple_blocks'][$m] = $this->getChild('module/'.$payment_method['code']);
                        $this->simplecheckout->resetCurrentBlock();
                    } elseif (!empty($payment_method['code']) && file_exists(DIR_APPLICATION . 'controller/module/' . $payment_method['code'] . '_simple.php')) {
                        $this->simplecheckout->setCurrentBlock($payment_method['code'].'_simple');
                        $this->data['simple_blocks'][$m] = $this->getChild('module/'.$payment_method['code'].'_simple');
                        $this->simplecheckout->resetCurrentBlock();
                    } else {
                        $this->data['simple_blocks'][$m] = '';
                    }
                }
            }

            if ($this->simplecheckout->hasBlock('summary')) {
                $this->data['simple_blocks']['summary'] = $this->getChild('checkout/simplecheckout_summary');
            }
            
            $this->simplecheckout->resetCurrentBlock();

            $this->data['block_order'] = $this->simplecheckout->isOrderBlocked();
            
            $this->data['error_warning_agreement'] = '';

            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                $this->data['agreement'] = !empty($this->request->post['agreement']) ? true : false;
            } else {
                $this->data['agreement'] = $this->simplecheckout->getSettingValue('agreementCheckboxInit');
            }

            if ($this->validate() && !$this->simplecheckout->isOrderBlocked() && $this->simplecheckout->canCreateOrder()) {   
                $this->saveObjects();  
                $order_id = $this->order();
            
                $payment_method = $this->simplecheckout->getPaymentMethod();

                $requestMethod = $this->request->server['REQUEST_METHOD'];
                $this->request->server['REQUEST_METHOD'] = 'GET';
                
                $this->data['simple_blocks']['payment_form'] = $this->getChild('payment/' . $payment_method['code']);

                $this->request->server['REQUEST_METHOD'] = $requestMethod;
            }

            $this->data['ajax']                       = $this->simplecheckout->isAjaxRequest();
            $this->data['weight']                     = $this->cart->getWeight();            
            $this->data['additional_path']            = $this->simplecheckout->getAdditionalPath();
            $this->data['additional_params']          = $this->simplecheckout->getAdditionalParams();
            $this->data['login_type']                 = $this->simplecheckout->getSettingValue('loginType');
            $this->data['current_theme']              = $this->config->get('config_template');
            $this->data['simple_template']            = $this->simplecheckout->getTemplate();
            $this->data['logged']                     = $this->customer->isLogged();
            $this->data['steps_count']                = $this->simplecheckout->getStepsCount();
            $this->data['step_names']                 = $this->simplecheckout->getStepsNames();
            $this->data['display_agreement_checkbox'] = $this->simplecheckout->getSettingValue('displayAgreementCheckbox');
            
            $this->data['order_blocked']              = $this->simplecheckout->isOrderBlocked();
            $this->data['javascript_callback']        = $this->simplecheckout->getJavascriptCallback();
            
            $this->data['display_error']              = $this->simplecheckout->displayError();
            $this->data['has_error']                  = $this->simplecheckout->hasError();
            $this->data['display_weight']             = $this->simplecheckout->displayWeight();
            $this->data['display_back_button']        = $this->simplecheckout->getSettingValue('displayBackButton');
            $this->data['display_proceed_text']       = $this->simplecheckout->getSettingValue('displayProceedText');
            $this->data['scroll_to_error']            = $this->simplecheckout->getSettingValue('scrollToError');
            $this->data['scroll_to_payment_form']     = $this->simplecheckout->getSettingValue('scrollToPaymentForm');
            $this->data['left_column_width']          = $this->simplecheckout->getSettingValue('leftColumnWidth');
            $this->data['right_column_width']         = $this->simplecheckout->getSettingValue('rightColumnWidth');
            $this->data['use_autocomplete']           = $this->simplecheckout->getSettingValue('useAutocomplete');
            $this->data['use_google_api']             = $this->simplecheckout->getSettingValue('useGoogleApi');

            $this->data['errors'] = '';

            $errors = $this->simplecheckout->getErrors();

            if (!empty($errors) && is_array($errors)) {
                $this->data['errors'] = implode(',', $errors);
            }

            $this->data['popup']                      = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
            $this->data['as_module']                  = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

            $this->data['text_proceed_payment']       = $this->language->get('text_proceed_payment');
            $this->data['text_payment_form_title']    = $this->language->get('text_payment_form_title');
            $this->data['text_need_save_changes']     = $this->language->get('text_need_save_changes');
            $this->data['text_saving_changes']        = $this->language->get('text_saving_changes');
            $this->data['text_cart']                  = $this->language->get('text_cart');
            $this->data['text_please_confirm']        = $this->language->get('text_please_confirm');
            $this->data['button_save_changes']        = $this->language->get('button_save_changes');
            $this->data['button_order']               = $this->language->get('button_order');
            $this->data['button_back']                = $this->language->get('button_back');
            $this->data['button_prev']                = $this->language->get('button_prev');
            $this->data['button_next']                = $this->language->get('button_next');

            $langId = ($this->config->get('config_template') == 'shoppica' || $this->config->get('config_template') == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
            $title = $this->simplecheckout->getInformationTitle($this->simplecheckout->getSettingValue('agreementId'));
            $this->data['text_agreement'] = sprintf($this->language->get($langId), $this->url->link('information/information/info', $this->simplecheckout->getAdditionalParams() . 'information_id=' . $this->simplecheckout->getSettingValue('agreementId'), 'SSL'), $title, $title);
            
            if (!$this->simplecheckout->isAjaxRequest() && !$this->data['popup'] && !$this->data['as_module']) {
                $this->children = array(
                    'common/column_left',
                    'common/column_right',
                    'common/content_top',
                    'common/content_bottom',
                    'common/footer',
                    'common/header',
                );
          
                $this->data['simple_header'] = $this->simplecheckout->getLinkToHeaderTpl();
                $this->data['simple_footer'] = $this->simplecheckout->getLinkToFooterTpl();
            }
                  
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout.tpl';
            } else {
                $this->template = 'default/template/checkout/simplecheckout.tpl';
            }

            $this->response->setOutput($this->render());
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['text_error'] = $this->language->get('text_empty');
            
            $this->data['button_continue'] = $this->language->get('button_continue');
            
            $this->data['continue'] = $this->url->link('common/home');
            
            if ($this->simplecheckout->isAjaxRequest()) {
                $this->response->setOutput('<script type="text/javascript">location="'.$this->url->link('checkout/simplecheckout', '', 'SSL').'";</script>');
                return;    
            }

            $this->data['simple_header'] = $this->simplecheckout->getLinkToHeaderTpl();
            $this->data['simple_footer'] = $this->simplecheckout->getLinkToFooterTpl();

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_empty.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_empty.tpl';
            } else {
                $this->template = 'default/template/checkout/simplecheckout_empty.tpl';
            }
            
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'    
            );
                    
            $this->response->setOutput($this->render());            
        }
    }
    
    private function validate() {
        $error = false;

        if ($this->simplecheckout->getSettingValue('displayAgreementCheckbox') && !$this->data['agreement']) {
            $this->data['error_warning_agreement'] = sprintf($this->language->get('error_agree'), $this->simplecheckout->getInformationTitle($this->simplecheckout->getSettingValue('agreementId')));
            $this->simplecheckout->addError();
            $error = true; 
        }

        $errors = $this->simplecheckout->getErrors();

        if (!empty($errors)) {
            $error = true;
        }

        return !$error;
    }

    public function prevent_delete() {
        $this->load->library('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $this->simplecheckout->setPreventDeleteFlag();
    }

    private function saveObjects() {
        if (!$this->customer->isLogged()) {
            if ($this->simplecheckout->registerCustomer()) {
                $this->load->model('account/customer');
                $this->load->model('account/address');

                $customerInfo = $this->simplecheckout->getCustomerInfo();
                $paymentAddressInfo = $this->simplecheckout->getPaymentAddress();
                $shippingAddressInfo = $this->simplecheckout->getShippingAddress();
                $shippingAddressUsed = false;

                if ($this->simplecheckout->isBlockHidden('payment_address') && !$this->simplecheckout->isBlockHidden('shipping_address')) {
                    $info = array_merge($shippingAddressInfo, $customerInfo);
                    $shippingAddressUsed = true;
                } else {
                    $info = array_merge($paymentAddressInfo, $customerInfo);
                }

                // fix for old versions
                $tmpCustomerGroupId = $this->config->get('config_customer_group_id');
                $this->config->set('config_customer_group_id', $info['customer_group_id']);

                $this->model_account_customer->addCustomer($info);

                $this->config->set('config_customer_group_id', $tmpCustomerGroupId);

                $this->session->data['simple']['registered'] = true;

                $this->customer->login($info['email'], $info['password']);

                $customerId = 0;
                $addressId = 0;

                if ($this->customer->isLogged()) {
                    $customerId = $this->customer->getId();
                    $addressId = $this->customer->getAddressId();
                } else {
                    $customerInfo = $this->simplecheckout->getCustomerInfoByEmail($info['email']);
                    $customerId = $customerInfo['customer_id'];
                    $addressId = $customerInfo['address_id'];
                }

                $this->model_account_address->editAddress($addressId, $shippingAddressUsed ? $shippingAddressInfo : $paymentAddressInfo);

                $this->simplecheckout->setCustomerId($customerId); 
                $this->simplecheckout->setPaymentAddressId($addressId);

                $this->simplecheckout->saveCustomFields(array('customer', 'shipping', 'payment'), 'customer', $customerId);
                
                if (!$this->simplecheckout->isAddressSame() && !$this->simplecheckout->isBlockHidden('shipping_address') && !$shippingAddressUsed) {
                    $this->simplecheckout->saveCustomFields(array('payment_address', 'payment'), 'address', $addressId);

                    $addressId = $this->model_account_address->addAddress($shippingAddressInfo);

                    $this->simplecheckout->setShippingAddressId($addressId);

                    $this->simplecheckout->saveCustomFields(array('shipping_address', 'shipping'), 'address', $addressId);
                } else {
                    // need to get all values from shipping block when shipping address is hidden and save this info
                    $this->simplecheckout->saveCustomFields(array('payment_address', 'payment', 'shipping'), 'address', $addressId);
                }
            }
        } else {
            $this->load->model('account/customer');
            $this->load->model('account/address');

            $customerInfo = $this->simplecheckout->getCustomerInfo();
            if (!$this->simplecheckout->isBlockHidden('customer')) {
                $this->model_account_customer->editCustomer($customerInfo);
                $this->simplecheckout->saveCustomFields(array('customer', 'shipping', 'payment'), 'customer', $this->customer->getId());
            }

            $addressInfo = $this->simplecheckout->getPaymentAddress();

            $addressId = 0;

            if (!$this->simplecheckout->isBlockHidden('payment_address')) {
                if ($addressInfo['address_id']) {
                    $this->model_account_address->editAddress($addressInfo['address_id'], $addressInfo);
                    $addressId = $addressInfo['address_id'];
                } else {
                    $addressId = $this->model_account_address->addAddress($addressInfo);
                    $this->simplecheckout->setPaymentAddressId($addressId);
                }
            }

            if (!$this->simplecheckout->isBlockHidden('shipping_address') && !$this->simplecheckout->isAddressSame()) {
                if ($addressId) {
                    $this->simplecheckout->saveCustomFields(array('payment_address', 'payment'), 'address', $addressId);
                }

                $addressInfo = $this->simplecheckout->getShippingAddress();

                if ($addressInfo['address_id']) {
                    $this->model_account_address->editAddress($addressInfo['address_id'], $addressInfo);
                    $this->simplecheckout->saveCustomFields(array('shipping_address', 'shipping'), 'address', $addressInfo['address_id']);
                } else {
                    $addressId = $this->model_account_address->addAddress($addressInfo);
                    $this->simplecheckout->setShippingAddressId($addressId);
                    $this->simplecheckout->saveCustomFields(array('shipping_address', 'shipping'), 'address', $addressId);
                }
            } else {
                if ($addressId) {
                    $this->simplecheckout->saveCustomFields(array('payment_address', 'payment', 'shipping'), 'address', $addressId);
                }
            }
        }
    }
    
    private function order() {
        $this->simplecheckout->clearOrder();

        $customer_info    = $this->simplecheckout->getCustomerInfo();
        $payment_address  = $this->simplecheckout->getPaymentAddress();
        $payment_method   = $this->simplecheckout->getPaymentMethod();
        $shipping_address = $this->simplecheckout->getShippingAddress();
        $shipping_method  = $this->simplecheckout->getShippingMethod();
        $comment          = $this->simplecheckout->getComment();
        $version          = $this->simplecheckout->getOpencartVersion();

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
        
        $sort_order = array(); 
      
        foreach ($total_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }
    
        array_multisort($sort_order, SORT_ASC, $total_data);
    
        $data = array();
        
        $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $data['store_id'] = $this->config->get('config_store_id');
        $data['store_name'] = $this->config->get('config_name');
        
        if ($data['store_id']) {
            $data['store_url'] = $this->config->get('config_url');        
        } else {
            $data['store_url'] = HTTP_SERVER;    
        }
        
        $data['customer_id']            = $customer_info['customer_id'];
        $data['customer_group_id']      = $customer_info['customer_group_id'];
        $data['firstname']              = $customer_info['firstname'];
        $data['lastname']               = $customer_info['lastname'];
        $data['email']                  = $customer_info['email'];
        $data['telephone']              = $customer_info['telephone'];
        $data['fax']                    = $customer_info['fax'];

        $data['payment_firstname']      = $payment_address['firstname'];
        $data['payment_lastname']       = $payment_address['lastname'];    
        $data['payment_company']        = $payment_address['company'];    
        $data['payment_address_1']      = $payment_address['address_1'];
        $data['payment_address_2']      = $payment_address['address_2'];
        $data['payment_city']           = $payment_address['city'];
        $data['payment_postcode']       = $payment_address['postcode'];
        $data['payment_zone']           = $payment_address['zone'];
        $data['payment_zone_id']        = $payment_address['zone_id'];
        $data['payment_country']        = $payment_address['country'];
        $data['payment_country_id']     = $payment_address['country_id'];
        $data['payment_address_format'] = $payment_address['address_format'];
        $data['payment_company_id']     = $payment_address['company_id'];    
        $data['payment_tax_id']         = $payment_address['tax_id'];    
            
        if (isset($payment_method['title'])) {
            $data['payment_method'] = $payment_method['title'];
        } else {
            $data['payment_method'] = '';
        }
        
        if (isset($payment_method['code'])) {
            $data['payment_code'] = $payment_method['code'];
        } else {
            $data['payment_code'] = '';
        }
        
        if ($this->cart->hasShipping()) {
            $data['shipping_firstname']      = $shipping_address['firstname'];
            $data['shipping_lastname']       = $shipping_address['lastname'];    
            $data['shipping_company']        = $shipping_address['company'];    
            $data['shipping_address_1']      = $shipping_address['address_1'];
            $data['shipping_address_2']      = $shipping_address['address_2'];
            $data['shipping_city']           = $shipping_address['city'];
            $data['shipping_postcode']       = $shipping_address['postcode'];
            $data['shipping_zone']           = $shipping_address['zone'];
            $data['shipping_zone_id']        = $shipping_address['zone_id'];
            $data['shipping_country']        = $shipping_address['country'];
            $data['shipping_country_id']     = $shipping_address['country_id'];
            $data['shipping_address_format'] = $shipping_address['address_format'];
            
            if (isset($shipping_method['title'])) {
                $data['shipping_method'] = $shipping_method['title'];
            } else {
                $data['shipping_method'] = '';
            }
            
            if (isset($shipping_method['code'])) {
                $data['shipping_code'] = $shipping_method['code'];
            } else {
                $data['shipping_code'] = '';
            }
        } else {
            $data['shipping_firstname']      = '';
            $data['shipping_lastname']       = '';    
            $data['shipping_company']        = '';    
            $data['shipping_address_1']      = '';
            $data['shipping_address_2']      = '';
            $data['shipping_city']           = '';
            $data['shipping_postcode']       = '';
            $data['shipping_zone']           = '';
            $data['shipping_zone_id']        = '';
            $data['shipping_country']        = '';
            $data['shipping_country_id']     = '';
            $data['shipping_address_format'] = '';
            $data['shipping_method']         = '';
            $data['shipping_code']           = '';
        }
        
        $product_data = array();
        
        if ($version < 152) {
        
            if (method_exists($this->tax,'setZone')) {
                if ($this->cart->hasShipping()) {
                    $this->tax->setZone($data['shipping_country_id'], $data['shipping_zone_id']);
                } else {
                    $this->tax->setZone($data['payment_country_id'], $data['payment_zone_id']);
                }
            }
            
            $this->load->library('encryption');
        
            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();
        
                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {    
                        $option_data[] = array(
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id'               => $option['option_id'],
                            'option_value_id'         => $option['option_value_id'],                                   
                            'name'                    => $option['name'],
                            'value'                   => $option['option_value'],
                            'type'                    => $option['type']
                        );                    
                    } else {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        
                        $option_data[] = array(
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'product_option_id'       => $option['product_option_id'],
                            'product_option_value_id' => $option['product_option_value_id'],
                            'option_id'               => $option['option_id'],
                            'option_value_id'         => $option['option_value_id'],                                   
                            'name'                    => $option['name'],
                            'value'                   => $encryption->decrypt($option['option_value']),
                            'type'                    => $option['type']
                        );                                
                    }
                }
        
                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'option'     => $option_data,
                    'download'   => $product['download'],
                    'quantity'   => $product['quantity'],
                    'subtract'   => $product['subtract'],
                    'price'      => $product['price'],
                    'total'      => $product['total'],
                    'tax'        => method_exists($this->tax,'getRate') ? $this->tax->getRate($product['tax_class_id']) : $this->tax->getTax($product['price'], $product['tax_class_id'])
                ); 
            }
            
            // Gift Voucher
            if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $product_data[] = array(
                        'product_id' => 0,
                        'name'       => $voucher['description'],
                        'model'      => '',
                        'option'     => array(),
                        'download'   => array(),
                        'quantity'   => 1,
                        'subtract'   => false,
                        'price'      => $voucher['amount'],
                        'total'      => $voucher['amount'],
                        'tax'        => 0
                    );
                }
            } 
                        
            $data['products'] = $product_data;
            $data['totals'] = $total_data;
            $data['comment'] = $comment;
            $data['total'] = $total;
            $data['reward'] = $this->cart->getTotalRewardPoints();
        } elseif ($version >= 152) {
            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();
    
                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];    
                    } else {
                        $value = $this->encryption->decrypt($option['option_value']);
                    }    
                    
                    $option_data[] = array(
                        'product_option_id'       => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id'               => $option['option_id'],
                        'option_value_id'         => $option['option_value_id'],                                   
                        'name'                    => $option['name'],
                        'value'                   => $value,
                        'type'                    => $option['type']
                    );                    
                }
     
                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'option'     => $option_data,
                    'download'   => $product['download'],
                    'quantity'   => $product['quantity'],
                    'subtract'   => $product['subtract'],
                    'price'      => $product['price'],
                    'total'      => $product['total'],
                    'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward'     => $product['reward']
                ); 
            }
            
            // Gift Voucher
            $voucher_data = array();
            
            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $voucher_data[] = array(
                        'description'      => $voucher['description'],
                        'code'             => substr(md5(rand()), 0, 10),
                        'to_name'          => $voucher['to_name'],
                        'to_email'         => $voucher['to_email'],
                        'from_name'        => $voucher['from_name'],
                        'from_email'       => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message'          => $voucher['message'],                        
                        'amount'           => $voucher['amount']
    
                    );
                }
            }  
                        
            $data['products'] = $product_data;
            $data['vouchers'] = $voucher_data;
            $data['totals'] = $total_data;
            $data['comment'] = $comment;
            $data['total'] = $total; 
        }
        
        if (isset($this->request->cookie['tracking'])) {
            $this->load->model('affiliate/affiliate');
            
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
            $subtotal = $this->cart->getSubTotal();

            if ($affiliate_info) {
                $data['affiliate_id'] = $affiliate_info['affiliate_id']; 
                $data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }
        } else {
            $data['affiliate_id'] = 0;
            $data['commission'] = 0;
        }
        
        $data['language_id']    = $this->config->get('config_language_id');
        $data['currency_id']    = $this->currency->getId();
        $data['currency_code']  = $this->currency->getCode();
        $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
        $data['ip']             = $this->request->server['REMOTE_ADDR'];
        
        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];    
        } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];    
        } else {
            $data['forwarded_ip'] = '';
        }
        
        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];    
        } else {
            $data['user_agent'] = '';
        }
        
        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];    
        } else {
            $data['accept_language'] = '';
        }
        
        $this->load->model('checkout/order');
        
        $order_id = 0;

        if ($version < 152) {
            $order_id = $this->model_checkout_order->create($data);
            
            // Gift Voucher
            if (isset($this->session->data['vouchers']) && is_array($this->session->data['vouchers'])) {
                $this->load->model('checkout/voucher');
        
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $this->model_checkout_voucher->addVoucher($order_id, $voucher);
                }
            }
        } elseif ($version >= 152) {
            $order_id = $this->model_checkout_order->addOrder($data);
        }
        
        $this->session->data['order_id'] = $order_id;

        $this->simplecheckout->saveCustomFields(array('customer', 'payment_address', 'payment', 'shipping_address', 'shipping'), 'order', $order_id);
        
        return $order_id;
    }
}
