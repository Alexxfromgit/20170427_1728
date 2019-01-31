<?php   
header("Content-type: text/html; charset=utf-8");

class ControllerModuleKwflycart extends Controller {

	private $_path = HTTPS_SERVER;
	private $_name = 'kw_flycart';

	public function index() {
		$this->language->load('module/' . $this->_name);
		
		$this->data['type'] 		= $this->config->get( $this->_name . '_type');
		$this->data['position'] 	= $this->config->get( $this->_name . '_position');
		$this->data['topions'] 		= $this->config->get( $this->_name . '_topions');
		$this->data['offset_x'] 	= $this->config->get( $this->_name . '_offset_x');
		$this->data['offset_y'] 	= $this->config->get( $this->_name . '_offset_y');
		$this->data['fheight'] 		= $this->config->get( $this->_name . '_fheight');
		$this->data['fwidth'] 		= $this->config->get( $this->_name . '_fwidth');
		$this->data['postype'] 		= $this->config->get( $this->_name . '_postype');
		$this->data['image'] 		= $this->config->get( $this->_name . '_image');
		$this->data['tcolor'] 		= $this->config->get( $this->_name . '_tcolor');
		$this->data['tsize'] 		= $this->config->get( $this->_name . '_tsize');
		$this->data['tmtop'] 		= $this->config->get( $this->_name . '_tmtop');
		$this->data['tmright'] 		= $this->config->get( $this->_name . '_tmright');
		$this->data['tmbottom'] 	= $this->config->get( $this->_name . '_tmbottom');
		$this->data['tmleft'] 		= $this->config->get( $this->_name . '_tmleft');
		$this->data['color_bgp'] 	= $this->config->get( $this->_name . '_color_bgp');
		$this->data['head_bgp'] 	= $this->config->get( $this->_name . '_head_bgp');
		$this->data['bhead_bgp'] 	= $this->config->get( $this->_name . '_bhead_bgp');
		$this->data['chead_bgp'] 	= $this->config->get( $this->_name . '_chead_bgp');
		$this->data['close_bg'] 	= $this->config->get( $this->_name . '_close_bg');
		$this->data['remove_bg'] 	= $this->config->get( $this->_name . '_remove_bg');
		$this->data['color_a'] 		= $this->config->get( $this->_name . '_color_a');
		$this->data['color'] 		= $this->config->get( $this->_name . '_color');
		$this->data['border'] 		= $this->config->get( $this->_name . '_border');
		$this->data['scroll'] 		= $this->config->get( $this->_name . '_scroll');
		$this->data['color_fgp'] 	= $this->config->get( $this->_name . '_color_fgp');
		$this->data['color_fbgp']	= $this->config->get( $this->_name . '_color_fbgp');
		$this->data['pbutton'] 		= $this->config->get( $this->_name . '_pbutton');
		$this->data['empty'] 		= $this->config->get( $this->_name . '_empty');
		$this->data['overlay'] 		= $this->config->get( $this->_name . '_overlay');
		$this->data['pselect'] 		= $this->config->get( $this->_name . '_pselect');
		$this->data['bselect'] 		= $this->config->get( $this->_name . '_bselect');
		$this->data['flytype'] 		= $this->config->get( $this->_name . '_flytype');
		$this->data['flyimage'] 	= $this->config->get( $this->_name . '_flyimage');
		$this->data['color_f'] 		= $this->config->get( $this->_name . '_color_f');
		$this->data['frselect'] 	= $this->config->get( $this->_name . '_frselect');
		$this->data['size_f'] 		= $this->config->get( $this->_name . '_size_f');
		$this->data['speed'] 		= $this->config->get( $this->_name . '_speed');
		$this->data['rtselect'] 	= $this->config->get( $this->_name . '_rtselect');
		$this->data['radius'] 		= $this->config->get( $this->_name . '_radius');
		
		$this->document->addStyle($this->_path.'catalog/view/javascript/'.$this->_name.'/css/flycart.css', $rel = 'stylesheet', $media = 'screen');	
		$this->document->addScript($this->_path.'catalog/view/javascript/'.$this->_name.'/js/flycart.js');
		
		if (!empty($this->request->post['quantity'])) {
    		foreach ($this->request->post['quantity'] as $key => $value) {
    			$this->cart->update($key, $value);
    		}
    		
    		unset($this->session->data['shipping_method']);
    		unset($this->session->data['shipping_methods']);
    		unset($this->session->data['payment_method']);
    		unset($this->session->data['payment_methods']); 
    		unset($this->session->data['reward']);		
    	}
		
      	if (isset($this->request->get['remove'])) {
          	$this->cart->remove($this->request->get['remove']);
			
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
      	}	
			
		// Totals
		$this->load->model('setting/extension');
		
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
				
				$sort_order = array(); 
			  
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
	
				array_multisort($sort_order, SORT_ASC, $total_data);			
			}		
		}
		
		$this->data['totals'] = $total_data;
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_cart'] = $this->language->get('text_cart');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['cart'] = $this->url->link('checkout/cart');				
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
			
		foreach ($this->cart->getProducts() as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
			} else {
				$image = '';
			}
							
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
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				);
			}
			
			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
			
			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
			} else {
				$total = false;
			}
													
			$this->data['products'][] = array(
				'key'      => $product['key'],
				'thumb'    => $image,
				'name'     => $product['name'],
				'model'    => $product['model'], 
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $price,	
				'total'    => $total,	
				'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])		
			);
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
					
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/' . $this->_name . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/' . $this->_name . '.tpl';
		} else {
			$this->template = 'default/template/module/' . $this->_name . '.tpl';
		}

		$this->response->setOutput($this->render());
	}
}
?>