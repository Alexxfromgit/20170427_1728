<?php 
/*
@author    Dmitriy Kubarev
@link    http://www.simpleopencart.com
@link    http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  
class ControllerCheckoutSimpleCheckoutCart extends Controller { 
    static $error = array();
    static $updated = false;

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('cart');

        $this->language->load('checkout/cart');
        $this->language->load('checkout/simplecheckout');
    }
    
    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $this->data['attention'] = '';
        
        if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
            $this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/simpleregister'));
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder();
        }
        
        $this->data['error_warning'] = '';
        
        if (isset(self::$error['warning'])) {
            $this->data['error_warning'] = self::$error['warning'];
        }
        
        if (!$this->cart->hasStock()) {
            if ($this->config->get('config_stock_warning')) {
                $this->data['error_warning'] = $this->language->get('error_stock');
            }
            if (!$this->config->get('config_stock_checkout')) {
                $this->data['error_warning'] = $this->language->get('error_stock');
                $this->simplecheckout->addError();
                $this->simplecheckout->blockOrder();
            }
        }

        $customerGroupId = $this->simplecheckout->getCustomerGroupId();
        
        $useTotal    = $this->simplecheckout->getSettingValue('useTotal');

        $tmp = $this->simplecheckout->getSettingValue('minAmount');
        $minAmount = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;
        
        $tmp = $this->simplecheckout->getSettingValue('maxAmount');
        $maxAmount = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('minQuantity');
        $minQuantity = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;
        
        $tmp = $this->simplecheckout->getSettingValue('maxQuantity');
        $maxQuantity = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('minWeight');
        $minWeight = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;
        
        $tmp = $this->simplecheckout->getSettingValue('maxWeight');
        $maxWeight = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;
        
        $cartSubtotal = 0;
        
        if (!empty($minAmount) || !empty($maxAmount)) {
            if ($useTotal) {
                $cartSubtotal = $this->cart->getTotal();
            } else {
                $cartSubtotal = $this->cart->getSubTotal();
            }
        }

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $cartSubtotal += $voucher['amount'];
            }
        } 
        
        $cartQuantity = $this->cart->countProducts();
        $cartWeight = $this->cart->getWeight();

        $this->data['quantity'] = $cartQuantity;

        if (!empty($minAmount) && $minAmount > $cartSubtotal) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder(); 
            $this->data['error_warning'] = sprintf($this->language->get('error_min_amount'),$this->currency->format($minAmount));
        }
        
        if (!empty($maxAmount) && $maxAmount < $cartSubtotal) {
            $this->simplecheckout->blockOrder();    
            $this->simplecheckout->addError();
            $this->data['error_warning'] = sprintf($this->language->get('error_max_amount'),$this->currency->format($maxAmount));
        }
            
        if (!empty($minQuantity) && $minQuantity > $cartQuantity) {
            $this->simplecheckout->blockOrder();    
            $this->simplecheckout->addError();
            $this->data['error_warning'] = sprintf($this->language->get('error_min_quantity'), $minQuantity);
        }
        
        if (!empty($maxQuantity) && $maxQuantity < $cartQuantity) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder(); 
            $this->data['error_warning'] = sprintf($this->language->get('error_max_quantity'), $maxQuantity);
        }
        
        if (!empty($minWeight) && !empty($cartWeight) && $minWeight > $cartWeight) {
            $this->simplecheckout->blockOrder();    
            $this->simplecheckout->addError();
            $this->data['error_warning'] = sprintf($this->language->get('error_min_weight'), $minWeight);
        }
        
        if (!empty($maxWeight) && !empty($cartWeight) && $maxWeight < $cartWeight) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder(); 
            $this->data['error_warning'] = sprintf($this->language->get('error_max_weight'), $maxWeight);
        }
        
        $this->load->model('tool/image');
        
        $this->load->library('encryption');
        
        $this->data['column_image']         = $this->language->get('column_image');
        $this->data['column_name']          = $this->language->get('column_name');
        $this->data['column_model']         = $this->language->get('column_model');
        $this->data['column_quantity']      = $this->language->get('column_quantity');
        $this->data['column_price']         = $this->language->get('column_price');
        $this->data['column_total']         = $this->language->get('column_total');
        $this->data['text_until_cancelled'] = $this->language->get('text_until_cancelled');
        $this->data['text_freq_day']        = $this->language->get('text_freq_day');
        $this->data['text_freq_week']       = $this->language->get('text_freq_week');
        $this->data['text_freq_month']      = $this->language->get('text_freq_month');
        $this->data['text_freq_bi_month']   = $this->language->get('text_freq_bi_month');
        $this->data['text_freq_year']       = $this->language->get('text_freq_year');
        $this->data['text_trial']           = $this->language->get('text_trial');
        $this->data['text_recurring']       = $this->language->get('text_recurring');
        $this->data['text_length']          = $this->language->get('text_length');
        $this->data['text_recurring_item']  = $this->language->get('text_recurring_item');
        $this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
        $this->data['text_cart']            = $this->language->get('text_cart');

        $this->data['button_update'] = $this->language->get('button_update');
        
        $this->data['products'] = array();
        
        $this->data['config_stock_warning'] = $this->config->get('config_stock_warning');
        $this->data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
        
        $products = $this->cart->getProducts();
        
        $points_total = 0;

        $version = $this->simplecheckout->getOpencartVersion();
        
        foreach ($products as $product) {
            
            $product_total = 0;
                
            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }        
            
            if ($product['minimum'] > $product_total) {
                $this->data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                $this->simplecheckout->addError();
                $this->simplecheckout->blockOrder();
            }
            
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];    
                } else {
                    $encryption = new Encryption($this->config->get('config_encryption'));
                    $option_value = $encryption->decrypt($option['option_value']);
                    $filename = substr($option_value, 0, strrpos($option_value, '.'));
                    $value = $filename;
                }
                
                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }
            
            if ($product['image']) {
                $image_cart_width = $this->config->get('config_image_cart_width');
                $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                $image_cart_height = $this->config->get('config_image_cart_height');
                $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                $image = $this->model_tool_image->resize($product['image'], $image_cart_width, $image_cart_height);
            } else {
                $image = '';
            }
            
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            if ($version >= 156) {
                $profile_description = '';
                    
                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring_trial']) {
                        $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                        $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                    }

                    $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));

                    if ($product['recurring_duration']) {
                        $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    } else {
                        $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    }
                }

                $this->data['products'][] = array(
                    'key'                 => $product['key'],
                    'thumb'               => $image,
                    'name'                => $product['name'],
                    'model'               => $product['model'],
                    'option'              => $option_data,
                    'quantity'            => $product['quantity'],
                    'stock'               => $product['stock'],
                    'reward'              => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'               => $price,
                    'total'               => $total,
                    'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'recurring'           => $product['recurring'],
                    'profile_name'        => $product['profile_name'],
                    'profile_description' => $profile_description,
                );
            } else {
                $this->data['products'][] = array(
                    'key'      => $product['key'],
                    'thumb'    => $image,
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'stock'    => $product['stock'],
                    'reward'   => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'    => $price,
                    'total'    => $total,
                    'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            if ($product['points']) {
                $points_total += $product['points'];
            }
        } 
        
        // Gift Voucher
        $this->data['vouchers'] = array();
        
        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $this->data['vouchers'][] = array(
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'])
                );
            }
        }  
        
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();
        
        $this->data['modules'] = array();
        
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {                         
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
                    
                    $this->data['modules'][$result['code']] = true;
                }
            }
            
            $sort_order = array(); 
          
            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }
        
        $this->data['totals'] = $total_data;
        
        $this->data['entry_coupon'] = $this->language->get('entry_coupon');
        $this->data['entry_voucher'] = $this->language->get('entry_voucher');
        
        $points = $this->customer->getRewardPoints();
        $points_to_use = $points > $points_total ? $points_total : $points;
        $this->data['points'] = $points_to_use;
        
        $this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_to_use);
        
        $this->data['reward']  = isset($this->session->data['reward']) ? $this->session->data['reward'] : '';
        $this->data['voucher'] = isset($this->session->data['voucher']) ? $this->session->data['voucher'] : '';
        $this->data['coupon']  = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';
         
        $this->data['display_weight'] = $this->simplecheckout->displayWeight();
        
        if ($this->data['display_weight']) {
            $this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
        }

        $this->data['additional_path'] = $this->simplecheckout->getAdditionalPath();
        $this->data['hide'] = $this->simplecheckout->isBlockHidden();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/simplecheckout_cart.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/simplecheckout_cart.tpl';
        } else {
            $this->template = 'default/template/checkout/simplecheckout_cart.tpl';
        }
        
        $current_theme = $this->config->get('config_template');
        
        if ($current_theme == 'shoppica' || $current_theme == 'shoppica2') {
            $this->data['cart_total'] = $this->currency->format($total);
        } else {
            $this->data['cart_total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        }

        $this->data['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->data['display_model']  = $this->simplecheckout->getSettingValue('displayModel');
        $this->data['display_error']  = $this->simplecheckout->displayError();
        $this->data['has_error']      = $this->simplecheckout->hasError();

        $this->response->setOutput($this->render());  
        $this->simplecheckout->resetCurrentBlock();
    }
    
    public function update() {
        self::$updated = true;

        $this->init();

        if (!isset($this->session->data['vouchers'])) {
            $this->session->data['vouchers'] = array();
        }
        
        // Update
        if (!empty($this->request->post['quantity'])) {
            $keys =  isset($this->session->data['cart']) ? $this->session->data['cart'] : array();
            foreach ($this->request->post['quantity'] as $key => $value) {
                if (!empty($keys) && array_key_exists($key, $keys)) {
                    $this->cart->update($key, $value);
                }
            }
        }
        
        // Remove
        if (!empty($this->request->post['remove'])) {
            $this->cart->remove($this->request->post['remove']);
            unset($this->session->data['vouchers'][$this->request->post['remove']]);
        }
        
        // Coupon    
        if (isset($this->request->post['coupon']) && $this->validateCoupon()) { 
            $this->session->data['coupon'] = trim($this->request->post['coupon']);
            if ($this->session->data['coupon'] == '') {
                unset($this->session->data['coupon']);
            }
        }
        
        // Voucher
        if (isset($this->request->post['voucher']) && $this->validateVoucher()) { 
            $this->session->data['voucher'] = trim($this->request->post['voucher']);
            if ($this->session->data['voucher'] == '') {
                unset($this->session->data['voucher']);
            }
        }
        
        if (!empty($this->request->post['quantity']) || !empty($this->request->post['remove']) || !empty($this->request->post['voucher'])) {
            unset($this->session->data['reward']);
        }
        
        // Reward
        if (isset($this->request->post['reward']) && $this->validateReward()) {
            $this->session->data['reward'] = $this->request->post['reward'];
        }

        if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
            if (!$this->simplecheckout->isAjaxRequest()) {
                $this->redirect($this->url->link('checkout/simplecheckout', '', 'SSL'));                
            } else {
               $this->simplecheckout->setRedirectUrl($this->url->link('checkout/simplecheckout', '', 'SSL'));    
            }
        }

        $this->simplecheckout->resetCurrentBlock();
    }
    
    private function validateCoupon() {
        $this->load->model('checkout/coupon');

        $error = false;
                
        if (!empty($this->request->post['coupon'])) {
            $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);            
            
            if (!$coupon_info) {            
                self::$error['warning'] = $this->language->get('error_coupon');
                $error = true;
            }
        }
        
        return !$error;
    }
    
    private function validateVoucher() {
        $this->load->model('checkout/voucher');

        $error = false;
            
        if (!empty($this->request->post['voucher'])) {
            $voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);            
            
            if (!$voucher_info) {            
                self::$error['warning'] = $this->language->get('error_voucher');
                $error = true;
            }
        }
        
        return !$error;      
    }
    
    private function validateReward() {
        $error = false;

        if (!empty($this->request->post['reward'])) {
            $points = $this->customer->getRewardPoints();
            
            $points_total = 0;
            
            foreach ($this->cart->getProducts() as $product) {
                if ($product['points']) {
                    $points_total += $product['points'];
                }
            }    
            
            if ($this->request->post['reward'] > $points) {
                self::$error['warning'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
                $error = true;
            }
            
            if ($this->request->post['reward'] > $points_total) {
                self::$error['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
                $error = true;
            }
        } else {
            $error = true;
        }

        return !$error;       
    }
}
?>