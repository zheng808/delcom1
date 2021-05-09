<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-time">Timelogs</h1>
  <div id="timelogs-grid"></div>
  <div id="timelogs-edit"></div>
</div>

<script type="text/javascript">
var is_resetting = false;

Ext.onReady(function(){
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

var employeesFilterStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  remoteSort: true,
  autoLoad: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '/employee/datagrid',
    simpleSortMode: true,
    extraParams: {firstlast: '0', checkself: '1', status: 'active', sort: 'firstname'},
    reader: {
      root: 'employees'
    }
  }
});

var workordersStore = new Ext.data.JsonStore({
  fields: ['id', 'customer', 'boat', 'boattype', 'date', 'status','haulout','haulin','color','for_rigging','category_name', 'progress', 'pst_exempt', 'gst_exempt','tax_exempt','text'],
  remoteSort: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid'); ?>',
    extraParams: { status: 'In Progress', sort: 'id', dir: 'DESC' },
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

  var timelogsStore = new Ext.data.JsonStore({
    fields: ['id', 'employee_id', 'employee', 'date', 'billable', 'type', 
             'rate', 'cost', 'payroll_hours', 'billable_hours', 'start_time', 'end_time', 
             'workorder', 'item', 'boat', 'customer', 'status', 'created_at', 'updated_at',
             'employee_notes', 'admin_notes', 'completed_status'],
    remoteSort: true,
    pageSize: 50,
    sorters: [{ property: 'date', direction: 'DESC' }],
    proxy: {
      type: 'ajax',
      url: '<?php echo url_for('timelogs/datagrid'); ?>',
      simpleSortMode: true,
      reader: {
        root: 'timelogs',
        totalProperty: 'totalCount',
        idProperty: 'id'
      }
    }
  });


  // Custom rendering Template
  var workorderSearchTpl = new Ext.XTemplate(

  );


  timelogs_list = new Ext.grid.GridPanel({
    height: 450,
    enableColumnMove: false,
    emptyText: 'No matching Timelogs found',
    store: timelogsStore,
    viewConfig: { stripeRows: true, loadMask: true },
    columns:[
    {
      header: "Date",
      dataIndex: 'date',
      hideable: false,
      sortable: true,
      width: 80
    },{
      header: "Employee",
      dataIndex: 'employee',
      sortable: true,
      width: 120
    },{
      header: "Customer",
      dataIndex: 'customer',
      sortable: true,
      width: 120
    },{ 
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: true,
    width: 60
    }, 
    {
      header: "Type",
      dataIndex: 'type',
      sortable: false,
      flex: 1,
      renderer: function(value,metaData,record){
        if (record.data['billable'] == true){
          output = '<img src="/images/silkicon/money_dollar.png" width="10" height="10" title="Billable" alt="Billable" />';
        } else {
          output = '<img src="/images/silkicon/money_dollar_hollow.png" width="10" height="10" title="Non-Billable" alt="Non-Billable" />';
        }
        output += ' '+value;
        return output;
      }
    },{
      header: "Payroll",
      dataIndex: 'payroll_hours',
      sortable: false,
      align: 'center',
      width: 55
    },{
      header: "Billed",
      dataIndex: 'billable_hours',
      sortable: false,
      align: 'center',
      width: 50,
      renderer: function(value,metaData,record){
        if (value != record.data.payroll_hours) {
          return '<span style="color: red">'+value+'</span>';
        } else {
          return value;
        }
      }
    },{
      header: "Workorder",
      dataIndex: 'workorder',
      sortable: false,
      flex: 1,
      renderer: function(value,metaData,record){
        if (record.data['workorder'] != ''){
          return '#' + record.data['workorder'] + ' - ' + record.data['item'];
        } else {
          return 'None';
        }
      }
    },{
      header: "Complete Status",
      dataIndex: 'completed_status',
      sortable: false,
      width: 55,
      flex: 1,
      renderer: function(value,metaData,record){
        if (record.data['completed_status'] == true){
          output = '<img src="/images/silkicon/accept.png" width="15" height="15"/>';
        }else{
          output = '<img src="/images/silkicon/folder_edit.png" width="15" height="15"/>';
        } 
        return output;
      }
    },{
      header: "Status",
      dataIndex: 'status',
      sortable: true,
      width: 50,
      renderer: function(value,metaData,record){
        if (value == 'Approved'){
          img = 'tick';
        }
        else if (value == 'Flagged'){
          img = 'flag_red';
        }
        else{
          img = 'error'
        }
        output = '<img src="/images/silkicon/'+img+'.png" title="'+value+'" alt="'+value+'" />';
        if (record.data['employee_notes']){
          output += '<img src="/images/silkicon/information.png" title="Has Employee Notes" alt="Has Employee Notes" />';
        } else {
          output += '<img src="/images/x.gif" width="16" height="16" />';
        }
        if (record.data['admin_notes']){
          output += '<img src="/images/silkicon/note.png" title="Has Admin Notes" alt="Has Admin Notes" />';
        }
        return output;
      }
    }],

    selModel: new Ext.selection.RowModel({
      mode: 'MULTI',
      listeners: { 
        selectionchange: function(sm, records){
          if (sm.getCount() == 0){
            Ext.getCmp('details').getLayout().setActiveItem(0);
          } else if (sm.getCount() == 1){
            Ext.getCmp('details').getLayout().setActiveItem(1);
            thisdata = sm.getSelection()[0].data;
            detailsTpl.overwrite(Ext.getCmp('details').items.get(1).body, thisdata);
            Ext.getCmp('singleflag').setVisible(thisdata.status != 'Flagged');
            Ext.getCmp('singleunflag').setVisible(thisdata.status == 'Flagged');
            Ext.getCmp('singleapprove').setVisible(thisdata.status != 'Approved');
            Ext.getCmp('singleunapprove').setVisible(thisdata.status == 'Approved');
          } else {
            Ext.getCmp('details').getLayout().setActiveItem(2);
            showflag = showunflag = showapprove = showunapprove = false;
            sels = sm.getSelection();
            for (var i=0; i<sels.length; i++){
              if (sels[i].data.status == 'Flagged'){showunflag = true; showapprove = true; }
              else if (sels[i].data.status == 'Approved') { showunapprove = true; showflag = true; }
              else { showflag = true; showapprove = true; }
            }
            Ext.getCmp('multiflag').setVisible(showflag);
            Ext.getCmp('multiunflag').setVisible(showunflag);
            Ext.getCmp('multiapprove').setVisible(showapprove);
            Ext.getCmp('multiunapprove').setVisible(showunapprove);
            Ext.getCmp('multiapprovesep').setVisible(showunapprove && showapprove);
            Ext.getCmp('multiflagsep').setVisible(showunflag && showflag);
          }
        }
      }
    }),

    tbar: new Ext.Toolbar({
      items: ['->',{
        text:'Add Timelog',
        iconCls: 'timeadd',
        handler: function(){
          <?php if ($sf_user->hasCredential(array('timelogs_add_self','timelogs_add_other'), false)): ?>
            new Ext.ux.TimelogEditWin();
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','You do not have permission to add timelogs');
          <?php endif; ?>
        }
      },'-',{
        text: 'Edit Labour Rates',
        iconCls: 'dept',
        handler: function(){
          <?php if ($sf_user->hasCredential('timelogs_labour')): ?>
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Labour Rates..."});
            myMask.show();
            location.href= '<?php echo url_for('timelogs/labour'); ?>';
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','You do not have permission to edit labour rates');
          <?php endif; ?>
        }
      },'-',{
        text: 'Edit Non-Billable Types',
        iconCls: 'dept',
        handler: function(){
          <?php if ($sf_user->hasCredential('timelogs_labour')): ?>
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Non-Billable Types..."});
            myMask.show();
            location.href= '<?php echo url_for('timelogs/nonbill'); ?>';
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','You do not have permission to edit labour rates');
          <?php endif; ?>
        }


      }] 
    }),

    bbar: new Ext.PagingToolbar({
      id: 'timelogs_pager',
      store: timelogsStore 
    }),

    listeners: {
      'beforerender': function(grid){

        //customize display of rows
        grid.getView().getRowClass = function(record, index){
          return (record.data['status'] == 'Flagged' ? 'red-row' : (record.data['status'] == 'Unapproved' ? 'orange-row' : ''));
        }

        grid.getStore().loadRawData(<?php
          //load the initial data
          $inst = sfContext::getInstance();
          $inst->getRequest()->setParameter('limit', 50);
          $inst->getRequest()->setParameter('sort', 'date');
          $inst->getRequest()->setParameter('dir', 'DESC');
          $inst->getController()->getPresentationFor('timelogs','datagrid');
       ?>);
      }
    }

  });


  var updateFilterButtonVal = function(btn, pressed){
    if (pressed) {
      newval = (btn.valueField == 'All' ? '' : btn.valueField);
      timelogs_list.store.proxy.setExtraParam(btn.toggleGroup, newval);
      if (!is_resetting)
      {
        Ext.getCmp('timelogs_pager').moveFirst();
      }
    } 
  };

  var updateFilterVal = function(field){
    if (timelogs_list.store.proxy.extraParams[field.paramField]){
      oldval = timelogs_list.store.proxy.extraParams[field.paramField];
    } else {
      oldval = '';
    }
    if (field.isXType('datefield')){
      newval = (field.getValue() ? Ext.Date.format(new Date(field.getValue()), 'Y-m-d H:i:s') : '');
    } else if (field.getValue() == 'All') {
      newval = '';
    } else {
      newval = field.getValue()
    }
    if (oldval != newval)
    {
      timelogs_list.store.proxy.setExtraParam(field.paramField, newval);
      if (!is_resetting)
      {
        Ext.getCmp('timelogs_pager').moveFirst();
      }
    }
  }

  var filter = new Ext.Panel({
    width: 225,
    title: 'Filter Timelogs',
    items: [{
      xtype: 'panel',
      layout: 'anchor',
      id: 'filter_form',
      border: false,
      bodyStyle: 'padding: 10px;',
      labelWidth: 60,
      items: [{
        id: 'filter_status',
        xtype: 'container',
        padding: '5 0 5 0',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'All',
          pressed: true,
          isDefault: true,
          toggleGroup: 'status',
          cls: 'buttongroup-first',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'All',
          width: 25
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Approved',
          toggleGroup: 'status',
          cls: 'buttongroup-middle',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Approved',
          flex: 16
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Unapproved',
          toggleGroup: 'status',
          cls: 'buttongroup-middle',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Unapproved',
          flex: 20
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Flagged',
          toggleGroup: 'status',
          cls: 'buttongroup-last',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Flagged',
          flex: 14
        }] 
      },{
        id: 'filter_type',
        xtype: 'container',
        padding: '5 0 15 0',
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
          width: 25
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Billable',
          toggleGroup: 'type',
          cls: 'buttongroup-middle',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Billable',
          flex: 2
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Non-Billable',
          toggleGroup: 'type',
          cls: 'buttongroup-last',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Non-Billable',
          flex: 2
        }] 
      },{      
        id: 'filter_employee',
        xtype: 'combo',
        fieldLabel: 'Employee',
        anchor: '-1',
        forceSelection: true,
        queryMode: 'local',
        valueField: 'id',
        displayField: 'name',
        triggerAction: 'all',
        minChars: 2,
        store: employeesFilterStore,
        listConfig: { minWidth: 200 },
        paramField: 'employee_id',
        listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
      },{
        id: 'filter_workorder',
        xtype: 'combo',
        fieldLabel: 'Workorder',
        anchor: '-1',
        forceSelection: true,
        queryMode: 'remote',
        valueField: 'id',
        displayField: 'summary',
        hideTrigger: true,
        listConfig: { 
          minWidth: 500,
          getInnerTpl: function(){
            return '<a class="search-item" style="display: block; border-top: 1px dotted #ccc; color: #000;">' +
                   '<span style="font-weight: bold; font-size: 13px;">#{id}: {boat}</span>' +
                   '<span style="font-weight: bold; padding-left: 5px;">({customer})</span><br />' +
                   '<span style="padding-left: 20px; color: green">{date} - {status}<span>' +
                   '</a>';
          }
        },
        minChars: 2,
        emptyText: 'Customer/Boat name...',
        store: workordersStore,
        paramField: 'workorder_id',
        listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
      },{
        id: 'filter_startdate',
        xtype: 'datefield',
        fieldLabel: 'Start Date',
        anchor: '-1',
        endDateField: 'filter_enddate',
        vtype: 'daterange',
        emptyText: 'Show logs after...',
        format: 'M j, Y',
        paramField: 'start_date',
        listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
      },{
        id: 'filter_enddate',
        xtype: 'datefield',
        fieldLabel: 'End Date',
        anchor: '-1',
        startDateField: 'filter_startdate',
        vtype: 'daterange',
        emptyText: 'Show logs before...',
        format: 'M j, Y',
        paramField: 'end_date',
        listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
      }]
    }],

    bbar: new Ext.Toolbar({
      items: ['->',{
        text:'Reset All',
        iconCls: 'undo',
        handler: function(){
          is_resetting = true;
          Ext.getCmp('filter_employee').reset();
          timelogs_list.store.proxy.setExtraParam('employee_id', null);
          Ext.getCmp('filter_workorder').reset();
          timelogs_list.store.proxy.setExtraParam('workorder_id', null);
          Ext.getCmp('filter_status').down('button[isDefault]').toggle(true);
          Ext.getCmp('filter_type').down('button[isDefault]').toggle(true);
          Ext.getCmp('filter_startdate').reset();
          Ext.getCmp('filter_startdate').setMaxValue(false);
          timelogs_list.store.proxy.setExtraParam('start_date', null);
          Ext.getCmp('filter_enddate').reset();
          Ext.getCmp('filter_enddate').setMaxValue(false);
          timelogs_list.store.proxy.setExtraParam('end_date', null);
          is_resetting = false;

          Ext.getCmp('timelogs_pager').moveFirst();
          timelogs_list.getSelectionModel().deselectAll();
        }
      }]
    })
  });


  //panel view Template
  var detailsTpl = new Ext.XTemplate(
    '<table class="infotable"><tr>',
    '<td class="label">Employee:</td><td><a href="<?php echo url_for('employee/view?id='); ?>{employee_id}">{employee}</a></td>',
    '<td class="label">Billable:</td><td><tpl if="billable">Yes</tpl><tpl if="!billable">No</tpl></td></tr>',
    '<tr><td class="label">Labour Type:</td><td>{type}</td>',
    '<td class="label">Rate:</td><td><tpl if="rate == \'Unknown\'">-</tpl><tpl if="rate &gt; 0">${rate} /hr</tpl></td></tr>',
    '<tr><td class="label">Date:</td><td>{date}</td>',
    '<td class="label">Hours:</td><td>{payroll_hours}<tpl if="billable_hours != \'\' && billable_hours != payroll_hours"> (billed {billable_hours})</tpl></td></tr>',
    '<tpl if="start_time != \'\'"><tr><td class="label">Start Time:</td><td>{start_time}</td>',
    '  <td class="label">End Time:</td><td>{end_time}</td></tr></tpl>',
    '<tr><td class="label">Workorder:</td><td><tpl if="workorder == \'\'">None</tpl>',
    '  <tpl if="workorder != \'\'"><a href="<?php echo url_for('work_order/view?id='); ?>{workorder}">#{workorder} - {customer}</a></tpl>',
    '</td>',
    '<tpl if="item != \'\'"><td class="label">Workorder Item:</td><td>{item}</td></tpl></tr>',
    '<tr><td class="label">Status:</td><td>{status}</td>',
    '<tpl if="cost &gt; 0"><td class="label">Amount:</td><td>{cost}</td></tpl></tr>',
    '<tpl if="employee_notes != \'\'"><tr><td class="label">Employee Notes:</td><td colspan="3">{employee_notes}</td></tr></tpl>',
    '<tpl if="admin_notes != \'\'"><tr><td class="label">Admin notes:</td><td colspan="3">{admin_notes}</td></tr></tpl>',
    '<tr><td class="label">Created On:</td><td>{created_at}</td><td class="label">Last Updated:</td><td>{updated_at}</td>',
    '</table>'
  );

  var details = new Ext.Panel({
    id: 'details',
    height: 250,
    layout: 'card',
    activeItem: 0,
    border: false,
    items: [{
      html: 'Click a timelog entry to view details<br /><br />Use Ctrl+click to select additional logs<br />Or Shift+click to select a range',
      bodyStyle: 'text-align: center; padding-top: 60px; background-color: #eee; border-top: none;'
    },{
      bodyStyle: 'font-size: 12px; padding: 10px 10px 0 10px; font-weight: normal; border-top: none;',
      autoScroll: true,
      bbar: new Ext.Toolbar({
        items: [{
          id: 'singleedit',
          text: 'Edit',
          iconCls: 'timeedit',
          handler: function(){
            <?php if ($sf_user->hasCredential('timelogs_edit')): ?>

              var sel_id = timelogs_list.getSelectionModel().getSelection()[0].data.id;
              new Ext.ux.TimelogEditWin({
                formConfig: {
                  params: { id: sel_id },
                  autoLoadUrl: '<?php echo url_for('timelogs/load'); ?>?id=' + sel_id
                }
              });
            <?php else: ?>
              Ext.Msg.alert('Permission Denied','You do not have permission to edit timelogs');
            <?php endif; ?>
          }
        },'->',{
          id: 'singleapprove',
          doAction: 'approve',
          text: 'Approve',
          iconCls: 'approve',
          handler: doTimelogAction
        },{
          id: 'singleunapprove',
          doAction: 'unapprove',
          text: 'Unapprove',
          iconCls: 'reject',
          handler: doTimelogAction
        },'-',{
          id: 'singleunflag',
          doAction: 'unflag',
          text: 'Un-Flag',
          iconCls: 'flag',
          handler: doTimelogAction
        },{
          id: 'singleflag',
          doAction: 'flag',
          text: 'Flag',
          iconCls: 'flag',
          handler: doTimelogAction
        },'-',{
          id: 'singledelete',
          doAction: 'delete',
          text: 'Delete',
          iconCls: 'delete',
          handler: doTimelogAction
        },'-',{
          id: 'overtime',
          doAction: 'OT',
          text: 'OverTime',
          iconCls: 'flag',
          handler: doTimelogAction
        },{
          id: 'doubletime',
          doAction: 'DT',
          text: 'DoubleTime',
          iconCls: 'flag',
          handler: doTimelogAction
        }]
      })
    },{
      bodyStyle: 'text-align: center; padding-top: 70px; background-color: #eee;',
      html: 'Multiple timelogs selected.',
      bbar: new Ext.Toolbar({
        items: ['->',{
          id: 'multiapprove',
          doAction: 'approve',
          text: 'Approve Selected',
          iconCls: 'approve',
          handler: doTimelogAction
        },{
          xtype: 'tbseparator',
          id: 'multiapprovesep'
        },{
          id: 'multiunapprove',
          doAction: 'unapprove',
          text: 'Unapprove Selected',
          iconCls: 'reject',
          handler: doTimelogAction
        },'-',{
          id: 'multiunflag',
          doAction: 'unflag',
          text: 'Un-flag Selected',
          iconCls: 'flag',
          handler: doTimelogAction
        },{
          xtype: 'tbseparator',
          id: 'multiflagsep'
        },{
          id: 'multiflag',
          doAction: 'flag',
          text: 'Flag Selected',
          iconCls: 'flag',
          handler: doTimelogAction
        },'-',{
          id: 'multidelete',
          doAction: 'delete',
          text: 'Delete Selected',
          iconCls: 'delete',
          handler: doTimelogAction
        }]
      })
    }]
  });

  //centralized action code
  function doTimelogAction(button){
    sm = timelogs_list.getSelectionModel();
    if (button.doAction == 'delete' || sm.getCount() > 1){
      <?php if ($sf_user->hasCredential('timelogs_edit')): ?>
        if (button.doAction == 'delete'){
          msg = 'Are you sure you want to delete the selected Timelog(s)?<br /><br />This cannot be undone!';
        }else{
          msg = 'Are you sure you want to ' + button.doAction + ' the selected timelogs?';
        }
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: msg,
          modal: true,
          title: 'Confirm '+ button.doAction,
          fn: function(butid){
            if (butid == 'ok'){
              executeTimelogAction(button.doAction);
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to edit or delete timelogs');
      <?php endif; ?>
    } else {
      <?php if ($sf_user->hasCredential('timelogs_approve')): ?>
        executeTimelogAction(button.doAction);
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to modify timelog status');
      <?php endif; ?>
    }
  }

  function executeTimelogAction(name){
    var selectedIds = new Array;
    var selectedRecords = new Array;
    var flag = true;
    sm = timelogs_list.getSelectionModel();
    Ext.each(sm.getSelection(),function(record){ 
      //cannot approve complete timelog
      if(name == 'approve'){
         if (record.data['completed_status'] == true){
            //msg = 'cannot approve Timelog with completed status';
            flag = false;
         }
      }
      selectedRecords.push(record); 
      selectedIds.push(record.data.id); 
      
    });
    if(flag == false){
        Ext.Msg.show({
        buttons: Ext.MessageBox.OK,
        msg: 'cannot approve Timelog with completed status',
        width: 300,
      });
      return;
    }else{
      Ext.Msg.show({
        msg: 'loading...',
        width: 300,
        wait: true
      });
    }
    
    if(flag == true){
      Ext.Ajax.request({
      url: '<?php echo url_for('timelogs/changeStatus?dowhat='); ?>' + name,
      method: 'POST',
      params: {ids: selectedIds.join()},
      success: function(response, options){
        Ext.Msg.hide();
        var data = Ext.JSON.decode(response.responseText);
        if (data && data.success){
          timelogs_list.store.load();
          timelogs_list.getSelectionModel().deselectAll();
          task = new Ext.util.DelayedTask(function(){
            timelogs_list.getSelectionModel().select(selectedRecords);
          });
          task.delay(200);
          //location.reload();
        } else {
          Ext.Msg.show({
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK,
            msg: (data.reason ? data.reason : 'Could not edit timelog(s)! Reload page and try again.'),
            modal: true,
            title: 'Error'
          });
        }
      },
      failure: function(){
        Ext.Msg.hide();
        Ext.Msg.show({
          icon: Ext.MessageBox.ERROR,
          buttons: Ext.MessageBox.OK,
          msg: 'Could not edit timelog(s)! Reload page and try again.',
          modal: true,
          title: 'Error'
        });
      }
    });
    }
    
  }


    filter.render("index-filter");
    timelogs_list.render("timelogs-grid");
    details.render("timelogs-edit");

    //override the beforeblur event for TimeFields, to allow for more logical entry
    (function() {
      Ext.override(Ext.form.TimeField, {
        beforeBlur: function() {
          var r = this.getRawValue().replace(/^\s*/, "").replace(/\s*$/, "");
          var matches = r.match(/^((?:1[012])|(?:\d))(?:\:[012345]\d)?$/);
          if (matches && matches[1]){
            parsed = parseInt(matches[1]);
            if (parsed < 7) { r += 'pm'; }
            else if (parsed >= 12) { r += 'pm'; }
            else { r += 'am'; }
            var v = this.parseDate(r);
          }
          else
          {
            var v = this.parseDate(r);
          }
          if(v){
            this.setValue(v.dateFormat(this.format));
          }
          Ext.form.TimeField.superclass.beforeBlur.call(this);
        }
      });
    })();

});

</script>
