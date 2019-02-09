<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
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
        <!--Development by <php echo link_to('Wildfire Interactive', 'http://wildfireinteractive.ca'); ?>. -->
        <?php if (function_exists('xdebug_time_index')): ?>
          <?php if (($time = xdebug_time_index()) > 0): ?>
            Page took <?php echo round($time, 3);?>s to generate
          <?php endif; ?>
        <?php endif; ?>
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