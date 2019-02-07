<div id="filter">

  <div class="filter-area">
    <div class="filter-label">
      Customer:
    </div>
    <div class="filter-options">
      <?php $base_url = 'timelogs/workorderselect?'. ($filter_boat ? 'filter_boat='.$filter_boat.'&' : '').'filter_name='; ?>
      <?php echo link_to('All', $base_url, array('class' => 'button'.($filter_name == '' ? ' active' : ''))); ?>
      <?php for ($i = 1; $i <= 26; $i ++): ?>
        <?php $letter = chr(64 + $i); ?>
        <?php if (isset($names[$letter])): ?>
          <?php echo link_to($letter, $base_url.$letter, array('class' => 'button'.($filter_name == $letter ? ' active' :''))); ?>
        <?php else: ?>
          <a class="button disabled" href="#" onclick="return false;"><?php echo $letter; ?></a>
        <?php endif; ?>
      <?php endfor; ?>
    </div>
    <div class="clear"></div>
    <div class="filter-label">
      Boat Name:
    </div>
    <div class="filter-options">
      <?php $base_url = 'timelogs/workorderselect?'. ($filter_name ? 'filter_name='.$filter_name.'&' : '').'filter_boat='; ?>
      <?php echo link_to('All', $base_url, array('class' => 'button'.($filter_boat == '' ? ' active' : ''))); ?>
      <?php for ($i = 1; $i <= 26; $i ++): ?>
        <?php $letter = chr(64 + $i); ?>
        <?php if (isset($boats[$letter])): ?>
          <?php echo link_to($letter, $base_url.$letter, array('class' => 'button'.($filter_boat == $letter ? ' active' : ''))); ?>
        <?php else: ?>
          <a class="button disabled" href="#" onclick="return false;"><?php echo $letter; ?></a>
        <?php endif; ?>
      <?php endfor; ?>

    </div>
    <div class="clear"></div>
  </div>

  <h2>Select a Workorder:</h2>
</div>

<div id="results">

  <div class="padding20">
  <?php
    $max_rows = 5;
    $max_cols = 4;
    $col = 1;
    $row = 1;
  ?>
  <?php $workorders = $pager->getResults(); ?>
  <?php if (count($workorders) > 0): ?>
    <?php foreach ($workorders AS $workorder): ?>
      <?php if ($row == 1): ?>
        <div class="woselect-col">
      <?php endif; ?>
      <div class="woselect">
        <a class="button" href="<?php echo url_for('timelogs/workorderitemselect?id='.$workorder->getId()); ?>" class="buttonlink woselect-link">
        <span class="woselect-number">#<?php echo $workorder->getId(); ?>: <?php echo $workorder->getCustomer(); ?></span><br />
        <span class="woselect-boatname"><?php echo $workorder->getCustomerBoat()->getName(); ?></span><br />
        <span class="woselect-boatinfo"><?php echo $workorder->getCustomerBoat()->getMakeModel(); ?></span><br />
        <span class="woselect-date"><?php echo $workorder->getStartedOn('M j, Y'); ?></span>
        </a>
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
      There are no matching workorders to display.
    </div>
  <?php endif; ?>
  </div>

  <div class="clear"></div>
  <div class="pager">
    <?php if ($pager->haveToPaginate()): ?>
        <?php $base_url = 'timelogs/workorderselect?'.($filter_name ? 'filter_name='.$filter_name.'&' : '').($filter_boat ? 'filter_boat='.$filter_boat.'&' : '').'page='; ?>
        <?php echo link_to('&lt; Previous Page', $base_url.$pager->getPreviousPage(), array('class' => 'button', 'style' => 'width: 150px;'.($pager->getPage() == $pager->getPreviousPage() ? 'visibility: hidden;' : ''))); ?>
        <?php echo link_to('Next Page &gt;', $base_url.$pager->getNextPage(), array('class' => 'button', 'style' => 'width: 150px;'.($pager->getPage() == $pager->getLastPage() ? 'visibility: hidden;' : ''))); ?>
    <?php endif; ?>
  </div>

</div>
