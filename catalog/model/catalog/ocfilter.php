<?php
class ModelCatalogOCFilter extends Model {
	public $settings = array(
		'sep_par' => ';',
		'sep_opt' => ':',
		'sep_val' => ',',
		'index'   => 'filter_ocfilter'
	);

	public function __construct($registry) {
		$this->registry = $registry;

		if ($this->config->has('ocfilter_module')) {
	    $modules = $this->config->get('ocfilter_module');

	    $this->settings = array_merge($this->settings, array_shift($modules));

      $this->settings['template'] = $this->config->get('config_template');
			$this->settings['modules'] = $modules;

			$this->registry->set('ocfilter', $this->settings);
		}
	}

  public function getOCFilterOptionsByCategoryId($category_id) {
    $options_data = $this->cache->get('ocfilter.option.' . $category_id . '.' . $this->config->get('config_language_id'));

		if ($options_data && is_array($options_data)) {
			return $options_data;
		}

    $options_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (oo.option_id = ood.option_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_to_category cotc ON (oo.option_id = cotc.option_id) WHERE oo.status = '1' AND cotc.category_id = '" . (int)$category_id . "' AND ood.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oo.sort_order");

    if ($options_query->num_rows) {
      $options_id = array();

      foreach ($options_query->rows as $option) $options_id[] = (int)$option['option_id'];

      $values_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option_value oov LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description oovd ON (oov.value_id = oovd.value_id) WHERE oov.option_id IN (" . implode(',', $options_id) . ") AND oovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oov.sort_order, ABS(oovd.name)");

      $values = array();

      foreach ($values_query->rows as $value) $values[$value['option_id']][] = $value;

      $slider_options_id = array();

      foreach ($options_query->rows as $option) {
        $options_data[$option['option_id']] = $option;
        $options_data[$option['option_id']]['slide_value_min'] = 0;
        $options_data[$option['option_id']]['slide_value_max'] = 0;
        $options_data[$option['option_id']]['values'] = array();

        if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') {
          $slider_options_id[] = $option['option_id'];
        }

        if (isset($values[$option['option_id']])) {
          $options_data[$option['option_id']]['values'] = $values[$option['option_id']];
        }
      }

      if ($slider_options_id) {
        $query = $this->db->query("SELECT slide_value_min, slide_value_max, option_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE option_id IN (" . implode(',', $slider_options_id) . ") AND slide_value_min > '0'");

        if ($query->num_rows) {
          $slide_data = array();

          foreach ($query->rows as $row) {
            $slide_data[$row['option_id']]['min'][] = $row['slide_value_min'];
            $slide_data[$row['option_id']]['max'][] = $row['slide_value_max'];
          }

          foreach ($query->rows as $row) {
            $options_data[$row['option_id']]['slide_value_min'] = preg_replace('!(0+?$)|(\.0+?$)!', '', min($slide_data[$row['option_id']]['min']));
            $options_data[$row['option_id']]['slide_value_max'] = preg_replace('!(0+?$)|(\.0+?$)!', '', max(array_merge($slide_data[$row['option_id']]['max'], $slide_data[$row['option_id']]['min'])));
          }
        }
      }
    }

    $this->cache->set('ocfilter.option.' . $category_id . '.' . $this->config->get('config_language_id'), $options_data);

    return $options_data;
  }

  public function getOptionsByProductsId($products_id = array(), $description = false) {
    $options_data = array();

    if ($products_id) {
      $options_query = $this->db->query("SELECT oov2p.product_id, oo.option_id, ood.name, ood.postfix FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (ood.option_id = oo.option_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_to_product oov2p ON (oo.option_id = oov2p.option_id) WHERE ood.language_id = '" . (int)$this->config->get('config_language_id') . "' AND oov2p.product_id IN (" . implode(',', $products_id) . ") ORDER BY oo.sort_order, ood.name");
      $values_query = $this->db->query("SELECT *, oov2p.product_id, oov2p.option_id, oov2p.value_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product oov2p LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_to_product_description oov2pd ON (oov2pd.value_id = oov2p.value_id AND oov2pd.option_id = oov2p.option_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_value oov ON (oov.value_id = oov2p.value_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description oovd ON (oovd.value_id = oov.value_id) WHERE (oovd.language_id = '" . $this->config->get('config_language_id') . "' OR oovd.language_id IS NULL) AND (oov2pd.language_id = '" . $this->config->get('config_language_id') . "' OR oov2pd.language_id IS NULL) AND oov2p.product_id IN (" . implode(',', $products_id) . ") ORDER BY oov.sort_order, oovd.name");

      if ($options_query->num_rows && $values_query->num_rows) {
        $values = array();

		    foreach ($values_query->rows as $row) {
					$name = '';

          if (!$row['value_id']) {
            if ((float)$row['slide_value_min']) {
              $name .= preg_replace('!(0+?$)|(\.0+?$)!', '', $row['slide_value_min']);
            }

            if ((float)$row['slide_value_max']) {
              $name .= '&nbsp;&mdash;&nbsp;' . preg_replace('!(0+?$)|(\.0+?$)!', '', $row['slide_value_max']);
            }
					} else {
            $name = $row['name'];
					}

          if ($name) {
            $name .= '{postfix} ';
          }

          $values[$row['product_id']][$row['option_id']][$row['value_id']] = $name . ($description ? html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8') : '');
        }

        foreach ($options_query->rows as $row) {
          if (isset($values[$row['product_id']][$row['option_id']])) {
            $options_data[$row['product_id']][$row['option_id']] = $row;
            $options_data[$row['product_id']][$row['option_id']]['values'] = str_replace('{postfix}', $row['postfix'], implode(' &bull; ', $values[$row['product_id']][$row['option_id']]));
          }
        }
      }
    }

    return $options_data;
  }

  public function getStockStatuses() {
    $stock_statuses_data = $this->cache->get('ocfilter.stock_status');

		if ($stock_statuses_data && is_array($stock_statuses_data)) {
			return $stock_statuses_data;
		}

		$query = $this->db->query("SELECT stock_status_id AS value_id, name, 's' AS option_id FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

    $stock_statuses_data = $query->rows;

    $this->cache->set('ocfilter.stock_status', $stock_statuses_data);

		return $stock_statuses_data;
	}

  public function getManufacturersByCategoryId($category_id) {
    $manufacturers_data = $this->cache->get('ocfilter.manufacturer.' . (int)$category_id);

		if ($manufacturers_data && is_array($manufacturers_data)) {
			return $manufacturers_data;
		}

		$query = $this->db->query("SELECT m.manufacturer_id AS value_id, m.name, 'm' AS option_id FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product p ON (m.manufacturer_id = p.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2c.category_id = '" . (int)$category_id . "' GROUP BY m.manufacturer_id ORDER BY name");

    $manufacturers_data = $query->rows;

    $this->cache->set('ocfilter.manufacturer.' . (int)$category_id, $manufacturers_data);

		return $manufacturers_data;
	}

  public function getProductPrices($data) {
    if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

    $products_data = array(
			'min' => 0,
			'max' => 0,
			'products' => array()
		);

    $sql = "SELECT p.product_id, p.price, (SELECT MIN(pd.price) FROM " . DB_PREFIX . "product_discount pd WHERE pd.product_id = p.product_id AND pd.customer_group_id = '" . (int)$customer_group_id . "' AND pd.quantity > '0' AND ((pd.date_start = '0000-00-00' OR pd.date_start < '" . $this->db->escape(date('Y-m-d')) . "') AND (pd.date_end = '0000-00-00' OR pd.date_end > '" . $this->db->escape(date('Y-m-d')) . "'))) AS discount, (SELECT MIN(ps.price) FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->db->escape(date('Y-m-d')) . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->db->escape(date('Y-m-d')) . "'))) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' AND p.price > '0' AND p2c.category_id = '" . (int)$data['filter_category_id'] . "' AND p.date_available <= '" . $this->db->escape(date('Y-m-d')) . "'";

    if (!empty($data['filter_ocfilter'])) {
			$this->getOCFilterData($data);

      if ($this->registry->has('ocfilter_sql') && $this->registry->get('ocfilter_sql')) {
				$sql .= $this->registry->get('ocfilter_sql');
      } else {
        return $products_data;
      }
    }

    $sql .= " ORDER BY p.price";

    $query = $this->db->query($sql);

    $product_prices = array();

    foreach ($query->rows as $key => $row) {
      $prices = array($row['price']);

      if ($row['discount']) {
        $prices[] = $row['discount'];
      }

      if ($row['special']) {
        $prices[] = $row['special'];
      }

      $product_prices[$row['product_id']] = min($prices) * $this->currency->getValue();

			unset($query->rows[$key]);
    }

    if ($product_prices) {
			$products_data = array(
				'min' => floor(min($product_prices)),
				'max' => ceil(max($product_prices)),
				'products' => $product_prices
			);
    }

    return $products_data;
  }

	public function getOCFilterData($data = array()) {
		$cache = md5(http_build_query($data));

		$ocfilter_data = $this->cache->get('ocfilter.data.' . $cache);

		if ($ocfilter_data && is_array($ocfilter_data)) {
			return $ocfilter_data;
		} else {
      $ocfilter_data = array(
        'products_total'  => 0,
        'products_id'     => array(),
        'counters'        => array()
      );
		}

    if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$params = $this->decodeParamsFromString($data['filter_ocfilter']);

		$sql = "SELECT p.product_id, oov2p.value_id, oov2p.option_id, oov2p.slide_value_min, oov2p.slide_value_max, p.manufacturer_id, p.quantity, p.stock_status_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_to_product oov2p ON (oov2p.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = p.product_id) WHERE p.status = '1' AND p.date_available <= '" . $this->db->escape(date('Y-m-d')) . "' AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

		if (isset($params['p'])) {
      list($price_from, $price_to) = explode('-', array_shift($params['p']));

      $price_from = floor((float)$price_from / $this->currency->getValue());
      $price_to = ceil((float)$price_to / $this->currency->getValue());

      $sql .= " AND (p.price BETWEEN '" . (float)$price_from . "' AND '" . (float)$price_to . "'";

      if ($this->ocfilter['consider_discount']) {
        $sql .= " OR p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "' AND quantity > '0' AND ((date_start = '0000-00-00' OR date_start < '" . $this->db->escape(date('Y-m-d')) . "') AND (date_end = '0000-00-00' OR date_end > '" . $this->db->escape(date('Y-m-d')) . "')) AND price BETWEEN '" . (float)$price_from . "' AND '" . (float)$price_to . "')";
      }

      if ($this->ocfilter['consider_special']) {
        $sql .= " OR p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "' AND quantity > '0' AND ((date_start = '0000-00-00' OR date_start < '" . $this->db->escape(date('Y-m-d')) . "') AND (date_end = '0000-00-00' OR date_end > '" . $this->db->escape(date('Y-m-d')) . "')) AND price BETWEEN '" . (float)$price_from . "' AND '" . (float)$price_to . "')";
      }

      $sql .= ")";

      unset($params['p']);
		}

		$query = $this->db->query($sql);

    $product_data = array();

		foreach ($query->rows as $key => $row) {
      if (!$row['value_id']) {
        $row['value_id'] = '';

        if ((float)$row['slide_value_min']) {
          $row['value_id'] .= $row['slide_value_min'];

          if (!(float)$row['slide_value_max']) {
            $row['slide_value_max'] = $row['slide_value_min'];
          }

          $row['value_id'] .= '-' . $row['slide_value_max'];
        }
      }

      if ($row['value_id']) {
        $product_data[$row['product_id']][$row['option_id']][$row['value_id']] = $row['value_id'];
      }

      if ($row['manufacturer_id']) {
        $product_data[$row['product_id']]['m'][$row['manufacturer_id']] = $row['manufacturer_id'];
      }

			if ($this->ocfilter['stock_status_method'] == 'stock_status_id') {
				$product_data[$row['product_id']]['s'][$row['stock_status_id']] = $row['stock_status_id'];
			} else {
				if ((int)$row['quantity']) {
					$product_data[$row['product_id']]['s']['in'] = 'in';
				} else {
					$product_data[$row['product_id']]['s']['out'] = 'out';
				}
			}

			unset($query->rows[$key]);
		}

    foreach ($product_data as $product_id => $product_options) {
      foreach ($product_options as $product_option_id => $product_values) {
        foreach ($product_values as $product_value_id) {
          if (!$params) {
            continue;
          }

          $incoming_data = $params;

          foreach ($incoming_data as $incoming_option_id => $incoming_values) {
            if (false !== strpos($product_value_id, '-') && $incoming_option_id == $product_option_id) {
              list($slide_value_min, $slide_value_max) = explode('-', $product_value_id);
              list($incoming_slide_value_min, $incoming_slide_value_max) = explode('-', $incoming_values[0]);

              if ($incoming_slide_value_min != $incoming_slide_value_max) { # If incoming option is dual slider
                if ($slide_value_min && $slide_value_max && $slide_value_min != $slide_value_max) { # If product has min and max slide values
                  if ($slide_value_min <= $incoming_slide_value_max && $incoming_slide_value_min <= $slide_value_max) {
                    $product_data[$product_id][$product_option_id][$product_value_id] = $incoming_values[0];
                  }
                } elseif ($slide_value_min) { # If product has only min slide value
                  if ($slide_value_min >= $incoming_slide_value_min && $slide_value_min <= $incoming_slide_value_max) {
                    $product_data[$product_id][$product_option_id][$product_value_id] = $incoming_values[0];
                  }
                }
              } else { # If incoming option is single slider
                if ($slide_value_min && $slide_value_max && $slide_value_min != $slide_value_max) { # If product has min and max slide values
                  if ($slide_value_min <= $incoming_slide_value_min && $slide_value_max >= $incoming_slide_value_max) {
                    $product_data[$product_id][$product_option_id][$product_value_id] = $incoming_values[0];
                  }
                } elseif ($slide_value_min) { # If product has only min slide value
                  if ($slide_value_min == $incoming_slide_value_min) {
                    $product_data[$product_id][$product_option_id][$product_value_id] = $incoming_values[0];
                  }
                }
              }
            }
          }
        }
      }
    }

 		foreach ($product_data as $product_id => $product_options) {
			$add = true;

			foreach ($product_options as $product_option_id => $product_values) {
				$total_matches = 0;

				foreach ($product_values as $product_value_id => $product_value) {
					if ($incoming_data = $params) {
						$matches = 0;

						foreach ($incoming_data as $incoming_option_id => $incoming_values) {
							$incoming_values[] = $product_value;

							if (isset($product_data[$product_id][$incoming_option_id]) && array_intersect($product_data[$product_id][$incoming_option_id], $incoming_values)) {
							  $matches++;
							}
						}

						if ($matches && $matches == count($incoming_data)) {
							$add = true;
						} else {
							$add = false;
						}
					}

 					if ($add) {
						if (isset($ocfilter_data['counters'][$product_option_id . $product_value_id])) {
							$ocfilter_data['counters'][$product_option_id . $product_value_id]++;
						} else {
							$ocfilter_data['counters'][$product_option_id . $product_value_id] = 1;
						}

						if (isset($params[$product_option_id]) && in_array($product_value_id, $params[$product_option_id])) {
			 				$total_matches++;
						}
					}

  				if (isset($params[$product_option_id]) && !$total_matches && $total_matches != count($params[$product_option_id])) {
  					$add = false;
  				}
  			}
      }

			if ($add) {
				$ocfilter_data['products_total']++;

        $ocfilter_data['products_id'][] = $product_id;
			}

			unset($product_data[$product_id]);
		}

    $sql = '';

    if ($ocfilter_data['products_id']) {
      $sql .= " AND p.product_id IN (" . implode(',', $ocfilter_data['products_id']) . ")";
    }

    $this->registry->set('ocfilter_sql', $sql);

    //print_r($ocfilter_data); exit;
    

    //$this->cache->set('ocfilter.data.' . $cache, $ocfilter_data);

		return $ocfilter_data;
	}

	public function getOCFilterProductsOptions($products_data = array()) {
		if (!$products_data) {
			return array();
		}

		$products_options = array();

	  $products_id = array();

		foreach ($products_data as $product) $products_id[] = (int)$product['product_id'];

		if (isset($this->settings['pco_show_type']) && isset($this->settings['pco_show_limit']) && (int)$this->settings['pco_show_limit']) {
	    foreach ($this->getOptionsByProductsId($products_id) as $product_id => $options) {
	      array_splice($options, $this->settings['pco_show_limit']);

	      foreach($options as $option) {
	        if ($this->settings['pco_show_type'] == 'inline') {
	          $products_options[$product_id][] = html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8') . ': <b>' . html_entity_decode($option['values'], ENT_QUOTES, 'UTF-8') . '</b>';
	        } else {
	          $products_options[$product_id][] = array(
	            'name'   => html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8'),
	            'values' => html_entity_decode($option['values'], ENT_QUOTES, 'UTF-8')
	          );
	        }
	      }

				if ($this->settings['pco_show_type'] == 'inline') {
					$products_options[$product_id] = implode(' / ', $products_options[$product_id]);
				}
	    }
		}

		foreach ($products_id as $product_id) {
			if (!isset($products_options[$product_id])) {
        $products_options[$product_id] = '';
			}
		}

		return $products_options;
	}

  public function cleanParamsString($params) {
    $matches = array();

    if ($params) {
      foreach (explode($this->ocfilter['sep_par'], (string)$params) as $part) {
        $option = explode($this->ocfilter['sep_opt'], $part);

        $values = array();

        if (isset($option[1])) {
          if (false !== strpos($option[1], '-')) {
            $range = explode('-', $option[1]);

            if (isset($range[0]) && isset($range[1]) && (float)$range[0] > 0 && (float)$range[1] > 0) {
              $matches[] = $option[0] . $this->ocfilter['sep_opt'] . (float)$range[0] . '-' . (float)$range[1];
            }
          } elseif ($option[0] == 'm' || ($option[0] == 's' && $this->ocfilter['stock_status_method'] == 'stock_status_id')) {
            foreach (explode($this->ocfilter['sep_val'], $option[1]) as $value_id) $values[] = (int)$value_id;

            if ($values) $matches[] = $option[0] . $this->ocfilter['sep_opt'] . implode($this->ocfilter['sep_val'], $values);
          } elseif ($option[0] == 's' && $this->ocfilter['stock_status_method'] == 'quantity') {
						if ($option[1] == 'in' || $option[1] == 'out') {
							$matches[] = 's' . $this->ocfilter['sep_opt'] . $option[1];
						}
          } elseif ((int)$option[0]) {
            foreach (explode($this->ocfilter['sep_val'], $option[1]) as $value_id) $values[] = (int)$value_id;

            if ($values) $matches[] = (int)$option[0] . $this->ocfilter['sep_opt'] . implode($this->ocfilter['sep_val'], $values);
          }
        }
      } # end foreach
    }

    return implode($this->ocfilter['sep_par'], $matches);
  }

	public function decodeParamsFromString($params) { # From params string to array
		$decode = array();

    if ($params = $this->cleanParamsString($params)) {
      foreach (explode($this->ocfilter['sep_par'], $params) as $part) {
        $option = explode($this->ocfilter['sep_opt'], $part);

        $decode[$option[0]] = explode($this->ocfilter['sep_val'], $option[1]);
      }
    }

    return $decode;
	}

	public function encodeParamsToString($params) { # From params array to string
		$encode = array();

    if ($params) {
      foreach ($params as $option_id => $values) {
        if ($values) $encode[] = $option_id . $this->ocfilter['sep_opt'] . implode($this->ocfilter['sep_val'], $values);
      }
    }

    return $this->cleanParamsString(implode($this->ocfilter['sep_par'], $encode));
	}
}

?>