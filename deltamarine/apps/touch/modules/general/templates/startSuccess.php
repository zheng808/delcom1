<h2 style="text-align: center; margin-top: 40px;">Welcome Back</h2>

<p style="text-align: center; font-size: 14px; font-weight: bold;">Please select an action:</p>

<div class="startcol rounded10-top">
  <h2 class="rounded10-top">Timelogs</h2>
  <?php echo link_to('Add Timelog to Workorder', 'timelogs/workorderselect', array('class' => 'button')); ?>
  <?php echo link_to('Add Non-Billable Timelog', 'timelogs/nonbillable', array('class' => 'button')); ?>
  <?php echo link_to('View/Edit Previous Timelogs', 'timelogs/view', array('class' => 'button', 'onclick' => "alert('temporarily disabled, sorry.');return false;")); ?>
</div>

<div class="startcol rounded10-top">
<h2 class="rounded10-top">Parts</h2>
  <?php echo link_to('Add Part to Workorder', 'parts/workorderselect', array('class' => 'button')); ?>
  <?php echo link_to('Part Details Lookup', 'parts/lookup', array('class' => 'button', 'onclick' => "alert('temporarily disabled, sorry.'); return false;")); ?>
</div>

