<h2 style="text-align: center; margin-top: 40px;">Part Added</h2>

<p style="text-align: center; font-size: 14px; font-weight: bold;">What do you want to do now?</p>

<div class="doneitems">
  <?php echo link_to('Add Another Part to Same Task', 'parts/search?id='.$workorder->getId().'&item='.$item->getId(), array('class' => 'button')); ?>
  <?php echo link_to('Add Another Part to Same Workorder', 'parts/workorderitemselect?id='.$workorder->getId(), array('class' => 'button')); ?>
</div>
<div class="doneitems">
  <?php echo link_to('Return to Home Menu (Start Over)', 'general/start', array('class' => 'button success')); ?>
  <?php echo link_to('Finished - Log Out', '@sf_guard_signout', array('class' => 'button error')); ?>
</div>

