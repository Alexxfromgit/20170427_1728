<?php
class ModelCatalogOCFilter extends Model {

  public function addOption($data) {
    $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option SET status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', sort_order = '" . (int)$data['sort_order'] . "', type = '" . $this->db->escape($data['type']) . "', grouping = '" . (int)$data['grouping'] . "', selectbox = '" . (isset($data['selectbox']) ? (int)$data['selectbox'] : 0) . "', color = '" . (isset($data['color']) ? (int)$data['color'] : 0) . "', image = '" . (isset($data['image']) ? (int)$data['image'] : 0) . "'");

    $option_id = $this->db->getLastId();

    foreach ($data['ocfilter_option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', postfix = '" . $this->db->escape($value['postfix']) . "'");
		}

    if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_category SET option_id = '" . (int)$option_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['option_store'])) {
			foreach ($data['option_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_store SET option_id = '" . (int)$option_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['ocfilter_option_value']['insert'])) {
			foreach ($data['ocfilter_option_value']['insert'] as $value) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value SET option_id = '" . (int)$option_id . "', sort_order = '" . (int)$value['sort_order'] . "', color = '" . $this->db->escape($value['color']) . "', image = '" . $this->db->escape($value['image']) . "'");

				$value_id = $this->db->getLastId();

        foreach ($value['language'] as $language_id => $language) {
				  $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_description SET value_id = '" . (int)$value_id . "', option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($language['name']) . "'");
				}
			}
		}

    $this->cache->delete('ocfilter');
  }

  public function editOption($option_id, $data) {
    $this->db->query("UPDATE " . DB_PREFIX . "ocfilter_option SET status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', sort_order = '" . (int)$data['sort_order'] . "', type = '" . $this->db->escape($data['type']) . "', grouping = '" . (int)$data['grouping'] . "', selectbox = '" . (isset($data['selectbox']) ? (int)$data['selectbox'] : 0) . "', color = '" . (isset($data['color']) ? (int)$data['color'] : 0) . "', image = '" . (isset($data['image']) ? (int)$data['image'] : 0) . "' WHERE option_id = '" . (int)$option_id . "'");

    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_description WHERE option_id = '" . (int)$option_id . "'");

    foreach ($data['ocfilter_option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', postfix = '" . $this->db->escape($value['postfix']) . "'");
		}

    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_to_category WHERE option_id = '" . (int)$option_id . "'");

    if (isset($data['category_id'])) {
			foreach ($data['category_id'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_category SET option_id = '" . (int)$option_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_to_store WHERE option_id = '" . (int)$option_id . "'");

		if (isset($data['option_store'])) {
			foreach ($data['option_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_store SET option_id = '" . (int)$option_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_description WHERE option_id = '" . (int)$option_id . "'");

    if (isset($data['ocfilter_option_value'])) {
			if (isset($data['ocfilter_option_value']['update'])) {
				foreach ($data['ocfilter_option_value']['update'] as $value_id => $value) {
	        $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value SET option_id = '" . (int)$option_id . "', value_id = '" . (int)$value_id . "', sort_order = '" . (int)$value['sort_order'] . "', color = '" . $this->db->escape($value['color']) . "', image = '" . $this->db->escape($value['image']) . "'");

	        foreach ($value['language'] as $language_id => $language) {
					  $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_description SET value_id = '" . (int)$value_id . "', option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($language['name']) . "'");
					}
				}
			}

			if (isset($data['ocfilter_option_value']['insert'])) {
				foreach ($data['ocfilter_option_value']['insert'] as $value) {
	        $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value SET option_id = '" . (int)$option_id . "', sort_order = '" . (int)$value['sort_order'] . "', color = '" . $this->db->escape($value['color']) . "', image = '" . $this->db->escape($value['image']) . "'");

					$value_id = $this->db->getLastId();

	        foreach ($value['language'] as $language_id => $language) {
					  $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_description SET value_id = '" . (int)$value_id . "', option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($language['name']) . "'");
					}
				}
			}
    }

    $this->cache->delete('ocfilter');
  }

  public function deleteOption($option_id) {
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_description WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_to_category WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_to_store WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_description WHERE option_id = '" . (int)$option_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE option_id = '" . (int)$option_id . "'");
    $this->cache->delete('ocfilter');
  }

  public function getOption($option_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (oo.option_id = ood.option_id) WHERE oo.option_id = '" . (int)$option_id . "'");

    return $query->row;
  }

  public function getOptionByCategoriesId($categories_id) {

    $data = array();

    foreach ($categories_id as $category_id) $data[] = (int)$category_id;

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (ood.option_id = oo.option_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_to_category oo2c ON (oo.option_id = oo2c.option_id) WHERE oo2c.category_id IN (" . implode(',', $data) . ") AND ood.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oo.sort_order");

    return $query->rows;
  }

  public function getOptionsByCategoryId($category_id) {
    $options_data = array();

    $options_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (oo.option_id = ood.option_id) LEFT JOIN " . DB_PREFIX . "ocfilter_option_to_category cotc ON (oo.option_id = cotc.option_id) WHERE cotc.category_id = '" . (int)$category_id . "' AND ood.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oo.sort_order");

    if ($options_query->num_rows) {
      $options_id = array();

      foreach ($options_query->rows as $option) $options_id[] = (int)$option['option_id'];

      $values_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option_value oov LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description oovd ON (oov.value_id = oovd.value_id) WHERE oov.option_id IN (" . implode(',', $options_id) . ") AND oovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oov.sort_order, ABS(oovd.name)");

      $values = array();

      foreach ($values_query->rows as $value) $values[$value['option_id']][] = $value;

      foreach ($options_query->rows as $option) {
        $options_data[$option['option_id']] = $option;
        $options_data[$option['option_id']]['values'] = array();

        if (isset($values[$option['option_id']])) {
          $options_data[$option['option_id']]['values'] = $values[$option['option_id']];
        }
      }
    }

    return $options_data;
  }

  public function getOptionCategories($option_id) {
    $option_category_data = array();

    $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "ocfilter_option_to_category WHERE option_id = '" . (int)$option_id . "'");

    foreach ($query->rows as $result) $option_category_data[] = $result['category_id'];

    return $option_category_data;
  }

  public function getOptionStores($option_id) {
		$option_store_data = array();

		$query = $this->db->query("SELECT store_id FROM " . DB_PREFIX . "ocfilter_option_to_store WHERE option_id = '" . (int)$option_id . "'");

		foreach ($query->rows as $result) { $option_store_data[] = $result['store_id']; }

		return $option_store_data;
	}

  public function getProductValues($product_id) {
    $product_values_data = array();

    $query = $this->db->query("SELECT oov2p.*, oov2pd.description, oov2pd.language_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product oov2p LEFT OUTER JOIN " . DB_PREFIX . "ocfilter_option_value_to_product_description oov2pd ON (oov2pd.value_id = oov2p.value_id AND oov2pd.option_id = oov2p.option_id) WHERE oov2p.product_id = '" . (int)$product_id . "'");

    $description = array();

    $this->load->model('localisation/language');

    $languages = $this->model_localisation_language->getLanguages();

    foreach ($query->rows as $result) {
      if ($result['language_id'] && $result['description']) {
        $description[$result['option_id'] . $result['value_id']][$result['language_id']] = array(
          'description' => $result['description']
        );
      } else {
        foreach ($languages as $language) {
          $description[$result['option_id'] . $result['value_id']][$language['language_id']] = array(
            'description' => ''
          );
        }
      }
    }

    foreach ($query->rows as $result) {
      unset($result['language_id']);
      unset($result['description']);

      $product_values_data[$result['option_id']][$result['value_id']] = $result;
      $product_values_data[$result['option_id']][$result['value_id']]['description'] = $description[$result['option_id'] . $result['value_id']];
    }

    return $product_values_data;
  }

  public function getOptionValues($option_id) { # In option form and product callback
    $value_data = array();

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option_value oov LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description oovd ON (oovd.value_id = oov.value_id) WHERE oov.option_id = '" . (int)$option_id . "' ORDER BY oov.sort_order, ABS(oovd.name)");

		$results = array();

		foreach ($query->rows as $row) {
      $results[$row['value_id']][] = $row;
		}

    foreach ($results as $key => $values) {
			$value = array_shift($values);

			$value_description = array();

			foreach ($results[$value['value_id']] as $result) {
				$value_description[$result['language_id']] = array(
          'name' => $result['name']
        );
			}

      $value_data[$key] = $value;
      $value_data[$key]['language'] = $value_description;
    }

		return $value_data;
  }

	public function getOptionDescriptions($option_id) {
		$option_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option_description WHERE option_id = '" . (int)$option_id . "'");

		foreach ($query->rows as $result) {
			$option_description_data[$result['language_id']] = array(
        'name'        => $result['name'],
        'description' => $result['description'],
        'postfix'     => $result['postfix']
      );
		}

		return $option_description_data;
	}

  public function getOptions($data = array()) { # In options list
    $option_data = array();

    $sql = "SELECT oo.option_id, oo.type, oo.sort_order, oo.status, ood.name, ood.postfix FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (oo.option_id = ood.option_id)";

    if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "ocfilter_option_to_category oo2c ON (oo.option_id = oo2c.option_id)";
    }

    $sql .= " WHERE ood.language_id = '" . (int)$this->config->get('config_language_id') . "'";

    if (!empty($data['filter_category_id'])) {
			$sql .= " AND oo2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

    if (!empty($data['filter_type'])) {
			$sql .= " AND oo.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

    if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(ood.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

    if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND oo.status = '" . (int)$data['filter_status'] . "'";
		}

    $sql .= " GROUP BY oo.option_id";

    $sort_data = array(
			'oo.sort_order',
			'ood.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY oo.sort_order, ood.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

    $options_query = $this->db->query($sql);

    if ($options_query->num_rows) {
      $options_id = array();

      foreach ($options_query->rows as $option) {
        $options_id[] = (int)$option['option_id'];
      }

      $values_query = $this->db->query("SELECT oov.value_id, oov.option_id, oovd.name FROM " . DB_PREFIX . "ocfilter_option_value oov LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description oovd ON (oov.value_id = oovd.value_id) WHERE oov.option_id IN (" . implode(',', $options_id) . ") AND oovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oov.sort_order ASC, oovd.name DESC");

      $values = array();
      foreach ($values_query->rows as $value) $values[$value['option_id']][] = $value;

      $categories_query = $this->db->query("SELECT c.category_id, cd.name, oo2c.option_id FROM " . DB_PREFIX . "ocfilter_option_to_category oo2c LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = oo2c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = c.category_id) WHERE oo2c.option_id IN (" . implode(',', $options_id) . ") AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name DESC");

      $categories = array();
      foreach ($categories_query->rows as $category) $categories[$category['option_id']][] = $category;

      foreach ($options_query->rows as $key => $option) {
        $option_data[$key] = $option;
        $option_data[$key]['values'] = (isset($values[$option['option_id']]) ? $values[$option['option_id']] : array());
        $option_data[$key]['categories'] = (isset($categories[$option['option_id']]) ? $categories[$option['option_id']] : array());
      }
    }

    return $option_data;
  }

  public function getTotalOptions($data = array()) {

    $sql = "SELECT COUNT(DISTINCT oo.option_id) AS total  FROM " . DB_PREFIX . "ocfilter_option oo LEFT JOIN " . DB_PREFIX . "ocfilter_option_description ood ON (oo.option_id = ood.option_id)";

    if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "ocfilter_option_to_category oo2c ON (oo.option_id = oo2c.option_id)";
    }

    $sql .= " WHERE ood.language_id = '" . (int)$this->config->get('config_language_id') . "'";

    if (!empty($data['filter_category_id'])) {
			$sql .= " AND oo2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

    if (!empty($data['filter_type'])) {
			$sql .= " AND oo.type = '" . $this->db->escape($data['filter_type']) . "'";
		}

    if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(ood.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

    if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND oo.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

  public function getCategories($parent_id, $level = -1) {
    $level++;

    $results = $this->getCategoriesByParentId($parent_id);

    $categories_data = array();

    foreach ($results as $result) {
      $categories_data[] = array(
        'category_id' => $result['category_id'],
        'name'        => $result['name'],
        'level'       => $level
      );

      $categories_data = array_merge($categories_data, $this->getCategories($result['category_id'], $level));
    }

    return $categories_data;
  }

  private function getCategoriesByParentId($parent_id = 0) { # For options list and form
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

		return $query->rows;
	}

  public function getProductOCFilterValues($product_id) { # For product copy
    $product_filter_value_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_value_data[$result['option_id']]['values'][] = $result['value_id'];
		}

		return $product_filter_value_data;
  }

  public function copyAttributesToOCFilter($data = array()) {
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_description`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_to_category`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_to_store`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_value`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_value_description`");
    $this->db->query("TRUNCATE `" . DB_PREFIX . "ocfilter_option_value_to_product`");

    $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option (option_id, status, type, sort_order) SELECT attribute_id, '1' AS status, '" . $this->db->escape($data['type']) . "' AS type, sort_order FROM " . DB_PREFIX . "attribute");
    $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_description (option_id, language_id, name) SELECT attribute_id, language_id, name FROM " . DB_PREFIX . "attribute_description");

		$this->db->query("SET @row=0");
		$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value (value_id, option_id) SELECT @row:=@row+1, attribute_id FROM " . DB_PREFIX . "product_attribute GROUP BY attribute_id, `text` ORDER BY attribute_id, `text`");

		$this->db->query("SET @row=0");
		$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_description (value_id, language_id, option_id, name) SELECT @row:=@row+1, language_id, attribute_id, text FROM " . DB_PREFIX . "product_attribute GROUP BY attribute_id, `text` ORDER BY attribute_id, `text`");

    foreach ($data['option_store'] as $store_id) {
      $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_store (option_id, store_id) SELECT option_id, '" . (int)$store_id . "' AS store_id FROM " . DB_PREFIX . "ocfilter_option");
    }

    $this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_value_to_product (product_id, value_id, option_id) SELECT pa.product_id, (SELECT oovd.value_id FROM " . DB_PREFIX . "ocfilter_option_value_description oovd LEFT JOIN " . DB_PREFIX . "ocfilter_option_value oov ON (oovd.value_id = oov.value_id) WHERE oovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND oov.option_id = pa.attribute_id AND BINARY oovd.name = pa.text LIMIT 1) AS value_id, pa.attribute_id FROM " . DB_PREFIX . "product_attribute pa WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "ocfilter_option_to_category (option_id, category_id) SELECT p2v.option_id, p2c.category_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product p2v LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = p2v.product_id) WHERE p2c.category_id != '0' GROUP BY p2v.option_id, p2c.category_id");

    $this->cache->delete('ocfilter');
  }

	/*
	 **********************
	 *	Install methods   *
	 **********************
	*/

  public function existsTables() {
    $exists = true;

    $this->config->load('ocfilter');

    foreach ($this->config->get('ocfilter_install_table_steps') as $table => $fields) {
      if (!mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . DB_PREFIX . $this->db->escape($table) . "'"))) {
        $exists = false;
      }
    }

    return $exists;
  }

  public function createTables() {
    $this->config->load('ocfilter');

		foreach($this->config->get('ocfilter_install_table_steps') as $table => $fields) {
      $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $table . "` (" . implode(", ", $fields) . ") COLLATE='utf8_general_ci' ENGINE=MyISAM");
		}

    return $this->existsTables();
	}

  public function installCode($packages) {
		foreach ($this->getCodeSteps() as $step) {
			if (!in_array($step['package'], $packages)) {
				continue;
			}

			$content = file_get_contents($step['file']);

      preg_match_all('!(#|\<\!--)\s+?ocfilter start.+?ocfilter end\s+?(--\>|)!is', $content, $matches);

			if ($matches && $matches[0]) {
		    $content = str_replace($matches[0], '', $content);
			}

			$search = array();
			$replace = array();

			foreach ($step['actions'] as $code) {
			  if (isset($code['{PREG_QUOTE}'])) {
			    $search[] = '!(' . $code['{SEARCH}'] . ')!sui';
			  } else {
			    $search[] = '!(' . preg_quote($code['{SEARCH}']) . ')!sui';
			  }

			  $replace[] = $code['{REPLACE}'];
			}

			$content = preg_replace($search, $replace, $content);

			$fp = fopen($step['file'], 'w+');
			fwrite($fp, $content);
			fclose($fp);
		}
	}

	public function getCodeSteps() {
  	$this->config->load('ocfilter');

		$steps = $this->config->get('ocfilter_install_code_steps');

    $this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($steps as $key => $step) {
      if (strpos($step['file'], '/language/')) {
				unset($steps[$key]);

        foreach ($languages as $key => $language) {
					$_step = $step;

					$_step['file'] = str_replace('{directory}', $language['directory'], $step['file']);

					$steps[] = $_step;
				}
			}
		}

		return $steps;
	}
}

?>