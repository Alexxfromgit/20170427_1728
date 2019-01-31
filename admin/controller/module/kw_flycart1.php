<?php
header('Content-type: text/html; charset="windows-1251"');


class ControllerModuleKwflycart extends Controller {
	private $error = array(); 
	private $_path = HTTPS_SERVER;
	private $_name = 'kw_flycart';
	 
	public function index() {   
		$this->load->language('module/' . $this->_name);

		$this->document->setTitle($this->language->get('common_title'));
		
		$this->document->addStyle($this->_path.'view/javascript/' . $this->_name . '/css/bootstrap.min.css');
		$this->document->addStyle($this->_path.'view/javascript/' . $this->_name . '/css/template.css');
        $this->document->addScript($this->_path.'view/javascript/' . $this->_name . '/js/bootstrap.min.js');
        $this->document->addScript($this->_path.'view/javascript/' . $this->_name . '/js/colorpicker.js');
        $this->document->addScript($this->_path.'view/javascript/' . $this->_name . '/upload/ajaxupload.3.5.js');
        $this->document->addScript($this->_path.'view/javascript/' . $this->_name . '/js/presets.js');
        $this->document->addScript($this->_path.'view/javascript/' . $this->_name . '/js/script.js');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->_name, $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] 			= $this->language->get('common_title');
		$this->data['heading_title'] 			= $this->language->get('heading_title');
		$this->data['common_title'] 			= $this->language->get('common_title');
		$this->data['common_description'] 		= $this->language->get('common_description');
		$this->data['module_tools'] 			= $this->language->get('module_tools');
		
		// tools
		$this->data['tools_main'] 				= $this->language->get('tools_main');
		$this->data['tools_popup'] 				= $this->language->get('tools_popup');
		$this->data['tools_fly'] 				= $this->language->get('tools_fly');
		$this->data['tools_typepres'] 			= $this->language->get('tools_typepres');
		$this->data['tools_options'] 			= $this->language->get('tools_options');
		$this->data['tools_butcolor'] 			= $this->language->get('tools_butcolor');
		$this->data['tools_blue'] 				= $this->language->get('tools_blue');
		$this->data['tools_black'] 				= $this->language->get('tools_black');
		$this->data['tools_lblue'] 				= $this->language->get('tools_lblue');
		$this->data['tools_white'] 				= $this->language->get('tools_white');
		$this->data['tools_green'] 				= $this->language->get('tools_green');
		$this->data['tools_orange'] 			= $this->language->get('tools_orange');
		$this->data['tools_red'] 				= $this->language->get('tools_red');
		$this->data['tools_position'] 			= $this->language->get('tools_position');
		$this->data['tools_design'] 			= $this->language->get('tools_design');
		$this->data['tools_tselect'] 			= $this->language->get('tools_tselect');
		$this->data['tools_tpreset'] 			= $this->language->get('tools_tpreset');
		$this->data['tools_flycart'] 			= $this->language->get('tools_flycart');
		$this->data['tools_module'] 			= $this->language->get('tools_module');
		$this->data['tools_srtandart'] 			= $this->language->get('tools_srtandart');
		$this->data['tools_flypos'] 			= $this->language->get('tools_flypos');
		$this->data['tools_offset'] 			= $this->language->get('tools_offset');
		$this->data['tools_horizontal'] 		= $this->language->get('tools_horizontal');
		$this->data['tools_vertical'] 			= $this->language->get('tools_vertical');
		$this->data['tools_top_right'] 			= $this->language->get('tools_top_right');
		$this->data['tools_bottom_right'] 		= $this->language->get('tools_bottom_right');
		$this->data['tools_top_left'] 			= $this->language->get('tools_top_left');
		$this->data['tools_bottom_left'] 		= $this->language->get('tools_bottom_left');
		$this->data['tools_size'] 				= $this->language->get('tools_size');
		$this->data['tools_height'] 			= $this->language->get('tools_height');
		$this->data['tools_width'] 				= $this->language->get('tools_width');
		$this->data['tools_fixed'] 				= $this->language->get('tools_fixed');
		$this->data['tools_absolute'] 			= $this->language->get('tools_absolute');
		$this->data['tools_relative'] 			= $this->language->get('tools_relative');
		$this->data['tools_vimg'] 				= $this->language->get('tools_vimg');
		$this->data['tools_vtext'] 				= $this->language->get('tools_vtext');
		$this->data['tools_vcolor'] 			= $this->language->get('tools_vcolor');
		$this->data['tools_vsize'] 				= $this->language->get('tools_vsize');
		$this->data['tools_vmargin'] 			= $this->language->get('tools_vmargin');
		$this->data['tools_pgeneral'] 			= $this->language->get('tools_pgeneral');
		$this->data['tools_pheader'] 			= $this->language->get('tools_pheader');
		$this->data['tools_pcont'] 				= $this->language->get('tools_pcont');
		$this->data['tools_pfooter'] 			= $this->language->get('tools_pfooter');
		$this->data['tools_plb'] 				= $this->language->get('tools_plb');
		$this->data['tools_pcf'] 				= $this->language->get('tools_pcf');
		$this->data['tools_pco'] 				= $this->language->get('tools_pco');
		$this->data['tools_pbg'] 				= $this->language->get('tools_pbg');
		$this->data['tools_pcb'] 				= $this->language->get('tools_pcb');
		$this->data['tools_pct'] 				= $this->language->get('tools_pct');
		$this->data['tools_pcct'] 				= $this->language->get('tools_pcct');
		$this->data['tools_pcrt'] 				= $this->language->get('tools_pcrt');
		$this->data['tools_pcl'] 				= $this->language->get('tools_pcl');
		$this->data['tools_pcs'] 				= $this->language->get('tools_pcs');
		$this->data['tools_pce'] 				= $this->language->get('tools_pce');
		$this->data['tools_frame'] 				= $this->language->get('tools_frame');
		$this->data['tools_itemimg'] 			= $this->language->get('tools_itemimg');
		$this->data['tools_img'] 				= $this->language->get('tools_img');
		$this->data['tools_fs'] 				= $this->language->get('tools_fs');
		$this->data['tools_fc'] 				= $this->language->get('tools_fc');
		$this->data['tools_fsize'] 				= $this->language->get('tools_fsize');
		$this->data['tools_anim'] 				= $this->language->get('tools_anim');
		$this->data['tools_radius'] 			= $this->language->get('tools_radius');
		$this->data['tools_rotate'] 			= $this->language->get('tools_rotate');
		
		// presets
		$this->data['preset_1'] 				= $this->language->get('preset_1');
		$this->data['preset_2'] 				= $this->language->get('preset_2');
		$this->data['preset_3'] 				= $this->language->get('preset_3');
		$this->data['preset_4'] 				= $this->language->get('preset_4');
		$this->data['preset_5'] 				= $this->language->get('preset_5');
		$this->data['preset_6'] 				= $this->language->get('preset_6');
		$this->data['preset_7'] 				= $this->language->get('preset_7');
		
		// tools help
		$this->data['tools_tselech'] 			= $this->language->get('tools_tselech');
		$this->data['tools_tpreseth'] 			= $this->language->get('tools_tpreseth');
		$this->data['tools_optionsh'] 			= $this->language->get('tools_optionsh');
		$this->data['tools_butcolorh'] 			= $this->language->get('tools_butcolorh');
		$this->data['tools_flyposh'] 			= $this->language->get('tools_flyposh');
		$this->data['tools_positionh'] 			= $this->language->get('tools_positionh');
		$this->data['tools_horizontalh'] 		= $this->language->get('tools_horizontalh');
		$this->data['tools_verticalh'] 			= $this->language->get('tools_verticalh');
		$this->data['tools_heighth'] 			= $this->language->get('tools_heighth');
		$this->data['tools_widthh'] 			= $this->language->get('tools_widthh');
		$this->data['tools_vimgh'] 				= $this->language->get('tools_vimgh');
		$this->data['tools_vcolorh'] 			= $this->language->get('tools_vcolorh');
		$this->data['tools_vsizeh'] 			= $this->language->get('tools_vsizeh');
		$this->data['tools_vmarginh'] 			= $this->language->get('tools_vmarginh');
		$this->data['tools_plbh'] 				= $this->language->get('tools_plbh');
		$this->data['tools_pcfh'] 				= $this->language->get('tools_pcfh');
		$this->data['tools_pcoh'] 				= $this->language->get('tools_pcoh');
		$this->data['tools_pbgh'] 				= $this->language->get('tools_pbgh');
		$this->data['tools_pcbh'] 				= $this->language->get('tools_pcbh');
		$this->data['tools_pcth'] 				= $this->language->get('tools_pcth');
		$this->data['tools_pccth'] 				= $this->language->get('tools_pccth');
		$this->data['tools_pclh'] 				= $this->language->get('tools_pclh');
		$this->data['tools_pcth'] 				= $this->language->get('tools_pcth');
		$this->data['tools_pcrth'] 				= $this->language->get('tools_pcrth');
		$this->data['tools_pcbhb'] 				= $this->language->get('tools_pcbhb');
		$this->data['tools_pcsрh'] 				= $this->language->get('tools_pcsрh');
		$this->data['tools_pceh'] 				= $this->language->get('tools_pceh');
		$this->data['tools_pcbth'] 				= $this->language->get('tools_pcbth');
		$this->data['tools_butcolorp'] 			= $this->language->get('tools_butcolorp');
		$this->data['tools_tselecth'] 			= $this->language->get('tools_tselecth');
		$this->data['tools_fsh'] 				= $this->language->get('tools_fsh');
		$this->data['tools_fch'] 				= $this->language->get('tools_fch');
		$this->data['tools_fsizeh'] 			= $this->language->get('tools_fsizeh');
		$this->data['tools_animh'] 				= $this->language->get('tools_animh');
		$this->data['tools_radiush'] 			= $this->language->get('tools_radiush');
		$this->data['tools_rotateh'] 			= $this->language->get('tools_rotateh');
		
		// system
		$this->data['button_save'] 				= $this->language->get('button_save');
		$this->data['button_cancel'] 			= $this->language->get('button_cancel');
		$this->data['button_apply'] 			= $this->language->get('button_apply');
		$this->data['tools_choose'] 			= $this->language->get('tools_choose');
		$this->data['tools_upload'] 			= $this->language->get('tools_upload');
		$this->data['tools_delete'] 			= $this->language->get('tools_delete');
		$this->data['tools_close'] 				= $this->language->get('tools_close');
		$this->data['tools_open'] 				= $this->language->get('tools_open');
		$this->data['entry_description'] 		= $this->language->get('entry_description');
		$this->data['text_yes'] 				= $this->language->get('text_yes');
		$this->data['text_no']					= $this->language->get('text_no');
		$this->data['text_enabled'] 			= $this->language->get('text_enabled');
		$this->data['text_disabled'] 			= $this->language->get('text_disabled');
		$this->data['text_content_top'] 		= $this->language->get('text_content_top');
		$this->data['text_content_bottom'] 		= $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] 		= $this->language->get('text_column_left');
		$this->data['text_column_right'] 		= $this->language->get('text_column_right');
		$this->data['entry_admin'] 				= $this->language->get('entry_admin');
		$this->data['entry_layout'] 			= $this->language->get('entry_layout');
		$this->data['entry_position'] 			= $this->language->get('entry_position');
		$this->data['entry_status'] 			= $this->language->get('entry_status');
		$this->data['entry_sort_order'] 		= $this->language->get('entry_sort_order');
		$this->data['button_save'] 				= $this->language->get('button_save');
		$this->data['button_cancel'] 			= $this->language->get('button_cancel');
		$this->data['button_add_module'] 		= $this->language->get('button_add_module');
		$this->data['button_remove'] 			= $this->language->get('button_remove');		
		

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();		
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' &#10026; '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('common_title'),
			'href'      => $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' &#10026; '
   		);
		
		$this->data['action'] = $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
/*---------------------------------------------------------------------------------------------------------------------------*/	
		
		if( isset( $this->request->post[$this->_name . '_type'] ) ) {
			$this->data[$this->_name . '_type'] = $this->request->post[$this->_name . '_type'];
		}else{
			$this->data[$this->_name . '_type'] = $this->config->get( $this->_name . '_type' );
		}	

		if( isset( $this->request->post[$this->_name . '_preset'] ) ) {
			$this->data[$this->_name . '_preset'] = $this->request->post[$this->_name . '_preset'];
		}else{
			$this->data[$this->_name . '_preset'] = $this->config->get( $this->_name . '_preset' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_position'] ) ) {
			$this->data[$this->_name . '_position'] = $this->request->post[$this->_name . '_position'];
		}else{
			$this->data[$this->_name . '_position'] = $this->config->get( $this->_name . '_position' );
		}				
		
		if( isset( $this->request->post[$this->_name . '_topions'] ) ) {
			$this->data[$this->_name . '_topions'] = $this->request->post[$this->_name . '_topions'];
		}else{
			$this->data[$this->_name . '_topions'] = $this->config->get( $this->_name . '_topions' );
		}		
		
		if( isset( $this->request->post[$this->_name . '_offset_y'] ) ) {
			$this->data[$this->_name . '_offset_y'] = $this->request->post[$this->_name . '_offset_y'];
		}else{
			$this->data[$this->_name . '_offset_y'] = $this->config->get( $this->_name . '_offset_y' );
		}		
		
		if( isset( $this->request->post[$this->_name . '_offset_x'] ) ) {
			$this->data[$this->_name . '_offset_x'] = $this->request->post[$this->_name . '_offset_x'];
		}else{
			$this->data[$this->_name . '_offset_x'] = $this->config->get( $this->_name . '_offset_x' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_fheight'] ) ) {
			$this->data[$this->_name . '_fheight'] = $this->request->post[$this->_name . '_fheight'];
		}else{
			$this->data[$this->_name . '_fheight'] = $this->config->get( $this->_name . '_fheight' );
		}		
		
		if( isset( $this->request->post[$this->_name . '_fwidth'] ) ) {
			$this->data[$this->_name . '_fwidth'] = $this->request->post[$this->_name . '_fwidth'];
		}else{
			$this->data[$this->_name . '_fwidth'] = $this->config->get( $this->_name . '_fwidth' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_postype'] ) ) {
			$this->data[$this->_name . '_postype'] = $this->request->post[$this->_name . '_postype'];
		}else{
			$this->data[$this->_name . '_postype'] = $this->config->get( $this->_name . '_postype' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_image'] ) ) {
			$this->data[$this->_name . '_image'] = $this->request->post[$this->_name . '_image'];
		}else{
			$this->data[$this->_name . '_image'] = $this->config->get( $this->_name . '_image' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_tcolor'] ) ) {
			$this->data[$this->_name . '_tcolor'] = $this->request->post[$this->_name . '_tcolor'];
		}else{
			$this->data[$this->_name . '_tcolor'] = $this->config->get( $this->_name . '_tcolor' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_tsize'] ) ) {
			$this->data[$this->_name . '_tsize'] = $this->request->post[$this->_name . '_tsize'];
		}else{
			$this->data[$this->_name . '_tsize'] = $this->config->get( $this->_name . '_tsize' );
		}					
		
		if( isset( $this->request->post[$this->_name . '_tmtop'] ) ) {
			$this->data[$this->_name . '_tmtop'] = $this->request->post[$this->_name . '_tmtop'];
		}else{
			$this->data[$this->_name . '_tmtop'] = $this->config->get( $this->_name . '_tmtop' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_tmright'] ) ) {
			$this->data[$this->_name . '_tmright'] = $this->request->post[$this->_name . '_tmright'];
		}else{
			$this->data[$this->_name . '_tmright'] = $this->config->get( $this->_name . '_tmright' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_tmbottom'] ) ) {
			$this->data[$this->_name . '_tmbottom'] = $this->request->post[$this->_name . '_tmbottom'];
		}else{
			$this->data[$this->_name . '_tmbottom'] = $this->config->get( $this->_name . '_tmbottom' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_tmleft'] ) ) {
			$this->data[$this->_name . '_tmleft'] = $this->request->post[$this->_name . '_tmleft'];
		}else{
			$this->data[$this->_name . '_tmleft'] = $this->config->get( $this->_name . '_tmleft' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color_bgp'] ) ) {
			$this->data[$this->_name . '_color_bgp'] = $this->request->post[$this->_name . '_color_bgp'];
		}else{
			$this->data[$this->_name . '_color_bgp'] = $this->config->get( $this->_name . '_color_bgp' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_head_bgp'] ) ) {
			$this->data[$this->_name . '_head_bgp'] = $this->request->post[$this->_name . '_head_bgp'];
		}else{
			$this->data[$this->_name . '_head_bgp'] = $this->config->get( $this->_name . '_head_bgp' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_bhead_bgp'] ) ) {
			$this->data[$this->_name . '_bhead_bgp'] = $this->request->post[$this->_name . '_bhead_bgp'];
		}else{
			$this->data[$this->_name . '_bhead_bgp'] = $this->config->get( $this->_name . '_bhead_bgp' );
		}				
		
		if( isset( $this->request->post[$this->_name . '_chead_bgp'] ) ) {
			$this->data[$this->_name . '_chead_bgp'] = $this->request->post[$this->_name . '_chead_bgp'];
		}else{
			$this->data[$this->_name . '_chead_bgp'] = $this->config->get( $this->_name . '_chead_bgp' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_close_bg'] ) ) {
			$this->data[$this->_name . '_close_bg'] = $this->request->post[$this->_name . '_close_bg'];
		}else{
			$this->data[$this->_name . '_close_bg'] = $this->config->get( $this->_name . '_close_bg' );
		}				
		
		if( isset( $this->request->post[$this->_name . '_remove_bg'] ) ) {
			$this->data[$this->_name . '_remove_bg'] = $this->request->post[$this->_name . '_remove_bg'];
		}else{
			$this->data[$this->_name . '_remove_bg'] = $this->config->get( $this->_name . '_remove_bg' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color_a'] ) ) {
			$this->data[$this->_name . '_color_a'] = $this->request->post[$this->_name . '_color_a'];
		}else{
			$this->data[$this->_name . '_color_a'] = $this->config->get( $this->_name . '_color_a' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color'] ) ) {
			$this->data[$this->_name . '_color'] = $this->request->post[$this->_name . '_color'];
		}else{
			$this->data[$this->_name . '_color'] = $this->config->get( $this->_name . '_color' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_border'] ) ) {
			$this->data[$this->_name . '_border'] = $this->request->post[$this->_name . '_border'];
		}else{
			$this->data[$this->_name . '_border'] = $this->config->get( $this->_name . '_border' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_scroll'] ) ) {
			$this->data[$this->_name . '_scroll'] = $this->request->post[$this->_name . '_scroll'];
		}else{
			$this->data[$this->_name . '_scroll'] = $this->config->get( $this->_name . '_scroll' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color_fgp'] ) ) {
			$this->data[$this->_name . '_color_fgp'] = $this->request->post[$this->_name . '_color_fgp'];
		}else{
			$this->data[$this->_name . '_color_fgp'] = $this->config->get( $this->_name . '_color_fgp' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color_fbgp'] ) ) {
			$this->data[$this->_name . '_color_fbgp'] = $this->request->post[$this->_name . '_color_fbgp'];
		}else{
			$this->data[$this->_name . '_color_fbgp'] = $this->config->get( $this->_name . '_color_fbgp' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_pbutton'] ) ) {
			$this->data[$this->_name . '_pbutton'] = $this->request->post[$this->_name . '_pbutton'];
		}else{
			$this->data[$this->_name . '_pbutton'] = $this->config->get( $this->_name . '_pbutton' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_empty'] ) ) {
			$this->data[$this->_name . '_empty'] = $this->request->post[$this->_name . '_empty'];
		}else{
			$this->data[$this->_name . '_empty'] = $this->config->get( $this->_name . '_empty' );
		}		
		
		if( isset( $this->request->post[$this->_name . '_overlay'] ) ) {
			$this->data[$this->_name . '_overlay'] = $this->request->post[$this->_name . '_overlay'];
		}else{
			$this->data[$this->_name . '_overlay'] = $this->config->get( $this->_name . '_overlay' );
		}						
		
		if( isset( $this->request->post[$this->_name . '_pselect'] ) ) {
			$this->data[$this->_name . '_pselect'] = $this->request->post[$this->_name . '_pselect'];
		}else{
			$this->data[$this->_name . '_pselect'] = $this->config->get( $this->_name . '_pselect' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_flytype'] ) ) {
			$this->data[$this->_name . '_flytype'] = $this->request->post[$this->_name . '_flytype'];
		}else{
			$this->data[$this->_name . '_flytype'] = $this->config->get( $this->_name . '_flytype' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_flyimage'] ) ) {
			$this->data[$this->_name . '_flyimage'] = $this->request->post[$this->_name . '_flytype'];
		}else{
			$this->data[$this->_name . '_flyimage'] = $this->config->get( $this->_name . '_flyimage' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_bselect'] ) ) {
			$this->data[$this->_name . '_bselect'] = $this->request->post[$this->_name . '_bselect'];
		}else{
			$this->data[$this->_name . '_bselect'] = $this->config->get( $this->_name . '_bselect' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_color_f'] ) ) {
			$this->data[$this->_name . '_color_f'] = $this->request->post[$this->_name . '_color_f'];
		}else{
			$this->data[$this->_name . '_color_f'] = $this->config->get( $this->_name . '_color_f' );
		}				
		
		if( isset( $this->request->post[$this->_name . '_frselect'] ) ) {
			$this->data[$this->_name . '_frselect'] = $this->request->post[$this->_name . '_frselect'];
		}else{
			$this->data[$this->_name . '_frselect'] = $this->config->get( $this->_name . '_frselect' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_size_f'] ) ) {
			$this->data[$this->_name . '_size_f'] = $this->request->post[$this->_name . '_size_f'];
		}else{
			$this->data[$this->_name . '_size_f'] = $this->config->get( $this->_name . '_size_f' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_speed'] ) ) {
			$this->data[$this->_name . '_speed'] = $this->request->post[$this->_name . '_speed'];
		}else{
			$this->data[$this->_name . '_speed'] = $this->config->get( $this->_name . '_speed' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_rtselect'] ) ) {
			$this->data[$this->_name . '_rtselect'] = $this->request->post[$this->_name . '_rtselect'];
		}else{
			$this->data[$this->_name . '_rtselect'] = $this->config->get( $this->_name . '_rtselect' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_radius'] ) ) {
			$this->data[$this->_name . '_radius'] = $this->request->post[$this->_name . '_radius'];
		}else{
			$this->data[$this->_name . '_radius'] = $this->config->get( $this->_name . '_radius' );
		}			
		
		if( isset( $this->request->post[$this->_name . '_numtype'] ) ) {
			$this->data[$this->_name . '_numtype'] = $this->request->post[$this->_name . '_numtype'];
		}else{
			$this->data[$this->_name . '_numtype'] = $this->config->get( $this->_name . '_numtype' );
		}			

/*---------------------------------------------------------------------------------------------------------------------------*/	

// cart_type
		$this->data['cart_type'] = array();
					
		$this->data['cart_type'][] = array(
			'name' => 'fly',
			'title'  => $this->data['tools_flycart'],
		);				
		$this->data['cart_type'][] = array(
			'name' => 'module',
			'title'  => $this->data['tools_module'],
		);
		$this->data['cart_type'][] = array(
			'name' => 'standart',
			'title'  => $this->data['tools_srtandart'],
		);	
		
// popup
		$this->data['popup_select'] = array();
					
		$this->data['popup_select'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);				
		$this->data['popup_select'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);
		
// popup
		$this->data['popup_topions'] = array();
					
		$this->data['popup_topions'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);				
		$this->data['popup_topions'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);	
			
// cart_position
		$this->data['cart_position'] = array();
		
		$this->data['cart_position'][] = array(
			'name' => 'top_right',
			'title'  => $this->data['tools_top_right'],
		);		
		$this->data['cart_position'][] = array(
			'name' => 'bottom_right',
			'title'  => $this->data['tools_bottom_right'],
		);
		$this->data['cart_position'][] = array(
			'name' => 'top_left',
			'title'  => $this->data['tools_top_left'],
		);	
		$this->data['cart_position'][] = array(
			'name' => 'bottom_left',
			'title'  => $this->data['tools_bottom_left'],
		);	
		
// cart_preset
		$this->data['cart_preset'] = array();
		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_1',
			'title'  => $this->data['preset_1'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_2',
			'title'  => $this->data['preset_2'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_3',
			'title'  => $this->data['preset_3'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_4',
			'title'  => $this->data['preset_4'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_5',
			'title'  => $this->data['preset_5'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_6',
			'title'  => $this->data['preset_6'],
		);		
		$this->data['cart_preset'][] = array(
			'name' => 'preset_7',
			'title'  => $this->data['preset_7'],
		);

// cart_postype
		$this->data['cart_postype'] = array();
		
		$this->data['cart_postype'][] = array(
			'name' => 'fixed',
			'title'  => $this->data['tools_fixed'],
		);		
		$this->data['cart_postype'][] = array(
			'name' => 'absolute',
			'title'  => $this->data['tools_absolute'],
		);
		$this->data['cart_postype'][] = array(
			'name' => 'relative',
			'title'  => $this->data['tools_relative'],
		);	
		
// popup_button
		$this->data['popup_button'] = array();
		
		$this->data['popup_button'][] = array(
			'name' => 'btn-primary',
			'title'  => $this->data['tools_blue'],
		);		
		$this->data['popup_button'][] = array(
			'name' => 'btn-inverse',
			'title'  => $this->data['tools_black'],
		);	
		$this->data['popup_button'][] = array(
			'name' => 'btn-info',
			'title'  => $this->data['tools_lblue'],
		);
		$this->data['popup_button'][] = array(
			'name' => 'white',
			'title'  => $this->data['tools_white'],
		);
		$this->data['popup_button'][] = array(
			'name' => 'btn-success',
			'title'  => $this->data['tools_green'],
		);
		$this->data['popup_button'][] = array(
			'name' => 'btn-warning',
			'title'  => $this->data['tools_orange'],
		);
		$this->data['popup_button'][] = array(
			'name' => 'btn-danger',
			'title'  => $this->data['tools_red'],
		);		
		
// cart_flytype
		$this->data['cart_flytype'] = array();

		$this->data['cart_flytype'][] = array(
			'name' => 'frame',
			'title'  => $this->data['tools_frame'],
		);		
		$this->data['cart_flytype'][] = array(
			'name' => 'item_img',
			'title'  => $this->data['tools_itemimg'],
		);		
		$this->data['cart_flytype'][] = array(
			'name' => 'image',
			'title'  => $this->data['tools_img'],
		);		
		$this->data['cart_flytype'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);	
		
// popup_bselect 
		$this->data['popup_bselect'] = array();
		
		$this->data['popup_bselect'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);
		$this->data['popup_bselect'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);	
		
// cart_frselect 
		$this->data['cart_frselect'] = array();
		
		$this->data['cart_frselect'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);			
		$this->data['cart_frselect'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);	
		
// cart_rtselect 
		$this->data['cart_rtselect'] = array();
		
		$this->data['cart_rtselect'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);			
		$this->data['cart_rtselect'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);
		
// cart_numtype 
		$this->data['cart_numtype'] = array();
		
		$this->data['cart_numtype'][] = array(
			'name' => 'yes',
			'title'  => $this->data['text_yes'],
		);			
		$this->data['cart_numtype'][] = array(
			'name' => 'none',
			'title'  => $this->data['text_no'],
		);	
		
/*---------------------------------------------------------------------------------------------------------------------------*/	

		$this->data['modules'] = array();
		
		if (isset($this->request->post[$this->_name . '_module'])) {
			$this->data['modules'] = $this->request->post[$this->_name . '_module'];
		} elseif ($this->config->get($this->_name . '_module')) { 
			$this->data['modules'] = $this->config->get($this->_name . '_module');
		}
/*---------------------------------------------------------------------------------------------------------------------------*/	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'module/' . $this->_name . '.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/' .$this->_name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>