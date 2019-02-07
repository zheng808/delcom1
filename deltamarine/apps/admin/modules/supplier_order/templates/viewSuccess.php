<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Orders List', 'supplier_order/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-person">Supplier Order #<?php echo $order->getId(); ?> to <?php echo $order->getSupplier()->getName(); ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <table class="infotable" style="width: 330px; float: left;">
      <tr>
        <td class="label">Current Status:</td>
        <td><?php echo $order->outputStatus(); ?></td>
      </tr>
      <tr>
        <td class="label">Ordered Date:</td>
        <td><?php echo ($order->getDateOrdered() ? $order->getDateOrdered('M j, Y - g:i a') : 'Not Yet Sent'); ?></td>
      </tr>
      <tr>
        <td class="label">PO Number:</td>
        <td><?php echo ($order->getPurchaseOrder() ? $order->getPurchaseOrder() : 'Unknown'); ?></td>
      <?php if ($order->getReceivedAll()): ?>
        <tr>
          <td class="label">Date Received:</td>
          <td><?php echo ($order->getDateReceived() ? $order->getDateReceived('M j, Y - g:i a') : 'Unknown'); ?></td>
        </tr>
      <?php else: ?>
        <tr>
          <td class="label">Date Expected:</td>
          <td><?php echo ($order->getDateExpected() ? $order->getDateExpected('M j, Y') : 'Unknown'); ?></td>
        </tr>
      <?php endif; ?>
    </table>

    <div id="actions-buttons" style="float: left; padding: 15px 0 0 15px;"></div>

    <div class="clear"></div>

    <?php if ($order->getNotes()): ?>
      <table class="infotable"><tr>
        <td class="label" style="width: 108px;">Order Notes:</td>
        <td colspan="3"><?php echo nl2br($order->getNotes()); ?></td>
      </tr></table>
   <?php endif; ?>

    <h3 style="margin-top: 20px;" >Order Items</h3>
    <div id="view-grid"></div>

  </div>
</div>


<script type="text/javascript">

var partsStore = new Ext.data.JsonStore({
  fields: ['id', 'part_variant_id', 'name', 'sku', 'units', 'min_quantity', 'max_quantity', 'on_hand', 'available', 
           'track_inventory', 'has_serial_number', 'category_path', 'supplier_sku', 'supplier_notes','standard_package_qty',
           'stocking_notes','created_at'],
  remoteSort: true,
  pageSize: 50,
  sorters: [{ field: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/datagrid'); ?>',
    extraParams: { supplier_id: <?php echo $order->getSupplierId(); ?>, supplier_info: 1 },
    simpleSortMode: true,
    reader: { 
      root: 'parts',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});

var itemsStore = new Ext.data.JsonStore({
  fields: ['supplier_order_item', 'part_variant_id', 'part_id', 'name', 'sku', 'units',
           'quantity', 'received', 'lots', 'supplier_sku', 'supplier_notes', 'special_orders','location' ],
  autoLoad: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier_order/itemsDatagrid?id='.$order->getId()); ?>',
    reader: {
      root: 'items',
      idProperty: 'supplier_order_item'
    }
  }
});

var categoriesStore = new Ext.data.TreeStore({
  root: {
    text: 'All Categories',
    expanded: true
  },
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/categoriestree'); ?>',
    reader: {
      root: 'categories'
    }
  }
});

function filterPartGrid(field){
  grid = Ext.getCmp('parts_grid');
    if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  newval = field.getValue();
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    Ext.getCmp('parts_pager').moveFirst();
  }
}

var addwin_oldquantity = '';

function AddItemSelect(record,focusquantity,setquantity){
  additem_selected_data = record;
  ItemAddWin.show();

  //update info if different part selected than before.
  if (Ext.getCmp('addwin_varid').getValue() != record.part_variant_id)
  {
    Ext.getCmp('addwin_varid').setValue(record.part_variant_id);
    Ext.getCmp('addwin_name').el.dom.innerHTML = '<a href="<?php echo url_for('part/view?id=');?>'+record.id+'"><strong>'+record.name+'</strong></a>';
    Ext.getCmp('addwin_sku').el.dom.innerHTML = '<strong>'+record.sku+'</strong>';
    Ext.getCmp('addwin_supplier_sku').el.dom.innerHTML = '<strong>'+record.supplier_sku+'</strong>';
    Ext.getCmp('addwin_date_added').el.dom.innerHTML = '<strong>'+record.created_at+'</strong>';
    Ext.getCmp('addwin_stocking_notes').el.dom.innerHTML = record.stocking_notes;
    if (record.track_inventory){
      Ext.getCmp('addwin_avail').el.dom.innerHTML = '<strong>'+record.available+' '+record.units+'</strong>';
      Ext.getCmp('addwin_min').el.dom.innerHTML = '<strong>'+record.min_quantity+' '+record.units+'</strong>';
      Ext.getCmp('addwin_max').el.dom.innerHTML = '<strong>'+record.max_quantity+' '+record.units+'</strong>';
    } else {
      Ext.getCmp('addwin_avail').el.dom.innerHTML = '<strong>Not Tracked</strong>';
      Ext.getCmp('addwin_min').el.dom.innerHTML = '<strong>Not Tracked</strong>';
      Ext.getCmp('addwin_max').el.dom.innerHTML = '<strong>Not Tracked</strong>';
    }
    if (record.standard_package_qty != ''){
      Ext.getCmp('addwin_standard').el.dom.innerHTML = '<strong>'+record.standard_package_qty+' '+record.units+'</strong>';
    } else {
      Ext.getCmp('addwin_standard').el.dom.innerHTML = '';
    }
    if (!setquantity){
      setquantity = Math.max(1,parseFloat(record.max_quantity) - parseFloat(record.available));
      if (record.standard_package_qty){
          var pkgs = Math.max(1, Math.round(setquantity/parseFloat(record.standard_package_qty)));
          setquantity = pkgs * parseFloat(record.standard_package_qty);
      }
    }
    Ext.getCmp('addwin_varid').validate();
    Ext.getCmp('addwin_quantity').setValue(setquantity);
    addwin_oldquantity = '';
    Ext.getCmp('addwin_form').doLayout();
  }

  if (focusquantity){
    Ext.getCmp('addwin_quantity').focus(true, 100);
  }
}

Ext.define('Ext.ux.SupplierOrderEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Edit Supplier Order',
  autoShow: true,

  defaultFormConfig: {
    url: '/supplier_order/edit',

    formSuccess: function(form,action,obj){
      var myMask = new Ext.LoadMask(Ext.getBody(), { msg: "Order Updated. Refreshing Page..."});
      myMask.show();
      location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
    },

    fieldDefaults: { labelWidth: 100 },

    items: [{
      xtype: 'datefield',
      fieldLabel: 'Date Expected',
      name: 'date_expected',
      value: <?php echo ($order->getDateExpected() ? '\''.$order->getDateExpected('M j, Y').'\'' : 'null'); ?>,
      format: 'M j, Y',
      width: 250
    },{
      xtype: 'textfield',
      fieldLabel: 'PO Number',        
      name: 'purchase_order',
      value: '<?php echo $order->getPurchaseOrder(); ?>'
    },{
      xtype: 'textarea',
      fieldLabel: 'Notes',
      name: 'notes',
      value: '<?php echo str_replace("\n", "\\\n", addslashes($order->getNotes())); ?>',
      height: 60,
      width: 350
    }]
  }
});

var ItemAddWin = new Ext.Window({
  width: 400,
  id: 'addwin',
  modal: true,
  closable: false,
  closeAction: 'hide',
  title: 'Add Part to Order',
  resizable: false,
  items: new Ext.form.FormPanel({
    id: 'addwin_form',
    forceLayout: true,
    border: false,
    url: '<?php echo url_for('supplier_order/additem'); ?>',
    bodyStyle: 'padding: 10px 10px 10px 10px;',
    items: [{
      id: 'addwin_partid',
      xtype: 'hidden',
      name: 'id',
      value: '<?php echo $order->getId(); ?>'
    },{
      id: 'addwin_varid',
      xtype: 'textfield',
      allowBlank: false,
      name: 'part_variant_id',
      hidden: true
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Part Name',
      border: false,
      items: [{ id: 'addwin_name', height: 25, html: '--' }]
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Delta SKU',
      border: false,
      items: [{ id: 'addwin_sku', height: 25, html: '--' }]      
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Supplier SKU',
      border: false,
      items: [{ id: 'addwin_supplier_sku', height: 25, html: '--' }]
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Date Added',
      border: false,
      items: [{ id: 'addwin_date_added', height: 25, html: '--' }]      
    },{
      xtype: 'fieldset',
      layout: 'anchor', 
      title: 'Stocking Information', 
      items: [{ 
        xtype: 'fieldcontainer',
        fieldLabel: 'Min Quantity',
        border: false,
        items: [{ id: 'addwin_min', height: 25, html: '--' }]
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Max Quantity',
        border: false,
        items: [{ id: 'addwin_max', height: 25, html: '--' }]
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Qty Available',
        border: false,
        items: [{ id: 'addwin_avail', height: 25, html: '--' }]
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Standard Pkg Qty',
        border: false,
        items: [{ id: 'addwin_standard', height: 25, html: '--' }]        
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Stocking Notes',
        border: false,
        items: [{ id: 'addwin_stocking_notes', height: 50, html: '--' }]      
      }]
    },{
      xtype: 'numberfield',
      id: 'addwin_quantity',
      fieldLabel: 'Quantity to Order',
      name: 'quantity',
      value: 1,
      allowBlank: false,
      disableKeyFilter: true,
      minValue: 0.001,
      width: 200,
    }],

    buttons: [{
      text: 'Add',
      formBind: true,
      handler: function(){
        ItemAddWin.hide();
        Ext.getCmp('addwin_form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Item...',
          success: function(form,action){
            Ext.getCmp('addwin_form').getForm().reset();
            items_grid.getStore().load();
          },
          failure: function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add item to order. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ ItemAddWin.show(); },
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

var ItemAddFindWin = new Ext.Window({
  width: 650,
  height: 550,
  modal: true,
  id: 'addfindwin',
  closable: false,
  closeAction: 'hide',
  title: 'Add Part to Order',
  resizable: false,
  layout: 'fit',
  items: new Ext.grid.GridPanel({
    id: 'parts_grid',
    enableColumnMove: false,
    border: false,
    store: partsStore,
    emptyText: 'No Parts Found...',
    viewconfig: { stripeRows: true, loadMask: true },
    columns:[{
      header: "Part Name",
      dataIndex: 'name',
      hideable: false,
      sortable: true,
      flex: 1
    },{
      header: "SKU",
      dataIndex: 'sku',
      sortable: true,
      width: 80
    },{
      header: "Category",
      dataIndex: 'category_path',
      hideable: true,
      sortable: true,
      width: 160
    },{
      header: 'Min',
      dataIndex: 'min_quantity',
      sortable: true,
      align: 'center',
      width: 50
    },{
      header: 'Max',
      dataIndex: 'max_quantity',
      sortable: true,
      align: 'center',
      width: 50
    },{
      header: 'Cur',
      dataIndex: 'available',
      renderer: function(val,meta,r) {
          if (parseFloat(val) < parseFloat(r.data.min_quantity)){
            return '<span style="color:red;">' + val + '<\/span>';
          }else{
             return val;
          }
        },
      sortable: true,
      align: 'center',
      width: 50
    },{
      header: 'Added',
      dataIndex: 'created_at',
      sortable: false,
      align: 'center',
      width: 80      
    }],

    selModel: new Ext.selection.RowModel({
      listeners: {
        select: function(sm, record){
          if (sm.getCount() == 1){
            Ext.getCmp('addwin_nextbutton').setDisabled(false);
          } else {
            Ext.getCmp('addwin_nextbutton').setDisabled(true);
          }
        }
      }
    }),

    tbar: new Ext.Toolbar({
      height: 27,
      items: [' ',{
        xtype: 'textfield',
        enableKeyEvents: true,
        name: 'namesearch',
        id: 'namesearch',
        paramField: 'name',
        width: 120,
        emptyText: 'Search By Name...',
        listeners: { keyup: filterPartGrid, blur: filterPartGrid }
      },'-',{
        xtype: 'textfield',
        enableKeyEvents: true,
        name: 'skusearch',
        id: 'skusearch',
        paramField: 'sku',
        width: 120,
        emptyText: 'Search By SKU...',
        listeners: { keyup: filterPartGrid, blur: filterPartGrid }
      },'-',{
        xtype: 'treecombo',
        paramField: 'category_id',
        displayField: 'text',
        rootVisible: true,
        selectChildren: false,
        canSelectFolders: true,
        width: 135,
        emptyText: 'Select Category...',
        store: categoriesStore,
        listeners: { 
          change: filterPartGrid, 
          blur: filterPartGrid
        }
      }]
    }),

    bbar: new Ext.PagingToolbar({
      id: 'parts_pager',
      store: partsStore,
      displayInfo: true,
      displayMsg: 'Dispaying Parts {0} - {1} of {2}',
      emptyMsg: 'No Matching Parts Found'
    }),

    listeners: { 
      afterrender: function(){ 
        partsStore.load({params: {start: 0, limit: 50}}); 
      },
      itemdblclick: function(grid,idx){
        AddItemSelect(grid.getSelectionModel().getSelection()[0].data, true);
      }

    },

    buttons: [{
      text: 'Add Selected...',
      id: 'addwin_nextbutton',
      formBind: true,
      disabled: true,
      handler: function(){
        selected = Ext.getCmp('parts_grid').getSelectionModel().getSelection()[0];
        AddItemSelect(selected.data, true);
      }
    },{
      text: 'Close',
      handler:function(){
        this.findParentByType('window').hide();
        Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
      }
    }]
  })
});

var ItemEditWin = new Ext.Window({
  title: 'Edit Order Item',
  closable: false,
  width: 300,
  height: 150,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'itemeditform',
    url: '<?php echo url_for('supplier_order/edititem?id='.$order->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'hidden',
      id: 'itemeditid',
      name: 'supplier_order_item'
    },{
      xtype: 'fieldcontainer',
      name: 'name',
      fieldLabel: 'Part Name',
      border: false,
      items: [{ id: 'editpricing_name', html: '...', height: 25 }]
    },{
      xtype: 'numberfield',
      name: 'quantity',
      id: 'editquantity',
      fieldLabel: 'Quantity',
      allowBlank: false,
      minValue: 0.01,
      anchor: '-50'
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        ItemEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            items_grid.store.load();
            obj = Ext.JSON.decode(action.response.responseText);
          },
          failure:function(form,action){
            myMsg = '';
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save changes. Try again later!';
            }
            if (myMsg != ''){
              Ext.Msg.show({
                closable:false, 
                fn: function(){ ItemEditWin.show(); },
                modal: true,
                title: 'Oops',
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: myMsg
              });
            }
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

var items_grid = new Ext.grid.GridPanel({
  minHeight: 100,
  deferEmptyText: true,
  emptyText: 'No Items...',
  enableHdMenu: false,
  enableColumnMove: false,
  store: itemsStore,
  viewConfig: { loadMask: true },
  columns: [{
    xtype: 'rownumberer'
  },{
    header: "Part Name",
    dataIndex: 'name',
    renderer: function(val, meta, r){
      retval = '<strong>'+val+'</strong>';
      data = r.data;
      if (data.special_orders.length > 0){
        for (i=0; i < data.special_orders.length; i++){
          retval += '<div style="color: orange; padding-left: 10px;">Special Order for ';
          if (data.special_orders[i].workorder_id){
            retval += '<a href="/work_order/view/id/'+data.special_orders[i].workorder_id+'">Work Order #'+data.special_orders[i].workorder_id+'</a>';
          } else {
            retval += '<a href="/sale/view/id/'+data.special_orders[i].sale_id+'">Sale #'+data.special_orders[i].sale_id+'</a>';
          }
          retval += ' (Qty: '+data.special_orders[i].quantity+')</div>';
        }
      }
      if (data.lots.length > 0){
        for (i=0; i < data.lots.length; i++){
          retval += '<div style="color: #888; padding-left: 10px;">Received '+data.lots[i].quantity_received+' into Part Lot #'+data.lots[i].id+' on '+data.lots[i].date+'</div>';
        }
      }
      if (data.location != ''){
        retval += '<div style="color: #777;">Location: ' + data.location + '</div>';
      } else {
        retval += '<div style="color: #777;"><em>Part Location Not Set</em></div>';
      }
      return retval;
    },
    flex: 1
  },{
    header: "SKU",
    dataIndex: 'sku',
    width: 80
  },{
    header: "Requested",
    dataIndex: 'quantity',
    align: 'center',
    width: 80
  },{
    header: "Received",
    dataIndex: 'received',
    align: 'center',
    width: 80,
    renderer: function(val,meta,r){
      if (val == r.data.quantity){
        return '<span style="color: green">'+val+'</span>';
      } else {
        return '<span style="color: red">'+val+'</span>';
      }
    }
  }],

<?php if (!$order->getFinalized()): ?>

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Selected',
      id: 'editbutton',
      iconCls: 'inventory',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('orders_edit')): ?>
          ItemEditWin.show();
          form = Ext.getCmp('itemeditform');
          selected = items_grid.getSelectionModel().getSelection()[0];
          form.form.loadRecord(selected);
          Ext.getCmp('editquantity').focus(true, 200);
          Ext.getCmp('editpricing_name').el.dom.innerHTML = '<a href="<?php echo url_for('part/view?id='); ?>'+selected.data.part_id+'">'+selected.data.name+'</a>';
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to approve supplier orders');
        <?php endif; ?>
      }
    },'-',{
      text: 'Remove Selected Item',
      id: 'removebutton',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('orders_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this item?<br /><br />This will fail if this part has already been received or if there are any special orders which are based on this order item.',
            modal: true,
            title: 'Remove Order Item',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({title:'Please Wait',msg:'Removing Item, please wait...', closable: false});
                Ext.Ajax.request({
                  url: '<?php echo url_for('supplier_order/deleteitem?id='.$order->getId().'&supplier_order_item='); ?>'+items_grid.getSelectionModel().getSelection()[0].data.supplier_order_item,
                  method: 'POST',
                  success: function(){
                    Ext.Msg.hide();
                    items_grid.store.load();
                  },
                  failure: function(){
                    Ext.Msg.hide();
                    Ext.Msg.show({
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: 'Could not delete item!',
                      modal: true,
                      title: 'Error'
                    });
                  }
                });
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit supplier orders');
        <?php endif; ?>
      }
    },'->',{
      text: 'Add Item',
      iconCls: 'add',
      disabled: false,
      handler: function(){
        <?php if ($sf_user->hasCredential('orders_edit')): ?>
          ItemAddFindWin.show();
          Ext.getCmp('namesearch').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit supplier orders');
        <?php endif; ?>      }
    }]
  }),

  listeners: { 
    itemdblclick: function(grid,idx){
      Ext.getCmp('editbutton').handler();
    } 
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function (sm, record){
        Ext.getCmp('removebutton').setDisabled(sm.getCount() != 1);
        Ext.getCmp('editbutton').setDisabled(sm.getCount() != 1);
      }
    }
  }) 
<?php else: ?>
  listeners: {
    itemdblclick: function(grid,idx){
      Ext.Msg.alert('Cannot Edit', 'You cannot edit items once the order is marked as finalized.<br /><br />You can un-finalize the order to make changes, if you have permissions to do so.');
    }
  },

<?php endif; ?>
});

var SupplierEditWin = new Ext.Window({
  title: 'Edit Supplier Info',
  closable: false,
  width: 450,
  height: 400,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
      id: 'suppliereditform',
      url: '<?php echo url_for('supplier/edit?id='.$order->getSupplierId()); ?>',
      bodyStyle: 'padding:15px 10px 0 10px',
      fieldDefaults: { labelAlign: 'top' },
      items: [{
          layout: 'column',
          border: false,
          items: [{
              border: false,
              columnWidth: 0.5,
              layout: 'anchor',
              items: [{
                  xtype: 'textfield',
                  fieldLabel: 'Supplier Name',
                  allowBlank: false,
                  name: 'department_name',
                  anchor: '-25'
              },{
                  xtype: 'numberfield',
                  allowBlank: true,
                  minValue: 0,
                  allowDecimals: true,
                  forcePrecision: true,
                  fieldLabel: 'Credit Limit ($)',
                  name: 'credit_limit',
                  anchor: '-25'
              },{
                  xtype: 'textfield',
                  fieldLabel: 'Work Phone',
                  name: 'work_phone',
                  anchor: '-25'
              },{
                  xtype: 'textfield',
                  fieldLabel: 'Email',
                  vtype: 'email',
                  name: 'email',
                  anchor: '-25'
              }]
          },{
              border: false,
              columnWidth: 0.5,
              layout: 'anchor',
              items: [{
                  xtype: 'textfield',
                  fieldLabel: 'Account Number at Supplier',
                  name: 'account_number',
                  anchor: '-25'
              },{
                  fieldLabel: 'Payment Terms',
                  xtype: 'combo',
                  name: 'net_days',
                  editable: false,
                  forceSelection: true,
                  queryMode: 'local',
                  store: [
                    [0,'On Delivery'],
                    [5,'Net 5 Days'], 
                    [10,'Net 10 Days'], 
                    [15,'Net 15 Days'], 
                    [30,'Net 30 Days'], 
                    [45,'Net 45 Days'],
                    [60,'Net 60 Days'],
                    [90,'Net 90 Days'],
                    [99,'Other']
                  ],
                  triggerAction: 'all',
                  anchor: '-25'
              },{
                  xtype: 'textfield',
                  fieldLabel: 'Fax',
                  name: 'fax',
                  anchor: '-25'
              },{
                  xtype: 'textfield',
                  vtype: 'url',
                  fieldLabel: 'Website',
                  name: 'homepage',
                  anchor: '-25'
              }]
          }]
      },{
          fieldLabel: 'Supplier Notes',
          xtype: 'textarea',
          name: 'private_notes',
          anchor: '-25',
          height: 85
      }],

      buttons:[{
          text: 'Save',
          formBind: true,
          handler:function(){
              SupplierEditWin.hide();
              this.findParentByType('form').getForm().submit({
                  waitTitle: 'Please Wait',
                  waitMsg: 'Saving Changes...',
                  success:function(form,action){
                      var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
                      myMask.show();
                      location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
                  },
                  failure:function(form,action){
                      if(action.failureType == 'server'){
                        obj = Ext.JSON.decode(action.response.responseText);
                        myMsg = obj.errors.reason;
                      }else{
                        myMsg = 'Could not save changes. Try again later!';
                      }
                      Ext.Msg.show({
                        closable:false, 
                        fn: function(){ SupplierEditWin.show(); },
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



var receive_fields = new Array();
var receive_costs = new Array();

function receiveValidate(){
  valid = false;
  for (i = 0; i < receive_fields.length; i++){
    if (receive_fields[i].getValue() > 0){
      valid = true;
    }
    if (!receive_fields[i].isValid()){
      valid = false;
      break;
    }
  }
  Ext.getCmp('addreceive_submit').setDisabled(!valid);
}

var fillin_receivefields = function(){
  for (i=0; i<receive_fields.length; i++){
    receive_fields[i].render(receive_fields[i].renderToLater);
  }
  for (i=0; i<receive_costs.length; i++){
    receive_costs[i].render(receive_costs[i].renderToLater);
  }
  receiveValidate(); 
};


var AddReceiveWin = new Ext.Window({
  id: 'addreceive_win',
  width: 650,
  height: 500,
  layout: 'fit',
  title: 'Select Items to Receive',
  closable: false,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',

  items: new Ext.grid.GridPanel({
    id: 'addreceive_grid',
    enableHdMenu: false,
    enableColumnMove: false,
    clicksToEdit: 1,
    columnLines: true,
    viewConfig: { stripeRows: true },
    store: itemsStore,

    columns: [{
      header: "Part Name",
      dataIndex: 'name',
      renderer: function(val,meta,r){
        retval = '<strong>'+val+'</strong>';
        data = r.data;
        if (data.location != ''){
          retval += '<div style="color: #777;">Location: ' + data.location + '</div>';
        } else {
          retval += '<div style="color: #777;"><em>Part Location Not Set</em></div>';
        }
        return retval;
      },
      flex: 1
    },{
      header: "SKU",
      dataIndex: 'sku',
      width: 80
    },{
      header: "Ordered",
      dataIndex: 'quantity',
      align: 'center',
      width: 50
    },{
      header: "Received",
      dataIndex: 'received',
      align: 'center',
      width: 65
    },{
      header: "Receive Now",
      editable: true,
      dataIndex: 'quantity',
      align: 'center',
      renderer: function(val,meta,r){
        receivable = val - r.data.received;
        if (receivable > 0){
          editfield = new Ext.form.NumberField({
            name: 'received['+r.data.supplier_order_item+']',
            value: receivable,
            maxValue: receivable,
            renderToLater: 'receiveval-'+r.data.supplier_order_item,
            minValue: 0,
            allowDecimals: !r.data.has_serial_number,
            width: 60,
            enableKeyEvents: true,
            listeners: { 
              keyup: receiveValidate, 
              blur: receiveValidate
            }
          });
          receive_fields.push(editfield);
          return '<div id="receiveval-'+r.data.supplier_order_item+'"></div>';
        } else {
          return 'N/A';
        }
      },
      width: 80
    },{
      header: "Landed Unit Cost",
      editable: true,
      dataIndex: 'quantity',
      align: 'center',
      renderer: function(val,meta,r){
        receivable = val - r.data.received;
        if (receivable > 0){
          editcostfield = new Ext.form.NumberField({
            name: 'receivedcost['+r.data.supplier_order_item+']',
            renderToLater: 'receivecost-'+r.data.supplier_order_item,
            minValue: 0,
            forcePrecision: true,
            width: 90
          });
          receive_costs.push(editcostfield);
          return '<div id="receivecost-'+r.data.supplier_order_item+'"></div>';
        } else {
          return 'N/A';
        }
      },
      width: 100
    }],

    selModel: false,

    listeners: {
      afterrender: function(){
        Ext.defer(fillin_receivefields, 200);
      }
    }

  }),
  buttons: [{
    id: 'addreceive_submit',
    text: 'Receive Items ',
    disabled: true,
    handler: function(){
      Ext.getCmp('addreceive_win').hide();
      //compile selections
      sels = new Array();
      for (i = 0; i < receive_fields.length; i++){
        if (receive_fields[i].isValid() && (receive_fields[i].getValue() > 0)){
          sels.push(receive_fields[i].getName()+'='+receive_fields[i].getValue());
        }
      }
      for (i = 0; i < receive_costs.length; i++){
        if (receive_costs[i].isValid() && (receive_costs[i].getValue() > 0)){
          sels.push(receive_costs[i].getName()+'='+receive_costs[i].getValue());
        }
      }
      Ext.Msg.show({title:'Please Wait',msg:'Setting items as received, please wait...', closable: false});
      Ext.Ajax.request({
        url: '<?php echo url_for('supplier_order/receiveitems?id='.$order->getId()); ?>',
        method: 'POST',
        params: sels.join('&'),
        success: function(){
          barcodeListener.handleroverride = barcode_prefocus_handler;
          Ext.Msg.hide();
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
          myMask.show();
          location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
        },
        failure: function(){
          Ext.Msg.hide();
          Ext.Msg.show({
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK,
            msg: 'Could not receive all items!',
            modal: true,
            title: 'Error',
            fn: function(){
              Ext.getCmp('addreceive_win').show();
            }
          });
        }
      });
    }
  },{
    text: 'Cancel',
    handler: function(){
      barcodeListener.handleroverride = barcode_prefocus_handler;
      this.findParentByType('window').hide();
      for (i = 0; i < receive_fields.length; i++){
        receive_fields[i].reset();
      }
      for (i = 0; i < receive_costs.length; i++){
        receive_costs[i].reset();
      }
    }
  }]
});


var tb = new Ext.Toolbar({
  height: 27,
  items: [{
    text: 'Edit Order Details',
    iconCls: 'infoedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_edit')): ?>
        new Ext.ux.SupplierOrderEditWin({
          formConfig: { params: { id: <?php echo $order->getId(); ?>}}
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit supplier orders');
      <?php endif; ?>    
    }
  },'-',{
    text: 'Go to Supplier Details Page',
    iconCls: 'building',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_supplier_view')): ?>
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Supplier Info..."});
        myMask.show();
        location.href = '<?php echo url_for('supplier/view?id='.$order->getSupplierId()); ?>';
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to view supplier details');
      <?php endif; ?>
    }
  },'-',{
    text: 'Edit Supplier Info',
    iconCls: 'buildingedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_supplier_edit')): ?>
        SupplierEditWin.show();
        Ext.getCmp('suppliereditform').setDisabled(true);
        Ext.getCmp('suppliereditform').load({
          url: '<?php echo url_for('supplier/load?id='.$order->getSupplierId()); ?>',
          failure: function (form, action){
            Ext.Msg.alert("Load Failed", "Could not load supplier info for editing");
            Ext.getCmp('suppliereditform').setDisabled(false);
            SupplierEditWin.hide();
          },
          success: function (){
            Ext.getCmp('suppliereditform').setDisabled(false);
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit suppier details');
      <?php endif; ?>
    }
  <?php if ($order->getFinalized()): ?>
  },'-',{
    text: 'Print Order Details',
    iconCls: 'inventory',
    handler: function(){
      invwin = new Ext.Window({
        title: 'Order #<?php echo $order->getId(); ?> Invoice/Statement',
        modal: true,
        width: 875,
        height: 700,
        closeAction: 'destroy',
        layout: 'fit',

        items: [{
          xtype: 'component',
          id:'printframe',
          name: 'printframe',
          autoEl: {
            tag: 'iframe', 
            src:'<?php echo url_for('supplier_order/invoice?id='.$order->getId()); ?>'
          }
        }],
  
        tbar: new Ext.Toolbar({
          height: 27,
          items: [{
            text: 'Print',
            iconCls: 'print',
            handler: function(){
              Ext.getDom('printframe').contentWindow.focus();
              Ext.getDom('printframe').contentWindow.print();
            }
          },'-',{
            text: 'Save as PDF',
            iconCls: 'pdf',
            disabled: true
          },'-',{
            text: 'Save as XLS',
            iconCls: 'xls',
            disabled: true
          },'->',{
            text: 'Close',
            handler: function()
            {
              this.findParentByType('window').close();
            }
          }]
        })
      });
      invwin.show();
    }
  <?php endif; ?>
  },'-',{
    text: 'Delete Order',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_edit')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this order?<br /><br />Orders that have been received or contain special orders can\'t be deleted!',
          modal: true,
          title: 'Delete Order',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Msg.show({title:'Please Wait',msg:'Deleting Order, please wait...', closable: false});
              Ext.Ajax.request({
                url: '<?php echo url_for('supplier_order/delete?id='.$order->getId()); ?>',
                method: 'POST',
                success: function(){
                  Ext.Msg.hide();
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Redirecting..."});
                  myMask.show();
                  location.href = '<?php echo url_for('supplier_order/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.hide();
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete order!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete supplier orders');
      <?php endif; ?>
    }
  },'->',{
      text: 'View Change History',
      disabled: true,
      iconCls: 'history'
    }
  ]
});


//this only fires when return window is open
var itemreturnlistener = function(code, symbid){
  Ext.Msg.show({
    closable: false,
    msg: 'Looking Up Scanned Barcode...',
    title: 'Please Wait'
  });
  //query for the part
  Ext.Ajax.request({
    url: '/part/datagrid',
    params: { code: code, symbid: symbid },
    callback : function (opt,success,response){
      Ext.Msg.hide(); 
      if (success){
        data = Ext.decode(response.responseText);
        if (data && data.parts.length > 0){
          if (data.parts && data.parts.length == 1){
            selected_data = data.parts[0];
            //find item to return by variant_id
            qty_updated = false;
            store = items_grid.getStore();
            for (i=0; i<store.getCount(); i++){
              if (store.getAt(i).data.part_variant_id == selected_data.part_variant_id){
                found_field = return_fields[i];
                if ((parseFloat(found_field.getValue()) + 1) <= found_field.maxValue){
                  found_field.setValue(parseFloat(found_field.getValue()) + 1);
                  returnValidate();
                  qty_updated = true;
                  break;
                }
              }
            } 
            if (!qty_updated) {
              Ext.Msg.alert('Not Found', 'Could not find any parts in this order with that barcode,'+
                                         ' which were available to be received.');
            }
          } else {
            Ext.Msg.alert('Multiple Parts Found', 'Error: could not select part; matched multiple parts!');
          }      
        } else if (barcodeListener.misshandleroverride) {
          barcodeListener.misshandleroverride(data, code, symbid);
        }
      }
    }
  });
};

//this only fires if order is inactive
var additem_selected_data = false;
var itemaddlistener = function(code,symbid){
  Ext.Msg.show({
    closable: false,
    msg: 'Looking Up Scanned Barcode...',
    title: 'Please Wait'
  });
  //query for the part
  Ext.Ajax.request({
    url: '/part/datagrid',
    params: { code: code, symbid: symbid, supplier_id: <?php echo $order->getSupplierId(); ?>, supplier_info: 1 },
    callback : function (opt,success,response){
      Ext.Msg.hide(); 
      if (success){
        data = Ext.decode(response.responseText);
        if (data && data.parts.length > 0){
          if (data.parts && data.parts.length == 1){
            ItemEditWin.hide();
            additem_selected_data = data.parts[0];
            //PART FOUND 
              //if window is already open
              if (ItemAddWin.isVisible()){
                if (Ext.getCmp('addwin_varid').getValue() == additem_selected_data.part_variant_id){
                  //INCREASE QUANTITY
                  Ext.getCmp('addwin_quantity').setValue(parseFloat(Ext.getCmp('addwin_quantity').getValue()) + 1);
                } else {
                  //ADD EXISTING ITEM AND RE-OPEN WINDOW
                  ItemAddWin.hide();
                  Ext.getCmp('addwin_form').getForm().submit({
                    waitTitle: 'Please Wait',
                    waitMsg: 'Adding Previous Item...',
                    success: function(form,action){
                      ItemAddWin.show();
                      AddItemSelect(additem_selected_data, false);
                      items_grid.getStore().load();
                    },
                    failure: function(form,action){
                      if(action.failureType == 'server'){
                        obj = Ext.JSON.decode(action.response.responseText);
                        myMsg = obj.errors.reason;
                      }else{
                        myMsg = 'Could not add item to order. Try again later!';
                      }
                      Ext.Msg.show({
                        closable:false, 
                        fn: function(){ ItemAddWin.show(); },
                        modal: true,
                        title: 'Oops',
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: myMsg
                      });
                    }
                  });
                }
              } else {
                //OPEN WINDOW
                ItemAddFindWin.hide();
                ItemAddWin.show();
                AddItemSelect(additem_selected_data, false);
              }
          } else {
            Ext.Msg.alert('Multiple Parts Found', 'Error: could not select part; matched multiple parts!');
          }      
        } else if (barcodeListener.misshandleroverride) {
          barcodeListener.misshandleroverride(data, code, symbid);
        }
      }
    }
  });
};

actions_buttons = new Ext.Panel({
  width: 300,
  defaultType: 'button',
  defaults: { iconAlign: 'top' },
  border: false,
  items: [{
    text: 'Finalize',
    width: 90,
    height: 45,
    iconCls: 'approve',
    <?php if ($order->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_edit')): ?>
        Ext.Msg.show({title:'Please Wait',msg:'Updating order status, please wait...', closable: false});
        Ext.Ajax.request({
          url: '<?php echo url_for('supplier_order/changestatus?status=finalize&id='.$order->getId()); ?>',
          method: 'POST',
          success: function(response){
            Ext.Msg.hide();
            obj = Ext.JSON.decode(response.responseText);
            if (obj.success){
              var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
              myMask.show();
              location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
            } else {
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: obj.errors.reason,
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
              msg: 'Could not change order status!',
              modal: true,
              title: 'Error'
            });
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit supplier orders');
      <?php endif; ?>
    }
  },{
    text: 'Approve',
    width: 90,
    height: 45,
    iconCls: 'approve',
    <?php if ($order->getApproved() || !$order->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_approve')): ?>
        Ext.Msg.show({title:'Please Wait',msg:'Updating order status, please wait...', closable: false});
        Ext.Ajax.request({
          url: '<?php echo url_for('supplier_order/changestatus?status=approve&id='.$order->getId()); ?>',
          method: 'POST',
          success: function(){
            Ext.Msg.hide();
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
            myMask.show();
            location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
          },
          failure: function(){
            Ext.Msg.hide();
            Ext.Msg.show({
              icon: Ext.MessageBox.ERROR,
              buttons: Ext.MessageBox.OK,
              msg: 'Could not change order status!',
              modal: true,
              title: 'Error'
            });
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to approve supplier orders');
      <?php endif; ?>
    }
  },{
    text: 'Un-Finalize',
    width: 90,
    height: 45,
    iconCls: 'reject',
    <?php if (!$order->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ((!$order->getApproved() && $sf_user->hasCredential('orders_edit')) || (!$order->getSent() && $sf_user->hasCredential('orders_approve')) || $sf_user->hasCredential('orders_unfinalize')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to unfinalize this order?',
          modal: true,
          title: 'Un-Finalize Order',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Msg.show({title:'Please Wait',msg:'Updating order status, please wait...', closable: false});
              Ext.Ajax.request({
                url: '<?php echo url_for('supplier_order/changestatus?status=unfinalize&id='.$order->getId()); ?>',
                method: 'POST',
                success: function(){
                  Ext.Msg.hide();
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
                  myMask.show();
                  location.href = '<?php echo url_for('supplier_order/view?id='.$order->getId()); ?>';
                },
                failure: function(){
                  Ext.Msg.hide();
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not change order status!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to un-finalize supplier orders');
      <?php endif; ?>
    }
  },{
    text: 'Set as Sent',
    width: 90,
    height: 45,
    iconCls: 'ship',
    <?php if (!$order->getApproved() || $order->getSent()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_send')): ?>
        new Ext.ux.SupplierOrderEditWin({
          title: 'Set Order as Sent',

          formConfig: {
            submitButtonText: 'Set as Sent',
            url: '<?php echo url_for('supplier_order/changestatus?status=send'); ?>',
            params: { id: <?php echo $order->getId(); ?>}
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to set an order as sent');
      <?php endif; ?>
    }
  },{
    text: 'Receive Order',
    width: 90,
    height: 45,
    iconCls: 'ship',
    <?php if (!$order->getSent() || $order->getReceivedAll()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('orders_receive')): ?>
        AddReceiveWin.show();
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to receive a supplier order');
      <?php endif; ?>
    }
  }]
});

Ext.onReady(function(){
  <?php if (!$order->getFinalized()): ?>
    barcodeListener.handleroverride = itemaddlistener;
  <?php endif; ?>

  tb.render('view-toolbar');
  items_grid.render('view-grid');
  actions_buttons.render('actions-buttons');
});
</script>
