<script type="text/Javascript">
var Calendar;
</script>
<?php
use_helper('Form');
$sf_context->getResponse()->addJavascript(sfConfig::get('sf_calendar_web_dir').'/calendar');
$sf_context->getResponse()->addJavascript(sfConfig::get('sf_calendar_web_dir').'/lang/calendar-en');
$sf_context->getResponse()->addJavascript(sfConfig::get('sf_calendar_web_dir').'/calendar-setup');
$sf_context->getResponse()->addStylesheet(sfConfig::get('sf_calendar_web_dir').'/skins/aqua/theme');
?>
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
<div id="glob">
  <h1 class="headicon headicon-person">Employee Performance</h1>
  <div class="pagebox">
        <?php
        ?>
    <div id="reports_list">
        <h2><u>Billable hours / non-billable hours per week:</u></h2><br/>
        <input type="button" value="Select a start of the week date" id="trigger_date_filter"/><br>
        <div id="date_selected">
        <?php
        echo date("d-m-Y",$week_start)." - ".date("d-m-Y",$week_finish);
        ?>
        </div><br/>
        <b>Result: </b>
                <i><?php
                        echo $general_billable_vc_non_billable_hours['billable']." / ".$general_billable_vc_non_billable_hours['nonbillable'];
                ?> (hours) = <?php
                        $perc_bill = (($general_billable_vc_non_billable_hours['nonbillable'] + $general_billable_vc_non_billable_hours['billable'])>0) ? 100*$general_billable_vc_non_billable_hours['billable']/($general_billable_vc_non_billable_hours['nonbillable'] + $general_billable_vc_non_billable_hours['billable']) : (0);
                        $perc_unbill = (($general_billable_vc_non_billable_hours['nonbillable'] + $general_billable_vc_non_billable_hours['billable'])>0) ? (100 - $perc_bill) : (0);
                        echo (($general_billable_vc_non_billable_hours['nonbillable'] + $general_billable_vc_non_billable_hours['billable'])>0) ? (round($perc_bill,2)."% vs ".round($perc_unbill,2)."%") : ("There aren't working hours recorded this week ");
                ?></i>
        <?php
        echo "<p>".link_to_remote("Show detailed report", array(
                'update' => 'notification_datagrid',
                'url' => '/reports/employeeVS'.( isset($week_start) ? ('?week_start='.$week_start) : ('') )
              ))."</p>";
        ?>
        <br/>
        <hr/>
        <h2><u>Time to do a task in a work order / Time the estimate was</u></h2>
                <div><?php
                        echo "Estimate Time vs Actual Time (general): <i>".( ($general_actual!=0) ? round(100*$general_estimate/$general_actual,2): (0))."%</i><br/><br/>";
                        echo "<p>".link_to_remote("Show detailed report", array(
                                'update' => 'notification_datagrid',
                                'url' => '/reports/timeVS'.( isset($week_start) ? ('?week_start='.$week_start) : ('') )
                        ))."</p>";
                ?></div>
        <br/>
        <hr/>
    </div>
    <div id="notification_datagrid">
      </div>
  </div>
</div>
<div id="ajax_loader" style="display:none;">
<img src="/images/ajax_loader.gif" /> Please wait...
</div>
<script type="text/Javascript">
function clz(cal)
{
        cal.hide();
        document.getElementById('glob').style.display='none';
        document.getElementById('ajax_loader').style.display='block';
        location.href = '/reports/employeeperformance?week_start='+(cal.date.print(cal.params.ifFormat));
}
if(window.Calendar)
Calendar.setup(
{
        displayArea : "date_filter_output",
        ifFormat : "%Y-%m-%d 00:00:00",
        daFormat : "%d-%m-%Y 00:00:00",
        button : "trigger_date_filter",
        showsTime : false,
        onClose : clz
});
</script>
