<?php

/**
 * Модуль: 			Фильтр товаров для OpenCart
 * Версия: 			3.0.1
 * Сайт: 				ocfilter.com
 * Разработчик: Александр Суруткович
*/

class ControllerModuleOCFilter extends Controller {

  private $link;
  private $path;
  private $params;
	private $module;
  private $min_price = 0;
  private $max_price = 0;
  private $min_price_get;
  private $max_price_get;
  private $category_id;
  private $products_total;
	private $heading_title = '';
  private $options_get = array(); # Массив вида array(option_id => array(value_id => value_id, value_id => value_id)) в GET запросе
	private $product_prices = array();
	private $product_count = array();

  private function construct($settings = array()) {
  	static $module = 0;

		$this->module = $module++;

		if (isset($this->request->get['module'])) {
      $this->module = $this->request->get['module'];
		}

		$this->language->load('module/ocfilter');
		$this->load->model('catalog/ocfilter');
		$this->load->model('catalog/product');
    $this->load->model('tool/image');

  	if (isset($settings['category_id']) && (!isset($this->request->get['path']) || (isset($this->request->get['path']) && isset($this->request->get['product_id'])))) {
      $this->path = (int)$settings['category_id'];

			if ($settings['heading_title']) {
        $this->heading_title = $settings['heading_title'];
			}
		} elseif (isset($this->request->get['path'])) {
      $this->path = (string)$this->request->get['path'];
		}

    if ($this->path) {
			$parts = explode('_', $this->path);
			$this->category_id = (int)end($parts);

      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . (string)$this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . (string)$this->request->get['order'];
      }

      if (isset($this->request->get['limit'])) {
        $url .= '&limit=' . (int)$this->request->get['limit'];
      }

      if (isset($this->request->get[$this->ocfilter['index']])) {
        $this->params = $this->model_catalog_ocfilter->cleanParamsString($this->request->get[$this->ocfilter['index']]);
      }

      $this->link = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->path) . $url);

      if ($this->params) {
				$this->options_get = $this->model_catalog_ocfilter->decodeParamsFromString($this->params);

        if ($this->ocfilter['show_price'] && isset($this->options_get['p']) && $this->options_get['p']) {
					list($this->min_price_get, $this->max_price_get) = explode('-', end($this->options_get['p']));
        }
      }

      $data = array(
				'filter_category_id' => $this->category_id,
        'filter_ocfilter'		 => $this->params
			);

			$this->ocfilter_data = $this->model_catalog_ocfilter->getOCFilterData($data);

			$this->products_total = $this->ocfilter_data['products_total'];

      if ($this->products_total && $this->ocfilter['show_price']) {
        /* TODO */ $data['filter_ocfilter'] = $this->cancelOptionParams('p');

        $this->product_prices = $this->model_catalog_ocfilter->getProductPrices($data);

        if ($this->product_prices) {
          $this->min_price = $this->product_prices['min'];
      	  $this->max_price = $this->product_prices['max'];
        }
      }
    }
  }

	protected function index($settings) {
		$this->construct($settings);

    $this->ocfilter = array_merge($this->ocfilter, $settings);

		if ($this->category_id && $this->products_total) {
      if ($this->options_get) {
        $this->data['heading_title'] = $this->declOfNum($this->products_total, array($this->language->get('text_total_1'), $this->language->get('text_total_2'), $this->language->get('text_total_3')));
			} elseif ($this->heading_title) {
        $this->data['heading_title'] = $this->heading_title;
			} else {
        $this->data['heading_title'] = $this->language->get('heading_title');
      }

			if ($this->min_price_get && $this->min_price_get < $this->min_price) {
				$this->min_price = $this->min_price_get;
      }

			if ($this->max_price_get && $this->max_price_get > $this->max_price) {
				$this->max_price = $this->max_price_get;
      }

      $this->data['options']              = $this->getOCFilterOptions();
			$this->data['price_links'] 					= $this->getPriceLinks();
      $this->data['min_price']            = $this->min_price;
  		$this->data['max_price']            = $this->max_price;
      $this->data['min_price_get']        = $this->min_price_get ? $this->min_price_get : $this->min_price;
      $this->data['max_price_get']        = $this->max_price_get ? $this->max_price_get : $this->max_price;
      $this->data['total']                = $this->products_total;
      $this->data['path']                 = $this->path;
      $this->data['link']                 = $this->link;
      $this->data['params']               = $this->params;

      $this->data['index']   							= $this->ocfilter['index'];
      $this->data['show_button']          = $this->ocfilter['show_button'];
      $this->data['show_values_limit']   	= $this->ocfilter['show_values_limit'];
      $this->data['use_animation']   	    = $this->ocfilter['use_animation'];
      $this->data['template']             = $this->ocfilter['template'];
			$this->data['price_type']         	= $this->ocfilter['price_type'];
			$this->data['position']         		= str_replace('_', '-', $this->ocfilter['position']);

      $this->data['text_show_all']        = $this->language->get('text_show_all');
      $this->data['text_hide']          	= $this->language->get('text_hide');
      $this->data['button_select']        = $this->language->get('button_select');
      $this->data['text_load']            = $this->language->get('text_load');
      $this->data['text_price']           = $this->language->get('text_price');
      $this->data['text_any']           	= $this->language->get('text_any');
      $this->data['text_cancel_all']      = $this->language->get('text_cancel_all');

      $this->data['symbol_left']      		= $this->currency->getSymbolLeft();
      $this->data['symbol_right']      		= $this->currency->getSymbolRight();

      if ($this->min_price_get && $this->max_price_get) {
        $this->data['price'] = 'p' . $this->ocfilter['sep_opt'] . $this->min_price_get . '-' . $this->max_price_get;
      } else {
        $this->data['price'] = '';
      }

      if ($this->ocfilter['show_counter']) {
        $this->data['show_counter'] = 1;
      } else {
        $this->data['show_counter'] = 0;
      }

      if (isset($this->session->data['ocfilter_show_options'])) {
        $this->data['show_options'] = $this->session->data['ocfilter_show_options'];
      } else {
        $this->data['show_options'] = 0;
      }

      if ($this->ocfilter['show_diagram'] && $this->ocfilter['show_price']) {
        $this->data['diagram'] = $this->getDiagram();
      } else {
        $this->data['diagram'] = array();
      }

      if ($this->ocfilter['show_selected'] && $this->options_get) {
        $this->data['selecteds'] = $this->getSelectedOptions();
      } else {
        $this->data['selecteds'] = array();
      }

      if ($this->ocfilter['show_price'] && $this->min_price < $this->max_price - 1) {
        $this->data['show_price'] = 1;
      } else {
        $this->data['show_price'] = 0;
      }

      if ($this->ocfilter['manual_price'] && ($this->ocfilter['price_type'] == 'links-slide' || $this->ocfilter['price_type'] == 'slide')) {
        $this->data['manual_price'] = 1;
      } else {
        $this->data['manual_price'] = 0;
      }

      if ($this->ocfilter['show_price'] && $this->min_price_get && $this->max_price_get) {
        $this->data['show_price_selected'] = 1;
      } else {
        $this->data['show_price_selected'] = 0;
      }

			if ($this->ocfilter['show_options_limit'] && $this->ocfilter['show_options_limit'] < count($this->data['options'])) {
      	$this->data['show_options_limit'] = $this->ocfilter['show_options_limit'];
			} else {
        $this->data['show_options_limit'] = false;
			}
    } else {
      $this->data['options'] = array();
    }

		$this->id = 'ocfilter';

    $this->data['module'] = $this->module;

		if (file_exists(DIR_TEMPLATE . $this->ocfilter['template'] . '/stylesheet/ocfilter/ocfilter.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->ocfilter['template'] . '/stylesheet/ocfilter/ocfilter.css');
		} else {
      $this->document->addStyle('catalog/view/theme/default/stylesheet/ocfilter/ocfilter.css');
		}

    $this->document->addScript('catalog/view/javascript/ocfilter/ocfilter.js');
		$this->document->addScript('catalog/view/javascript/ocfilter/trackbar.js');

    if (file_exists(DIR_TEMPLATE . $this->ocfilter['template'] . '/template/module/ocfilter.tpl')) {
			$this->template = $this->ocfilter['template'] . '/template/module/ocfilter.tpl';
		} else {
			$this->template = 'default/template/module/ocfilter.tpl';
		}

		$this->render();
	}

	private function getOCFilterOptions() {
    $options = array();

    # Stock status filtering
    if ($this->ocfilter['stock_status']) {
			if ($this->ocfilter['stock_status_method'] == 'stock_status_id') {
				$results = $this->model_catalog_ocfilter->getStockStatuses();

	      $options[] = array(
	        'option_id'   => 's',
	        'name'        => $this->language->get('text_stock'),
          'description' => $this->language->get('text_stock_description'),
	        'type'        => $this->ocfilter['stock_status_type'],
	        'values'      => $results
	      );
			} elseif ($this->ocfilter['stock_status_method'] == 'quantity') {
	      $options[0] = array(
	        'option_id'   => 's',
	        'name'        => $this->language->get('text_stock'),
          'description' => $this->language->get('text_stock_description'),
	        'type'        => ($this->ocfilter['stock_out_value'] ? 'radio' : 'checkbox'),
	        'values'      => array(
						array(
							'value_id'    => 'in',
							'name'        => $this->language->get('text_in_stock')
						)
					)
	      );

				if ($this->ocfilter['stock_out_value']) {
          $options[0]['values'][] = array(
						'value_id'    => 'out',
						'name'        => $this->language->get('text_out_of_stock')
					);
				}
			}
    }

    # Manufacturers filtering
    if ($this->ocfilter['manufacturer']) {
  		$results = $this->model_catalog_ocfilter->getManufacturersByCategoryId($this->category_id);

      if ($results) {
        $options[] = array(
          'option_id'   => 'm',
          'name'        => $this->language->get('text_manufacturer'),
          'description' => $this->language->get('text_manufacturer_description'),
          'type'        => $this->ocfilter['manufacturer_type'],
          'values'      => $results
        );
      }
    }

    # Get category options
	  $results = $this->model_catalog_ocfilter->getOCFilterOptionsByCategoryId($this->category_id);

    if ($results) {
			if (isset($this->ocfilter['options_id']) && is_array($this->ocfilter['options_id'])) {
				foreach ($results as $key => $result) {
					if (!in_array($result['option_id'], $this->ocfilter['options_id'])) {
						unset($results[$key]);
					}
				}
			}

	 		$options = array_merge($options, $results);
		}

    $options_data = array();

		if ($options) {
		  foreach ($options as $key => $option) { # Start options each
        $this_option = isset($this->options_get[$option['option_id']]);

				$values = array();

        if ($option['type'] == 'select' || $option['type'] == 'radio') {
          $values[] = array(
            'value_id' => $option['option_id'],
						'id'       => 'cancel-' . $option['option_id'] . $this->module,
            'name'     => $this->language->get('text_any'),
            'params'   => $this->cancelOptionParams($option['option_id']),
						'count'    => 1,//$this->ocfilter_data['counters'][$option['option_id']],
            'selected' => !$this_option
          );
				}

        if ($option['type'] != 'slide' && $option['type'] != 'slide_dual') {
        	if (isset($option['grouping']) && (int)$option['grouping']) {
  					if ((int)$option['grouping'] < 2) {
              $option['grouping'] = 2;
  					}

  	        for ($i = 0; $i < count($option['values']); $i = $i + $option['grouping']) {
  	          $groups = array();
  	          $selected = false;
  						$count = 0;

  						if ($option['type'] == 'select' || $option['type'] == 'radio') {
                $this->params = $this->cancelOptionParams($option['option_id']);
  						}

  	          for ($j = $i; $j < ($i + $option['grouping']); $j++) {
  	            if (isset($option['values'][$j])) {
  								if (isset($this->ocfilter_data['counters'][$option['option_id'] . $option['values'][$j]['value_id']])) {
  									$count += $this->ocfilter_data['counters'][$option['option_id'] . $option['values'][$j]['value_id']];
  								}

  	              $params = $this->getValueParams($option['option_id'], $option['values'][$j]['value_id'], 'checkbox');

  	              $groups[] = $option['values'][$j];

  	              if (isset($this->options_get[$option['option_id']]) && in_array($option['values'][$j]['value_id'], $this->options_get[$option['option_id']])) {
  	                $selected = true;
  	              }

  	              $this->params = $params;
  	            }
  	          }

  	          if ($groups && (!$this->ocfilter['hide_empty_values'] || ($this->ocfilter['hide_empty_values'] && $count))) {
  							$first = array_shift($groups);
                $last = array_pop($groups);

  							if ($count && $this_option && $option['type'] == 'checkbox') {
  								$count = '+' . $count;
  							}

  	            $values[] = array(
  	              'value_id' => $first['value_id'],
  	              'id'       => $option['option_id'] . $first['value_id'] . $this->module,
  	              'name'     => html_entity_decode($first['name'] . ($last ? ' - ' . $last['name'] : '') . $option['postfix'], ENT_QUOTES, 'UTF-8'),
  	              'params'   => $params,
  								'count' 	 => $count,
  	              'selected' => $selected
  	            );
  	          }

  						# Reset params from request
  	          if (isset($this->request->get[$this->ocfilter['index']])) {
  	            $this->params = $this->model_catalog_ocfilter->cleanParamsString($this->request->get[$this->ocfilter['index']]);
  	          } else {
  	            $this->params = '';
  	          }
  	        }
  				} else {
  					foreach ($option['values'] as $value) {
  						$this_value = isset($this->options_get[$option['option_id']]) && in_array($value['value_id'], $this->options_get[$option['option_id']]);

  						if (isset($this->ocfilter_data['counters'][$option['option_id'] . $value['value_id']])) {
  							if ($this_option && $option['type'] == 'checkbox') {
  								$count = '+' . $this->ocfilter_data['counters'][$option['option_id'] . $value['value_id']];
  							} else {
  								$count = $this->ocfilter_data['counters'][$option['option_id'] . $value['value_id']];
  							}
  						} else {
  							$count = 0;
  						}

              if ($count || !$this->ocfilter['hide_empty_values']) {
  							if (isset($option['image']) && $option['image'] && isset($value['image']) && $value['image'] && file_exists(DIR_IMAGE . $value['image'])) {
                  $image = $this->model_tool_image->resize($value['image'], 19, 19);
  							} else {
  								$image = false;
  							}

  		          $values[] = array(
  		            'value_id' => $value['value_id'],
  								'id'       => $option['option_id'] . $value['value_id'] . $this->module,
  		            'name'     => html_entity_decode($value['name'] . (isset($option['postfix']) ? $option['postfix'] : ''), ENT_QUOTES, 'UTF-8'),
  								'color'    => ((isset($value['color']) && $value['color']) ? $value['color'] : '#FFFFFF'),
                  'image'    => $image,
  		            'params'   => $this->getValueParams($option['option_id'], $value['value_id'], $option['type']),
  								'count'    => $count,
  		            'selected' => $this_value
  		          );
  						}
  	        }
  				}
        }

        $options_data[$option['option_id']] = array(
          'option_id'           => $option['option_id'],
          'index'               => count($options_data) + 1,
         	'name'                => html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8'),
          'selectbox'           => (isset($option['selectbox']) ? $option['selectbox'] : false),
          'color'			          => (isset($option['color']) ? $option['color'] : false),
          'image'		            => (isset($option['image']) ? $option['image'] : false),
					'postfix' 		        => (isset($option['postfix']) ? html_entity_decode($option['postfix'], ENT_QUOTES, 'UTF-8') : ''),
          'description'         => (isset($option['description']) ? $option['description'] : ''),
          'slide_value_min'     => (isset($option['slide_value_min']) ? $option['slide_value_min'] : 0),
          'slide_value_max'     => (isset($option['slide_value_max']) ? $option['slide_value_max'] : 0),
          'slide_value_min_get' => (isset($option['slide_value_min']) ? $option['slide_value_min'] : 0),
          'slide_value_max_get' => (isset($option['slide_value_max']) ? $option['slide_value_max'] : 0),
          'type'                => $option['type'],
          'selected'            => $this_option,
          'values'              => $values
        );

        if (($option['type'] == 'slide' || $option['type'] == 'slide_dual') && isset($this->options_get[$option['option_id']][0])) {
          list($options_data[$option['option_id']]['slide_value_min_get'], $options_data[$option['option_id']]['slide_value_max_get']) = explode('-', $this->options_get[$option['option_id']][0]);

          # For getSelectedOptions
          array_unshift($options_data[$option['option_id']]['values'], array(
            'value_id' => $options_data[$option['option_id']]['slide_value_min_get'] . '-' . $options_data[$option['option_id']]['slide_value_max_get'],
            'name'     => $options_data[$option['option_id']]['slide_value_min_get'] . '&nbsp;-&nbsp;' . $options_data[$option['option_id']]['slide_value_max_get'] . $option['postfix']
          ));
        }
      } # End options each
    }

    return $options_data;
  }

	private function getValueParams($option_id, $value_id, $type = 'checkbox') {
		$decoded_params = $this->model_catalog_ocfilter->decodeParamsFromString($this->params);

		if ($type == 'checkbox') {
			if (isset($decoded_params[$option_id])) {
				if (false !== $key = array_search($value_id, $decoded_params[$option_id])) {
					unset($decoded_params[$option_id][$key]);
				} else {
					$decoded_params[$option_id][] = $value_id;
				}
			} else {
				$decoded_params[$option_id] = array($value_id);
			}
 		} elseif ($type == 'select' || $type == 'radio') {
			if (isset($decoded_params[$option_id])) {
				unset($decoded_params[$option_id]);
			}

			$decoded_params[$option_id] = array($value_id);
		}

		return $this->model_catalog_ocfilter->encodeParamsToString($decoded_params);
	}

  private function cancelOptionParams($option_id) {
    if ($this->params) {
			$params = $this->model_catalog_ocfilter->decodeParamsFromString($this->params);

			if (isset($params[$option_id])) {
				unset($params[$option_id]);
			}

			return $this->model_catalog_ocfilter->encodeParamsToString($params);
    }
  }

  private function getSelectedOptions() {
    $selected_options = array();

    $category_options = $this->getOCFilterOptions();

    if ($this->min_price_get && $this->max_price_get) {
      $category_options['p'] = array(
        'name'      => $this->language->get('text_price'),
				'type'      => 'select',
        'selected'  => isset($this->options_get['p']),
        'values'    => array(array(
					'value_id' 	=> $this->min_price_get . '-' . $this->max_price_get,
          'name' 			=> $this->currency->getSymbolLeft() . $this->min_price_get . '&nbsp;-&nbsp;' . $this->max_price_get . $this->currency->getSymbolRight()
				))
      );
    }

		foreach ($category_options as $option_id => $option) {
			if (!$option['selected']) {
				continue;
			}

			$values = array();

			foreach ($option['values'] as $value) {
        if (!in_array($value['value_id'], $this->options_get[$option_id])) {
          continue;
				}

			  $href = $this->link;

        if (count($this->options_get) > 1 || count($this->options_get[$option_id]) > 1) {
          $href .= '&' . $this->ocfilter['index'] . '=';

          if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'slide' || $option['type'] == 'slide_dual') {
            $href .= $this->cancelOptionParams($option_id);
          } else {
            $href .= $value['params'];
          }
        }

        $name = html_entity_decode($value['name'], ENT_QUOTES, 'UTF-8');

        if (false === strpos($value['value_id'], '-') && mb_strlen($name, 'UTF-8') > 20) {
          $name = mb_substr($value['name'], 0, 20, 'UTF-8');
        }

			  $values[] = array(
          'name' => $name,
          'id'   => $option_id . $value['value_id'] . $this->module,
          'href' => $href
        );
			}

			$selected_options[$option_id] = array(
        'name'   		=> $option['name'],
        'values' 		=> $values
      );
		}

    return $selected_options;
  }

	private function getPriceLinks() {
		$price_links = array();

		if ($this->ocfilter['price_type'] == 'links' || $this->ocfilter['price_type'] == 'links-slide') {
			if ($this->ocfilter['price_type'] == 'links-slide') {
				if ($this->ocfilter['position'] == 'content_top' || $this->ocfilter['position'] == 'content_bottom') {
					$links = 6;
				} else {
					$links = 4;
				}
			} elseif ((int)$this->ocfilter['price_links_count']) {
				$links = (int)$this->ocfilter['price_links_count'];
			} else {
				$links = 4;
			}

			$price_part = ($this->max_price - $this->min_price) / $links;

			if ($price_part < $links) {
				return;
			}

			$this->data['price_links'] = array();

			for ($i = 0; $i < $links; $i++) {
				$from = round($price_part * $i + $this->min_price);
				$to = round($price_part * ($i + 1) + $this->min_price);
				$params = $this->getValueParams('p', $from . '-' . $to, 'select');

				$count = 0;

				foreach ($this->product_prices['products'] as $price) {
					if ($from <= $price && $to >= $price) {
						$count++;
					}
				}

				$price_links[] = array(
					'from' 			=> $from,
					'to' 				=> $to,
					'href' 			=> $this->link . '&' . $this->ocfilter['index'] . '=' . $params,
					'selected' 	=> ($this->min_price_get == $from && $this->max_price_get == $to),
					'count'     => $count,
					'strlen'    => strlen($from),
					'first'     => !$i,
					'last'      => $links == $i+1,
				);
	    }

			if (count($price_links) > 1) {
				foreach($price_links as $key => $link) {
					$price_links[$key]['width'] = 100 / count($price_links);
				}
			} else {
				return array();
			}
		}

		return $price_links;
	}

  private function getDiagram() {
    # Generate product price diagram coords by SooR 18-02-2013 v.2.0

		$diagram_data = array();

    if ($this->product_prices && count($this->product_prices['products']) > 1) {
			$height = 40;

			if ($this->ocfilter['position'] == 'content_top' || $this->ocfilter['position'] == 'content_bottom') {
				$items = 7;
				$width = 155;
			} else {
				$items = 6;
				$width = 138;
			}

      $price_range = $this->max_price - $this->min_price;

			if ($price_range < $items) {
				return;
			}

	 		$price_interval = $price_range / $items;

			$items_data = array();

      $max_count = 0;

			for ($i = 0; $i < $items; $i++) {
        $from = $i * $price_interval + $this->min_price;
        $to = ($i + 1) * $price_interval + $this->min_price;

        $count = 0;

        foreach ($this->product_prices['products'] as $price) {
          if ($price >= $from && $price <= $to) {
            $count++;
          }
        }

				if ($count > $max_count) {
					$max_count = $count;
				}

				$items_data[] = $count;
      }

			$items_interval = $width / ($items - 1);

			$diagram_data['circles'] = array();

			$diagram_data['path'] = 'M0,' . $height;

			foreach ($items_data as $key => $count) {
				$y = round($height / 100 * (100 - $count / $max_count * 100));
				$y = ($y < $height / 2 ? $y + 5 : $y - 5);

				$x = round($key * $items_interval, 1);

				$diagram_data['circles'][] = array('y' => $y, 'x'	=> $x, 'count' => $count);

				$diagram_data['path'] .= ' L' . $x . ',' . $y;

				if ($key == count($items_data) - 1) {
					$diagram_data['path'] .= ' L' . $x . ',' . $height;
				}
			}

			$diagram_data['path'] .= ' L0,' . $height . 'Z';
    }

    return $diagram_data;
  }

  private function declOfNum($number, $cases) {

    if ($number % 10 == 1 && $number % 100 != 11) {
      $key = 0;
    } elseif ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20)) {
      $key = 1;
    } else {
      $key = 2;
    }

    return sprintf($cases[$key], $number);
  }

  public function callback() {
    $this->construct();

    $json = array();

    $json['total'] = $this->products_total;
    $json['text_total'] = $this->declOfNum($this->products_total, array($this->language->get('button_show_total_1'), $this->language->get('button_show_total_2'), $this->language->get('button_show_total_3')));

    $json['values'] = array();

    foreach ($this->getOCFilterOptions() as $option) {
      if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') {
        continue;
      }

      if ($option['type'] == 'select' || $option['type'] == 'radio') {
				$json['values']['cancel-' . $option['option_id']] = array(
          't' => 1,
          'p' => $this->cancelOptionParams($option['option_id']),
					's' => false
        );
			}

      foreach ($option['values'] as $value) {
        $json['values'][$value['id']] = array(
          't' => $value['count'],
          'p' => $value['params'],
					's' => isset($this->options_get[$option['option_id']][$value['value_id']])
        );
      }
    }

    $this->response->setOutput(json_encode($json));
  }
}
?>