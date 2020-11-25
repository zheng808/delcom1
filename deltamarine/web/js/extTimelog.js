Ext.onReady(function(){


/*********************************************/
/*      LABOUR STUFF                         */
/*********************************************/

var workorderSearchTpl = new Ext.XTemplate(
    '<tpl for="."><li role="option" class="x-boundlist-item" style="border-top: 1px dotted #ccc;">',
        '<span style="font-weight: bold; font-size: 13px;">#{id}: {boat}</span>',
        '<span style="font-weight: bold; padding-left: 5px;">({customer})</span><br />',
        '<span style="padding-left: 20px; color: green">{date} - {status}<span>',
    '</li></tpl>'
);

var employeesStore = new Ext.data.JsonStore({
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

var labourtypesStore = new Ext.data.JsonStore({
  fields: ['id','name','rate','desc'],
  autoLoad: true,
  pageSize: 1000,
  proxy: { 
    type: 'ajax',
    url: '/timelogs/labourDatagrid',
    reader: { 
      root: 'labourtypes',
      totalProperty: 'totalCount'
    }
  }
});

var nonbillsStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  autoLoad: true,
  pageSize: 1000,
  proxy: { 
    type: 'ajax',
    url: '/timelogs/nonbillDatagrid',
    reader: { 
      root: 'nonbilltypes',
      totalProperty: 'totalCount'
    }
  }
});

var workordersStore = new Ext.data.JsonStore({
  fields: ['id','summary', 'customer','boat','date','status'],
  pageSize: 15,
  proxy: {
    type: 'ajax',
    url: '/work_order/datagrid',
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount'
    }
  }
});

if (!foldersStore)
{
  var foldersStore = new Ext.data.TreeStore({
    root: {
      text: 'Items',
      id: 1,
      expanded: true
    },
    proxy: {
      type: 'ajax',
      url: '/work_order/folderstree'
    }
  });
}

Ext.define('Ext.ux.TimelogEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Edit Timelog',
  width: 580,
  autoShow: true,
  closeAction: 'destroy',

  is_estimate: false,
  workorder_id: null,

  defaultFormConfig: { 
    waitMsg: 'Saving Timelog...',
    url:  '/timelogs/save',
    params: {
      id: 'new'
    },

    doneSetup: function(){
      var me = this;

      if (me.parentWin.workorder_id) {
        //if in workorder admin
        me.params.workorder_id = me.parentWin.workorder_id;
        me.params.billable = 1;
      } else {
        //NOT in workorders admin
        me.down('#billnonbill').setVisible(true);
        me.down('#parentfield').setDisabled(true);
        me.down('#estimate').setVisible(false);
      }

      //preload stores so that they're ready when we load an item in
      if (me.down('#billtype').getStore().getCount() == 0) me.down('#billtype').getStore().load();
      if (me.down('#employee').getStore().getCount() == 0) me.down('#employee').getStore().load();

      //add listener to timelog cost fields and call it.
      me.down('#billtype').on('select', me.updateTimelogCost, me);
      me.down('#billablehours').on('change', me.updateTimelogCost, me);
      me.down('#customrate').on('change', me.updateTimelogCost, me);
      me.down('#payrollhours').on('blur', me.updatePayrollHours, me);

      if (me.params.id == 'new' && me.down('#parentfield').store.getRootNode().childNodes.length == 0)
      {
        if (me.parentWin.workorder_id){
          me.down('#parentfield').store.setRootNode({text: 'Root Item', id: 'root-' + me.parentWin.workorder_id});
        }
      }

      //try to smartly set the task based on the selection status or if there's only one item
      if (me.params.id == 'new' && (tree = Ext.ComponentQuery.query('#workorder_tree')[0])){
        if (selitem = tree.getSelectionModel().getSelection()[0]){
          while (selitem && !(/^[0-9]+$/.test(selitem.data.id))){
            selitem = selitem.parentNode;
          }
          if (selitem){
            me.down('#parentfield').setValue(selitem.data.id);
          }
        } else {
          var root = me.down('#parentfield').store.getRootNode();
          if (root.childNodes.length == 1) {
            me.down('#parentfield').setValue(root.childNodes[0].getId());  
          }
        }        
      }

      me.down('#dates').setVisible(me.params.id != 'new');

      me.down('#estimate').setValue(me.parentWin.is_estimate ? '1' : '0');
      me.down('#billnonbill').fireEvent('change', me.down('#billnonbill'), me.down('#billnonbill').getValue());
      
    },

    formLoad: function(data){
      var me = this;

      me.updateTimelogCost();

      //populate the workorder and task dropdowns, for use in non-WorkOrder Admin context.
      if (!me.parentWin.workorder_id && data.workorder_summary != ''){
        var wo = me.down('#workorder');        
        wo.store.add({id: data.workorder_id, summary: data.workorder_summary});
        wo.setValue(data.workorder_id);
        var wo_id = data.workorder_id;
      } else {
        var wo_id = me.parentWin.workorder_id;
      }

      var woi = me.down('#parentfield');
      woi.setValue('');
      woi.store.setRootNode({text: 'Root Item', id: 'root-' + wo_id});
      woi.setDisabled(false);

      if (data.workorder_item_name != ''){
        woi.setValue(data.workorder_item_id);
        woi.validate();
        var task = new Ext.util.DelayedTask(function(){ woi.validate(); });
        task.delay(200);
      }    
    },

    formSuccess: function(){
      if (typeof timelogs_list != 'undefined') timelogs_list.store.load();
      if (typeof reload_tree != 'undefined') reload_tree();
    },

    updateTimelogCost: function(){
      var me = this;
      var val = '---';

      var ratefield = me.down('#billtype');
      var customfield = me.down('#custom');
      if (customfield.getValue() == '1'){
        val = me.down('#customrate').getValue();
        val = val * me.down('#billablehours').getValue();
        val = val.toFixed(2);        
      } else if (ratefield.getValue()) {
        val = ratefield.findRecordByValue(ratefield.getValue()).data.rate;
        val = val * me.down('#billablehours').getValue();
        val = val.toFixed(2);
      }

      me.down('#outcost').setValue('<strong>' + val + '</strong>');
    },

    updatePayrollHours: function(f){
      var me = this;

      var bill = me.down('#billablehours');
      if (f.getValue() != '' && bill.isVisible() && !bill.getValue()){
        bill.setValue(f.getValue());
      }
    },

    fieldDefaults: { labelAlign: 'top' },

    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.48,
        layout: 'anchor',
        items: [{
          itemId: 'billnonbill',
          xtype: 'acbuttongroup',
          fieldLabel: 'Timelog Type',
          name: 'billable',
          hidden: true,
          anchor: '-25',
          value: '1',
          items: [
            { value: '1', text: 'Billable' },
            { value: '0', text: 'Non-Billable' }
          ],
          listeners: {
            change: function(f,newval){
              var billable = (newval == '1');
              var fm = f.up('form');
              fm.disableAndHide('component[billOnly]', !billable);
              fm.disableAndHide('component[nonBillOnly]', billable);
              fm.disableAndHide('#workorder', !billable || fm.parentWin.workorder_id);
            }
          }
        },{
          itemId: 'estimate',
          xtype: 'acbuttongroup',
          name: 'estimate',
          fieldLabel: 'Estimate Only',
          value: '0',
          anchor: '-25',
          items: [ 
            { value: '1', text: 'Yes' },
            { value: '0', text: 'No' }
          ]
        },{
          itemId: 'custom',
          xtype: 'acbuttongroup',
          name: 'custom',
          fieldLabel: 'Custom Rate',
          value: '0',
          anchor: '-25',
          billOnly: true,
          items: [ 
            { value: '1', text: 'Yes' },
            { value: '0', text: 'No' }
          ],
          listeners: {
            change: function(f,newval){
              if (newval == '1'){
                var cf = f.up('form').down('#customlabel');
                var cr = f.up('form').down('#customrate');
                var rf = f.up('form').down('#billtype');
                if (rf.getValue()){
                  var rec = rf.findRecordByValue(rf.getValue());
                  if (cf.getValue() == '' ) cf.setValue(rec.data.name);
                  if (!(cr.getValue() > 0 )) cr.setValue(rec.data.rate);
                }
                cf.focus(false, 200);
              }
            }
          }
        },{          
          xtype: 'acbuttongroup',
          name: 'status',
          fieldLabel: 'Status',
          value: 'Approved',
          anchor: '-25',
          items: [ 'Approved', 'Unapproved', 'Flagged' ],
          visibleIf: {
            itemId: 'estimate',
            compareValue: '0',
            onlyDisable: true
          }
        }]        
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          itemId: 'workorder',
          fieldLabel: 'Workorder',
          xtype: 'combo',
          name: 'workorder_id',
          anchor: '-25',
          forceSelection: true,
          allowBlank: false,
          queryMode: 'remote',
          valueField: 'id',
          displayField: 'summary',
          hideTrigger: true,
          hidden: true,
          disabled: true,
          minChars: 1,
          pageSize: 15,
          listConfig: { 
            minWidth: 500,
            getInnerTpl: function(){
              return '<a class="search-item" style="display: block; border-top: 1px dotted #ccc; color: #000;">' +
                     '<span style="font-weight: bold; font-size: 13px;">#{id}: {boat}</span>' +
                     '<span style="font-weight: bold; padding-left: 5px;">({customer})</span><br />' +
                     '<span style="padding-left: 20px; color: green">{date} - {status}<span>' +
                     '</a>';
            },
          },
          emptyText: 'Customer/Boat',
          store: workordersStore,
          listeners: {
            select: function(field,r){
              var itemfield = field.up('form').down('#parentfield');
              if (itemfield.store.getRootNode().id != 'root-' + field.getValue())
              {
                itemfield.setValue('');
                itemfield.store.setRootNode({text: 'Root Item', id: 'root-' + field.getValue()});
                itemfield.setDisabled(false);
                itemfield.onTriggerClick();
              }
            }
          }
        },{
          itemId: 'parentfield',
          fieldLabel: 'Workorder Task',
          xtype: 'treecombo',
          anchor: '-25',
          treeWidth: 300,
          name: 'workorder_item_id',
          valueField: 'id',
          displayField: 'text',
          forceSelection: true,
          selectChildren: false,
          canSelectFolders: true,
          allowBlank: false,
          rootVisible: false,
          billOnly: true,
          store: foldersStore
        },{
          itemId: 'employee',
          fieldLabel: 'Employee',
          xtype: 'combo',
          anchor: '-25',
          forceSelection: true,
          queryMode: 'local',
          name: 'employee_id',
          allowBlank: false,
          valueField: 'id',
          displayField: 'name',
          triggerAction: 'all',
          initialFocus: true,
          minChars: 1,
          store: employeesStore,
          visibleIf: {
            itemId: 'estimate',
            compareType: '!=',
            onlyDisable: true
          }                    
        },{          
          xtype: 'panel',
          border: false,
          anchor: '0',
          layout: 'anchor',
          billOnly: true,
          items: [{
            itemId: 'billtype',
            fieldLabel: 'Labour Type/Rate',
            xtype: 'combo',
            anchor: '-25',
            allowBlank: false,
            forceSelection: true,
            queryMode: 'local',
            name: 'labour_type_id',
            valueField: 'id',
            displayField: 'desc',
            triggerAction: 'all',
            listConfig: { minWidth: 350, maxHeight: 700 },
            store: labourtypesStore,
            visibleIf: {
              itemId: 'custom',
              compareType: '!='
            }
          },{
            itemId: 'customlabel',
            fieldLabel: 'Custom Labour Type Description',
            xtype: 'textfield',
            name: 'custom_label',
            allowBlank: false,
            anchor: '-25',
            hidden: true,
            visibleIf: 'custom'
          },{
            itemId: 'customrate',
            fieldLabel: 'Custom Rate',
            xtype: 'numberfield',
            name: 'rate',
            allowBlank: false,
            width: 100,
            minValue: 0,
            maxValue: 1000,
            step: 2,
            hidden: true,
            forcePrecision: true,
            visibleIf: 'custom'
          }] 
        },{
          itemId: 'nonbilltype',
          fieldLabel: 'Non-Bill Type',
          xtype: 'combo',
          hidden: true,
          anchor: '-25',
          allowBlank: false,
          forceSelection: true,
          queryMode: 'local',
          name: 'nonbill_type_id',
          valueField: 'id',
          displayField: 'name',
          triggerAction: 'all',
          listConfig: { maxHeight: 700 },
          nonBillOnly: true,
          store: nonbillsStore
        }]                   
      }]
    },{
      layout: 'hbox',
      border: false,
      bodyStyle: 'padding-top: 10px;',
      items:[{
        layout: 'anchor',
        width: 160,
        border: false,
        items: [{
          fieldLabel: 'Date',
          xtype: 'datefield',
          anchor: '-25',
          format: 'M j, Y',
          name: 'date',
          value: new Date(),
          visibleIf: {
            itemId: 'estimate',
            compareType: '!=',
            onlyDisable: true
          }          
        }]
      },{
        layout: 'anchor',
        width: 100,
        border: false,
        items: [{
          id: 'payrollhours',
          fieldLabel: 'Payroll Hours',
          xtype: 'numberfield',
          width: 80,
          minValue: 0,
          maxValue: 24,
          step: 0.5,
          forcePrecision: true,
          name: 'payroll_hours',
          visibleIf: {
            itemId: 'estimate',
            compareType: '!=',
            onlyDisable: true
          }
        }]
      },{
        layout: 'anchor',
        width: 100,
        border: false,
        items: [{
          itemId: 'billablehours',
          fieldLabel: 'Billed Hours',
          xtype: 'numberfield',
          width: 80,
          minValue: 0,
          maxValue: 24,
          step: 0.5,
          allowBlank: false,
          forcePrecision: true,
          billOnly: true,
          name: 'billable_hours'
        }]
      },{
        layout: 'anchor',
        width: 100,
        border: false,
        items: [{
          itemId: 'outcost',
          fieldLabel: 'Billed Cost',
          xtype: 'displayfield',
          billOnly: true,
          value: '<strong>---</strong>'
        }]
      }]
    },{
      fieldLabel: 'Employee Notes',
      xtype: 'textarea',
      name: 'employee_notes',
      allowBlank: false,
      anchor: '-25',
      height: 85,
      visibleIf: {
        itemId: 'estimate',
        compareValue: '0',
        onlyDisable: true
      },      
    },{
      fieldLabel: 'Admin Notes',
      xtype: 'textarea',
      name: 'admin_notes',
      anchor: '-25',
      height: 85
    },{
      itemId: 'dates',
      hidden: true,
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.48,
        layout: 'anchor',
        items: [{
          xtype: 'displayfield',
          fieldLabel: 'Date Created',
	  height: 50,	
	  name: 'created_at'
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'displayfield',
          fieldLabel: 'Last Updated',
          height: 50,
	  name: 'updated_at'
        }]

      }]
    }]

  }
});


});
