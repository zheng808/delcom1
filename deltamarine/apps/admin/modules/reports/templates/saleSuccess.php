<?php
use_helper('Form');
?>
<div class="leftside" style="padding-top: 34px;">
  <?php /*echo link_to('Add a New Sale', 'sale/add', array('class' => 'button tabbutton'));*/ ?>
</div>

<div>
  <h1 class="headicon headicon-person">View Sale Reports</h1>
  <div class="pagebox">
        <?php
        echo $stat;
        ?>
    <div id="reports_list">
      <?php
        if ( count($list) < 1 )
          echo "<p>There are no sales.</p>";
        else {
          echo "<ul class='notifications'>";
          foreach ($list as $sale ):
            echo "<li>".link_to_remote($sale['text'], array(
                'update' => 'notification_datagrid',
                'url' => $sale['link']
              ))."</li>";
          endforeach;
          echo "</ul>";
        }
        ?>
    </div>
    <div id="notification_datagrid">
      </div>
  </div>
</div>
