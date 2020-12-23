<?php include_once 'util/dbconfig.php'; 
$version = 'v';

$query = 'select value from system_settings where code = :code';
$code = 'APP_VERSION';
$values = array(':code' => $code);
$res = $pdo->prepare($query);
$res->execute($values);

while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
  $version = $version.'.'.$row['value'];
}

$code = 'DB_VERSION';
$values = array(':code' => $code);
$res = $pdo->prepare($query);
$res->execute($values);

while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
  $version = $version.'.'.$row['value'];
}
$code = 'EXT_VERSION';
$values = array(':code' => $code);
$res = $pdo->prepare($query);
$res->execute($values);

while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
  $version = $version.'.'.$row['value'];
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <!-- script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script -->
    <!-- script>window.LogRocket && window.LogRocket.init('dknck2/dev-demo');</script -->
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div id="statusarea">
          <?php include_partial('dashboard/userdetails'); ?>
          <div id="statustime"></div>
        </div>
        <h1>Delta Marine Service - Management &amp; Administration</h1>
      </div>

      <?php include_partial('dashboard/menu'); ?>
      <?php include_partial('dashboard/flasharea'); ?>

      <div id="mainarea">
        <?php echo $sf_content ?>
      </div>

      <div id="prefooter">
      </div>
    </div>

    <div id="footer">
      <div>
        Development by <a href='https://zheng808.github.io/' target='_blank'> Alex Li <img src='/images/Bridge.png' height='20' width='47'> </a> 
        <?php if (function_exists('xdebug_time_index')): ?>
          <?php if (($time = xdebug_time_index()) > 0): ?>
            Page took <?php echo round($time, 3);?>s to generate
          <?php endif; ?>
        <?php endif; ?>
        <div class="alignright"><?php   echo $version; ?></div>
      </div>
    </div>
    
  <script type="text/javascript">
    var clockdiv = Ext.get('statustime');
    var updateClock = function(){
      clockdiv.update(new Date().format('l, F j Y g:i:s A'));
    };
    Ext.onReady(function(){
      var clocktask = {
        run: function() { clockdiv.update(Ext.Date.format(Ext.Date.now(), 'l, F j Y g:i:s A')); },
        interval: 1000
      };
      Ext.TaskManager.start(clocktask);
      Ext.tip.QuickTipManager.init();
    });
  </script>

  </body>
</html>
