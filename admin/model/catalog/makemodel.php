<?php 
class ModelCatalogMakemodel extends Model {
    public function getMakes($data = array()) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array( 'id', 'make' );
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        
        /* DB table to use */
        $sTable = DB_PREFIX . "make";
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
         * no need to edit below this line
         */
        
        /* 
         * MySQL connection
         */
        
        $sLimit = "";
        if ( isset( $data['iDisplayStart'] ) && $data['iDisplayLength'] != '-1' ) {
            $sLimit = "LIMIT ".$this->db->escape( $data['iDisplayStart'] ).", ".
                $this->db->escape( $data['iDisplayLength'] );
        }
        
        /*
         * Ordering
         */
        if ( isset( $data['iSortCol_0'] ) ) {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $data['iSortingCols'] ) ; $i++ ) {
                if ( $data[ 'bSortable_'.intval($data['iSortCol_'.$i]) ] == "true" ) {
                    $sOrder .= $aColumns[ intval( $data['iSortCol_'.$i] ) ]."
                        ".$this->db->escape( $data['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ) {
                $sOrder = "";
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ( $data['sSearch'] != "" ) {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape( $data['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $data['bSearchable_'.$i] == "true" && $data['sSearch_'.$i] != '' ) {
                if ( $sWhere == "" ) {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape($data['sSearch_'.$i])."%' ";
            }
        }
        
        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
        ";
        $rResult = $this->db->query($sQuery);
        
        /* Data set length after filtering */
        $sQuery = "
            SELECT FOUND_ROWS() AS total
        ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row;
        $iFilteredTotal = $aResultFilterTotal['total'];
        
        /* Total data set length */
        $sQuery = "
            SELECT COUNT(".$sIndexColumn.") AS total 
            FROM   $sTable
        ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row;
        $iTotal = $aResultTotal['total'];
        
        
        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($data['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ( $rResult->rows as $aRow ){
            $row = array();
            
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'make_'.$aRow['id'];
            
            for ( $i=0 ; $i<count($aColumns) ; $i++ ){
                if ( $aColumns[$i] == "version" ){
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                } else if ( $aColumns[$i] != ' ' ) {
                    /* General output */
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }
        
        return $output;
    }
    
    public function getModels($data = array()) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array( 'id', 'model' );
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        
        /* DB table to use */
        $sTable = DB_PREFIX . "model";
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
         * no need to edit below this line
         */
        
        /* 
         * MySQL connection
         */
        
        $sLimit = "";
        if ( isset( $data['iDisplayStart'] ) && $data['iDisplayLength'] != '-1' ) {
            $sLimit = "LIMIT ".$this->db->escape( $data['iDisplayStart'] ).", ".
                $this->db->escape( $data['iDisplayLength'] );
        }
        
        /*
         * Ordering
         */
        if ( isset( $data['iSortCol_0'] ) ) {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $data['iSortingCols'] ) ; $i++ ) {
                if ( $data[ 'bSortable_'.intval($data['iSortCol_'.$i]) ] == "true" ) {
                    $sOrder .= $aColumns[ intval( $data['iSortCol_'.$i] ) ]."
                        ".$this->db->escape( $data['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ) {
                $sOrder = "";
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ( $data['sSearch'] != "" ) {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape( $data['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $data['bSearchable_'.$i] == "true" && $data['sSearch_'.$i] != '' ) {
                if ( $sWhere == "" ) {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape($data['sSearch_'.$i])."%' ";
            }
        }
        
        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
        ";
        $rResult = $this->db->query($sQuery);
        
        /* Data set length after filtering */
        $sQuery = "
            SELECT FOUND_ROWS() AS total
        ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row;
        $iFilteredTotal = $aResultFilterTotal['total'];
        
        /* Total data set length */
        $sQuery = "
            SELECT COUNT(".$sIndexColumn.") AS total 
            FROM   $sTable
        ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row;
        $iTotal = $aResultTotal['total'];
        
        
        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($data['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ( $rResult->rows as $aRow ) {
            $row = array();
            
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'model_'.$aRow['id'];
            
            for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
                if ( $aColumns[$i] == "version" ) {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                } else if ( $aColumns[$i] != ' ' ) {
                    /* General output */
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }
        
        return $output;
    }
    
    public function getEngines($data = array()) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array( 'id', 'engine' );
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        
        /* DB table to use */
        $sTable = DB_PREFIX . "engine";
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
         * no need to edit below this line
         */
        
        /* 
         * MySQL connection
         */
        
        $sLimit = "";
        if ( isset( $data['iDisplayStart'] ) && $data['iDisplayLength'] != '-1' ) {
            $sLimit = "LIMIT ".$this->db->escape( $data['iDisplayStart'] ).", ".
                $this->db->escape( $data['iDisplayLength'] );
        }
        
        /*
         * Ordering
         */
        if ( isset( $data['iSortCol_0'] ) ) {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $data['iSortingCols'] ) ; $i++ ) {
                if ( $data[ 'bSortable_'.intval($data['iSortCol_'.$i]) ] == "true" ) {
                    $sOrder .= $aColumns[ intval( $data['iSortCol_'.$i] ) ]."
                        ".$this->db->escape( $data['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" ) {
                $sOrder = "";
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ( $data['sSearch'] != "" ) {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape( $data['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        
        /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $data['bSearchable_'.$i] == "true" && $data['sSearch_'.$i] != '' ) {
                if ( $sWhere == "" ) {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".$this->db->escape($data['sSearch_'.$i])."%' ";
            }
        }
        
        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
        ";
        $rResult = $this->db->query($sQuery);
        
        /* Data set length after filtering */
        $sQuery = "
            SELECT FOUND_ROWS() AS total
        ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row;
        $iFilteredTotal = $aResultFilterTotal['total'];
        
        /* Total data set length */
        $sQuery = "
            SELECT COUNT(".$sIndexColumn.") AS total 
            FROM   $sTable
        ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row;
        $iTotal = $aResultTotal['total'];
        
        
        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($data['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ( $rResult->rows as $aRow ) {
            $row = array();
            
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'engine_'.$aRow['id'];
            
            for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
                if ( $aColumns[$i] == "version" ) {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                } else if ( $aColumns[$i] != ' ' ) {
                    /* General output */
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }
        
        return $output;
    }
    
    public function deleteMake($id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "make` WHERE id = '" . (int)$id . "'");
    }
    
    public function deleteModel($id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "model` WHERE id = '" . (int)$id . "'");
    }
    
    public function deleteEngine($id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "engine` WHERE id = '" . (int)$id . "'");
    }
    
    public function insertMake($new_make) {
        $check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "make` WHERE `make` = '" . $new_make . "'");
        if($check->num_rows){
            return 'Make exists';
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "make` (make) values ('" . $new_make . "')");
            return 'success';
        }
    }
    
    public function insertModel($new_model) {
        $check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "model` WHERE `model` = '" . $new_model . "'");
        if($check->num_rows){
            return 'Model exists';
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "model` (model) values ('" . $new_model . "')");
            return 'success';
        }
    }
    
    public function insertEngine($new_engine) {
        $check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "engine` WHERE `engine` = '" . $new_engine . "'");
        if($check->num_rows){
            return 'Engine exists';
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "engine` (`engine`) values ('" . $new_engine . "')");
            return 'success';
        }
    }
    
    public function editMake($id, $value) {
        $this->db->query("UPDATE `" . DB_PREFIX . "make` SET make = '" . $value . "' WHERE id = '" . (int)$id . "'");
    }
    
    public function editModel($id, $value) {
        $this->db->query("UPDATE `" . DB_PREFIX . "model` SET model = '" . $value . "' WHERE id = '" . (int)$id . "'");
    }
    
    public function editEngine($id, $value) {
        $this->db->query("UPDATE `" . DB_PREFIX . "engine` SET `engine` = '" . $value . "' WHERE id = '" . (int)$id . "'");
    }
    
    public function completeMake($term) {
        $query = $this->db->query("SELECT make AS value FROM `" . DB_PREFIX . "make` WHERE make LIKE '" . $this->db->escape($term) . "%'");
        
        $data = array();
        if($query->num_rows){
            $data = $query->rows;
        }
        
        return $data;
    }
    
    public function completeModel($term) {
        $query = $this->db->query("SELECT model AS value FROM `" . DB_PREFIX . "model` WHERE model LIKE '" . $this->db->escape($term) . "%'");
        
        $data = array();
        if($query->num_rows){
            $data = $query->rows;
        }
        
        return $data;
    }
    
    public function completeEngine($term) {
        $query = $this->db->query("SELECT engine AS value FROM `" . DB_PREFIX . "engine` WHERE engine LIKE '" . $this->db->escape($term) . "%'");
        
        $data = array();
        if($query->num_rows){
            $data = $query->rows;
        }
        
        return $data;
    }
    
    public function upload($filename, $empty = null, $uid = 'product_id') {
        
        $firstrow = 1;
        if(($handle = fopen($filename, 'r')) !== FALSE){
            if(isset($empty) && $empty == '1') {
                $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "product_to_ymm`");
                $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "make`");
                $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "model`");
                $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "engine`");
            }
            while(($data = fgetcsv($handle, 1000)) !== FALSE){
                //ignore headers
                if($firstrow == 1){
                    $firstrow = 0;
                    continue;
                }
                
                switch($uid) {
                    case 'model':
                        //get product_id from model
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `model` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    case 'sku':
                        //get product_id from sku
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `sku` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    case 'upc':
                        //get product_id from upc
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `upc` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    case 'ean':
                        //get product_id from ean
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `ean` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    case 'mpn':
                        //get product_id from mpn
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `mpn` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    case 'isbn':
                        //get product_id from isbn
                        $product_id_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `isbn` = '" . $this->db->escape(trim($data[0])) . "'");
                        if($product_id_query->num_rows) {
                            $product_id = $product_id_query->row['product_id'];
                        } else {
                            continue 2;
                        }
                        break;
                    default:
                        $product_id = (int)$data[0];
                        break;
                }
                
                //check for make id if it doesn't exist create it.
                $make_id_query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "make` WHERE TRIM(make) = '" . $this->db->escape(trim($data[1])) . "'");
                if($make_id_query->num_rows){
                    $make_id = $make_id_query->row['id'];
                } else {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "make` (make) VALUES ('" . $this->db->escape(trim($data[1])) . "')");
                    $make_id = $this->db->getLastId();
                }
                
                if(trim($data[2]) == '0'){
                    $model_id = 0;
                } else {
                    //check for model id if it doesn't exist create it.
                    $model_id_query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "model` WHERE TRIM(model) = '" . $this->db->escape(trim($data[2])) . "'");
                    if($model_id_query->num_rows){
                        $model_id = $model_id_query->row['id'];
                    } else {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "model` (model) VALUES ('" . $this->db->escape(trim($data[2])) . "')");
                        $model_id = $this->db->getLastId();
                    }
                }
                
                if(trim($data[5]) == '0'){
                    $engine_id = 0;
                } else {
                    //check for engine id if it doesn't exist create it.
                    $engine_id_query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "engine` WHERE TRIM(engine) = '" . $this->db->escape(trim($data[5])) . "'");
                    if($engine_id_query->num_rows){
                        $engine_id = $engine_id_query->row['id'];
                    } else {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "engine` (`engine`) VALUES ('" . $this->db->escape(trim($data[5])) . "')");
                        $engine_id = $this->db->getLastId();
                    }
                }
                
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_ymm` (product_id, make_id, model_id, begin_year, end_year, `engine_id`) VALUES ('" . $product_id . "', '" . $make_id . "', '" . $model_id . "', '" . (int)$data[3] . "', '" . (int)$data[4] . "', '" . $engine_id . "') ON DUPLICATE KEY UPDATE product_id = '" . $product_id . "', make_id = '" . $make_id . "', model_id = '" . $model_id . "', begin_year = '" . (int)$data[3] . "', end_year = '" . (int)$data[4] . "', `engine_id` = '" . $engine_id . "'");
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET universal = '0' WHERE product_id = '" . $product_id . "'");
            }
            $file_opened = 'yes';
        }
        
        if($file_opened == 'yes'){
            return true;
        } else {
            return false;
        }
        
    }
    
    public function download($uid = 'product_id'){
        if($uid == 'product_id') {
            $query = $this->db->query("SELECT p2y.product_id, TRIM(ma.make) AS make, IFNULL(TRIM(mo.model), 0) AS model, p2y.begin_year, p2y.end_year, IFNULL(TRIM(en.engine), 0) AS engine FROM " . DB_PREFIX . "product_to_ymm p2y LEFT JOIN " . DB_PREFIX . "make ma ON ma.id = p2y.make_id LEFT JOIN " . DB_PREFIX . "model mo ON mo.id = p2y.model_id LEFT JOIN `" . DB_PREFIX . "engine` en ON en.id = p2y.engine_id ORDER by ma.make, mo.model, en.engine, p2y.begin_year");
        } else {
            switch($uid) {
                case 'model':
                    $identifier = 'p.model AS product_model';
                    break;
                case 'sku':
                    $identifier = 'p.sku AS product_sku';
                    break;
                case 'upc':
                    $identifier = 'p.upc AS product_upc';
                    break;
                case 'ean':
                    $identifier = 'p.ean AS product_ean';
                    break;
                case 'mpn':
                    $identifier = 'p.mpn as product_mpn';
                    break;
                case 'isbn':
                    $identifier = 'p.isbn AS product_isbn';
                    break;
                default:
                    $identifier = 'p2y.product_id';
                    break;
            }
            $query = $this->db->query("SELECT " . $identifier . ", TRIM(ma.make) AS make, IFNULL(TRIM(mo.model), 0) AS model, p2y.begin_year, p2y.end_year, IFNULL(TRIM(en.engine), 0) AS engine FROM " . DB_PREFIX . "product_to_ymm p2y LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = p2y.product_id LEFT JOIN " . DB_PREFIX . "make ma ON ma.id = p2y.make_id LEFT JOIN " . DB_PREFIX . "model mo ON mo.id = p2y.model_id LEFT JOIN `" . DB_PREFIX . "engine` en ON en.id = p2y.engine_id ORDER by ma.make, mo.model, en.engine, p2y.begin_year");
        }
        if($query->num_rows) {
            return $query->rows;
        } else {
            return false;
        }
    }
}