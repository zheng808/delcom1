<div class="leftside" style="padding-top: 36px;">
  <div id="employee-index-filter"></div>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-person">Employee Timesheets</h1>
  <div style="margin-top: 25px;" id="report-tabs"></div>
</div>

<script type="text/javascript">
Ext.apply(Ext.form.VTypes, {
  daterange : function(val, field) {
    var date = field.parseDate(val);

    if(!date){
      return;
    }
    if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
      var start = Ext.getCmp(field.startDateField);
      start.setMaxValue(date);
      start.validate();
      this.dateRangeMax = date;
    } 
    else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
      var end = Ext.getCmp(field.endDateField);
      end.setMinValue(date);
      end.validate();
      this.dateRangeMin = date;
    }
    /*
     * Always return true since we're only using this vtype to set the
     * min/max allowed values (these are tested for after the vtype test)
     */
    return true;
  }
});

var employeeStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  remoteSort: true,
  autoLoad: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('employee/datagrid'); ?>',
    extraParams: {firstlast: '0', status: 'active', sort: 'firstname'},
    reader: {
      root: 'employees'
    }
  }
});

var workorderStore = new Ext.data.JsonStore({
  fields: ['id','summary', 'customer','boat','date','status'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid'); ?>',
    reader: {
      root: 'workorders',
      totalProperty: 'totalCount'
    }
  }
});

// Custom rendering Template
var workorderSearchTpl = new Ext.XTemplate(
    '<tpl for="."><li role="option" class="x-boundlist-item" style="border-top: 1px dotted #ccc;">',
        '<span style="font-weight: bold; font-size: 13px;">#{id}: {boat}</span>',
        '<span style="font-weight: bold; padding-left: 5px;">({customer})</span><br />',
        '<span style="padding-left: 20px; color: green">{date} - {status}<span>',
    '</li></tpl>'
);

var download_settings = new Ext.FormPanel({
  url: '<?php echo url_for('reports/timelogsPdf'); ?>',
  target: 'iframe',
  standardSubmit: true,
  border: false,
  items: [{
    layout: 'anchor',
    xtype: 'panel',
    border: false,
    bodyStyle: 'padding: 20px',
    fieldDefaults: { labelWidth: 125 },
    items: [{
      xtype: 'hidden',
      name: 'summary',
      value: 'full'
    },{
      xtype: 'combo',
      fieldLabel: 'Specific Employee',
      anchor: '-300',
      forceSelection: true,
      queryMode: 'local',
      valueField: 'id',
      displayField: 'name',
      triggerAction: 'all',
      minChars: 2,
      store: employeeStore,
      name: 'employee_id'
    },{
      xtype: 'combo',
      fieldLabel: 'Specific Workorder',
      anchor: '-300',
      forceSelection: true,
      queryMode: 'remote',
      valueField: 'id',
      displayField: 'summary',
      hideTrigger: true,
      tpl: workorderSearchTpl,
      minChars: 2,
      pageSize: 15,
      listConfig: { minWidth: 500 },
      store: workorderStore,
      name: 'workorder_id'
    },{
      xtype: 'combo',
      fieldLabel: 'Status',
      anchor: '-400',
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      store: ['All', 'Approved', 'Flagged', 'Unapproved'],
      value: 'All',
      triggerAction: 'all',
      name: 'status'
    },{
      id: 'filter_startdate',
      xtype: 'datefield',
      fieldLabel: 'Start Date',
      anchor: '-400',
      endDateField: 'filter_enddate',
      vtype: 'daterange',
      allowBlank: false,
      format: 'M j, Y',
      name: 'start_date'
    },{
      id: 'filter_enddate',
      xtype: 'datefield',
      fieldLabel: 'End Date',
      anchor: '-400',
      startDateField: 'filter_startdate',
      vtype: 'daterange',
      allowBlank: false,  
      format: 'M j, Y',
      name: 'end_date'
    },{
      border: false,
      bodyStyle: 'font-size: 12px; padding: 20px;',
      html: 'Click the Download button below to download a PDF of the report using the options you specify above. Please be patient as this may take a few moments to generate, especially for larger date ragnes. You\'ll be presented with a download window when the file is ready.'
    }]
  }],
  buttons:[{
      text: 'Generate Report as PDF',
      formBind: true,
      handler:function(){
        this.findParentByType('form').getForm().submit();
      }
    }]

});


var tabs = new Ext.TabPanel({
  activeTab: 0,
  width: 660,
  height:400,
  plain:true,
  items:[{
    title: 'Download Report',
    layout: 'fit',
    items: [download_settings]
  },{
    title: 'View',
    disabled: true
  }]
});


Ext.onReady(function(){

      //create iframe for printing pdfs
  var body = Ext.getBody();
  var frame = body.createChild({
    tag:'iframe',
    cls:'x-hidden',
    id:'iframe',
    name:'iframe'
  });

    tabs.render('report-tabs');
});

</script>
