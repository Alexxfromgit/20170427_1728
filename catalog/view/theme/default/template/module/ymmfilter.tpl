<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-ymm">
      <?php if(isset($this->session->data['ymm'])){ ?>
      <p><?php echo $text_make . ' ' . $make_name; ?></p>
      <?php if(!empty($this->session->data['ymm']['model'])){ ?>
      <p><?php echo $text_model . ' ' . $model_name; ?></p>
      <?php } ?>
      <?php if(!empty($this->session->data['ymm']['engine'])){ ?>
      <p><?php echo $text_engine . ' ' . $engine_name; ?></p>
      <?php } ?>
      <?php if(!empty($this->session->data['ymm']['year'])){ ?>
      <p><?php echo $text_year . ' ' . $this->session->data['ymm']['year']; ?></p>
      <?php } ?>
      <p><a id="change-vehicle"><?php echo $text_change_vehicle; ?></a>
      <script type="text/javascript">
      $('#change-vehicle').click(function(e){
        e.preventDefault();
        $.get('index.php?route=module/ymmfilter/changeymm', function(json){
            if(json['message'] == 'success'){
                location.reload(true);
            }
        },
        'json');
      });
      </script>
      </p>
      <?php } else { ?>
      <div class="ymm-make">
        <label for="make"><?php echo $text_make; ?></label>
        <select name="make" id="ymmmake">
            <option value="<?php echo $text_please_select; ?>"><?php echo $text_please_select; ?></option>
            <?php foreach ($makes as $make) { ?>
            <option value="<?php echo $make['id']; ?>"><?php echo $make['make']; ?></option>
            <?php } ?>
        </select>
      </div>
      <?php if($this->config->get('ymmfilter_model')) { ?>
      <div class="ymm-model">
        <label for="model"><?php echo $text_model; ?></label>
        <select id="ymmmodel" name="model" disabled="disabled">
            <option value=""><?php echo $text_please_select; ?></option>
        </select>
      </div>
      <?php }
      if($this->config->get('ymmfilter_engine')) { ?>
      <div class="ymm-engine">
        <label for="engine"><?php echo $text_engine; ?></label>
        <select id="ymmengine" name="engine" disabled="disabled">
            <option value=""><?php echo $text_please_select; ?></option>
        </select>
      </div>
      <?php }
      if($this->config->get('ymmfilter_year')) { ?>
      <div class="ymm-year">
        <label for="year"><?php echo $text_year; ?></label>
        <select id="ymmyear" name="year" disabled="disabled">
            <option value=""><?php echo $text_please_select; ?></option>
        </select>
      </div>
      <?php } ?>
      <div class="ymm-submit">
        <?php if($this->config->get('ymmfilter_remember') == '1'){ ?>
        <input id="ymm-remember" name="ymm-remember" type="checkbox" value="1"/>
        <label for="ymm-remember">
            <?php echo $text_ymm_remember; ?>
        </label>
        <?php } ?>
        <br />
        <?php 
        if (strcmp(VERSION,'1.5.2') >= 0) { //1.5.2 or later
            $class = 'button-disabled-53';
        } else {
            $class = 'button-disabled';
        }
        ?>
        <a class="<?php echo $class; ?>"><?php if (strcmp(VERSION,'1.5.2') < 0) { ?><span><?php } ?><?php echo $text_submit; ?><?php if (strcmp(VERSION,'1.5.2') < 0) { ?></span><?php } ?></a>
    </div>
    <script type="text/javascript">
    var loading = '<option>Loading...</option>';
    $('#ymmmake').change(function(){
        $.ajax({
            url: 'index.php?route=module/ymmfilter/getmodel',
            type: 'post',
            data: { make_id : $('#ymmmake').val() },
            beforeSend: function() {
                $('#ymmmodel').html(loading);
            },
            success: function(data) {
                $('#ymmmodel').html(data);
        $('#ymmmodel').prop('disabled', false);
                $('#ymmengine').prop('disabled', true);
        $('#ymmyear').prop('disabled', true);
        <?php if (strcmp(VERSION,'1.5.2') < 0) { ?>
        $('.ymm-submit a').removeClass("button-disabled").addClass("button");
        <?php } else { ?>
        $('.ymm-submit a').removeClass("button-disabled-53").addClass("button");
        <?php } ?>
            }
        });
        
    });
    $('#ymmmodel').change(function(){
        <?php if($this->config->get('ymmfilter_year')) { ?>
        $.ajax({
            url: 'index.php?route=module/ymmfilter/getyear',
            type: 'post',
            data: { make_id : $('#ymmmake').val(), model_id : $('#ymmmodel').val() },
            beforeSend: function() {
                $('#ymmyear').html(loading);
            },
            success: function(data) {
                $('#ymmyear').html(data);
            }
        });
        <?php }
        if($this->config->get('ymmfilter_engine')) { ?>
        $.ajax({
            url: 'index.php?route=module/ymmfilter/getengine',
            type: 'post',
            data: { make_id : $('#ymmmake').val(), model_id : $('#ymmmodel').val() },
            beforeSend: function() {
                $('#ymmengine').html(loading);
            },
            success: function(data) {
                $('#ymmengine').html(data);
            }
        });
        <?php }
        if($this->config->get('ymmfilter_year')) { ?>
        $('#ymmyear').prop('disabled', false);
        <?php }
        if($this->config->get('ymmfilter_engine')) { ?>
        $('#ymmengine').prop('disabled', false);
        <?php } ?>
    });
    $('#ymmengine').change(function(){
        <?php
        if($this->config->get('ymmfilter_year')) { ?>
        $.ajax({
            url: 'index.php?route=module/ymmfilter/getyear',
            type: 'post',
            data: { make_id : $('#ymmmake').val(), model_id : $('#ymmmodel').val(), engine_id : $('#ymmengine').val() },
            beforeSend: function() {
                $('#ymmyear').html(loading);
            },
            success: function(data) {
                $('#ymmyear').html(data);
            }
        });
        <?php } ?>
    });
    $('.ymm-submit a').click(function(e){
        e.preventDefault();
        if($('#ymmmake').val() != '<?php echo $text_please_select; ?>'){
            $.post('index.php?route=module/ymmfilter/setymm',
                { make_id : $('#ymmmake').val(), model_id : $('#ymmmodel').val(), engine_id : $('#ymmengine').val(), year : $('#ymmyear').val(), ymm_remember : $('#ymm-remember').val() },
                function(json) {
                    if(json['message'] == 'success'){
                        <?php if($setting['destination'] == 'home'){ ?>
                        window.location ="<?php echo $this->config->get('config_url'); ?>" ;
                        <?php } elseif($setting['destination'] == 'search' && (strcmp(VERSION, '1.5.5.1') >= 0)) { // v1.5.5.1 or greater ?>
                        window.location = "<?php echo $this->config->get('config_url') . 'index.php?route=product/search&search='; ?>";
                        <?php } elseif($setting['destination'] == 'search') { // less than v1.5.5.0 ?>
                        window.location = "<?php echo $this->config->get('config_url') . 'index.php?route=product/search&filter_name='; ?>";
                        <?php } else { ?>
                        location.reload(true);
                        <?php } ?>
                    }
                },
                'json'
            );
        }
    });
    </script>
    <?php } ?>
    </div>
  </div>
</div>