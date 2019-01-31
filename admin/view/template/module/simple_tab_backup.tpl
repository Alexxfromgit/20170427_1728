<div>
  <vtabs>
    <vtab title="<?php echo $l->get('tab_save', true) ?>" title-lang-id="tab_save">
      <h3><?php echo $l->get('text_backup') ?></h3>
      <table class="form">
        <tr>
          <td>
            <a class="button" target="_blank" ng-href="<?php echo $action_backup ?>&{{$root.settings.additionalParams}}"><span><?php echo $l->get('button_download') ?></span></a>
          </td>
        </tr>
      </table>
    </vtab>
    <vtab title="<?php echo $l->get('tab_restore', true) ?>" title-lang-id="tab_restore">
      <h3><?php echo $l->get('text_restore') ?></h3>
      <table class="form">
        <tr>
          <td><input type="file" name="import" />&nbsp;<a onclick="jQuery('#form').submit();" class="button"><span><?php echo $l->get('button_restore') ?></span></a></td>
        </tr>
      </table>
    </vtab>
  </vtabs>
</div>