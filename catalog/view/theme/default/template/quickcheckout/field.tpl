 <?php foreach($fields as $field){
          if(isset($field['type'])) {
    		    switch ($field['type']) {
     			    
          case "heading": ?>

        <?php if($field['display']){ ?>
                <div class="clear"></div>
              </div>
            </div>
          </div>
          <div id="<?php echo $field['id']; ?>_input" class="box box-border sort-item <?php echo $field['id']; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
            <div class="box-heading"><span class="wrap"><span class="qc-icon-payment-address"></span></span> <span><?php echo $field['title']; ?></span></div>
              <div class="box-content">
                <div>
        <?php } ?>

<?php	break; case "label": ?>

      <div id="<?php echo $field['id']; ?>_input" class="label-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
        <label for="<?php echo $name; ?>_<?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> </label>
        <p name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" id="<?php echo $name; ?>_<?php echo $field['id']; ?>" class="label-text" />
        <?php echo isset($field['value'])? $field['value'] : ''; ?>
        </p>
      </div>
      
<?php	break;	case "radio": ?>

      <?php if(isset($field['options'])){ ?>
      <div id="<?php echo $field['id']; ?>_input" class="radio-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
        <label class="title"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" rel="tooltip" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>
        <ul>
          <?php foreach ($field['options'] as $option) { ?>
          <?php if ($option['value'] == $field['value']) { ?>
          <li>
            <input type="radio" name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" value="<?php echo $option['value']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" id="<?php echo $name; ?>_<?php echo $field['id'].$option['value']; ?>" checked="checked" class="styled"  autocomplete='off'/>
            <label for="<?php echo $name; ?>_<?php echo $field['id'].$option['value']; ?>"><?php echo $option['title']; ?></label>
          </li>
          <?php } else { ?>
          <li>
            <input type="radio" name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" value="<?php echo $option['value']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" id="<?php echo $name; ?>_<?php echo $field['id'].$option['value']; ?>"  class="styled"  autocomplete='off'/>
            <label for="<?php echo $name; ?>_<?php echo $field['id'].$option['value']; ?>"><?php echo $option['title']; ?> </label>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>

<?php break; case "checkbox": ?>

  <div id="<?php echo $field['id']; ?>_input" class="checkbox-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">

      <input type="checkbox" name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" id="<?php echo $name; ?>_<?php echo $field['id']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>"  <?php if (isset($field['value'])) { ?> value="1" <?php }else{ ?> value="0" <?php } ?> <?php if(isset($field['value']) && $field['value'] == 1){?> checked="checked" <?php } ?> class="styled"  autocomplete='off' />

      <label for="<?php echo $name; ?>_<?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" rel="tooltip" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>

      </div>

<?php break; case "select": ?>

      <div id="<?php echo $field['id']; ?>_input" class="select-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
        <label for="<?php echo $name; ?>_<?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" rel="tooltip" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>
        <select name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" id="<?php echo $name; ?>_<?php echo $field['id']; ?>">
          <option value=""><?php echo $text_select; ?></option>
          <?php if(!empty($field['options'])) { ?>
              <?php foreach ($field['options'] as $option) { ?>
                  <?php if ($option['value'] == $field['value']) { ?>
                  <option value="<?php echo $option['value']; ?>" selected="selected"><?php echo $option['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $option['value']; ?>"><?php echo $option['name']; ?></option>
                  <?php } ?>
              <?php } ?>
          <?php } ?>
        </select>
      </div>

<?php	break;	case "password": ?>

      <div id="<?php echo $field['id']; ?>_input" class="password-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
        <label for="<?php echo $name; ?>_<?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" rel="tooltip" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>
        <input type="password" name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" id="<?php echo $name; ?>_<?php echo $field['id']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" value="<?php echo isset($field['value'])? $field['value'] : ''; ?>" placeholder="<?php echo (isset($field['require']) &&  $field['require']) ? '*' : ''; ?> <?php echo str_replace(':', '', $field['title']); ?>"/>
      </div>

<?php	break; case "textarea": ?>

      <div id="<?php echo $field['id']; ?>_input" class="textarea-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">
        <label for="<?php echo $name; ?><?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>
        <textarea name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" id="<?php echo $name; ?>_<?php echo $field['id']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" placeholder="<?php echo (isset($field['require']) &&  $field['require']) ? '*' : ''; ?> <?php echo str_replace(':', '', $field['title']); ?>"><?php echo isset($field['value'])? $field['value'] : ''; ?></textarea>
      </div>
      
<?php	break;  default: ?>

      <div id="<?php echo $field['id']; ?>_input" class="text-input sort-item <?php echo (!$field['display'])? 'qc-hide' : ''; ?> <?php echo ($field['class'])? $field['class'] : ''; ?>" data-sort="<?php echo $field['sort_order']; ?>">

        <label for="<?php echo $name; ?>_<?php echo $field['id']; ?>"> <span class="required <?php echo (!isset($field['require']) ||  !$field['require']) ? 'qc-hide' : ''; ?>">*</span> <span class="text"><?php echo $field['title']; ?></span> <?php echo (!empty($field['tooltip']))? '<i class="icon-help" rel="tooltip" data-help="'.$field['tooltip'] .'"></i>' : '' ; ?></label>

        <input type="text" name="<?php echo $name; ?>[<?php echo $field['id']; ?>]" id="<?php echo $name; ?>_<?php echo $field['id']; ?>" data-require="<?php echo (isset($field['require']) && $field['require']) ? 'require' : ''; ?>" data-refresh="<?php echo ($field['refresh']) ? $field['refresh'] : 0; ?>" value="<?php echo isset($field['value'])? $field['value'] : ''; ?>" placeholder="<?php echo (isset($field['require']) && $field['require']) ? '*' : ''; ?> <?php echo str_replace(':', '', $field['title']); ?>"/>

      </div>
    <?php } //switch ?>
  <?php } //if ?>
<?php } //foreach ?>

<div class="clear"></div>