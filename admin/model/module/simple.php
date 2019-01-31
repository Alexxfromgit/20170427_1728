<?php
class ModelModuleSimple extends Model {
    public function createTableForCustomerFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'customer_simple_fields` (
                          `customer_id` int(11) NOT NULL,
                          PRIMARY KEY (`customer_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function createTableForAddressFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'address_simple_fields` (
                          `address_id` int(11) NOT NULL,
                          PRIMARY KEY (`address_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function createTableForOrderFields() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'order_simple_fields` (
                          `order_id` int(11) NOT NULL,
                          PRIMARY KEY (`order_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }

    public function alterTableOfCustomer($fields) {
        $this->alterTable('customer_simple_fields', $fields);
    }

    public function alterTableOfAddress($fields) {
        $this->alterTable('address_simple_fields', $fields);
    }

    public function alterTableOfOrder($fields) {
        $this->alterTable('order_simple_fields', $fields);
    }

    private function alterTable($table, $fields) {
        if (count($fields) == 0) {
            return;
        }

        $tmp = array();
        $existFields = $this->getColumnsFrom($table);

        foreach ($fields as $field) {
            if (!in_array($field, $existFields)) {
                $tmp[] = 'ADD `' . $field . '` TEXT NULL';
            }
        }
        
        $this->db->query('ALTER TABLE `' . DB_PREFIX . $table . '` ' . implode(',', $tmp));
    }

    private function getColumnsFrom($table) {
        $query = $this->db->query('SHOW COLUMNS FROM ' . DB_PREFIX . $table);
        
        $result = array();

        foreach ($query->rows as $column) {
            if (empty($column['Key'])) {
                $result[] = $column['Field'];
            }
        }

        return $result;
    }
}