<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-company">View Supplier Orders</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var suppliersStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier/datagrid'); ?>',
    extraParams: {firstlast: '0'},
    reader: {
      root: 'suppliers'
    }
  }
});

var ordersStore = new Ext.data.JsonStore({
  fields: ['id','supplier', 'date', 'status', 'received', 'purchase_order'],
  remoteSort: true,
  pageSize: 25,
  sorters: [{ property: 'id', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier_order/datagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'orders',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});


var OrderAddWin = new Ext.Window({
  title: 'Add Order',
  closable: false,
  width: 350,
  height: 125,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',
  items: new Ext.FormPanel({
    id: 'orderaddform',
    url: '<?php echo url_for('supplier_order/add'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'combo',
      id: 'supplierfield',
      fieldLabel: 'Supplier Name',
      name: 'supplier_id',
      forceSelection: true,
      allowBlank: false,
      valueField: 'id',
      displayField: 'name',
      triggerAction: 'query',
      hideTrigger: true,
      minChars: 2,
      store: suppliersStore,
      anchor: '-25',
      queryMode: 'remote'
    }],
  
    buttons:[{
      text: 'Create Order',
      formBind: true,
      handler:function(){
        OrderAddWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Creating Order...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Order..."});
            myMask.show();
            obj = Ext.JSON.decode(action.response.responseText);
            location.href = '<?php echo url_for('supplier_order/view?id='); ?>' + obj.newid;
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add order. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ OrderAddWin.show(); },
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
});

var grid = new Ext.grid.GridPanel({
  minHeight: 500,
  store: ordersStore,
  enableColumnMove: false,
  bodyCls: 'indexgrid',    
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 35
  },{
    header: "Date Ordered",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 80
  },{
    header: "Supplier Name",
    dataIndex: 'supplier',
    sortable: true,
    flex: 1
  },{
    header: 'PO #',
    dataIndex: 'purchase_order',
    width: 80
  },{
    header: "Ordering Status",
    dataIndex: 'status',
    sortable: true,
    flex: 1
  },{
    header: "Received",
    dataIndex: 'received',
    sortable: true,
    width: 100
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add A New Order',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('orders_edit')): ?>
          OrderAddWin.show();
          Ext.getCmp('supplierfield').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to create a supplier order.');
        <?php endif; ?>
      }
    }]
  }),

  bbar: new Ext.PagingToolbar({
    id: 'index_pager',
    store: ordersStore,
    displayInfo: true,
    displayMsg: 'Displaying Orders {0} - {1} of {2}',
    emptyMsg:   'No Orders Available'
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Order Information..."});
        myMask.show();
        location.href= '<?php echo url_for('supplier_order/view?id='); ?>' + record.data.id ;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('limit', 25);
        $inst->getRequest()->setParameter('sort', 'id');
        $inst->getRequest()->setParameter('dir', 'DESC');
        $inst->getController()->getPresentationFor('supplier_order','datagrid');
     ?>);
    }
  }

});

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
    Ext.getCmp('index_pager').moveFirst();
  }
};

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Supplier Orders',
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
      fieldLabel: 'Supplier Name',
      paramField: 'supplier_name',
      anchor: '-1',
      enableKeyEvents: true,
      listConfig: { minWidth: 300 },
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_id',
      xtype: 'textfield',
      fieldLabel: 'Order ID',
      paramField: 'id',
      anchor: '-1',
      enableKeyEvents: true,
      listConfig: { minWidth: 300 },
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }      
    },{
      id: 'filter_po',
      xtype: 'textfield',
      fieldLabel: 'PO Number',
      paramField: 'purchase_order',
      anchor: '-1',
      enableKeyEvents: true,
      listConfig: { minWidth: 300 },
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    }]
  }],

  bbar: new Ext.Toolbar({
    items: ['->',{
      text:'Reset',
      iconCls: 'undo',
      handler: function(){
        Ext.getCmp('filter_name').reset();
        Ext.getCmp('filter_id').reset();
        Ext.getCmp('filter_po').reset();
        grid.store.proxy.setExtraParam('supplier_name', null);
        grid.store.proxy.setExtraParam('id', null);
        grid.store.proxy.setExtraParam('purchase_order', null);
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
