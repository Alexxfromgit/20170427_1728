<?php 
/*  path: admin/view/template/module/quickcheckout.tpl
*   author: dreamvention team
*/
?><?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-puzzle"></i><?php echo $heading_title; ?> <?php echo $version; ?></h1>
      <div class="buttons"><?php if(isset($stores)){ ?>
        <select onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
          <?php foreach($stores as $store){ ?>
          <?php if($store['store_id'] ==  $store_id){ ?>
          <option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
          <?php }else{ ?>
          <option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select><?php } ?><a onClick="saveAndStay()" class="button"><span><?php echo $button_save_and_stay; ?></span></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="vtabs">
        <ul>
          <li><a href="#home" id="vtab_item_1" class="vtab-item"><i class="icon-home"></i><span>Home</span></a></li>
          <li><a href="#general" id="vtab_item_1" class="vtab-item"><i class="icon-settings"></i><span><?php echo $text_general; ?></span></a></li>
          <li><a href="#login" id="vtab_item_1" class="vtab-item"><i class="icon-key"></i><span><?php echo $text_login; ?></span></a></li>
          <li><a href="#payment_address" id="vtab_item_1" class="vtab-item"><i class="icon-address"></i><span><?php echo $text_payment_address; ?></span></a></li>
          <li><a href="#shipping_address" id="vtab_item_1" class="vtab-item"><i class="icon-address"></i><span><?php echo $text_shipping_address; ?></span></a></li>
          <li><a href="#shipping_method" id="vtab_item_1" class="vtab-item"><i class="icon-shipping"></i><span><?php echo $text_shipping_method; ?></span></a></li>
          <li><a href="#payment_method" id="vtab_item_1" class="vtab-item"><i class="icon-payment"></i><span><?php echo $text_payment_method; ?></span></a></li>
          <li><a href="#confirm" id="vtab_item_1" class="vtab-item"><i class="icon-cart"></i><span><?php echo $text_confirm; ?></span></a></li>
          <li><a href="#design" id="vtab_item_1" class="vtab-item"><i class="icon-design"></i><span><?php echo $text_design; ?></span></a></li>
          <li><a href="#analytics" id="vtab_item_1" class="vtab-item"><i class="icon-chart-bar"></i><span><?php echo $text_analytics; ?></span></a></li>
        </ul>
      </div>
      <div id="home" class="vtabs-content">
        <div class="tab-title"><i class="icon-home"></i><span>Home</span></div>
        <div class="tab-body dashboard">
        <div class="heading-group">
        <h1><?php echo $heading_title; ?></h1>
        <h3><?php echo $heading_title_slogan; ?></h3>
        </div>
        <div class="row">
<div class="col-sm-6 col-md-4"><a href="#general" class="tab-trigger"><i class="icon-settings icon-large"></i><h3><?php echo $text_general; ?></h3><span><?php echo $text_general_intro; ?></span></a></div>
  <div class="clear  visible-xs"></div>
  <div class="col-sm-6 col-md-4"><a href="#login" class="tab-trigger"><i class="icon-key icon-large"></i><h3><?php echo $text_login; ?></h3><span><?php echo $text_login_intro; ?></span></a></div>
  <div class="clear  visible-sm visible-xs"></div>
  <div class="col-sm-6 col-md-4"><a href="#payment_address" class="tab-trigger"><i class="icon-address icon-large"></i><h3><?php echo $text_payment_address; ?></h3><span><?php echo $text_payment_address_intro; ?></span></a></div>
  <div class="clear  hidden-sm "></div>
 <div class="col-sm-6 col-md-4"><a href="#shipping_address" class="tab-trigger"><i class="icon-address icon-large"></i><h3><?php echo $text_shipping_address; ?></h3><span><?php echo $text_shipping_address_intro; ?></span></a></div>  
   <div class="clear  visible-sm visible-xs"></div>
   <div class="col-sm-6 col-md-4"><a href="#shipping_method" class="tab-trigger"><i class="icon-shipping icon-large"></i><h3><?php echo $text_shipping_method; ?></h3><span><?php echo $text_shipping_method_intro; ?></span></a></div>
  <div class="clear  visible-xs"></div>
    <div class="col-sm-6 col-md-4"><a href="#payment_method" class="tab-trigger"><i class="icon-payment icon-large"></i><h3><?php echo $text_payment_method; ?></h3><span><?php echo $text_payment_method_intro; ?></span></a></div>
    <div class="clear  hidden-sm "></div>
    <div class="col-sm-6 col-md-4"><a href="#confirm" class="tab-trigger"><i class="icon-cart icon-large"></i><h3><?php echo $text_confirm; ?></h3><span><?php echo $text_confirm_intro; ?></span></a></div>
  <div class="clear  visible-xs"></div>
  <div class="col-sm-6 col-md-4"><a href="#design" class="tab-trigger"><i class="icon-design icon-large"></i><h3><?php echo $text_design; ?></h3><span><?php echo $text_design_intro; ?></span></a></div>
  <div class="clear  visible-sm visible-xs"></div>
  <div class="col-sm-6 col-md-4"><a href="#analytics" class="tab-trigger"><i class="icon-chart-bar icon-large"></i><h3><?php echo $text_analytics; ?></h3><span><?php echo $text_analytics_intro; ?></span></a></div>
 
  </div>
  

        </div>
      </div>
      <!---------------------------------- General ---------------------------------->
      <div id="general" class="vtabs-content">
        <div class="tab-title"><i class="icon-settings"></i><span><?php echo $text_general; ?></span></div>
        <div class="tab-body">
          <div class="row">
            <div class="col-md-6"><div class="wrap-5"><table class="form">
                <tr>
                  <td class="name"><span><?php echo $text_general_enable; ?></span><i class="icon-question" rel="tooltip" data-help="<?php echo $general_enable_tooltip; ?>"></i></td>
                  <td class="settings "><input type="hidden" value="0" name="quickcheckout[general][enable]" />
                    <?php if(isset($quickcheckout['general']['enable']) && $quickcheckout['general']['enable'] == 1){ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][enable]" id="checkout_enable" checked="checked" />
                    <?php }else{ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][enable]" id="checkout_enable"/>
                    <?php } ?>
                    <label  for="checkout_enable"><?php echo $settings_enable; ?></label>
                  </td>
                </tr>
                
                
                <tr>
                  <td class="name"><span><?php echo $text_general_default; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_default_tooltip; ?>"></i></td>
                  <td class="settings">
                    <?php if(isset($quickcheckout['general']['default_option']) && $quickcheckout['general']['default_option'] == 'guest'){ ?>
                    <input class="styled" type="radio" value="guest" name="quickcheckout[general][default_option]" checked="checked" id="general_default_option_guest" /><label  for="general_default_option_guest"><?php echo $text_guest; ?></label>
                    <input class="styled" type="radio" value="register" name="quickcheckout[general][default_option]" id="general_default_option_register" /><label  for="general_default_option_register"><?php echo $text_register; ?></label>
                    <?php }else{ ?>
                    <input class="styled" type="radio" value="guest" name="quickcheckout[general][default_option]" id="general_default_option_guest" /><label  for="general_default_option_guest"><?php echo $text_guest; ?></label>
                    <input class="styled" type="radio" value="register" name="quickcheckout[general][default_option]" checked="checked"  id="general_default_option_register" /><label  for="general_default_option_register"><?php echo $text_register; ?></label>
                    <?php } ?>
                    </td>
                  
                </tr>
                <tr>
                  <td class="name"><span><?php echo $text_general_main_checkout; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_main_checkout_tooltip; ?>"></i></td>
                  <td class="settings"><input type="hidden" value="0" name="quickcheckout[general][main_checkout]" />
                    <?php if(isset($quickcheckout['general']['main_checkout']) && $quickcheckout['general']['main_checkout'] == 1){ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][main_checkout]" checked="checked" id="general_main_checkout" />
                    <?php }else{ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][main_checkout]" id="general_main_checkout" />
                    <?php } ?>
                    <label  for="general_main_checkout"><?php echo $settings_enable; ?></label></td>
                </tr>

                <tr>
                  <td class="name"><span><?php echo $text_general_clear_session; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_clear_session_tooltip; ?>"></i></td>
                  <td class="settings"><input type="hidden" value="0" name="quickcheckout[general][clear_session]" />
                    <?php if(isset($quickcheckout['general']['clear_session']) && $quickcheckout['general']['clear_session'] == 1){ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][clear_session]" checked="checked" id="general_clear_session" />
                    <?php }else{ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][clear_session]" id="general_clear_session" />
                    <?php } ?>
                    <label  for="general_clear_session"><?php echo $settings_enable; ?></label></td>
                </tr>
                <tr>
                  <td class="name"><span><?php echo $text_general_login_refresh; ?></span></td>
                  <td class="settings"><input type="hidden" value="0" name="quickcheckout[general][login_refresh]" />
                    <?php if(isset($quickcheckout['general']['login_refresh']) && $quickcheckout['general']['login_refresh'] == 1){ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][login_refresh]" checked="checked" id="general_login_refresh" />
                    <?php }else{ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][login_refresh]" id="general_login_refresh" />
                    <?php } ?>
                    <label  for="general_login_refresh"><?php echo $settings_enable; ?></label></td>
                </tr>
                
                <tr>
                  <td class="name"><span><?php echo $text_general_default_email; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_default_email_tooltip; ?>"></i></td>
                  <td class="settings"><?php if(isset($quickcheckout['general']['default_email']) && $quickcheckout['general']['default_email'] != ""){ ?>
                    <input type="text" value="<?php echo $quickcheckout['general']['default_email']; ?>" name="quickcheckout[general][default_email]" id="general_default_email" class="styled"/>
                    <?php }else{ ?>
                    <input type="text" value="0" name="quickcheckout[general][default_email]" id="general_default_email"/>
                    <?php } ?>
                   
                  </td>
                  
                </tr>
            </table></div></div>
            <div class="col-md-6"><div class="wrap-5">
              <table class="form">
              <tr>
                  <td class="name"><span><?php echo $text_general_version; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_version_tooltip; ?>"></i></td>
                  <td class="settings "><a id="version_check" class="button"><?php echo $text_debug_button; ?></a><div id="version_result"></div>
                  </td>
                   
                </tr>
                <tr>
                  <td class="name"><span><?php echo $text_general_debug; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_debug_tooltip; ?>"></i></td>
                  <td class="settings"><input type="hidden" value="0" name="quickcheckout[general][debug]" />
                    <?php if(isset($quickcheckout['general']['debug']) && $quickcheckout['general']['debug'] == 1){ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][debug]" checked="checked" id="general_debug" />
                    <?php }else{ ?>
                    <input class="styled" type="checkbox" value="1" name="quickcheckout[general][debug]" id="general_debug" />
                    <?php } ?>
                    <label  for="general_debug"><?php echo $settings_enable; ?></label></td>
                 
                </tr>
                <tr>
                  <td class="name"><span><?php echo $text_general_min_order; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_min_order_tooltip; ?>"></i></td>
                  <td class="settings"><label for="general_min_order_value" id="label_general_min_order_value"> %s</label><?php if(isset($quickcheckout['general']['min_order']['value']) && $quickcheckout['general']['min_order']['value'] != ""){ ?>
                    <input type="text" value="<?php echo $quickcheckout['general']['min_order']['value']; ?>" name="quickcheckout[general][min_order][value]" id="general_min_order_value" class="styled"/>
                    <?php }else{ ?>
                    <input type="text" value="0" name="quickcheckout[general][min_order][value]" id="general_min_order_value"/>
                    <?php } ?>
                    
                  <?php foreach ($languages as $language) { ?>
                    <div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>">
                      <label for="general_min_order_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></label>
                      <input type="text" name="quickcheckout[general][min_order][text][<?php echo $language['language_id']; ?>]" id="general_min_order_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($quickcheckout['general']['min_order']['text'][$language['language_id']]) ? $quickcheckout['general']['min_order']['text'][$language['language_id']] : $language_min_order_text; ?>" >
                    </div>
                    <?php } ?>
                  </td>
                </tr>
                 <tr>
                  <td class="name"><span><?php echo $text_general_min_quantity; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_min_quantity_tooltip; ?>"></i></td>
                  <td class="settings"><label for="general_min_quantity_value" id="label_general_min_quantity_value"> %s</label><?php if(isset($quickcheckout['general']['min_quantity']['value']) && $quickcheckout['general']['min_quantity']['value'] != ""){ ?>
                    <input type="text" value="<?php echo $quickcheckout['general']['min_quantity']['value']; ?>" name="quickcheckout[general][min_quantity][value]" id="general_min_quantity_value" class="styled"/>
                    <?php }else{ ?>
                    <input type="text" value="0" name="quickcheckout[general][min_quantity][value]" id="general_min_quantity_value"/>
                    <?php } ?>
                    
                  <?php foreach ($languages as $language) { ?>
                    <div id="tab_general_min_quantity_text_<?php echo $language['language_id']; ?>">
                      <label for="general_min_quantity_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></label>
                      <input type="text" name="quickcheckout[general][min_quantity][text][<?php echo $language['language_id']; ?>]" id="general_min_quantity_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($quickcheckout['general']['min_quantity']['text'][$language['language_id']]) ? $quickcheckout['general']['min_quantity']['text'][$language['language_id']] : $language_min_quantity_text; ?>" >
                    </div>
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                    <td class="name"><span><?php echo $text_general_trigger; ?></span><i class="icon-question" rel="tooltip"  data-help="<?php echo $general_trigger_tooltip; ?>"></i></td>
                    <td colspan="2"><?php if(isset($quickcheckout['general']['trigger'])){ ?>
                      <input name="quickcheckout[general][trigger]" class="full-width" value="<?php echo $quickcheckout['general']['trigger']; ?>" />
                      <?php }else{ ?>
                      <input name="quickcheckout[general][trigger]" class="full-width" value="" />
                      <?php } ?></td>
                  </tr> 
            </table></div></div>
          </div>

          
          <h3><?php echo $text_position_module; ?><i class="icon-question" rel="tooltip"  data-help="<?php echo $position_module_tooltip; ?>"></i></h2>
           <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="left"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($quickcheckout_modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="quickcheckout_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="quickcheckout_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="quickcheckout_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><input type="text" name="quickcheckout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onClick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onClick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
        </div>
      </div>
      <!---------------------------------- Login ---------------------------------->
      <div id="login" class="vtabs-content">
        <div class="tab-title"><i class="icon-key"></i><span><?php echo $text_login; ?></span></div>
        <div class="tab-body table-form">
            
            <?php if($quickcheckout['general']['social_login']) { ?>
            <div id="sortable_social_login" class="atab col-md-5">

              <?php foreach($quickcheckout['general']['social_login']['providers'] as $provider_name => $provider){ ?>
                <?php if(isset($provider['id'])) { ?> 
                <div class="row sort-item atab-item">
                  <div class="col-sm-5"><span><span class="<?php echo $provider['icon']; ?>"></span> <?php echo ${'text_'.$provider['id']};?></span></div>
                  <div class="col-sm-7"><span>
                    <input type="hidden" class="sort-value" value="<?php echo $provider['sort_order']; ?>" name="quickcheckout[general][social_login][providers][<?php echo $provider_name; ?>][sort_order]">
                    <input type="hidden" value="0" name="quickcheckout[general][social_login][providers][<?php echo $provider_name; ?>][enabled]">
                    <input type="checkbox" value="1" id="general_social_login_providers_<?php echo $provider['id']; ?>" <?php echo ($provider['enabled']) ? 'checked="checked"': ''; ?> name="quickcheckout[general][social_login][providers][<?php echo $provider_name; ?>][enabled]"> <label for="general_social_login_providers_<?php echo $provider['id']; ?>"><?php echo $settings_enable; ?><label></span> 
                    <span class="icon-drag"></span>
                  </div>
                </div>
                <?php } ?>
              <?php } ?>
              </div>
              <div class="col-md-7">
                <div class="wrap-5">
                  <div class="row">
                    <div class="col-md-4"><span><?php echo $text_socila_login_style; ?></span></div>
                    <div class="col-md-8">
                    <select name="quickcheckout[general][socila_login_style]">
                      <?php foreach ($socila_login_styles as $style) {?>
                              <?php if($quickcheckout['general']['socila_login_style'] == $style){ ?>
                                <option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
                                <?php }else{ ?>
                                <option value="<?php echo $style; ?>"><?php echo $style; ?></option>
                                <?php } ?>
                      <?php } ?>
                     </select>
                     </div>
                  </div>
                  <div class="row">
                  <a class="button" href="<?php echo $link_social_login_edit; ?>"><?php echo $text_social_login_edit?></a>
                  </div>
                </div>
              </div>
            <?php }else{ ?>
              <div class="callout callout-warning"><?php echo $text_social_login_required; ?></div>
            <?php } ?>
            <script>
              $(function() {
                  $( "#sortable_social_login" ).sortable({
                    axis: "y",
                    placeholder: "ui-state-highlight",
                    distance: 5,
                    stop: function( event, ui ) {
                      ui.item.children( ".sort-item" ).triggerHandler( "focusout" );
                  $(this).find(".sort-item").each(function(i, el){
                    $(this).find(".sort-value").val($(el).index())
                  });
                    }
                  });
              });
            </script>
        </div>
      </div>
      <!---------------------------------- Payment Address ---------------------------------->
      <div id="payment_address" class="vtabs-content">
        <div class="tab-title"><i class="icon-address"></i><span><?php echo $text_payment_address; ?></span></div>
        <div class="tab-body">
          <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
        </div>
      </div>
      <!---------------------------------- Shipping Address ---------------------------------->
      <div id="shipping_address" class="vtabs-content">
        <div class="tab-title"><i class="icon-address"></i><span><?php echo $text_shipping_address; ?></span></div>
        <div class="tab-body">
        <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
            </div>
      </div>
      <!---------------------------------- Shipping Method ---------------------------------->
      <div id="shipping_method" class="vtabs-content">
        <div class="tab-title"><i class="icon-shipping"></i><span><?php echo $text_shipping_method; ?></span></div>
        <div class="tab-body">
        <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
            </div>
      </div>
      <!---------------------------------- Payment Method ---------------------------------->
      <div id="payment_method" class="vtabs-content">
        <div class="tab-title"><i class="icon-payment"></i><span><?php echo $text_payment_method; ?></span></div>
        <div class="tab-body">
<div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
            </div>
      </div>
      <!---------------------------------- Confirm ---------------------------------->
      <div id="confirm" class="vtabs-content">
        <div class="tab-title"><i class="icon-cart"></i><span><?php echo $text_confirm; ?></span></div>
        <div class="tab-body">
        <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
            </div>
      </div>
      <!---------------------------------- Design ---------------------------------->
      <div id="design" class="vtabs-content">
        <div class="tab-title"><i class="icon-design"></i><span><?php echo $text_design; ?></span></div>
        <div class="tab-body">
        <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
            </div>
      </div>
      <!---------------------------------- Analytics ---------------------------------->
      <div id="analytics" class="vtabs-content">
        <div class="tab-title"><i class="icon-chart-bar"></i><span><?php echo $text_analytics; ?></span></div>
        <div class="tab-body">In Development.</div>
      </div>
    </form>
    </div><!-- .content -->
  </div><!-- .box -->
</div><!-- #content -->
<script>
$('.vtabs a').tabs();
</script>
<?php $main_width = 100/576;
$column_1 =  $quickcheckout['general']['column_width'][1]/$main_width; 
$column_2 = $quickcheckout['general']['column_width'][2]/$main_width;
$column_3 = $quickcheckout['general']['column_width'][3]/$main_width;
$column_4 = $quickcheckout['general']['column_width'][4]/$main_width; ?>
<style>
.full-width{
  width:100%}
  .column-1, .column-data-1{width:<?php echo $column_1; ?>px;}
 .column-2, .column-data-2{
   width:<?php echo $column_2; ?>px;} 
 .column-3, .column-data-3{width:<?php echo $column_3; ?>px;}
 .column-4, .column-data-4{width:<?php echo $column_4; ?>px;}
 #column_group_2{width:<?php echo $column_4; ?>px;}

</style>
<script type="text/javascript">
// var editor = CodeMirror.fromTextArea(document.getElementById("general_checkout_style"), {});


//   function resizeMenu(){
//     $("#tabs_menu").css({minHeight:($('.content').height())});
//     console.log($('.content').height());
//   }
//   $(document).ready(function(){
//     resizeMenu();

      
    
    
//   })

  $('#tabs_menu a').tabs();
  $('#tabs_menu a').click(function(){
    resizeMenu();
  })
  $('.tile a').click(function(){
    $('#tabs_menu a[href='+$(this).attr('href')+']').trigger('click')
    resizeMenu();
    return false  
  })

  $('.active a').click(function(){
    $('.active .activeClass').removeClass('activeClass');
    $(this).addClass('activeClass');
  });

      $(function(){
        $("input:checkbox.styled, input:radio.styled").uniform();
      });

// $(function() {
//   $('#checkout_select_all').click(function(){
//     $('input:checkbox').attr('checked', 'checked')
//     $.uniform.update();
//   })
  
//   $('#checkout_unselect_all').click(function(){
//     $('input:checkbox').removeAttr('checked')
//     $.uniform.update();
//   })
  
//   $('.sortable > tr').tsort({attr:'sort-data'});
  
//   $( ".sortable" ).sortable({
//     revert: true,
//     cursor: "move",
//     items: "> .sort-item",
//     containment: "document",
//      distance: 5 ,
//     opacity: 0.8,
//     stop: function( event, ui ) {
//       $(this).find("tr").each(function(i, el){
//         $(this).find(".sort").val($(el).index())
//         //alert($('[name="'+$(this).find(".sort").attr('name')+'"]').val())
//       });
//     }
//   });
//   var main_width = 100 / 576;
  
//         $( "#slider" ).slider({
//       range: true,    
//             min: 0,
//             max: 576,
//       step: 5.76,
//             values: [ <?php echo $column_1; ?>,  <?php echo ($column_1 + $column_2); ?>],
//       slide: function( event, ui ) {
        
//         $('.column-data-1').val(Math.round(main_width*(ui.values[ 0 ])))
//                 .attr('width-data', ui.values[ 0 ])
//                 .attr('left-data', 0)
//                 .css({'width' : parseInt( ui.values[ 0 ] ) + 'px'})
//         $('.column-data-2').val(Math.round(main_width*(ui.values[ 1 ] - ui.values[ 0 ])))
//                 .attr('width-data',ui.values[ 1 ] - ui.values[ 0 ])
//                 .attr('left-data', parseInt(ui.values[ 0 ]+10))
//                 .css({'width' : parseInt( ui.values[ 1 ] - ui.values[ 0 ]) + 'px'})
//         $('.column-data-3').val(Math.round(main_width*(576 - ui.values[ 1 ])))
//                 .attr('width-data',576 - ui.values[ 1 ])
//                 .attr('left-data', parseInt(ui.values[ 1 ]))
//                 .css({'width' : parseInt( 576 - ui.values[ 1 ]) + 'px'})
//         $('.column-data-4').val(Math.round(main_width*(576 - ui.values[ 0 ])))
//                 .attr('width-data',576 - ui.values[ 0 ])
//         $('.column-1').css({'width' :  parseInt( ui.values[ 0 ]) +'px' })
//         $('.column-2').css({'width' : parseInt( ui.values[ 1 ] - ui.values[ 0 ])+'px'})
//         $('.column-3').css({'width' :  parseInt(576 - ui.values[ 1 ]) +'px'})
//         $('.column-4').css({'width' :  parseInt(576 - ui.values[ 0 ]) +'px'})
//         $('#column_group_2').css({'width' :  parseInt(576 - ui.values[ 0 ]) +'px'})

//         $(".ui-slider-handle").attr('href', '#design')
        
              
//       }
//     });
//   $( ".column" ).sortable({
//             connectWith: ".column",
//       scroll: false,
//       stop: function( event, ui ) {
//       $('.column').find("li").each(function(i, el){
        
//         $(this).find(".row-data").val($(el).index())
//         $(this).find(".col-data").val($(this).parent().attr('col-data'))

//       });
//       }
//         });
 
//   $( ".column" ).disableSelection();
//   $('.column > li').tsort({attr:'row-data'});
//   $('.column > li').each(function(){
//         $(this).appendTo('.column-' + $(this).attr('col-data'));          
//                   })
//   $(".sort-item  td:last-child").append('<i class="icon-drag"></i>')
  
  
//   $('#general_checkout_style').autosize();  
//   $('#checkout_settings').val(decodeURIComponent(($('#form').serialize())))
  
//   $('#checkout_settings_checkbox').live('click', function(){
//     if($(this).attr('checked')) {
//       $('#checkout_settings').fadeIn()
//       $('#checkout_settings').select()
//       }else{
//       $('#checkout_settings').fadeOut()
      
//       }                     
//     })
//    $('#checkout_settings').click(function(){
//      $(this).select()
//      })
// });

/*function image_upload(field, thumb) {
  $('#dialog').remove();
  
  $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=f2b06e3035867ee1624458759982f6ac&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
  
  $('#dialog').dialog({
    title: 'Image Manager',
    close: function (event, ui) {
      if ($('#' + field).attr('value')) {
        $.ajax({
          url: 'index.php?route=common/filemanager/image&token=f2b06e3035867ee1624458759982f6ac&image=' + encodeURIComponent($('#' + field).val()),
          dataType: 'text',
          success: function(data) {
            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
          }
        });
      }
    },  
    bgiframe: false,
    width: 800,
    height: 400,
    resizable: false,
    modal: false
  });
};*/


var module_row = <?php echo $module_row; ?>;

function addModule() {  
  html  = '<tbody id="module-row' + module_row + '">';
  html += '  <tr>';
  html += '    <td class="left"><select name="quickcheckout_module[' + module_row + '][layout_id]">';
  <?php foreach ($layouts as $layout) { ?>
  html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
  <?php } ?>
  html += '    </select></td>';
  html += '    <td class="left"><select name="quickcheckout_module[' + module_row + '][position]">';
  html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
  html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
  html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
  html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
  html += '    </select></td>';
  html += '    <td class="left"><select name="quickcheckout_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
  html += '    <td class="left"><input type="text" name="quickcheckout_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
  html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '  </tr>';
  html += '</tbody>';
  
  $('#module tfoot').before(html);
  
  module_row++;
}

function saveAndStay(){
  //$('#general_checkout_style').val(editor.getValue())
    $.ajax( {
      type: "POST",
      url: $('#form').attr( 'action' ) + '&save',
      data: $('#form').serialize(),
    beforeSend: function() {
    $('#form').fadeTo('slow', 0.5);
    },
    complete: function() {
    $('#form').fadeTo('slow', 1);   
    },
      success: function( response ) {
        console.log( response );
      }
    } );  
}
$('#version_check').click(function(){
  $.ajax( {
      type: "POST",
      url: 'index.php?route=module/quickcheckout/version_check&token=<?php echo $token; ?>',
    dataType: 'json',
    beforeSend: function() {
    $('#form').fadeTo('slow', 0.5);
    },
    complete: function() {
    $('#form').fadeTo('slow', 1);   
    },
      success: function( json ) {
        console.log( json );
    if(json['error']){
      $('#version_result').html('<div class="warning">' + json['error'] + '</div>')
    }
    if(json['attention']){
      $html = '';
      if(json['update']){
         $.each(json['update'] , function(k, v) {
            $html += '<div>Version: ' +k+ '</div><div>'+ v +'</div>';
         });
      }
       $('#version_result').html('<div class="attention">' + json['attention'] + $html + '</div>')
    }
    if(json['success']){
      $('#version_result').html('<div class="success">' + json['success'] + '</div>')
    } 
      }
  })
})

$(document).ajaxStart(function(){
    $(".wait").show();
})
$(document).ajaxStop(function(){
    $(".wait").hide();
});
//--></script> 
<div class="wait"><span></span></div>
<!-- Vtabs-->
<?php echo $footer; ?>