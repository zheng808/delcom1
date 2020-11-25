<div id="results">
  <?php $base_url = 'parts/workorderitemselect?id='.$workorder->getId().'&parent_id='; ?>
  <div class="filter-area">

    <div class="woiselect-label">Parent Tasks:</div>
    <div class="woiselect-items">
      <?php echo link_to("Workorder #".$workorder->getId(), $base_url.'0', array('class' => 'button woiselect-item')); ?>
      <div class="woiselect-separator"> &gt; </div>

      <?php if (isset($path)): ?>
        <?php foreach ($path AS $path_item): ?>
          <?php if (!$path_item->isRoot()): ?>
            <?php echo link_to($path_item->getLabel(), $base_url.$path_item->getId(), array('class' => 'button woiselect-item')); ?>
            <div class="woiselect-separator"> &gt; </div>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="clear"></div>
  </div>


  <div id="filter">
    <h2>Select a Workorder Task</h2>
  </div>

  <?php if(isset($path)): ?>
    <h2 style="padding-left: 100px; padding-top: 10px;">Use The Selected Task:</h2>
    <div class="woiselect-thistask">
      <?php echo link_to('Select "'.$parent->getLabel().'" As the Task', 'parts/search?id='.$workorder->getId().'&item='.$parent->getId(),
                         array('class' => 'button')); ?>                
      <div class="clear"></div>
    </div>
    <h2 style="padding-left: 100px; padding-top: 2px;">Or, Select a More Specific Sub-Task:</h2>
  <?php else: ?>
    <h2 style="padding-left: 100px; padding-top: 200px;">Please select a Task from this Workorder:</h2>
  <?php endif; ?>

  <?php
    $max_rows = 6;
    $max_cols = 2;
    $col = 1;
    $row = 1;

    $offset = 1;
    if ($page > 1 && count($children) > 0)
    {
      $offset += ($page - 1) * ($max_rows * $max_cols);
      $children = array_slice($children, $offset - 1);
    }
  ?>
  <?php if (count($children) > 0): ?>
    <div class="woiselect-children">
    <?php foreach ($children AS $idx => $child): ?>
      <?php if ($row == 1): ?>
        <div class="woiselect-col">
      <?php endif; ?>
        <?php if ($child->hasChildren()): ?>
          <?php echo link_to(($offset + $idx).': '.$child->getLabel(), $base_url.$child->getId(), array('class' => 'button woiselect')); ?>
        <?php else: ?>
          <?php echo link_to(($offset + $idx).': '.$child->getLabel(), 'parts/search?id='.$workorder->getId().'&item='.$child->getId(), 
                             array('class' => 'button woiselect')); ?>
        <?php endif; ?>
      <?php if ($row == $max_rows): ?>
        </div>
        <?php if ($col == $max_cols) break; ?>
        <?php $row = 1; $col++; ?>
      <?php else: ?>
        <?php $row ++; ?>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if (($row != $max_rows) || (($row == $max_rows) && ($col != $max_cols))): ?>
      </div>
    <?php endif; ?>
    <div class="clear"></div>
    </div>

    <div class="pager">
      <?php if ((count($children) > ($max_rows * $max_cols)) || ($page > 1)): ?>
          <?php $base_url .= (isset($path) ? $parent->getId() : 0).'&page='; ?>
          <?php echo link_to('&lt; Previous Tasks', $base_url.($page - 1), array('class' => 'button', 'style' => 'width: 150px;'.($page == 1 ? 'visibility: hidden;' : ''))); ?>
          <?php echo link_to('More Tasks &gt;', $base_url.($page + 1), array('class' => 'button', 'style' => 'width: 150px;'.((count($children) <= ($max_rows * $max_cols)) ? 'visibility: hidden;' : ''))); ?>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <div class="bigwarning error" style="margin-top: 50px;">
      There are no tasks to display. Ask an administrator to create tasks first.
    </div>
  <?php endif; ?>

</div>
