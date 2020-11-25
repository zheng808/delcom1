<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <div id="header">
      <h1>Delta Marine Services</h1>
      <h2>Touch Screen System</h2>
      <div id="statusarea">
        <h3>
          <?php if ($sf_user->isAuthenticated()): ?>
            <?php echo $sf_user->getEmployee()->generateName(); ?>
          <?php endif; ?>
        </h3>
          <?php if ($sf_user->isAuthenticated()): ?>
            <div id="logout-button"><?php echo link_to('Logout', '@sf_guard_signout', array('class' => 'button')); ?></div>
            <div id="startover-button"><?php echo link_to('Start Over', 'general/start', array('class' => 'button')); ?></div>
          <?php endif; ?>
      </div>
    </div>

    <div id="content">
      <?php echo $sf_content ?>
    </div>

  </body>
</html>
