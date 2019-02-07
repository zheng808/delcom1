<ul>
  <?php foreach ( $contacts as $c ): ?>
    <li id="<?php echo $c->pk ?>"><?php echo $c->name ?></li>
  <?php endforeach; ?>
</ul>
