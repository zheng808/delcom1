<?php if ($sf_user->isAuthenticated()): ?>
  You are logged in as: <strong><?php echo $sf_user->getEmployee(); ?> </strong> 
  (<?php echo link_to('Logout', '@sf_guard_signout'); ?>)
<?php else: ?>
  You are not currently logged in!
<?php endif; ?>
