<div class="leftside" style="padding-top: 36px;">
  <div id="index-goto"></div>
  <div id="index-filter"></div>
  <?php
    echo link_to('Print Open Workorders List', 'work_order/openWorkordersSheet',
      array('class' => 'button tabbutton', 'style' => 'margin: 20px auto;'));
  ?>
</div>
<div class="rightside" style="width: 950px;">
  <h1 class="headicon headicon-part">Work Orders</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">
var is_resetting = false;

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

var boattypesTpl = new Ext.XTemplate(
  '<tpl for="."><div class="x-boundlist-item">{make}',
    '<tpl if="model == \'\'"> <span style="font-size: 10px; color: #999;"> (all models)</span></tpl>',
    '<tpl if="model != \'\'"> {model}</tpl>',
  '</div></tpl>'
);

var boatTpl = new Ext.XTemplate(
  '<tpl for="."><div class="x-boundlist-item">{name}',
    '<tpl if="make != \'\'"><span style="font-size: 10px; color: #999;"> ({make}',
      '<tpl if="model != \'\'"> {model}</tpl>',
    ')</span></tpl>',
  '</div></tpl>'
);

var workordersStore = new Ext.data.JsonStore({
  fields: ['id', 'customer', 'boat', 'boattype', 'date', 'status','haulout','haulin','color','for_rigging','category_name', 'progress', 'pst_exempt', 'gst_exempt','tax_exempt'],
  sorters: [{ property: 'id', direction: 'DESC' }],
  remoteSort: true,
  pageSize: 25,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid'); ?>',
    extraParams: { status: 'In Progress' },
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});//workordersStore()---------------------------------------------------------

var boatStore = new Ext.data.JsonStore({
  fields: ['id','name','make','model'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boatsdatagrid',
    reader: {
      root: 'boats'
    }
  }
});

var boattypeStore = new Ext.data.JsonStore({
  fields: ['id','make','model','desc'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boattypes',
    reader: {
      root: 'types'
    }
  }
});

var customerStore = new Ext.data.JsonStore({
  fields: ['id','name','country'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/datagrid'); ?>',
    extraParams: {firstlast: '0', withcountry: '1'},
    reader: {
      root: 'customers'
    }
  }
});

var catsStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  autoLoad: true,
  pageSize: 1000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/categoryDatagrid?uncat=1'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'categories',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var customerBoatStore = new Ext.data.JsonStore({
  fields: ['id','name', 'make', 'model'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/boatsdatagrid'); ?>',
    reader: {
      root: 'boats'
    }
  }
});

var WorkOrderAddWin = new Ext.Window({
  title: 'Add Work Order',
  closable: false,
  width: 500,
  height: 370,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',
  items: new Ext.FormPanel({
    autoWidth: true,
    id: 'workorderaddform',
    url: '<?php echo url_for('work_order/add'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.7,
        layout: 'anchor',
        items: [{
          xtype: 'combo',
          itemId: 'customer',
          fieldLabel: 'Customer',
          name: 'customer_id',
          forceSelection: true,
          allowBlank: false,
          valueField: 'id',
          displayField: 'name',
          emptyText: 'Enter Customer Name...',
          hideTrigger: true,
          minChars: 2,
          store: customerStore,
          anchor: '-25',
          queryMode: 'remote',
          listeners: {
            'select': function(field,r){
              field.up('form').down('#boatbutton').setDisabled(false);
              var boatfield = field.up('form').down('#boatfield');
              boatfield.clearValue();
              boatfield.setDisabled(false);
              boatfield.getStore().proxy.setExtraParam('customer_id', field.getValue());
              boatfield.getStore().load({
                callback: function(){
                  var boatfield = field.up('form').down('#boatfield');
                  if (boatfield.getStore().getCount() === 0){
                    Ext.Msg.confirm(
                      'No Boats Available', 
                      'This customer doesn\'t have a boat set up.<br /><br />Would you like to add one now?', 
                      function (btn){
                        if (btn == 'yes'){
                          new Ext.ux.BoatEditWin({
                              customer_id: field.getValue(),
                              loadIntoSelect: field.up('form').down('#boatfield')
                          });
                        }
                      }
                    );
                  }
                  else if (boatfield.getStore().getCount() == 1){
                    boatfield.setValue(boatfield.getStore().getAt(0).data.id);
                  } else {
                    boatfield.onTriggerClick();
                  }
                }
              });
              r = field.findRecordByValue(field.getValue());
              
              if (!r || r.data.country == '' || r.data.country == 'CA') 
              {
                Ext.getCmp('addwo_taxable_pst').setValue(1);
                Ext.getCmp('addwo_taxable_gst').setValue(1);
                //Ext.getCmp('tax_code').setValue('Full Tax');
                //field.up('form').down('#taxstatus').setValue('Full Tax');
                field.up('form').down('#colorCode').setValue('33DD33');

              } else {
                Ext.getCmp('addwo_taxable_pst').setValue(0);
                Ext.getCmp('addwo_taxable_gst').setValue(0);
                //field.up('form').down('#taxstatus').setValue('No Tax');
                //Ext.getCmp('tax_code').setValue('No Tax');
                field.up('form').down('#colorCode').setValue('FF3333');

              }

              //Ext.getCmp('addwo_taxable_pst').setValue( (!r || r.data.country == '' || r.data.country == 'CA') ? 1 : 0);
              //Ext.getCmp('addwo_taxable_gst').setValue( (!r || r.data.country == '' || r.data.country == 'CA') ? 1 : 0);

            },
            'blur': function(field){
              if (field.getValue() == '')
              {
                var boatfield = field.up('form').down('#boatfield');
                boatfield.clearValue();
                boatfield.setDisabled(true);
                boatfield.getStore().proxy.setExtraParam('customer_id', null);
                field.up('form').down('#boatbutton').setDisabled(true);
              }
            }
          }
        }]
      },{
        border: false,
        columnWidth: 0.3,
        items: new Ext.Button({
          text: 'Add Customer',
          iconCls: 'add',
          width: 125,
          handler: function(btn){
            new Ext.ux.CustomerEditWin({
              loadIntoSelect: btn.up('form').down('#customer')
            });
          }
        })
      }]
    },{
      layout: 'column',
      border: false,
      padding: '0 0 15 0',
      items: [{
        border: false,
        columnWidth: 0.7,
        layout: 'anchor',
        items: [{
          xtype: 'combo',
          itemId: 'boatfield',
          fieldLabel: 'Boat',
          name: 'customer_boat_id',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          valueField: 'id',
          disabled: true,
          displayField: 'name',
          triggerAction: 'all',
          emptyText: 'Select Customer Boat...',
          minChars: 1,
          store: customerBoatStore,
          tpl: boatTpl,
          anchor: '-25',
          listConfig: { minWidth: 250 },
          queryMode: 'local'
        }]
      },{
        border: false,
        columnWidth: 0.3,
        items: new Ext.Button({
          text: 'Add Boat',
          itemId: 'boatbutton',
          iconCls: 'add',
          disabled: true,
          width: 125,
          handler: function(btn){
            new Ext.ux.BoatEditWin({
              customer_id: btn.up('form').down('#customer').getValue(),
              loadIntoSelect: btn.up('form').down('#boatfield')
            });
          }
        })
      }]
    },
    
    /*
    Removed for_rigging field
    {
      xtype: 'fieldcontainer',
      fieldLabel: 'Company',
      layout: 'hbox',
      width: 300,
      items: [{
        xtype: 'hidden',
        name: 'for_rigging',
        value: 0,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addworigging',
        allowDepress: false,
        pressed: true,
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Delta Services',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addworigging',
        allowDepress: false,
        flex: 1,
        cls: 'buttongroup-last',
        text: 'Delta Rigging',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]      
    },
    */
    {
      xtype: 'fieldcontainer',
      fieldLabel: 'Initial Status',
      layout: 'hbox',
      width: 300,
      items: [{
        xtype: 'hidden',
        name: 'status',
        value: 'Estimate',
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwostatus',
        allowDepress: false,
        pressed: true,
        isDefault: true,
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Estimate',
        valueField: 'Estimate',
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwostatus',
        allowDepress: false,
        flex: 1,
        cls: 'buttongroup-last',
        text: 'In Progress',
        valueField: 'In Progress',
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]      
    },
    /*
    {
      itemId: 'taxstatus',
      xtype: 'acbuttongroup',
      name: 'tax_status',
      value: '<?php echo $taxCategory; ?>',
      fieldLabel: 'Tax Status',
      items: [ 'Full Tax', 'GST Only', 'PST Only', 'No Tax' ],
      listeners: { 
        change: function(field){
          //alert('Tax status changed');

          var value = field.getValue();
          var form = field.up('form');

          var newval = value;
          var oldval = 'null';

          if (newval === 'No Tax')
          {
            Ext.getCmp('addwo_taxable_pst').setValue(0);
            Ext.getCmp('addwo_taxable_gst').setValue(0);
            //alert('No Tax - gst = 0');
            //Ext.getCmp('colorCode').setValue('FF3333');
            //alert('No Tax - color = FF3333');
            //form.down('#addwo_taxable_pst').setValue(1);
            //form.down('#addwo_taxable_gst').setValue(1);
            form.down('#colorCode').setValue('FF3333');
          }  
          else if (newval === 'Full Tax')
          {
            //alert('Full Tax');
            Ext.getCmp('addwo_taxable_pst').setValue(1);
            //alert('Full Tax - pst = 1');
            Ext.getCmp('addwo_taxable_gst').setValue(1);
            //alert('Full Tax - gst = 1');
            //Ext.getCmp('colorCode').setValue('33DD33');
            //alert('Full Tax - color = 33DD33');
            //form.down('#addwo_taxable_pst').setValue(0);
            //form.down('#addwo_taxable_gst').setValue(0);
            form.down('#colorCode').setValue('33DD33');
          }
          else if (newval === 'GST Only')
          {
            //alert('GST Tax');
            Ext.getCmp('addwo_taxable_pst').setValue(0);
            //alert('GST Tax - pst = 0');
            Ext.getCmp('addwo_taxable_gst').setValue(1);
            //alert('GST Tax - gst = 1');
            //Ext.getCmp('colorCode').setValue('0000FF');
            //alert('GST Tax - color = 0000FF');
            //form.down('#addwo_taxable_pst').setValue(1);
            //form.down('#addwo_taxable_gst').setValue(0);
            form.down('#colorCode').setValue('0000FF');

          } else if (newval === 'PST Only')
          {
            //alert('PST Tax');
            Ext.getCmp('addwo_taxable_pst').setValue(1);
            //alert('PST Tax - pst = 1');
            Ext.getCmp('addwo_taxable_gst').setValue(0);
            //alert('PST Tax - gst = 0');
            //Ext.getCmp('colorCode').setValue('FFA500');
            //alert('PST Tax - color = FFA500');
            //form.down('#addwo_taxable_pst').setValue(0);
            //form.down('#addwo_taxable_gst').setValue(1);
            form.down('#colorCode').setValue('FFA500');
          }
        } 
      }
    },
    */
    /*
    {
      itemId: 'colorCode',
      xtype: 'acbuttongroup',
      //xtype: 'hidden',
      fieldLabel: 'Color Code',
      name: 'color_code',
      value: '33DD33',
      width: 350,
      //items: color_code_array
      items: ['33DD33','0000FF','FFA500','FF3333']
    },
*/
    {
      xtype: 'fieldcontainer',
      fieldLabel: 'Tax Status',
      layout: 'hbox',
      width: 450,
      items: [{
        id: 'colorCode',
        xtype: 'hidden',
        name: 'color_code',
        value: 'Full Tax',
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
          }}
        }
      ,{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          pressed: true,
          text: '<div style="float: left; width: 15px; height: 15px; border: 1px solid #000; background-color: #33DD33; margin-right: 8px;">&nbsp;</div>Full Tax',
          toggleGroup: 'addwotax',
          cls: 'buttongroup-first', 
          listeners: { 'toggle' : function(btn, pressed){
            if (pressed) {
              btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('addwo_taxable_pst').setValue(1);
              Ext.getCmp('addwo_taxable_gst').setValue(1);
            }
          }},
          valueField: '33DD33',
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          pressed: false,
          text: '<div style="float: left; width: 15px; height: 15px; border: 1px solid #000; background-color: #0000FF; margin-right: 8px;">&nbsp;</div>GST Only', 
          toggleGroup: 'addwotax',
          cls: 'buttongroup-middle',
          listeners: { 'toggle' : function(btn, pressed){
            if (pressed) {
              btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('addwo_taxable_pst').setValue(0);
              Ext.getCmp('addwo_taxable_gst').setValue(1);
            }
          }},
          valueField: '0000FF',
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          pressed: false,
          text: '<div style="float: left; width: 15px; height: 15px; border: 1px solid #000; background-color: #FFA500; margin-right: 8px;">&nbsp;</div>PST Only', 
          toggleGroup: 'addwotax',
          cls: 'buttongroup-middle',
          listeners: { 'toggle' : function(btn, pressed){
            if (pressed) {
              btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('addwo_taxable_pst').setValue(1);
              Ext.getCmp('addwo_taxable_gst').setValue(0);
            }
          }},
          valueField: 'FFA500',
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          pressed: false,
          text: '<div style="float: left; width: 15px; height: 15px; border: 1px solid #000; background-color: #FF3333; margin-right: 8px;">&nbsp;</div>No Tax', 
          toggleGroup: 'addwotax',
          cls: 'buttongroup-last',
          listeners: { 'toggle' : function(btn, pressed){
            if (pressed) {
              btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('addwo_taxable_pst').setValue(0);
              Ext.getCmp('addwo_taxable_gst').setValue(0);
            }
          }},
          valueField: 'FF3333',
          flex: 1
        }
      ]      
    },

    {
      xtype: 'hidden',
      id: 'addwo_taxable_pst',
      fieldLabel: 'PST',
      width: 350,
      margin: '15 0 5 0',
      name: 'taxable_pst',
      value:  1,
      items: [
        { value: '0', text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST', flex: 5 },
        { value: '1', text: 'PST Exempt', flex: 3 }
      ]
    },{
      xtype: 'hidden',
      fieldLabel: 'GST',
      itemId: 'addwo_taxable_gst',
      id: 'addwo_taxable_gst',
      width: 350,
      name: 'taxable_gst',
      value: 1,
      items: [
        { value: '0', text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST', flex: 5 },
        { value: '1', text: 'GST Exempt', flex: 3 }
      ]
    },
    /*
    {
      xtype: 'fieldcontainer',
      fieldLabel: 'PST',
      layout: 'hbox',
      width: 300,
      items: [{
        id: 'addwo_taxable_pst',
        xtype: 'hidden',
        name: 'taxable_pst',
        value: 1,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwopst',
        allowDepress: false,
        pressed: true,
        isDefault: true,
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwopst',
        allowDepress: false,
        flex: 1,
        cls: 'buttongroup-last',
        text: 'PST Exempt',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]          
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'GST',
      layout: 'hbox',
      width: 300,
      items: [{
        xtype: 'hidden',
        id: 'addwo_taxable_gst',
        name: 'taxable_gst',
        value: 1,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwogst',
        allowDepress: false,
        pressed: true,
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'addwogst',
        allowDepress: false,
        flex: 1,
        cls: 'buttongroup-last',
        text: 'GST Exempt',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]        
    },
    */


    {
      xtype: 'combo',
      id: 'workorderadd_category',
      fieldLabel: 'Category',
      name: 'workorder_category_id',
      width: 300,
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      displayField: 'name',
      valueField: 'id',
      triggerAction: 'all',
      store: catsStore,
      value: 0,
      listConfig: { minWidth: 200 }
    },

    {
      xtype: 'numberfield',
      fieldLabel: 'Shop Supplies %',
      name: 'shop_supplies_surcharge',
      minValue: 0,
      maxValue: 100,
      anchor: '50%',
      value: 0
    },{
      xtype: 'numberfield',
      fieldLabel: 'Power/Moorage %',
      name: 'moorage_surcharge_amt',
      minValue: 0,
      maxValue: 100,
      forcePrecision: true,
      anchor: '50%',
      value: 0
    }],
  
    buttons:[{
      text: 'Create Work Order',
      formBind: true,
      handler:function(){
        WorkOrderAddWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Creating Work Order...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Work Order..."});
            myMask.show();
            obj = Ext.JSON.decode(action.response.responseText);
            location.href = '<?php echo url_for('work_order/view?id='); ?>' + obj.newid;
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add work order. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ WorkOrderAddWin.show(); },
              modal: true,
              title: 'Oops',
              icon: Ext.MessageBox.ERROR,
              buttons: Ext.MessageBox.OK,
              msg: myMsg
            });
          }
        });
      }
    },{
      text: 'Cancel',
      handler:function(){
        this.findParentByType('window').hide();
        this.findParentByType('form').getForm().reset();
      }
    }]
  })
});//WorkOrderAddWin()---------------------------------------------------------

var grid = new Ext.grid.GridPanel({
  minHeight: 500,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No matching Work Orders found',
  viewConfig: { stripeRows: true, loadMask: true },
  store: workordersStore,
  columns:[{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45
  },{
    header: "Date Created",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 90
  },{
    header: "Customer Name",
    dataIndex: 'customer',
    sortable: true,
    width : 90
    //flex: 1
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    width : 90
    //flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    width : 90
    //flex: 1
  },{
    header: "Haulout",
    dataIndex: 'haulout',
    sortable: true,
    width: 75
  },{
    header: "Relaunch",
    dataIndex: 'haulin',
    sortable: true,
    width: 75
  },{
    header: "Category",
    dataIndex: 'category_name',
    sortable: true,
    width: 90
  },
  /* Individual GST and PST columns */
 // {
 //   header: "Tax Exempt",
 //   dataIndex: 'tax_exempt',
 //   sortable: true,
 //   renderer: function(value,metaData,record){
 //     output = ' '
 //     if (value == 'Y'){
 //       img = 'flag_red';
 //       output = '<img src="/images/silkicon/tick.png" title="Tax Exempt" alt="'+value+'" />';
 //     }
 //     return output;
 //   },
 //   align: 'center',
 //   width: 70
 // },
 {
    header: "PST",
    dataIndex: 'pst_exempt',
    sortable: true,
    renderer: function(value,metaData,record){
      output = ' '
      if (value == 'Y'){
        output = '<img src="/images/silkicon/cross.png" title="PST Exempt" alt="'+value+'" />';
      } else {
        output = '<img src="/images/silkicon/tick.png" title="PST Exempt" alt="'+value+'" />';
      }
      return output;
    },
    align: 'center',
    width: 75
  },{
    header: "GST",
    dataIndex: 'gst_exempt',
    sortable: true,
    renderer: function(value,metaData,record){
      output = ' '
      if (value == 'Y'){
        output = '<img src="/images/silkicon/cross.png" title="GST Exempt" alt="'+value+'" />';
      } else {
        output = '<img src="/images/silkicon/tick.png" title="GST Exempt" alt="'+value+'" />';
      }
      return output;
    },
    align: 'center',
    width: 75
  },
  {
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    renderer: function(value,metaData,record){
      ret = '<div style="float: left; width: 15px; height: 15px; border: 1px solid #000; background-color: #' + record.data['color'] + '; margin-right: 8px;">&nbsp;</div>' + value;
      if (value == 'In Progress') {
        ret = ret + ' <span style="color: #aaa;">(' + record.data.progress + ')</span>';
      }
      return ret;
    },
    width: 130
  }],

  tbar: new Ext.Toolbar({
    items: [{
      text:'Add Work Order',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_estimates')): ?>
          WorkOrderAddWin.show();
          Ext.getCmp('workorderadd_category').setValue('-1');
          WorkOrderAddWin.down('#customer').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to create a new work order.');
        <?php endif; ?>
      }
    },'->',{
      text: 'Edit Workorder Categories',
      iconCls: 'dept',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_edit')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Workorder Categories"});
          myMask.show();
          location.href= '<?php echo url_for('work_order/categories'); ?>';
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit workorder categories');
        <?php endif; ?>        
      }
    }]
  }),

  bbar: new Ext.PagingToolbar({
    id: 'workorders_pager',
    store: workordersStore,
    displayInfo: true,
    displayMsg: 'Displaying Work Orders {0} - {1} of {2}',
    emptyMsg:   'No Work Orders Available'
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php 
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('status', 'In Progress');
        $inst->getRequest()->setParameter('limit', 25);
        $inst->getRequest()->setParameter('sort', 'id');
        $inst->getRequest()->setParameter('dir', 'DESC');
        $inst->getController()->getPresentationFor('work_order','datagrid');
     ?>);
    }
  }
});

var updateFilterButtonVal = function (btn, pressed){
  if (pressed) {
    newval = (btn.valueField == 'All' ? '' : btn.valueField);
    grid.store.proxy.setExtraParam(btn.toggleGroup, newval);
    if (!is_resetting)
    {
      Ext.getCmp('workorders_pager').moveFirst();
    }
  } 
};

var updateFilterVal = function(field){
  if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  if (field.isXType('datefield')){
    newval = (field.getValue() ? Ext.Date.format(new Date(field.getValue()), 'Y-m-d H:i:s') : '');
  } else if (field.getValue() == 'All') {
    newval = '';
  } else {
    newval = field.getValue();
  }
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    Ext.getCmp('workorders_pager').moveFirst();
  }
};

var goto_panel = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Go To Workorder',
  items: [
  new Ext.FormPanel({
    autoWidth: true,
    standardSubmit: true,
    id: 'gotoform',
    url: '<?php echo url_for('work_order/view'); ?>',
    bodyStyle: 'padding: 10px',
    labelWidth: 70,
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.8,
        layout: 'anchor',
        items: [{      
          itemId: 'woid',
          name: 'id',
          xtype: 'textfield',
          fieldLabel: 'Workorder #',
          anchor: '-1',
          listeners: {
              specialkey: function(field, e){
                  if (e.getKey() == e.ENTER) {
                      field.up('form').submit();
                  }
              }
          }
        }]
      },{
        border: false,
        columnWidth: 0.2,
        items: new Ext.Button({
          text: 'Go',
          handler: function(btn){
            btn.up('form').submit();
          }
        })
      }]
    }]
  })]
});

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Work Orders',
  items: [{
    xtype: 'panel',
    layout: 'anchor',
    id: 'filter_form',
    border: false,
    bodyStyle: 'padding: 10px;',
    labelWidth: 70,
    items: [{
      id: 'filter_status',
      xtype: 'container',
      padding: '5',
      layout: 'fit',
      items: [{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'All',
          toggleGroup: 'status',
          cls: 'buttongroup-first buttongroup-top',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'All',
          width: 30
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          pressed: true,
          isDefault: true,
          text: 'In Progress',
          toggleGroup: 'status',
          cls: 'buttongroup-middle buttongroup-top',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'In Progress',
          flex: 3
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Estimate',
          toggleGroup: 'status',
          cls: 'buttongroup-last buttongroup-top',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Estimate',
          flex: 3
        }]
      },{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Completed',
          toggleGroup: 'status',
          cls: 'buttongroup-first buttongroup-bottom',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Completed',
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Cancelled',
          toggleGroup: 'status',
          cls: 'buttongroup-last buttongroup-bottom',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: 'Cancelled',
          flex: 1
        }]
      }]
    },{
      id: 'filter_color',
      xtype: 'container',
      padding: '5',
      layout: 'hbox',
      items: [
      {
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        pressed: true,
        isDefault: true,
        text: 'All',
        toggleGroup: 'color',
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'All',
        width: 30
      }
      <?php $colors = WorkorderPeer::getColorCodesArray(); ?>
      <?php $i = 0; ?>
      <?php foreach ($colors AS $colorcode => $colorname): ?>
        <?php $i++; ?>
        ,{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: '<span style="display: inline-block; height: 15px; width: 13px; margin-left: 10px; border: 1px solid #333; background-color: #<?php echo $colorcode; ?>;',
          toggleGroup: 'color',
          cls: '<?php 
              if ($i == count($colors)) echo 'buttongroup-last';
              else echo 'buttongroup-middle';
            ?>',
          listeners: { 'toggle' : updateFilterButtonVal },
          valueField: '<?php echo $colorcode; ?>',
          flex: 2
        }
      <?php endforeach; ?>
      ]
    },
    /*
    Removed Rigging Filter
    {
      id: 'filter_rigging',
      xtype: 'container',
      padding: '5 5 15 5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        toggleGroup: 'for_rigging',
        pressed: true,
        isDefault: true,
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '0',
        width: 30
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Delta Services',
        toggleGroup: 'for_rigging',
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '2',
        flex: 3
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Delta Rigging',
        toggleGroup: 'for_rigging',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '1',
        flex: 3
      }]
    },
    */
    {
      id: 'filter_category',
      xtype: 'combo',
      fieldLabel: 'Category',
      anchor: '-1',
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      displayField: 'name',
      valueField: 'id',
      name:'category_id',
      triggerAction: 'all',
      paramField: 'workorder_category_id',
      store: catsStore,
      listConfig: { minWidth: 200 },
      listeners: { 'select': updateFilterVal }
    },{
      id: 'filter_customer',
      xtype: 'combo',
      fieldLabel: 'Customer',
      anchor: '-1',
      forceSelection: true,
      queryMode: 'remote',
      valueField: 'id',
      displayField: 'name',
      minChars: 2,
      listConfig: { minWidth: 400 },
      hideTrigger: true,
      emptyText: 'Customer name...',
      store: customerStore,
      paramField: 'customer_id',
      listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_boat',
      xtype: 'combo',
      fieldLabel: 'Boat',
      anchor: '-1',
      forceSelection: true,
      queryMode: 'remote',
      valueField: 'id',
      displayField: 'name',
      minChars: 2,
      listConfig: { minWidth: 250 },
      emptyText: 'Boat name...',
      hideTrigger: true,
      store: boatStore,
      tpl: boatTpl,
      paramField: 'boat_id',  
      listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_boattype',
      xtype: 'combo',
      fieldLabel: 'Boat Type',
      anchor: '-1',
      forceSelection: true,
      queryMode: 'remote',
      valueField: 'id',
      displayField: 'desc',
      minChars: 2,
      listConfig: { minWidth: 250 },
      emptyText: 'Make or Model...',
      hideTrigger: true,
      store: boattypeStore,
      tpl: boattypesTpl,
      paramField: 'boat_type',  
      listeners: { 'select': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_startdate',
      xtype: 'datefield',
      fieldLabel: 'Start Date',
      anchor: '-1',
      endDateField: 'filter_enddate',
      vtype: 'daterange',
      emptyText: 'Created after...',
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
      emptyText: 'Created before...',
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
        Ext.getCmp('filter_status').down('button[isDefault]').toggle(true);
        //Ext.getCmp('filter_rigging').down('button[isDefault]').toggle(true);
        Ext.getCmp('filter_color').down('button[isDefault]').toggle(true);

        Ext.getCmp('filter_category').reset();
        grid.store.proxy.setExtraParam('workorder_category_id', null);
        Ext.getCmp('filter_customer').reset();
        grid.store.proxy.setExtraParam('customer_id', null);
        Ext.getCmp('filter_boat').reset();
        grid.store.proxy.setExtraParam('boat_id', null);
        Ext.getCmp('filter_boattype').reset();
        grid.store.proxy.setExtraParam('boat_type', null);
        Ext.getCmp('filter_startdate').reset();
        Ext.getCmp('filter_startdate').setMaxValue(false);
        grid.store.proxy.setExtraParam('start_date', null);
        Ext.getCmp('filter_enddate').reset();
        Ext.getCmp('filter_enddate').setMinValue(false);
        grid.store.proxy.setExtraParam('end_date', null);
        Ext.getCmp('workorders_pager').moveFirst();
      }
    }]
  })
});

Ext.onReady(function(){

    goto_panel.render("index-goto");
    filter.render("index-filter");
    grid.render("index-grid");

});

</script>
