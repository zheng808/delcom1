<h2 style="text-align: center; margin-top: 40px;">Timelog Added</h2>

<p style="text-align: center; font-size: 14px; font-weight: bold;">What do you want to do now?</p>

<div class="doneitems">
  <?php echo link_to('Add Another Timelog to Same Task', 'timelogs/details?id='.$workorder->getId().'&item='.$item->getId(), array('class' => 'button')); ?>
  <?php echo link_to('Add Another Timelog to Same Workorder', 'timelogs/workorderitemselect?id='.$workorder->getId(), array('class' => 'button')); ?>
  <?php echo link_to('Add Billable Timelog to another Workorder', 'timelogs/workorderselect', array('class' => 'button')); ?>
  <?php echo link_to('Add a Non-Billable Timelog', 'timelogs/nonbillable', array('class' => 'button')); ?>
</div>
<div class="doneitems">
  <?php echo link_to('Return to Home Menu (Start Over)', 'general/start', array('class' => 'button success')); ?>
  <?php echo link_to('Finished - Log Out', '@sf_guard_signout', array('class' => 'button error')); ?>
</div>

