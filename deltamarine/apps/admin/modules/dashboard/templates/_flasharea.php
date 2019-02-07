<?php 
  $messages = array();
  if ($success = $sf_user->getFlash('success'))
  {
    if (!is_array($success)) $success = array($success);
    foreach ($success AS $suc)
    {
      $messages[] = array('success', $suc);
    }
  }
  if ($notice = $sf_user->getFlash('notice'))
  {
    if (!is_array($notice)) $notice = array($notice);
    foreach ($notice AS $note)
    {
      $messages[] = array('notice', $note);
    }
  }
  if ($error = $sf_user->getFlash('error'))
  {
    if (!is_array($error)) $error = array($error);
    foreach ($error AS $err)
    {
      $messages[] = array('error', $err);
    }
  }
?>
<?php foreach ($messages AS $message) : ?>
  <div class="flasharea <?php echo $message[0]; ?>">
    <a href="#" onclick="this.parentNode.style.display='none'; return false;" style="float: right; padding: 3px; font-size: 12px;">[Hide]</a>
    <?php echo $message[1]; ?>
  </div>
<?php endforeach; ?>

