<?php
/*
default session array
(
    'language' => 'en',
    'currency] => USD,
    'cart' => array
        (
            '46::' => 1
        ),

    'payment_country_id' => 219,
    'payment_zone_id' => 3465,
    'shipping_country_id' => 219,
    'shipping_zone_id' => 3465,
    'shipping_postcode' => '123456',
    'account' => guest,
    'vouchers' => array
        (
        ),

    'guest' => array
        (
            'customer_group_id' => 1,
            'firstname' => 'test',
            'lastname' => 'test',
            'email' => 'test@test.com',
            'telephone' => '123456',
            'fax' => '',
            'payment' => array
                (
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'company' => '',
                    'company_id' => '',
                    'tax_id' => '',
                    'address_1' => 'test',
                    'address_2' => '',
                    'postcode' => '12345',
                    'city' => 'city',
                    'country_id' => 219,
                    'zone_id' => 3465,
                    'country' => 'Uganda',
                    'iso_code_2' => 'UG',
                    'iso_code_3' => 'UGA',
                    'address_format' =>  '',
                    'zone' => 'Bundibugyo',
                    'zone_code' => 'BUN',
                ),

            'shipping_address' => 1,
            'shipping' => array
                (
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'company' => '',
                    'address_1' => 'test',
                    'address_2' => '',
                    'postcode' => '12345',
                    'city' => 'city',
                    'country_id' => 219,
                    'zone_id' => 3465,
                    'country' => 'Uganda',
                    'iso_code_2' => 'UG',
                    'iso_code_3' => 'UGA',
                    'address_format' => '',
                    'zone' => 'Bundibugyo',
                    'zone_code' => 'BUN',
                ),

        ),

    'shipping_methods' => array
        (
            'flat' => array
                (
                    'title' => 'Flat Rate',
                    'quote' => array
                        (
                            'flat' => array
                                (
                                    'code' => 'flat.flat',
                                    'title' => 'Flat Shipping Rate',
                                    'cost' => '5.00',
                                    'tax_class_id' => 9,
                                    'text' => '$5.00',
                                )

                        ),

                    'sort_order' => 1,
                    'error' => '',
                )

        ),

    'shipping_method' => array
        (
            'code' => 'flat.flat',
            'title' => 'Flat Shipping Rate',
            'cost' => '5.00',
            'tax_class_id' => 9,
            'text' => '$5.00',
        ),

    'comment' => '',
    'payment_methods' => array
        (
            'cod' => array
                (
                    'code' => 'cod',
                    'title' => 'Cash On Delivery',
                    'sort_order' => 5,
                )

        ),

    'payment_method' => array
        (
            'code' => 'cod',
            'title' => 'Cash On Delivery',
            'sort_order' => 5,
        ),

    'order_id' => 2
)
*/
//session_unset();
/**
 * Class of Ajax Quick Checkout module. This is the main file for calculating and validating all the fields.
 * 
 * @author dreamvention
 * @link http://www.opencart.com/index.php?route=extension/extension/info&extension_id=9132
 * @package quickcheckout
 */
class ControllerModuleQuickcheckout extends Controller { 

	private $settings = array();
	private $texts = array('title', 'tooltip', 'description', 'text');
	private $debug_on = false;
	private $debug_path = 'system/logs/error.txt';
	private $time = '';

/**
 
 * Index method: loads the quickcheckout module.
 * 
 * This is the main method to show all view blocks of the get_view methods
 *
 * @uses ControllerModuleQuickcheckout:validate() 
 * @uses ControllerModuleQuickcheckout:load_settings()
 * @uses ControllerModuleQuickcheckout:get_login_view()
 * @uses ControllerModuleQuickcheckout:get_payment_address_view()
 * @uses ControllerModuleQuickcheckout:get_shipping_address_view()
 * @uses ControllerModuleQuickcheckout:get_shipping_method_view()
 * @uses ControllerModuleQuickcheckout:get_payment_method_view()
 * @uses ControllerModuleQuickcheckout:get_cart_view()
 * @uses ControllerModuleQuickcheckout:get_payment_view()
 * @uses ControllerModuleQuickcheckout:get_confirm_view()
 */
	public function index(){

		$this->cache->delete('quickcheckout');
		$this->check_order_id();
		unset($this->session->data['qc_settings']);

		

		if($this->validate()) {		
			$this->load_settings();
			$this->clear_session();

			if($this->settings['general']['enable']){


				$this->load_head_files();
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				//Set Breadcrumbs
				$this->data['breadcrumbs'] = array();

				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				); 

				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_cart'),
					'href'      => $this->url->link('checkout/cart'),
					'separator' => $this->language->get('text_separator')
				);

				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
					'separator' => $this->language->get('text_separator')
				);

				//Get customer option (Guest, Registration, Company etc)
				if(!$this->customer->isLogged()){
					$this->data['get_login_view'] = $this->get_login_view();
				}else{
					$this->data['get_login_view'] = '';
				}

				//Get customer info
				$this->data['get_payment_address_view'] = $this->get_payment_address_view();

				//Get Shipping address
				$this->data['get_shipping_address_view'] = $this->get_shipping_address_view();

				//Get shipping method
				$this->data['get_shipping_method_view'] = $this->get_shipping_method_view();

				//Get payment method
				$this->data['get_payment_method_view'] = $this->get_payment_method_view();

				//Get cart view
				$this->data['get_cart_view'] = $this->get_cart_view();

				//Get payment view
				$this->data['get_payment_view'] = $this->get_payment_view();

				//Get confirm view
				$this->data['get_confirm_view'] = $this->get_confirm_view();

				//Logo
				$this->data['logo'] = '';
				if($this->settings['general']['only_quickcheckout']){
					$this->data['logo'] = $this->get_logo();
					$this->data['home'] = $this->url->link('common/home');
					$this->data['name'] = $this->config->get('config_name');
				}

				//rest of data
				$this->data['settings'] = $this->settings;
				$this->data['checkout'] = $this->session->data;

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/quickcheckout.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/quickcheckout.tpl';
				} else {
					$this->template = 'default/template/module/quickcheckout.tpl';
				}

				return $this->render();
			} //if enabled
		}else{
			$this->language->load('checkout/cart');
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_message'] = $this->language->get('text_empty');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/empty.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/quickcheckout/empty.tpl';
			} else {
				$this->template = 'default/template/quickcheckout/empty.tpl';
			}

			return $this->render();
		}
	}
	
/**
 
 * load_settings() 
 * 
 * builds the settings string and sets the settings in the session. Uploads all the required models. 
 * 
 * @uses ControllerModuleQuickcheckout:get_settings()
 * @uses ControllerModuleQuickcheckout:get_shipping_methods()
 * @uses ControllerModuleQuickcheckout:get_shipping_methods()
 * 
 * @return void
 */
	private function load_settings(){

		//load models
		$this->load->model('setting/setting');
		$this->load->model('account/address');
		$this->load->model('setting/extension');
		$this->load->model('account/customer');
		$this->load->model('localisation/country');	
		$this->load->model('localisation/zone');
		$this->load->model('quickcheckout/order');
		$this->load->model('tool/image');
		$this->load->model('checkout/coupon');
		
		//Load languages
		$this->language->load('checkout/cart');
		$this->language->load('checkout/checkout');
		$this->language->load('module/quickcheckout');

		//Get Settings
		$this->settings = $this->get_settings();
		
		//Min order
		$this->session->data['min_order'] = (($this->cart->getTotal() + $this->get_vouchers_total()) >= $this->settings['general']['min_order']['value']);
		$this->session->data['min_quantity'] = ($this->cart->countProducts() >= $this->settings['general']['min_quantity']['value']);
		$this->session->data['min_quantity_product'] = true;
		foreach ($this->cart->getProducts() as $product) {
				$product_total = 0;

				foreach ($this->cart->getProducts() as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}		

				if ($product['minimum'] > $product_total) {
					$this->session->data['min_quantity_product'] = false;
					
				}				
			}

		//Post
		if(!empty($this->request->post)){
			if(!empty($this->request->post['cart'])){
				foreach ($this->request->post['cart'] as $key => $value){
					$this->cart->update($key, $value);
				}
				unset($this->request->post['cart']);
			}

			$this->session->data = $this->array_merge_recursive_distinct($this->session->data,  $this->request->post);

			if(isset($this->request->post['field']) && isset($this->request->post['value'])){
				$value = $this->request->post['value'];
				$field = explode("[", $this->request->post['field']);
				$field[1] =str_replace("]", "", $field[1]);
				
				$this->session->data[$field[0]][$field[1]] = $value;
			}
		}

		if($this->customer->isLogged()){	
			$this->session->data['account'] = 'logged';
		} elseif ((!$this->customer->isLogged() 
			&& isset($this->session->data['account']) 
			&& $this->session->data['account'] == 'logged') 
			|| !isset($this->session->data['account'])) {

				$this->session->data['account'] = $this->config->get('config_guest_checkout') 
												&& !$this->config->get('config_customer_price') 
												&& $this->settings['general']['default_option'] == 'guest' 
												&& !$this->cart->hasDownload() ? 'guest' : 'register';
		}

		if(!isset($this->session->data['payment_address'])){
			$this->session->data['payment_address'] = array();
		}
		if(!isset($this->session->data['shipping_address'])){
			$this->session->data['shipping_address'] = array();
		}

		//set payment_country_id, payment_zone_id, shipping_country_id, shipping_zone_id, shipping_postcode
		if(isset($this->session->data['payment_address']['country_id'])){
			$this->session->data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
		}
		if(isset($this->session->data['payment_address']['zone_id'])){
			$this->session->data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
		}
		
		if($this->shipping_same_as_payment()){
			if (isset($this->session->data['payment_address']['country_id'])) {
				$this->session->data['shipping_country_id'] = $this->session->data['payment_address']['country_id'];
				$this->session->data['shipping_address']['country_id'] = $this->session->data['payment_address']['country_id'];
			} else {
				$this->session->data['shipping_country_id'] = $this->config->get('config_country_id');
				$this->session->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
			}
			if (isset($this->session->data['payment_address']['zone_id'])) {
				$this->session->data['shipping_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$this->session->data['shipping_address']['zone_id'] = $this->session->data['payment_address']['zone_id'];
			} else {
				$this->session->data['shipping_zone_id'] =$this->config->get('config_zone_id');
				$this->session->data['shipping_address']['zone_id'] =$this->config->get('config_zone_id');
			}
			if (isset($this->session->data['payment_address']['postcode'])) {
				$this->session->data['shipping_postcode'] = $this->session->data['payment_address']['postcode'];
			}else{
				$this->session->data['shipping_postcode'] = '';
			}
			$this->session->data['shipping_address'] = $this->session->data['payment_address']; 
		}else{
			if(isset($this->session->data['shipping_address']['country_id'])){
				$this->session->data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
			}else{
				$this->session->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
			}
			if(isset($this->session->data['shipping_address']['zone_id'])){
				$this->session->data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
			}else{
				$this->session->data['shipping_address']['zone_id'] =$this->config->get('config_zone_id');
			}
			if(isset($this->session->data['shipping_address']['postcode'])){
				$this->session->data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
			}
			if(isset($this->session->data['shipping_address']['country_id']) && isset($this->session->data['shipping_address']['zone_id'])){
				$country_data = $this->get_country_data($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
				if (is_array($country_data)) $this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $country_data);
			}
		}



		if($this->customer->isLogged()){	
			if((isset($this->session->data['payment_address']['address_id']) && $this->session->data['payment_address']['address_id']) && $this->session->data['payment_address']['exists']){
				$this->session->data['payment_address']['shipping'] = 0;
			}
		}
		if(isset($this->session->data['payment_address']['country_id']) && isset($this->session->data['payment_address']['zone_id'])){
			if(!isset($this->session->data['payment_address']['exists']) || $this->session->data['payment_address']['exists'] == 0){
				$country_data = $this->get_country_data($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
				if (is_array($country_data)) $this->session->data['payment_address'] = array_merge($this->session->data['payment_address'], $country_data);
			}
		}
		
		if(isset($this->session->data['shipping_address']['country_id']) && isset($this->session->data['shipping_address']['zone_id'])){
			if(!isset($this->session->data['shipping_address']['exists']) || $this->session->data['shipping_address']['exists'] == 0){
				$country_data = $this->get_country_data($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
				if (is_array($country_data)) $this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $country_data);
			}
		}
		

		if($this->customer->isLogged()){
			if(isset($this->session->data['payment_address']['address_id']) && $this->session->data['payment_address']['address_id'] != 0){
				$address = $this->model_account_address->getAddress($this->session->data['payment_address']['address_id']);
				if($address) $this->session->data['payment_address'] = array_merge($this->session->data['payment_address'], $address);
			}

			if(isset($this->session->data['shipping_address']['address_id']) && $this->session->data['shipping_address']['address_id'] != 0 && $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id'])){
				$address = $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id']);
				if($address) $this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $address);
			}
		}
		//Set new session
		if($this->customer->isLogged()){
			$this->session->data['payment_address']['islogged'] = 1;
		}else{
			$this->session->data['payment_address']['islogged'] = 0;
			$this->session->data['payment_address']['exists'] = 0;
			$this->session->data['shipping_address']['exists'] = 0;
		}

		//Load shipping methods
		$this->session->data['shipping_methods'] = $this->get_shipping_methods($this->session->data['shipping_address']);
	

		$this->session->data['default_shipping_method'] = null;
		if(!empty($this->session->data['shipping_methods'])){
			$first = current($this->session->data['shipping_methods']);
			$first = (is_array($first['quote'])) ? current($first['quote']) : $first['quote'];
		
			$shipping = explode('.', $this->settings['step']['shipping_method']['default_option']);
			$this->session->data['default_shipping_method'] = (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'])) ? current($this->session->data['shipping_methods'][$shipping[0]]['quote']): $first;
		}

		//Load shipping method
		if(isset($this->request->post['shipping_method'])){
			$shipping = explode('.', $this->request->post['shipping_method']);
			$this->session->data['shipping_method'] = (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) ? $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]] : $this->session->data['default_shipping_method'];
		}

		if(!isset($this->session->data['shipping_method']) || !$this->session->data['shipping_method']){
			$this->session->data['shipping_method'] = $this->session->data['default_shipping_method'];
		}

		//Load payment method
		if(!empty($this->session->data['payment_methods'])){
			$first = current($this->session->data['payment_methods']);
			$default_payment_method = (isset($this->session->data['payment_methods'][$this->settings['step']['payment_method']['default_option']])) ? $this->session->data['payment_methods'][$this->settings['step']['payment_method']['default_option']] : $first;
		}else{
			$default_payment_method = null;
		}
		
		if(isset($this->request->post['payment_method'])){
			$this->session->data['payment_method'] = (isset($this->session->data['payment_methods'][$this->request->post['payment_method']]))? $this->session->data['payment_methods'][$this->request->post['payment_method']] : $default_payment_method; 
		}
		
		//Create or Update order
		if(!isset($this->session->data['order_id'])){
			$this->create_order();
		}else{
			$this->update_order();	
		}
		
		$this->after_load_settings();
	}


/**
 
 * Get login view
 */	
	private function get_login_view(){

		//Load languages
		$this->data['text_checkout_option'] =  $this->language->get('text_checkout_option');
		$this->data['text_new_customer'] = $this->language->get('text_new_customer');
		$this->data['text_new_guest'] = $this->language->get('text_new_guest');
		$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_guest'] = $this->language->get('text_guest');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$this->data['text_register_account'] = $this->language->get('text_register_account');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_login'] = $this->language->get('button_login');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

		//social login
		if($this->isInstalled('d_social_login')){
			$this->data['providers'] = $this->get_social_login_providers();
		}

		//Check if guest checkout is allowed
		$this->data['guest_checkout'] = $this->is_guest_checkout_allowed();
		
		//Get Sellected account
		$this->data['account'] = $this->session->data['account'];
		
		//Get settings
		$this->data['data'] = $this->session->data['qc_settings']['option'][$this->session->data['account']]['login'];
		
		//Display login, guest and registration blocks.
		$count = $this->data['data']['option']['login']['display'] 
			   + $this->data['data']['option']['register']['display'] 
			   + $this->data['data']['option']['guest']['display'];

		$this->data['count'] = $count;
		$this->data['width'] = ($count) ? (100 - $count)/$count : 0;
		$this->data['login_style'] = $this->settings['general']['login_style'];
		$this->data['dsl_size'] = $this->settings['general']['socila_login_style'];

		//Get template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/login.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/login.tpl';
		}
		
		return $this->render();
	}


	private function get_field_view($fields, $name){
		$this->data['fields'] = $fields;
		$this->data['name'] = $name;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/field.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/field.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/field.tpl';
		}
		
		return $this->render();
	}
/**
 
 * Get View: Payment Address
 * 
 * Load fields
 * Set default values 
 * Load data if islogged
 * Set depending values
 * Set session
 */
	private function get_payment_address_view(){
		//Load languages
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');

		//Load fields
		$data = $this->session->data['qc_settings']['option'][$this->session->data['account']]['payment_address'];
		$data['fields']['country_id']['value'] = $this->config->get('config_country_id');
		$data['fields']['country_id']['options'] = $this->get_countries();
		$data['fields']['zone_id']['value'] = $this->config->get('config_zone_id');
		$data['fields']['customer_group_id']['value'] = $this->config->get('config_customer_group_id');
		$data['fields']['customer_group_id']['options'] = $this->get_customer_groups();
		//$data['fields']['postcode']['value'] = '';

		$this->data['address_style'] = $this->settings['general']['address_style'];

		if(isset($this->session->data['payment_address'])){
			foreach($this->session->data['payment_address'] as $field => $value){
				if(isset($data['fields'][$field])){
					$data['fields'][$field]['value'] = $value;
				}
			}
		}
		
		$data['fields']['zone_id']['options'] = $this->get_zones_by_country_id($data['fields']['country_id']['value']);

		//Set default values
		$payment_address = array();
		foreach($data['fields'] as $field => $value){
			$payment_address[$field] = '';
			if(isset($value['value'])){
				$payment_address[$field] = $value['value'];
			}
		}

		$country_data = $this->get_country_data($payment_address['country_id'], $payment_address['zone_id']);
		if (is_array($country_data)) $payment_address = array_merge($payment_address, $country_data);

		//Load data of logged
		$this->session->data['addresses'] = '';
		$data['exists'] = (isset($data['exists'])) ? $data['exists'] : '';
		if($this->customer->isLogged()){
			//get address
			if ($this->customer->getId()!=null) {
				$this->session->data['addresses'] = $this->model_account_address->getAddresses();
			}else{
				$this->session->data['addresses'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if(isset($this->session->data['payment_address']['address_id'])){
				$data['address_id'] = $this->session->data['payment_address']['address_id'];
			}else{
				$data['address_id'] = $this->customer->getAddressId();
			}

			if(isset($this->session->data['payment_address']['exists'])){
				$data['exists'] = $this->session->data['payment_address']['exists'];
			}else{
				$data['exists'] = '1';
			}

			if(isset($this->session->data['payment_address']['created'])){
				$data['address_id'] = $this->session->data['payment_address']['created'];
			}
			if($data['address_id'] != 0 && $this->model_account_address->getAddress($data['address_id'])){
				$payment_address = $this->model_account_address->getAddress($data['address_id']);
			}

			if($this->data['address_style'] == 'radio'){
				$data['exists'] = $data['address_id'];	
				$payment_address['exists'] = $data['address_id'];	 
			}
		}else{
			unset($this->session->data['payment_address']);
		}
		$this->data['addresses'] = $this->session->data['addresses'];

		if (!$this->cart->hasShipping()) { 
			$data['fields']['shipping']['value'] = 1;
			$data['fields']['shipping']['display'] = 0;
		}
		
		//Set session
		$this->tax->setPaymentAddress($payment_address['country_id'], $payment_address['zone_id']);
		$this->data['payment_address'] = $data;
		$this->session->data['payment_address'] = $payment_address;
		$this->session->data['guest']['payment'] = $this->session->data['payment_address'];

		$this->data['field_view'] = $this->get_field_view($data['fields'], 'payment_address');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/payment_address.tpl';
		}

		return $this->render();	
		
	}

	private function shipping_same_as_payment(){

		if($this->session->data['qc_settings']['option'][$this->session->data['account']]['shipping_address']['require'] == 1) {
			$this->session->data['payment_address']['shipping'] = 0;
		}


		if(isset($this->session->data['account']) && isset($this->session->data['payment_address']['shipping'])){
			if($this->session->data['payment_address']['shipping'] || !$this->settings['option'][$this->session->data['account']]['shipping_address']['display']){
				return true;
			}
		}	 
		return false;
	}
	
	
/**
 
 * Get View: Shipping address
 *  
 * Load fields
 * Set default values 
 * Load data if islogged
 * Set depending values
 * Set session
 */
	private function get_shipping_address_view(){
		//Setting language
		if(!$this->cart->hasShipping()){ return false; }

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		
		$this->data['address_style'] = $this->settings['general']['address_style'];

		//Load fields
		$data = $this->session->data['qc_settings']['option'][$this->session->data['account']]['shipping_address'];
		$data['fields']['country_id']['value'] = $this->config->get('config_country_id');
		$data['fields']['country_id']['options'] = $this->get_countries();
		$data['fields']['zone_id']['value'] = $this->config->get('config_zone_id');
		//$data['fields']['postcode']['value'] = '';

		if(isset($this->session->data['shipping_address'])){
			foreach($this->session->data['shipping_address'] as $field => $value){
				if(isset($data['fields'][$field])){
					$data['fields'][$field]['value'] = $value;
				}
			}
		}
		
		$data['fields']['zone_id']['options'] = $this->get_zones_by_country_id($data['fields']['country_id']['value']);

		//Set default values
		$shipping_address = array();
		foreach($data['fields'] as $field => $value){
			$shipping_address[$field] = '';
			if(isset($value['value'])){
				$shipping_address[$field] = $value['value'];
			}
		}


		$data['address_id'] = (isset($data['address_id'])) ? $data['address_id'] : '';
		$data['exists'] = (isset($data['exists'])) ? $data['exists'] : '';
		$this->session->data['payment_address']['shipping'] = isset($this->session->data['payment_address']['shipping']) ? $this->session->data['payment_address']['shipping'] : 0;


		//Load data of logged
		$this->session->data['addresses'] = '';
		if($this->customer->isLogged()){
			//get address
			if ($this->customer->getId()!=null) {
				$this->session->data['addresses'] = $this->model_account_address->getAddresses();
			}else{
				$this->session->data['addresses'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if(isset($this->session->data['shipping_address']['address_id'])){
				$data['address_id'] = $this->session->data['shipping_address']['address_id'];
			}else{
				$data['address_id'] = $this->customer->getAddressId();
			}

			if(isset($this->session->data['shipping_address']['exists'])){
				$data['exists'] = $this->session->data['shipping_address']['exists'];
			}else{
				$data['exists'] = '1';
			}

			if(isset($this->session->data['shipping_address']['created'])){
				$data['address_id'] = $this->session->data['shipping_address']['created'];
			}
			if($data['address_id'] != 0 && $this->model_account_address->getAddress($data['address_id'])){
				$shipping_address = $this->model_account_address->getAddress($data['address_id']);
			}

			if($this->data['address_style'] == 'radio'){
				$shipping_address['exists'] =  $data['exists'] = $data['address_id'];		 
			}
			
		}

		$this->data['addresses'] = $this->session->data['addresses'];
		
		//Set session
		$country_data = $this->get_country_data($shipping_address['country_id'], $shipping_address['zone_id']);
		if (is_array($country_data)) $shipping_address = array_merge($shipping_address, $country_data);

		$this->tax->setShippingAddress($shipping_address['country_id'], $shipping_address['zone_id']);
		$this->data['shipping_address'] = $data;
		$this->session->data['shipping_address'] = $shipping_address;
		$this->session->data['guest']['shipping'] = $this->session->data['shipping_address'];

		$this->data['shipping_display'] = ($data['display'] &&  $this->session->data['payment_address']['shipping'] == 0) ;
		
		$this->data['field_view'] = $this->get_field_view($data['fields'], 'shipping_address');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/shipping_address.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/shipping_address.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/shipping_address.tpl';
		}
	
		return $this->render();
		
	}
		
	
/**
 
 * Ger View: Shipping method 
 */
	private function get_shipping_method_view(){

		if(!$this->cart->hasShipping()){ return false; }
		//Load shipping method
		if(isset($this->request->post['shipping_method'])){
			$shipping = explode('.', $this->request->post['shipping_method']);
			$this->session->data['shipping_method'] = (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) ? $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]] : $this->session->data['default_shipping_method'];
		}
				
		if(!isset($this->session->data['shipping_method']['code']) || 
			!isset($this->session->data['shipping_method']['title']) ||
			!isset($this->session->data['shipping_method']['cost'])){
			$this->session->data['shipping_method'] = $this->session->data['default_shipping_method'];	
		}

		if ((!$this->cart->hasProducts() && !empty($this->session->data['vouchers']))){
			$this->data['shipping_methods'] = array();
      	}elseif($this->cart->hasProducts() && !$this->cart->hasShipping()){
			$this->data['shipping_methods'] = array();
		}else{
				
			if (isset($this->session->data['shipping_methods'])) {
				$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
			} else {
				$this->data['shipping_methods'] = array();
			}
			
			if (isset($this->session->data['shipping_method']['code'])) {
				$this->data['code'] = $this->session->data['shipping_method']['code'];
			} else {
				$this->data['code'] = '';
			}
		}
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		
		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
			}
		$this->data['settings'] = $this->settings;

		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->session->data['account']]['shipping_method'],$this->settings['step']['shipping_method']);
		
		$lang = $this->language_merge($this->data['data'], $this->texts);
		$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl')) {
          $this->template = $this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/shipping_method.tpl';
		}
		
		return $this->render();
	}
	
	
/**
 
 *	Get View: Payment method
 */
	private function get_payment_method_view(){
		$this->debug_log('GET_PAYMENT_METHOD_VIEW-->'.json_encode($this->session->data), __LINE__);
		//Load payment methods
		$this->session->data['payment_methods'] = $this->get_payment_methods($this->session->data['payment_address']);

		if(isset($this->settings['step']['payment_method']['cost']) && is_array($this->settings['step']['payment_method']['cost'])){
			$this->get_total_data($total_data, $total, $taxes);
			foreach($this->settings['step']['payment_method']['cost'] as $payment_method){
				if(isset($this->session->data['payment_methods'][$payment_method['payment_method']])) {

					if(preg_match("/[0-99]%/", $payment_method['cost'])) {
						$payment_method['cost'] =  $total*(floatval($payment_method['cost'])/(100+floatval($payment_method['cost'])));
					}
					$this->session->data['payment_methods'][$payment_method['payment_method']]['cost'] = $this->currency->format($payment_method['cost']);
					
				}
			}
		}
		
	
		if(!empty($this->session->data['payment_methods'])){
			$first = current($this->session->data['payment_methods']);
			$default_payment_method = (isset($this->session->data['payment_methods'][$this->settings['step']['payment_method']['default_option']])) ? $this->session->data['payment_methods'][$this->settings['step']['payment_method']['default_option']] : $first;
		}else{
			$default_payment_method = null;
		}
		
		//Load payment method
		if(isset($this->request->post['payment_method'])){
			$this->session->data['payment_method'] = (isset($this->session->data['payment_methods'][$this->request->post['payment_method']]))? $this->session->data['payment_methods'][$this->request->post['payment_method']] : $default_payment_method; 
		}
		
		if(!isset($this->session->data['payment_method']['code']) || 
			!isset($this->session->data['payment_method']['title']) ||
			!isset($this->session->data['payment_method']['sort_order'])){
			$this->session->data['payment_method'] = $default_payment_method; 	
		}

		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['error_warning'] = '';
		$this->data['payment_methods'] = '';

		if (isset($this->session->data['payment_methods']) && !empty($this->session->data['payment_methods'])) {

			$this->data['payment_methods'] = $this->session->data['payment_methods']; 

			if (isset($this->session->data['payment_method']['code'])) {
				$this->data['code'] = $this->session->data['payment_method']['code'];
			} else {
				$this->data['code'] = '';
			}

			$this->data['settings'] = $this->settings;
			$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->session->data['account']]['payment_method'],$this->settings['step']['payment_method']);
			$lang = $this->language_merge($this->data['data'], $this->texts);
			$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);

		} else {
			
			$this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment_method.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment_method.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/payment_method.tpl';
		}
		
		return $this->render();
	}
		
/**
 
 *	Get View: Cart
 */
	private function get_cart_view(){

		if($this->cart->getProducts() || !empty($this->session->data['vouchers'])){
			$this->session->data['shipping_methods'] = $this->get_shipping_methods($this->session->data['shipping_address']);
			if(isset($this->request->post['shipping_method'])){
				$shipping = explode('.', $this->request->post['shipping_method']);
				$this->session->data['shipping_method'] = (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) ? $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]] : $this->session->data['default_shipping_method'];
			}
			$this->get_total_data($total_data, $total, $taxes);
	
			$points = $this->customer->getRewardPoints();
	
			$points_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if (isset($product['points']) && $product['points']) {
					$points_total += $product['points'];
				}
			}	
			
			if(!$this->session->data['min_order']){
				$this->data['error']['error_min_order'] = sprintf($this->settings['general']['min_order']['text'][(int)$this->config->get('config_language_id')], $this->currency->format($this->settings['general']['min_order']['value']));
			}
			if(!$this->session->data['min_quantity']){
				$this->data['error']['error_min_order'] = sprintf($this->settings['general']['min_quantity']['text'][(int)$this->config->get('config_language_id')], $this->settings['general']['min_quantity']['value']);
			}

			if (!$this->cart->hasStock() && $this->config->get('config_stock_warning')) {
				if(!$this->config->get('config_stock_checkout')){
      				$this->data['error']['error_stock'] = $this->language->get('error_stock');	
				}
			} 

			if(!$this->session->data['min_quantity_product']){
				$this->language->load('checkout/cart');
				$this->data['error']['error_min_order'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
			}

			$this->data['column_image'] = $this->language->get('column_image');
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['text_comments'] = $this->language->get('text_comments');
			$this->data['text_use_coupon'] = $this->language->get('text_use_coupon');
			$this->data['text_use_voucher'] = $this->language->get('text_use_voucher');
			$this->data['text_use_reward'] = sprintf($this->language->get('text_use_reward'), $points);
			$this->data['coupon_status'] = ( $this->settings['option'][$this->session->data['account']]['cart']['option']['coupon']['display'] && $this->config->get('coupon_status'));
			$this->data['voucher_status'] = ( $this->settings['option'][$this->session->data['account']]['cart']['option']['voucher']['display'] && $this->config->get('voucher_status'));
			$this->data['reward_status'] = ( $this->settings['option'][$this->session->data['account']]['cart']['option']['reward']['display'] && $points && $points_total && $this->config->get('reward_status'));
	
			$this->data['products'] = array();
			
			
			/*1.5.1x*/
			if(preg_match("/1.5.1/i", VERSION)){
			$this->language->load('total/coupon');
			$this->data['text_use_coupon'] = $this->language->get('entry_coupon');
			$this->language->load('total/voucher');
			$this->data['text_use_voucher'] = $this->language->get('heading_title');
			$this->language->load('total/reward');
			$this->data['text_use_reward'] = sprintf($this->language->get('entry_reward'), $points );
			
			}
			
			
			foreach ($this->session->data['cart'] as $key => $value) {
				$this->cart->update($key, $value);
			}
			
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
										
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}  
				
	 			if ($product['image']) {
					$thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$thumb = '';
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->settings['general']['cart_image_size']['width'], $this->settings['general']['cart_image_size']['height']);
				} else {
					$image = '';
				}

				$this->data['products'][] = array(
					'product_id' => $product['key'],
					'thumb'    	 => $thumb,
					'image'    	 => $image,
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'stock'    	 => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				); 
			} 


			// Gift Voucher
			$this->data['vouchers'] = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}  
			
			$this->data['coupon_status'] = $this->config->get('coupon_status');
			
			if (isset($this->request->post['coupon'])) {
				$this->data['coupon'] = $this->request->post['coupon'];			
			} elseif (isset($this->session->data['coupon'])) {
				$this->data['coupon'] = $this->session->data['coupon'];
			} else {
				$this->data['coupon'] = '';
			}
			
			$this->data['voucher_status'] = $this->config->get('voucher_status');
			
			if (isset($this->request->post['voucher'])) {
				$this->data['voucher'] = $this->request->post['voucher'];				
			} elseif (isset($this->session->data['voucher'])) {
				$this->data['voucher'] = $this->session->data['voucher'];
			} else {
				$this->data['voucher'] = '';
			}
			
			$this->data['reward_status'] = ($points && $points_total && $this->config->get('reward_status'));
			
			if (isset($this->request->post['reward'])) {
				$this->data['reward'] = $this->request->post['reward'];				
			} elseif (isset($this->session->data['reward'])) {
				$this->data['reward'] = $this->session->data['reward'];
			} else {
				$this->data['reward'] = '';
			}
		
			$this->data['totals'] = $total_data;
	
			$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->session->data['account']]['cart'], $this->settings['step']['cart']);
			$this->data['settings'] = $this->settings;
			$lang = $this->language_merge($this->data['data']['option'], $this->texts);
			$this->data['data']['option'] = $this->array_merge_recursive_distinct($this->data['data']['option'], $lang);
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/quickcheckout/cart.tpl';
			}else{
				$this->template = 'default/template/quickcheckout/cart.tpl';
			}

			return $this->render();	
			
		}else{
			return false;
		}
	}

/**
 
 *	Get View: Payment
 */
	private function get_payment_view(){
		if($this->cart->getProducts() || !empty($this->session->data['vouchers'])){

		$this->data['payment'] = '';
		if(isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code']){
			$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
		}
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/payment.tpl';
		}	
		return $this->render();	
	}else{
		return false;
		}
	}

/**
 
 *	Get View: Confirm
 */
	private function get_confirm_view(){
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){

			$this->data['button_confirm'] = $this->language->get('button_confirm');	
		
			$data = $this->session->data['qc_settings']['option'][$this->session->data['account']]['confirm'];

			if(isset($this->session->data['confirm'])){
				foreach($this->session->data['confirm'] as $field => $value){
					if(isset($data['fields'][$field])){
						$data['fields'][$field]['value'] = $value;
					}
				}
			}

			//Set default values
			$confirm = array();
			foreach($data['fields'] as $field => $value){
				$shipping_address[$field] = '';
				if(isset($value['value'])){
					$confirm[$field] = $value['value'];
				}
			}

			$this->data['confirm'] = $data;
			$this->session->data['confirm'] = $confirm; 

			$this->update_order();
			
			if(isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] != ''){
				$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
			}else{
				$this->data['payment'] = '';
				$this->data['error'] = 'No payment method loaded';
			}

			$this->data['button_confirm_display'] = $this->cart->hasStock() ? true : $this->config->get('config_stock_checkout');
			if(!$this->session->data['min_order']){
				$this->data['button_confirm_display'] = false;
			}
			if(!$this->session->data['min_quantity']){
				$this->data['button_confirm_display'] = false;
			}
			if(!$this->session->data['min_quantity_product']){
				$this->data['button_confirm_display'] = false;
			}

			
				
			$this->data['field_view'] = $this->get_field_view($data['fields'], 'confirm');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/confirm.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/quickcheckout/confirm.tpl';
			}else{
				$this->template = 'default/template/quickcheckout/confirm.tpl';
			}	
			return $this->render();	
		}else{
			return false;
		}
	}

/**
 
 *	Create Order
 */
	private function create_order(){
		$this->get_total_data($total_data, $total, $taxes);
			$data = array();
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
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
			
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
						
			$data['total'] = $total;
			
			if(preg_match("/1.5.1/i", VERSION)){
				$this->session->data['order_id'] = $this->model_quickcheckout_order->addOrder151($data);
			}else{
				$this->session->data['order_id'] = $this->model_quickcheckout_order->addOrder($data);
			}	
	}

/**
 
 *	Confirm Order
 */
	public function confirm_order(){
		
		$this->load_settings();
		
		$this->get_total_data($total_data, $total, $taxes);
		$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}

			if(!$this->session->data['payment_address']['email'] || $this->session->data['payment_address']['email']==""){
				$this->session->data['payment_address']['email'] = $this->settings['general']['default_email'];
			}
	
			if($this->customer->isLogged() && $this->session->data['payment_address']['address_id'] == 0){
				if(isset($this->session->data['payment_address']['created'])){
					$this->model_account_address->editAddress($this->session->data['payment_address']['created'], $this->session->data['payment_address']);
					$this->session->data['payment_address']['address_id'] = $this->session->data['payment_address']['created'];
				}else{
					$this->session->data['payment_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['payment_address']);
					$this->session->data['payment_address']['created'] = $this->session->data['payment_address']['address_id'];
				}
				//$this->session->data['payment_address']['address_id'] = $this->session->data['payment_address']['address_id'];

			}


			
			if($this->customer->isLogged() 
				&& $this->session->data['shipping_address']['exists'] == 0 
				&& $this->settings['option'][$this->session->data['account']]['shipping_address']['display'] 
				&& $this->session->data['payment_address']['shipping'] == 0){

				if(isset($this->session->data['shipping_address']['created'])){
					$this->model_account_address->editAddress($this->session->data['shipping_address']['created'], $this->session->data['shipping_address']);
				}else{
					$this->session->data['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['shipping_address']);
					$this->session->data['shipping_address']['created'] = $this->session->data['shipping_address']['address_id'];
				}
				//$this->session->data['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'];
			}

			if($this->session->data['account'] == 'register'){
				$this->create_customer($this->session->data['payment_address']);
				$this->customer->login($this->session->data['payment_address']['email'], $this->session->data['payment_address']['password']);
				$this->session->data['payment_address']['registered'] = 1;
				$this->session->data['payment_address']['exists'] = 1;
				$this->session->data['shipping_address']['registered'] = 1;
				$this->session->data['shipping_address']['exists'] = 1;
				if(!$this->session->data['payment_address']['shipping'] && $this->settings['option'][$this->session->data['account']]['shipping_address']['display']){
					$this->session->data['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['shipping_address']);	
					$this->session->data['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'];
				}
			}	
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();


			} elseif (isset($this->session->data['payment_address']) && isset($this->session->data['payment_address']['firstname'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['payment_address']['customer_group_id'];
				$data['firstname'] = $this->session->data['payment_address']['firstname'];
				$data['lastname'] = $this->session->data['payment_address']['lastname'];
				$data['email'] = $this->session->data['payment_address']['email'];
				$data['telephone'] = $this->session->data['payment_address']['telephone'];
				$data['fax'] = $this->session->data['payment_address']['fax'];
			} else {
				return false;
			}
			
					
		

			$payment_address = $this->session->data['payment_address'];

			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->session->data['payment_method']['title'])) {
				if ($this->session->data['payment_method']['code']=="klarna_invoice") $data['payment_method'] = "Klarna Factuur";
				else $data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
						
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
/*				$shipping_address =  $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id']);
				$this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $shipping_address);*/
				$shipping_address = $this->session->data['shipping_address'];
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
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
						'code'             => substr(md5(mt_rand()), 0, 10),
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
			$data['comment'] = (isset($this->session->data['confirm']['comment'])) ? $this->session->data['confirm']['comment'] : '';
			$data['total'] = $total;
			
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
			
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
			if(preg_match("/1.5.2/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder152($this->session->data['order_id'], $data);
			}elseif(preg_match("/1.5.1/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder151($this->session->data['order_id'], $data);
			}else{
				$this->model_quickcheckout_order->updateOrder($this->session->data['order_id'], $data);
			}
		}
	
/**
 
 *	Update Order
 */
	function update_order() {
		
		$this->get_total_data($total_data, $total, $taxes);
		$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();

			} elseif (isset($this->session->data['payment_address']) && isset($this->session->data['payment_address']['firstname'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['payment_address']['customer_group_id'];
				$data['firstname'] = $this->session->data['payment_address']['firstname'];
				$data['lastname'] = $this->session->data['payment_address']['lastname'];
				$data['email'] = $this->session->data['payment_address']['email'];
				if(!$this->session->data['payment_address']['email'] || $this->session->data['payment_address']['email']==""){
				    $data['email'] =$this->settings['general']['default_email'];
				 }
				$data['telephone'] = $this->session->data['payment_address']['telephone'];
				$data['fax'] = $this->session->data['payment_address']['fax'];
			} else {
				return false;
			}
			
			

			$payment_address = $this->session->data['payment_address'];

			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				$shipping_address = $this->session->data['shipping_address'];
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
				if(isset($product['option'])){
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
				}
	 
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => (isset($product['model'])) ? $product['model'] : '',
					'option'     => $option_data,
					'download'   => (isset($product['download'])) ? $product['download'] : '',
					'quantity'   => $product['quantity'],
					'subtract'   => (isset($product['subtract'])) ? $product['subtract'] : '',
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => (isset($product['reward'])) ? $product['reward'] : ''
				); 
			}
			
			// Gift Voucher
			$voucher_data = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
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
			$data['comment'] = (isset($this->session->data['confirm']['comment'])) ? $this->session->data['confirm']['comment'] : '';
			$data['total'] = $total;
			
			// compatibility
			if(preg_match("/1.5.1/i", VERSION)){
				$data['reward'] = $this->cart->getTotalRewardPoints();
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
			
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
			if(preg_match("/1.5.2/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder152($this->session->data['order_id'], $data);
			}elseif(preg_match("/1.5.1/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder151($this->session->data['order_id'], $data);
			}else{
				
				$this->model_quickcheckout_order->updateOrder($this->session->data['order_id'], $data);
			}

	}

/**
 
 *	Helper: create customer
 */
	function create_customer($data) {
			$this->model_account_customer->addCustomer($data);
			return true;
	}

	function get_customer_groups(){
		$result = array();
		if (is_array($this->config->get('config_customer_group_display'))) {
			
			$this->load->model('account/customer_group');
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups  as $customer_group) {
				
				//customer_group_id
				$customer_group['value'] = $customer_group['customer_group_id'];
				//unset($customer_group['customer_group_id']);
				
				//name
				$customer_group['title'] = $customer_group['name'];
				//unset($customer_group['name']);

				if (in_array($customer_group['value'], $this->config->get('config_customer_group_display'))) {
					$result[] = $customer_group;
				}
			}
		}

		return $result;
	}

	private function get_countries(){
		$this->load->model('localisation/country');	
		$countries = $this->model_localisation_country->getCountries();
		$options = array();
		foreach ($countries as $country){
			$country['value'] = $country['country_id']; 
			unset($country['country_id']);
			$options[] = $country;
		}
		return $options;

	}


	private function get_zones_by_country_id($country_id){
		$this->load->model('localisation/zone');
		$zones =  $this->model_localisation_zone->getZonesByCountryId($country_id);
		$options = array();
		foreach ($zones as $zone){
			$zone['value'] = $zone['zone_id']; 
			unset($zone['zone_id']);
			$options[] = $zone;
		}
		return $options;

	}

	/*
	*	Get total data of cart
	*/
	private function get_total_data(&$total_data, &$total, &$taxes){
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
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
		
		return $total_data;
	}
	
	/*
	*	Get shipping methods
	*/
	private function get_shipping_methods($shipping_address){
		$quote_data = array();
		
		$this->load->model('setting/extension');
		
		$results = $this->model_setting_extension->getExtensions('shipping');
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('shipping/' . $result['code']);
				
				$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
	
				if ($quote) {
					$quote_data[$result['code']] = array( 
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();
	  
		foreach ($quote_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $quote_data);
		
		return $quote_data;
		
	}
	/*
	*	Get Payment Methods
	*/
	private function get_payment_methods($payment_address){

		$this->get_total_data($total_data, $total, $taxes);

		$method_data = array();
		
		$results = $this->model_setting_extension->getExtensions('payment');

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('payment/' . $result['code']);
				
				$method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total); 
				
				if ($method) {
					$method_data[$result['code']] = $method;
					
				}
			}
		}

		$sort_order = array();
		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $method_data);			
		return $method_data;		
	}			
			
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}

	
	private function get_country_data($country_id, $zone_id = 0){
		
		$address = array();
		
		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($country_id);
		
		if ($country_info) {
			$address['country'] = $country_info['name'];	
			$address['iso_code_2'] = $country_info['iso_code_2'];
			$address['iso_code_3'] = $country_info['iso_code_3'];
			$address['address_format'] = $country_info['address_format'];
		} else {
			$address['country'] = '';	
			$address['iso_code_2'] = '';
			$address['iso_code_3'] = '';
			$address['address_format'] = '';
		}
						
		$this->load->model('localisation/zone');
		$zone_info = $this->model_localisation_zone->getZone($zone_id);
		
		if ($zone_info) {
			$address['zone'] = $zone_info['name'];
			$address['zone_code'] = $zone_info['code'];
		} else {
			$address['zone'] = '';
			$address['zone_code'] = '';
		}
		return $address;
	}

	
	public function update_settings(){
		$this->load_settings();
		$json = array();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$json['success'] = $this->session->data;
		}else{
			$json['error'] = 'error';
		}
		$this->response->setOutput(json_encode($json));
	}


/**
 
 *	Ajax Functions
 
 */
/**
 *	Ajax: Validate login. Load_settings not needed
 */
	public function login_validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		$this->settings = $this->get_settings();
		//check password
		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
			$json['error']['warning'] = $this->language->get('error_login');
		}
	
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		
		//validate is approved
		if ($customer_info && !$customer_info['approved']) {
			$json['error']['warning'] = $this->language->get('error_approved');
		}		
		
		if (!$json) {
			unset($this->session->data['guest']);
			
			// Default Addresses
			$this->load->model('account/address');
			
			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
									
			if ($address_info) {
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $address_info['country_id'];
					$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
					$this->session->data['shipping_postcode'] = $address_info['postcode'];	
				}
				
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] = $address_info['country_id'];
					$this->session->data['payment_zone_id'] = $address_info['zone_id'];
				}
				$this->session->data['payment_address'] = array_merge($this->session->data['payment_address'],$address_info);
				$this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'],$address_info);
				$this->session->data['payment_address']['exists'] = 1;
				$this->session->data['shipping_address']['exists'] = 1;
			} else {
				unset($this->session->data['shipping_country_id']);	
				unset($this->session->data['shipping_zone_id']);	
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['payment_country_id']);	
				unset($this->session->data['payment_zone_id']);	
			}					
			
			$json['reload'] = $this->settings['general']['login_refresh'];
		}
		$this->response->setOutput(json_encode($json));		
	}

	public function refresh(){
		$this->load_settings();
		$this->response->setOutput($this->index());
	}

	/*
	*	Get login view
	*/	
	public function refresh_payments(){
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			
			
			//Get shipping method
			$this->data['get_shipping_method_view'] = $this->get_shipping_method_view();
			
			//Get payment method
			$this->data['get_payment_method_view'] = $this->get_payment_method_view();
			
			//Get cart view
			$this->data['get_confirm_view'] = $this->get_confirm_view();
		}
		$this->response->setOutput($this->index());
	}
	/*
	*	Get views by ajax request
	*/	
	public function refresh_step1(){	
		$this->load_settings();
		if(($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) && !$this->customer->isLogged()){
			$this->response->setOutput($this->get_login_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step2(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_address_view());
		}else{
			$this->response->setOutput(false);	
		}
	}
	public function refresh_step3(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_shipping_address_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step4(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_shipping_method_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step5(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_method_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step6(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_cart_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	public function refresh_step7(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	public function refresh_step8(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_confirm_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	/*
	*	function for validating the fields input data
	*/
	public function validate_field(){
		
		$result = true;
		if(isset($this->request->post['field'])){
			$this->load_settings();
			
			$field = explode("[", $this->request->post['field']);
			$field[1] =str_replace("]", "", $field[1]);
			$settings = $this->array_merge_recursive_distinct($this->settings['step'][$field[0]], $this->settings['option'][$this->session->data['account']][$field[0]]);
			
			if(isset($settings['fields'][$field[1]]['error'])){
				foreach ($settings['fields'][$field[1]]['error'] as $error){
					if($this->invalid($this->request->post['value'], $error)){
						if(is_array($error['text'])){
							$result = (isset($error['text'][(int)$this->config->get('config_language_id')])) ? $error['text'][(int)$this->config->get('config_language_id')] : $error['text'][1];
						}else{
							$result = $this->language->get($error['text']);	
						}
						if(isset($error['information_id']) && !empty($result)){
						$this->load->model('catalog/information');
							$information_info = $this->model_catalog_information->getInformation($error['information_id']);
							$result = sprintf($result, $information_info['title']);	
						}
						print_r($result);
						break;
					}
				}
			}
			
		}
	}

	/*
	*	function for validating all required fields 
	*/	
	public function validate_all_fields(){
		$this->load->model('catalog/information');
		$json = array();
		$this->load_settings();
		
		foreach($this->request->post as $step => $data){
			if(isset($this->request->post[$step])){
				$settings = $this->array_merge_recursive_distinct($this->settings['step'][$step], $this->settings['option'][$this->session->data['account']][$step]);
				foreach($this->request->post[$step] as $key => $value){
					if(isset($settings['fields'][$key]['error'])){
						foreach ($settings['fields'][$key]['error'] as $error){
							if($this->invalid($value, $error)){
								if(is_array($error['text'])){
									$json['error'][$step][$key] = (isset($error['text'][(int)$this->config->get('config_language_id')])) ? $error['text'][(int)$this->config->get('config_language_id')] : $error['text'][1];
								}else{
									$json['error'][$step][$key] = $this->language->get($error['text']);	
								}	
							}
							if(isset($error['information_id']) && !empty($json['error'][$step][$key])){
								$information_info = $this->model_catalog_information->getInformation($error['information_id']);
								$json['error'][$step][$key] = sprintf($json['error'][$step][$key], $information_info['title']);	
							}
						}
					}
				}
			}
		}
		//shipping
		if(empty($this->session->data['shipping_methods']) && $this->settings['step']['shipping_method']['display']){
			$json['error']['shipping_method']['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		}
		//payment
		if(empty($this->session->data['payment_methods']) && $this->settings['step']['payment_method']['display']){
			$json['error']['payment_method']['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		}
		
		//Confirm
		if(!$this->cart->hasStock() && $this->config->get('config_stock_warning')){
			if(!$this->config->get('config_stock_checkout')){
				$json['error']['confirm']['error_warning']['error_stock'] = $this->language->get('error_stock');
			}
		}
		if(!$this->session->data['min_order']){
			$json['error']['confirm']['error_warning']['error_min_order'] = sprintf($this->settings['general']['min_order']['text'][(int)$this->config->get('config_language_id')], $this->currency->format($this->settings['general']['min_order']['value']));
		}
		$this->response->setOutput(json_encode($json));
	}
	
	public function validate_coupon() {
		$this->language->load('checkout/cart');
		$json = array();
		$this->load->model('checkout/coupon');
		
		if(!empty($this->request->post['coupon'])){
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
			if (!$coupon_info) {			
				$json['error'] = $this->language->get('error_coupon');
			}
		}else{
			$json['error'] = $this->language->get('error_coupon');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_coupon');
		}
		$this->response->setOutput(json_encode($json));
			
	}
	
	public function validate_voucher() {
		$this->language->load('checkout/cart');
		$json = array();
		$this->load->model('checkout/voucher');
		
		if(!empty($this->request->post['voucher'])){			
			$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			
			if (!$voucher_info) {			
				$json['error']= $this->language->get('error_voucher');
			}
		}else{
			$json['error']= $this->language->get('error_voucher');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_voucher');
		}
		$this->response->setOutput(json_encode($json));	
	}
	
	public function validate_reward() {
		$this->language->load('checkout/cart');
		$json = array();
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;

		if(!empty($this->request->post['reward'])){	

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}	
					
			if ($this->request->post['reward'] > $points) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}
			
			if ($this->request->post['reward'] > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
		}else{
			$json['error']= $this->language->get('error_reward');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_reward');
		}
		$this->response->setOutput(json_encode($json));	
	}

	public function get_vouchers_total(){
		$total = 0;

		if (isset($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$total += $voucher['amount'];
			}
		}
		return $total;
	}
	
/**
 
 * Helper functions

 */
	/*
	*	helper function for validating the fields input data
	*/
	public function invalid($value, $data = array()){
		$result = false;
		
		
		if(isset($data['not_empty'])){
			$result = (empty($value)) ? true : false;
		}	
		if(isset($data['min_length']) && !$result){
			$result = (utf8_strlen($value) < $data['min_length'])  ? true : false;	
		}
		if(isset($data['max_length']) && !$result){
			$result = (utf8_strlen($value) > $data['max_length'])  ? true : false;	
		}
		if(isset($data['vat_address']) && !$result){
			$result = (vat_validation($this->session->data[$data['vat_address']]['iso_code_2'], $value) == 'invalid')  ? true : false;	
		}
		if(isset($data['compare_to']) && !$result){
			$field = explode("[", $data['compare_to']);
			$field[1] =str_replace("]", "", $field[1]);
			$data['compare_to'] = (isset($this->session->data[$field[0]][$field[1]])) ? $this->session->data[$field[0]][$field[1]]: '';
			$result = ($value != $data['compare_to'])  ? true : false;
		}
		if(isset($data['regex']) && !$result){
			$result = (!preg_match($data['regex'], $value))  ? true : false;	
		}
		if(isset($data['email_exists']) && !$result){
			$result = ($this->model_account_customer->getTotalCustomersByEmail($value)) ? true : false;
		}
		if(isset($data['checked']) && !$result){
			$result =(!$value);
		}

		return $result ;
	}
	public function language_merge($array, $texts){
		$this->load->model('catalog/information');
		$array_full = $array; 
		$result = array();

		$result = $this->cache->get('quickcheckout.language_merge.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . md5(serialize($array)) . '.' . md5(serialize($texts)));

		if(!$result){
			foreach ($array as $key => $value){
				foreach ($texts as $text){
					if(isset($array_full[$text])){
						if(!is_array($array_full[$text])){
							$result[$text] = $this->language->get($array_full[$text]);	
						}else{
							if(isset($array_full[$text][(int)$this->config->get('config_language_id')])){
								$result[$text] = $array_full[$text][(int)$this->config->get('config_language_id')];
							}else{
								$result[$text] = current($array_full[$text]);
							}
						}
						if((strpos($result[$text], '%s') !== false) && isset($array_full['information_id'])){
							$information_info = $this->model_catalog_information->getInformation($array_full['information_id']);
							
							if(isset($information_info['title']) && substr_count($result[$text], '%s') == 3){
								$result[$text] = sprintf($result[$text], $this->url->link('information/information/info', 'information_id=' . $array_full['information_id'], 'SSL'), $information_info['title'], $information_info['title']);	
							}
						}
					}						
				}
				if(is_array($array_full[$key])){	
							$result[$key] = $this->language_merge_loop($array_full[$key], $texts);	
				}
				
			}
			$this->cache->set('quickcheckout.language_merge.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . md5(serialize($array)) . '.' . md5(serialize($texts)), $result);
		}

		return $result;
		
	}

	public function language_merge_loop($array, $texts){
		$this->load->model('catalog/information');
		$array_full = $array; 
		$result = array();
			foreach ($array as $key => $value){
				foreach ($texts as $text){
					if(isset($array_full[$text])){
						if(!is_array($array_full[$text])){
							$result[$text] = $this->language->get($array_full[$text]);	
						} else {
							if(isset($array_full[$text][(int)$this->config->get('config_language_id')])){
								$result[$text] = $array_full[$text][(int)$this->config->get('config_language_id')];
							} else {
								$result[$text] = current($array_full[$text]);
							}
						}
						if((strpos($result[$text], '%s') !== false) && isset($array_full['information_id'])){
							$information_info = $this->model_catalog_information->getInformation($array_full['information_id']);
							if (strpos($result[$text], '<a') !== false) {
								if(isset($information_info['title']) && substr_count($result[$text], '%s') == 3){
									$result[$text] = sprintf($result[$text], $this->url->link('information/information/info', 'information_id=' . $array_full['information_id'], 'SSL'), $information_info['title'], $information_info['title']);	
								}
							} elseif (substr_count($result[$text], '%s') == 1 && isset($information_info['title'])) {
								$result[$text] = sprintf($result[$text], $information_info['title']);
							}
							
						}
					}						
				}
				if(is_array($array_full[$key])){	
							$result[$key] = $this->language_merge_loop($array_full[$key], $texts);	
				}
				
			}

		return $result;
		
	}

	public function array_merge_recursive_distinct( array &$array1, array &$array2 ){
		$merged = $array1;
		$result = array();	
		$result = $this->cache->get('quickcheckout.array_merge_recursive_distinct.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . md5(serialize($array1)) . '.' . md5(serialize($array2)));
		if(!$result){
			foreach ($array2 as $key => &$value) {
				if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])){
					$merged [$key] = $this->array_merge_recursive_distinct_loop($merged[$key], $value);
				}else{
					$merged [$key] = $value;
				}
			}
			$this->cache->set('quickcheckout.array_merge_recursive_distinct.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . md5(serialize($array1)) . '.' . md5(serialize($array2)), $merged);
			$result = $merged;
		}
		return $result;
	}

	public function array_merge_recursive_distinct_loop( array &$array1, array &$array2 )
	{
	  $merged = $array1;	
	  foreach ( $array2 as $key => &$value )
		  {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
			  $merged [$key] = $this->array_merge_recursive_distinct_loop ( $merged [$key], $value );
			}
			else
			{
			  $merged [$key] = $value;
			}
		  }
		
	  return $merged;
	}

	public function isInstalled($code) {
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}

	public function is_guest_checkout_allowed(){
		return ($this->config->get('config_guest_checkout') 
				&& !$this->config->get('config_customer_price') 
				&& !$this->cart->hasDownload());
	}
		

	/**
 * Used by index()
 */
	private function load_head_files(){
		//Load Scripts
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addScript('catalog/view/javascript/quickcheckout/jquery.timer.js');
		$this->document->addScript('catalog/view/javascript/quickcheckout/tinysort/jquery.tinysort.min.js');
		
		if($this->settings['general']['uniform']){
			$this->document->addScript('catalog/view/javascript/quickcheckout/uniform/jquery.uniform.js');
			$this->document->addStyle('catalog/view/javascript/quickcheckout/uniform/css/uniform.default.css');
		}
		$this->document->addScript('catalog/view/javascript/quickcheckout/tooltip/tooltip.js');
		$this->document->addScript('catalog/view/javascript/quickcheckout/spin.min.js');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout/icon/styles.css');

		//switchery
		// $this->document->addScript('catalog/view/javascript/quickcheckout/switchery/switchery.min.js');
		// $this->document->addStyle('catalog/view/javascript/quickcheckout/switchery/switchery.min.css');


		//Load Styles
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/quickcheckout.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/quickcheckout.css?'.date('m'));
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout/quickcheckout.css?'.date('m'));
		}

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/mobile.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/mobile.css?'.date('m'));
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout/mobile.css?'.date('m'));
		}

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/theme/'.$this->settings['general']['theme'].'.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quickcheckout/theme/'.$this->settings['general']['theme'].'.css?'.date('m'));
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout/theme/'.$this->settings['general']['theme'].'.css?'.date('m'));
		}

		$this->document->addLink('//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700&subset=latin,cyrillic', "stylesheet");
	}

/**
 * Used by index()
 */
	private function validate(){
		$result = false;
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->load->model('setting/setting');
			$result = $this->model_setting_setting->getSetting('quickcheckout', $this->config->get('config_store_id'));
			if($result){
				$result = true;
			}
		}
		return $result;
	}
/**
 * Used by index()
 */
	private function get_logo(){
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$logo = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$logo = '';
		}

		return $logo;
	}

/**
 * Used by load_settings()
 */
	private function get_settings(){
		if(!isset($this->session->data['qc_settings'])){
			$this->set_settings();
		}

		return $this->session->data['qc_settings'];
	}

	private function check_order_id(){
		if(isset($this->session->data['order_id'])){
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info && $order_info['order_status_id']) {
				unset($this->session->data['order_id']);
			}
		}
	}

	private function clear_session(){
		if($this->settings['general']['clear_session']){
			unset($this->session->data['payment_address']);
			unset($this->session->data['shipping_address']);
		}
	}

/**
 * Used by load_settings()
 */
	private function set_settings(){
		$this->settings = $this->config->get('quickcheckout');
		$this->config->load('quickcheckout_settings');
		$settings = $this->config->get('quickcheckout_settings');
		$settings['general']['default_email'] = $this->config->get('config_email');
		$settings['step']['payment_address']['fields']['agree']['information_id'] = $this->config->get('config_account_id');
		$settings['step']['payment_address']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_account_id');
		$settings['step']['confirm']['fields']['agree']['information_id'] = $this->config->get('config_checkout_id');
		$settings['step']['confirm']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_checkout_id');
		
		
		if(!empty($this->settings)){
			$this->session->data['qc_settings'] = $this->array_merge_recursive_distinct($settings, $this->settings);
		}else{
			$this->session->data['qc_settings'] = $settings;
		}


		foreach($this->session->data['qc_settings']['option'] as $account => $value){
			$this->session->data['qc_settings']['option'][$account] = $this->array_merge_recursive_distinct( $this->session->data['qc_settings']['step'], $this->session->data['qc_settings']['option'][$account]);
			$lang = $this->language_merge($this->session->data['qc_settings']['option'][$account], $this->texts);
			$this->session->data['qc_settings']['option'][$account] = $this->array_merge_recursive_distinct($this->session->data['qc_settings']['option'][$account], $lang);

			foreach($this->session->data['qc_settings']['option'][$account] as $step => $value){
				if(isset($this->session->data['qc_settings']['option'][$account][$step]['fields'])){
					$sort_order = array(); 
					foreach ($this->session->data['qc_settings']['option'][$account][$step]['fields'] as $key => $value) {
						if(isset($value['sort_order'])){
			      			$sort_order[$key] = $value['sort_order'];
						}else{
							unset($this->session->data['qc_settings']['option'][$account][$step]['fields'][$key]);
							}
			    	}
					array_multisort($sort_order, SORT_ASC, $this->session->data['qc_settings']['option'][$account][$step]['fields']);
				}
			}
			$this->session->data['qc_settings']['option'][$account]['payment_address']['fields']['newsletter']['title'] = sprintf($this->session->data['qc_settings']['option'][$account]['payment_address']['fields']['newsletter']['title'], $this->config->get('config_name'));
		}


		$this->session->data['qc_settings'] = $this->array_merge_recursive_distinct($this->session->data['qc_settings'], $this->settings);
		$this->session->data['qc_settings']['step']['payment_method']['cost'] = $this->get_d_payment_fee();

		return $this->session->data['qc_settings'];
		
	}
/**
 * Used by get_login_view()
 */	
	private function get_social_login_providers(){
		$this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_login/styles.css');
		$this->load->language('module/d_social_login');

		$this->session->data['d_social_login']['return_url'] = $this->getCurrentUrl();

		$this->data['button_sign_in'] = $this->language->get('button_sign_in');
		$this->config->load($this->check_d_social_login());
		$social_login_settings = $this->config->get('d_social_login_settings');

		$social_login = $this->array_merge_recursive_distinct($social_login_settings, $this->settings['general']['social_login']);
		$providers = $social_login['providers'];

		$sort_order = array(); 
		foreach ($providers as $key => $value) {
			if(isset($value['sort_order'])){
      			$sort_order[$key] = $value['sort_order'];
			}else{
				unset($providers[$key]);
			}
    	}
		array_multisort($sort_order, SORT_ASC, $providers);

      	$data = $providers; 
      	foreach($providers as $key => $val) {
      		$data[$key]['heading'] = $this->language->get('text_sign_in_with_'.$val['id']);
      	}

      	return $data;
    }
/**
 * Used by get_social_login_providers()
 */
	public function check_d_social_login(){
			if($this->isInstalled('d_social_login')){
				$full = DIR_SYSTEM . "config/d_social_login.php";
				$light = DIR_SYSTEM . "config/d_social_login_lite.php"; 
				if (file_exists($full)) { 
					return 'd_social_login';
				} elseif (file_exists($light)) {
					return 'd_social_login_lite';
				}else{
					return false;
				}
			}else{
				return false;
			}

	}

	public function get_d_payment_fee(){
		if($this->config->get('d_payment_fee_module')){
			$modules = $this->config->get('d_payment_fee_module');
			return $modules;
		}
		return false;
	}
/**
 * Used by get_login_view()
 */
	public static function getCurrentUrl( $request_uri = true ) 
	{
		if(
			isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 )
		|| 	isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
		){
			$protocol = 'https://';
		}
		else {
			$protocol = 'http://';
		}

		$url = $protocol . $_SERVER['HTTP_HOST'];

		if( isset( $_SERVER['SERVER_PORT'] ) && strpos( $url, ':'.$_SERVER['SERVER_PORT'] ) === FALSE ) {
			$url .= ($protocol === 'http://' && $_SERVER['SERVER_PORT'] != 80 && !isset( $_SERVER['HTTP_X_FORWARDED_PROTO']))
				|| ($protocol === 'https://' && $_SERVER['SERVER_PORT'] != 443 && !isset( $_SERVER['HTTP_X_FORWARDED_PROTO']))
				? ':' . $_SERVER['SERVER_PORT'] 
				: '';
		}

		if( $request_uri ){
			$url .= $_SERVER['REQUEST_URI'];
		}
		else{
			$url .= $_SERVER['PHP_SELF'];
		}

		// return current url
		return $url;
	}
	public function debug_log( $message, $line, $object = NULL )
	{

		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];

		if($this->time !== ''){
			$time = round(($time - $this->time ), 4);
		}else{
			$this->time = $time;
		}

		if( $this->debug_on ){
			$datetime = new DateTime();
			$datetime =  $datetime->format(DATE_ATOM);

			file_put_contents( 
				$this->debug_path , 
				"AQC DEBUG -- line:" . $line . " -- speed:" . $time ."sec. -- date:" . $datetime . " -- text:" . $message . " -- " . print_r($object, true) . "\n", 
				FILE_APPEND
			);
		}
	}
	
	public function debug(){
		$this->load_settings();
		$this->data['settings'] = $this->settings;
		$this->data['checkout'] = $this->session->data;
		
		//Get template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/debug.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/debug.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/debug.tpl';
		}
		
		//Render all
		$this->response->setOutput($this->render());
	}

	public function after_load_settings(){
		if(isset($this->session->data['payment_method']['title'])){
			if(strpos($this->session->data['payment_method']['title'], 'larna Factuur') ){
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->db->escape('Klarna Factuur') . "' WHERE `order_id` = " . (int)$this->session->data['order_id']);
			}
			
			if(strpos($this->session->data['payment_method']['title'], 'larna Invoice') ){
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->db->escape('Klarna Invoice') . "' WHERE `order_id` = " . (int)$this->session->data['order_id']);
			}	
		}
		
	}
}


?>