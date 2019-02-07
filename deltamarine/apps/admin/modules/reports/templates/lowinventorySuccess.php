<?php use_helper('Form') ?>
<div class="leftside" style="padding-top: 34px;">
  <?php /*echo link_to('Add a New Sale', 'sale/add', array('class' => 'button tabbutton'));*/ ?>
</div>
<script>
a = false;
function removeErrors()
{
        if(document.getElementById('loader-notification_datagrid'))
        {
                document.getElementById('loader-notification_datagrid').parentNode.removeChild(document.getElementById('loader-notification_datagrid'));
        }
}
if(!a)
{
        a = setInterval('removeErrors()',100);
}
</script>
<div>
  <h1 class="headicon headicon-person">View Parts that are low on inventory</h1>
  <div class="pagebox">
        <?php
        ?>
    <div id="reports_list">
      <?php
        if ( count($lowinventory) < 1 )
          echo "<p>There is no low inventory.</p>";
        else {
          echo "<ul class='notifications'>";
          foreach ($lowinventory as $linv ):
            echo "<li>".link_to_remote($linv['text'], array(
                'update' => 'notification_datagrid',
                'url' => $linv['link']
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

