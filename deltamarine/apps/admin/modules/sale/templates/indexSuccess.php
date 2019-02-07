<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-person">View Customer Sales</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var is_resetting = false;

var salesStore = new Ext.data.JsonStore({
  fields: ['id', 'customer', 'date', 'status', 'for_rigging'],
  remoteSort: true,
  pageSize: 25,
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('sale/datagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'sales',
      idProperty: 'id',
      totalProperty: 'totalCount'
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
      root: 'customers',
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

var boatTpl = new Ext.XTemplate(
  '<tpl for="."><div class="x-boundlist-item">{name}',
    '<tpl if="make != \'\'"> <span style="font-size: 10px; color: #999;">({make}',
      '<tpl if="model != \'\'"> {model}</tpl>',
    ')</span></tpl>',
  '</li></tpl>'
);


Ext.define('Ext.ux.SaleAddWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Sale',
  width: 520,
  autoShow: true,
  closeAction: 'destroy',

  defaultFormConfig: {
    url: '/sale/add',

    fieldDefaults: { labelWidth: 150, labelAlign: 'left' },

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
              field.up('form').down('#exempt_pst').setValue( (r && r.data.country != '' && r.data.country != 'CA') ? 1 : 0);
              field.up('form').down('#exempt_gst').setValue( (r && r.data.country != '' && r.data.country != 'CA') ? 1 : 0);
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
          fieldLabel: 'Boat Name (optional)',
          name: 'boat_name',
          forceSelection: true,
          editable: true,
          allowBlank: true,
          valueField: 'name',
          displayField: 'name',
          triggerAction: 'all',
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
    },{
      xtype: 'textfield',
      fieldLabel: 'PO Number',
      maxLength: 126,
      anchor: '-100',
      name: 'po_num'
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Company',
      anchor: '-100',
      name: 'for_rigging',
      value: '0',
      items: [
        { value: '0', text: 'Delta Services' },
        { value: '1', text: 'Delta Rigging' }
      ]
    },{
      itemId: 'exempt_pst',
      xtype: 'acbuttongroup',
      fieldLabel: 'PST',
      anchor: '-100',
      name: 'pst_exempt',
      value: '0',
      items: [
        { value: '0', flex: 5, text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST' },
        { value: '1', flex: 3, text: 'PST Exempt' } 
      ]        
    },{
      itemId: 'exempt_gst',
      xtype: 'acbuttongroup',
      fieldLabel: 'GST',
      anchor: '-100',
      name: 'gst_exempt',
      value: '0',
      items: [
        { value: '0', flex: 5, text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST' },
        { value: '1', flex: 3, text: 'GST Exempt' } 
      ]
    },{
      xtype: 'numberfield',
      fieldLabel: 'Default Part Discount %',
      name: 'discount_pct',
      minValue: 0,
      maxValue: 100,
      value: 0,
      anchor: '-250',
    }]

  }
});

var grid = new Ext.grid.GridPanel({
  minHeight: 500,
  bodyCls: 'indexgrid',    
  store: salesStore,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 35
  },{
    header: "Sale Date",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 100
  },{
    header: "Customer Name",
    dataIndex: 'customer',
    sortable: true,
    flex: 1
  },{
    header: "Status",
    dataIndex: 'status',
    id: 'status',
    sortable: true,
    width: 180
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add A New Sale',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          new Ext.ux.SaleAddWin({
            formConfig: {
              formSuccess: function(form,action,obj){
                var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Sale..."});
                myMask.show();
                location.href = '<?php echo url_for('sale/view?id='); ?>' + obj.newid;
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to add sales');
        <?php endif; ?>
      }
    }]
  }),

  bbar: new Ext.PagingToolbar({
    id: 'index_pager',
    store: salesStore,
    displayInfo: true,
    displayMsg: 'Displaying Sales {0} - {1} of {2}',
    emptyMsg:   'No Sales Available'
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Sale Information..."});
        myMask.show();
        location.href= '<?php echo url_for('sale/view?id='); ?>' + record.data.id ;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('limit', 25);
        $inst->getRequest()->setParameter('sort', 'date');
        $inst->getRequest()->setParameter('dir', 'DESC');
        $inst->getController()->getPresentationFor('sale','datagrid');
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
      Ext.getCmp('index_pager').moveFirst();
    }
  } 
};


var updateFilterVal = function(field){
  if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  newval = field.getValue();
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    if (!is_resetting) {
      Ext.getCmp('index_pager').moveFirst();
    }
  }
};

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Sales',
  items: [{
    xtype: 'panel',
    layout: 'anchor',
    id: 'filter_form',
    border: false,
    bodyStyle: 'padding: 10px;',
    fieldDefaults: { labelWidth: 60 },
    items: [{
      id: 'filter_name',
      xtype: 'textfield',
      fieldLabel: 'Customer Name',
      paramField: 'query',
      anchor: '-1',
      enableKeyEvents: true,
      listConfig: { minWidth: 300 },
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_id',
      xtype: 'textfield',
      fieldLabel: 'Sale ID',
      paramField: 'id',
      anchor: '-1',
      enableKeyEvents: true,
      listConfig: { minWidth: 300 },
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_rigging',
      xtype: 'container',
      padding: '15 5 5 5',
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
        flex: 1
      },{        
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Delta Services',
        toggleGroup: 'for_rigging',
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '2',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Delta Rigging',
        toggleGroup: 'for_rigging',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '1',
        flex: 2
      }]
    }]
  }],

  bbar: new Ext.Toolbar({
    items: ['->',{
      text:'Reset',
      iconCls: 'undo',
      handler: function(){
        is_resetting = true;
        Ext.getCmp('filter_name').reset();
        Ext.getCmp('filter_id').reset();
        grid.store.proxy.setExtraParam('query', null);
        grid.store.proxy.setExtraParam('id', null);
        Ext.getCmp('filter_rigging').down('button[isDefault]').toggle(true);
        is_resetting = false;

        Ext.getCmp('index_pager').moveFirst();
      }
    }]
  })
});


Ext.onReady(function(){
  filter.render('index-filter');
  grid.render('index-grid');

});

</script>
