<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ModelToolSimpleApiCustom extends Model {
    public function example($filterFieldValue) {
        $values = array();
        
        $values[] = array(
            'id'   => 'my_id', 
            'text' => 'my_text'
        );

        return $values;
    }
}