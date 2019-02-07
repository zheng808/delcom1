<?php
  use_javascript('ext-3.3.1/adapter/ext/ext-base.js');
  use_javascript('ext-3.3.1/ext-all-debug.js');
  use_javascript('password.js');
  use_stylesheet('/js/ext-3.3.1/resources/css/ext-all.css');
?>
<script type="text/javascript">

function tryLogin(tryid){
  Ext.Msg.passwordPrompt('Password Required', 'Please enter your password:',
    function(btn,text){
      if (btn == 'ok')
      {
        Ext.Ajax.request({
          url: '<?php echo url_for('general/login'); ?>',
          params: { id: tryid, pass: text },
          callback: function (opt,success,response){
            Ext.Msg.hide();
            data = Ext.decode(response.responseText);
            if (data) {
              if (data.success && data.empid){
                location.href='<?php echo url_for('general/start'); ?>';
              } else {
                Ext.Msg.show({
                  title: 'Error',
                  closable: false,
                  width: 300,
                  height: 300,
                  icon: Ext.MessageBox.ERROR,
                  msg: data.error,
                  buttons: Ext.MessageBox.OK
                });
              }
            } else {
              Ext.Msg.show({
                title: 'Error',
                closable: false,
                width: 300,
                height: 300,
                icon: Ext.MessageBox.ERROR,
                buttons: [{
                  text: 'Ok',
                  scale: 'large',
                  handler: function(){
                    Ext.Msg.hide();
                  }
                }]
              });
            }
          }
        });
      }
    }
  );
}
</script>
<div id="filter"><h2>Select your name to begin:</h2></div>

<div class="padding20">
<?php
  $max_rows = 8;
  $max_cols = 4;
  $col = 1;
  $row = 1;
?>
<?php if (count($employees) > 0): ?>
  <?php foreach ($employees AS $employee): ?>
    <?php if ($row == 1): ?>
      <div class="empselect-col">
    <?php endif; ?>
    <div class="empselect">
      <a class="button" href="#" onclick="tryLogin(<?php echo $employee->getId(); ?>); return false;" class="buttonlink empselect-link"><?php echo $employee->getName(); ?></a>
      <div class="clear"></div>
    </div>
    <?php if ($row == $max_rows): ?>
      </div>
      <?php if ($col == $max_cols) break; ?>
      <?php $row = 1; $col++; ?>
    <?php else: ?>
      <?php $row ++; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php else: ?>
  <div class="bigwarning notice" style="margin-top: 250px;">
    There are no employees to display.
  </div>
<?php endif; ?>
</div>
