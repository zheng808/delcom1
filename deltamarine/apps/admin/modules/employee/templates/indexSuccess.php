<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-person">View Employees</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">
var is_resetting = false;

var employeesStore = new Ext.data.JsonStore({
  fields: ['id','name','job_title','mobile','home','email','last_timelog','department','type','status'],
  groupField: 'type',
  pageSize: 5000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('employee/datagrid'); ?>',
    simpleSortMode: true,
    extraParams: { status: 'Active' },
    reader: { 
      root: 'employees',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var grid = new Ext.grid.GridPanel({
  bodyCls: 'index-grid',
  minHeight: 500,
  store: employeesStore,
  features: [new Ext.grid.feature.Grouping({
    groupHeaderTpl: '{name}'
  })],
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[
  {
    header: "Employee Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    groupable: false,
    flex: 1
  },{
    header: "Job Title",
    dataIndex: 'job_title',
    sortable: true,
    groupable: false,
    flex: 1
  },{
    header: "Status",
    dataIndex: 'status',
    groupable: false,
    hideable: true
  },{
    header: "Mobile Number",
    dataIndex: 'mobile',
    sortable: true,
    groupable: false,
    width: 120,
  },{
    header: "Home Number",
    dataIndex: 'home',
    sortable: true,
    hidden: true,
    groupable: false,
    width: 120
  },{
    header: "Email",
    dataIndex: 'email',
    hidden: true,
    groupable: false
  },{
    header: "Last Timelog",
    dataIndex: 'last_timelog',
    align: 'center',
    groupable: false,
    width: 110
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add Employee',
      iconCls: 'add',
      handler: function(btn){
        var thegrid = btn.up('gridpanel');
        <?php if ($sf_user->hasCredential('employee_edit')): ?>
        new Ext.ux.EmployeeEditWin({
          autoShow: true,
          title: 'Add Employee',
          showPayrate: <?php echo ($sf_user->hasCredential('employee_payrate') ? 'true' : 'false'); ?>,
          formConfig: {
            url: '<?php echo url_for('employee/add'); ?>',
            formSuccess: function(form,action,response){
              var newid = response.newid;
              thegrid.getStore().load({ callback: function(){
                Ext.get(thegrid.getView().getNode(thegrid.getStore().find('id',newid), false)).highlight('66DD66', { duration: 2000});
              }});
            }
          },

        });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to add an employee');
        <?php endif; ?>
      }
    },'-',{
      text: 'Edit Departments',
      iconCls: 'dept',
      handler: function(){
        <?php if ($sf_user->hasCredential('employee_departments')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Departments..."});
          myMask.show();
          location.href= '<?php echo url_for('employee/department'); ?>';
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to edit employee departments');
        <?php endif; ?>
      }
    }]
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
     select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Employee Information..."});
        myMask.show();
        location.href= '<?php echo url_for('employee/view?id='); ?>' + record.data.id;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('status', 'Active');
        $inst->getRequest()->setParameter('sort', 'type');
        $inst->getRequest()->setParameter('dir', 'ASC');
        $inst->getController()->getPresentationFor('employee','datagrid');
     ?>);
    }
  }

});

var updateFilterButtonVal = function(btn, pressed){
  if (pressed) {
    newval = (btn.valueField == 'All' ? '' : btn.valueField);
    grid.store.proxy.setExtraParam(btn.toggleGroup, newval);
    if (!is_resetting)
    {
      grid.store.load();
    }
  } 
};

var updateFilterVal = function(field){
  if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  if (field.getValue() == 'All') {
    newval = '';
  } else {
    newval = field.getValue();
  }
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    if (!is_resetting)
    {    
      grid.store.load();
    }
  }
}

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Employees',
  items: [{
    xtype: 'panel',
    layout: 'anchor',
    id: 'filter_form',
    border: false,
    bodyStyle: 'padding: 10px;',
    fieldDefaults: { labelWidth: 70 },
    items: [{
      id: 'filter_name',
      xtype: 'textfield',
      fieldLabel: 'Name',
      anchor: '-1',
      paramField: 'name',
      emptyText: 'Employee name...',
      enableKeyEvents: true,
      listeners: { 'keypress': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_title',
      xtype: 'textfield',
      fieldLabel: 'Job Title',
      anchor: '-1',
      paramField: 'title',
      enableKeyEvents: true,
      listeners: { 'keypress': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_type',
      xtype: 'container',
      padding: '15 5 5 5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        pressed: true,
        isDefault: true,
        toggleGroup: 'type',
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'All',
        flex: 1
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Employees',
        toggleGroup: 'type',
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'Employees Only',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Contractors',
        toggleGroup: 'type',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'Contractors Only',
        flex: 2
      }] 
    },{
      id: 'filter_status',
      xtype: 'container',
      padding: '5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        toggleGroup: 'status',
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'All',
        flex: 1
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        pressed: true,
        isDefault: true,        
        text: 'Active',
        toggleGroup: 'status',
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'Active',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Inactive',
        toggleGroup: 'status',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'Inactive',
        flex: 2
      }]      
    }]
  }],

  bbar: new Ext.Toolbar({
    items: ['->',{
      text:'Reset All',
      iconCls: 'undo',
      handler: function(){
        is_resetting = true;
        Ext.getCmp('filter_name').reset();
        grid.store.proxy.setExtraParam('name', '');
        Ext.getCmp('filter_title').reset();
        grid.store.proxy.setExtraParam('title', '');
        Ext.getCmp('filter_type').down('button[isDefault]').toggle(true);
        Ext.getCmp('filter_status').down('button[isDefault]').toggle(true);
        is_resetting = false;

        grid.store.load();
      }
    }]
  })
});

Ext.onReady(function(){

  grid.render('index-grid');
  filter.render('index-filter');
});

</script>
