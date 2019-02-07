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
  <h1 class="headicon headicon-person">View Employee detailed time report</h1>
  <div class="pagebox">
        <?php
        ?>
    <div id="reports_list">
      <?php
        echo "<p>".link_to_remote($linv['text'], array(
                'update' => 'notification_datagrid',
                'url' => '/report/employeeVS'
              ))."</p>";
        }
        ?>
    </div>
    <div id="notification_datagrid">
      </div>
  </div>
</div>

