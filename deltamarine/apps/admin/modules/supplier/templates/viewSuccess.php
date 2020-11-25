<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Supplier List', 'supplier/index', 
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-company">Supplier: <?php echo $supplier; ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <table class="infotable">
      <tr>
        <td class="label">Credit Limit:</td>
        <td><?php echo (!$supplier->getCreditLimit() ? 'Not Set' : number_format($supplier->getCreditLimit(), 2)) ?></td>
        <td class="label">Payment Terms:</td>
        <td><?php echo ($supplier->getNetDays() == 0 ? 'On Delivery' : 'Net '.$supplier->getNetDays().' Days') ?></td>
      </tr><tr>
        <td class="label">Supplier Acct #:</td>
        <td><?php echo $supplier->getAccountNumber(); ?></td>
      </tr>
    </table>

    <?php 
      include_partial('wfCRMPlugin/crm_show', array('contact' => $supplier->getCRM(),
        'include_title' => false));
    ?>

    <div id="view-tabs"></div>

  </div>
</div>


<?php include_partial('wfCRMPlugin/crm_company_ext_tree', array('credential' => 'parts_supplier_edit', 'contact' => $supplier->getCRM())); ?>


<script type="text/javascript">

var partsStore = new Ext.data.JsonStore({
  fields: ['part_id', 'name', 'sku', 'onhand', 'min_quantity','active'],
  remoteSort: true,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/datagrid?supplier_id='.$supplier->getId()); ?>',
    extraParams: { 
      show_inactive: '1'
    },
    simpleSortMode: true,
    reader: {
      root: 'parts',
      totalProperty: 'totalCount',
      idProperty: 'part_id'
    }
  }
});

var ordersStore =  new Ext.data.JsonStore({
  fields: ['id', 'date', 'status', 'received'],
  remoteSort: true,
  sorters: [{ property: 'status', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier_order/datagrid?supplier_id='.$supplier->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'orders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var supplier_orders = new Ext.grid.GridPanel({
  title: 'Orders made from this Supplier',
  store: ordersStore,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[
  {
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
    align: 'center',
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

  bbar: new Ext.PagingToolbar({
    pagesize: 50,
    store: ordersStore,
    emptyMsg: 'No Orders for this Supplier',
    items: ['->',{
      text: 'Create a New Order',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('orders_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Create a New Order for this Supplier?',
            modal: true,
            title: 'Create Order',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({
                  msg: 'Creating Order...',
                  width: 300,
                  wait: true
                });
                Ext.Ajax.request({
                  url: '<?php echo url_for('supplier_order/add?supplier_id='.$supplier->getId()); ?>',
                  method: 'POST',
                  success:function(response){
                    Ext.Msg.hide();
                    var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Order..."});
                    myMask.show();
                    obj = Ext.JSON.decode(response.responseText);
                    location.href = '<?php echo url_for('supplier_order/view?id='); ?>' + obj.newid;
                  },
                  failure:function(response){
                    if(response.responseText != ''){
                      obj = Ext.JSON.decode(response.responseText);
                      myMsg = obj.errors.reason;
                    }else{
                      myMsg = 'Could not add order. Try again later!';
                    }
                    Ext.Msg.show({
                      closable:false, 
                      modal: true,
                      title: 'Oops',
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: myMsg
                    });
                  }
                });
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to create a supplier order');
        <?php endif; ?>
      }
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        ordersStore.load({params:{start:0, limit:25}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential('orders_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Supplier Order Information..."});
          myMask.show();
          location.href= '<?php echo url_for('supplier_order/view?id='); ?>' + record.data.id ;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to view supplier orders');
        <?php endif; ?>
      }
    }
  }) 
}); 

var supplier_parts = new Ext.grid.GridPanel({
  title: 'Parts from this Supplier',
  store: partsStore,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[{
    header: "Part Name",
    dataIndex: 'name',
    hideable: false,
    sortable: true,
    flex: 3,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if (record.get('active') != 1) {
          return '<span style="color: red; text-decoration: line-through;">' + value + '</span>';
      } else {
        return value;
      }
    }
  },{
    header: "SKU",
    width: 100,
    dataIndex: 'sku',
    sortable: true,
    flex: 1
  },{
    header: "Qty",
    dataIndex: 'onhand',
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
      if (value < parseFloat(record.data.min_quantity)){
        return '<span style="color:red;">' + value + '<\/span>';
      }else{
         return value;
      }
    },
    width: 60,
    align: 'center',
    sortable: true
  },{
    header: "Min",
    dataIndex: 'min_quantity',
    width: 60,
    xtype: 'numbercolumn',
    format: 0,
    align: 'center',
    sortable: true
  }],

  bbar: new Ext.PagingToolbar({
    pagesize: 50,
    store: partsStore,
    displayInfo: true,
    displayMsg: 'Displaying Parts {0} - {1} of {2}',
    emptyMsg: 'No Parts for this Supplier'
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        partsStore.load({params:{start:0, limit:50}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential('parts_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Part Information..."});
          myMask.show();
          location.href= '<?php echo url_for('part/view?id='); ?>' + record.data.part_id ;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to view parts information');
        <?php endif; ?>
      }
    }
  }) 
}); 

var SupplierEditWin = new Ext.Window({
  title: 'Edit Supplier Info',
  closable: false,
  width: 700,
  height: 400,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'suppliereditform',
    url: '<?php echo url_for('supplier/edit?id='.$supplier->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'top' },
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.6,
        layout: 'anchor',
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
              xtype: 'combo',
              name: 'net_days',
              fieldLabel: 'Payment Terms',
              valueField: 'value',
              displayField: 'text',
              editable: false,
              forceSelection: true,
              value: 0,
              queryMode: 'local',
              store: new Ext.data.ArrayStore({
                fields: ['value', 'text'],
                data: [
                  [0,'On Delivery'],
                  [5,'Net 5 Days'], 
                  [10,'Net 10 Days'], 
                  [15,'Net 15 Days'], 
                  [30,'Net 30 Days'], 
                  [45,'Net 45 Days'],
                  [60,'Net 60 Days'],
                  [90,'Net 90 Days'],
                  [99,'Other']
                  ]
              }),
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
        }]
      },{
        border: false,
        columnWidth: 0.4,
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
          fieldLabel: 'Address Line 1',
          name: 'address_line1',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Address Line 2',
          name: 'address_line2',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Town/City',
          name: 'address_city',
          anchor: '-25'
        },{
          layout: 'column',
          border: false,
          items: [{
            layout: 'anchor',
            columnWidth: 0.3,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Prov.',
              maxLength: 3,
              name: 'address_region',
              anchor: '-25'
            }]
          },{
            layout: 'anchor',
            columnWidth: 0.4,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Postal',
              name: 'address_postal',
              maxLength: 16,
              anchor: '-25'
            }]
          },{
            layout: 'anchor',
            columnWidth: 0.3,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Country',
              name: 'address_country',
              value: 'CA',
              maxLength: 2,
              anchor: '-25'
            }]
          }]
        }]
      }]
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
                    var myMask = new Ext.LoadMask(Ext.getBody(), {
                      msg: "Reloading Page"});
                    myMask.show();
                    location.href = '<?php echo url_for('supplier/view?id='.$supplier->getId()); ?>';
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

var tb = new Ext.Toolbar({
  height: 27,
  items: [{
    text: 'Update Supplier Information',
    iconCls: 'buildingedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_supplier_edit')): ?>
        SupplierEditWin.show();
        Ext.getCmp('suppliereditform').setDisabled(true);
        Ext.getCmp('suppliereditform').load({
          url: '<?php echo url_for('supplier/load?id='.$supplier->getId()); ?>',
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
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit supplier information');
      <?php endif; ?>    }
  },'-',{
    text: 'Delete Supplier',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_supplier_edit')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this supplier?<br /><br />All parts from this supplier will still exist, however this supplier will be removed from them. All record of past supplier orders will also be lost.',
          modal: true,
          title: 'Delete Supplier',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('supplier/delete?id='.$supplier->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg: "Deleting Supplier..."});
                  myMask.show();
                  location.href = '<?php echo url_for('supplier/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete supplier!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission delete a supplier');
      <?php endif; ?>
    }
  },'-',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }]
});

var supplier_tabs = new Ext.TabPanel({
    activeTab: 1,
    height:300,
    padding: '15 0 0 0',    
    plain:true,
    items:[
      { title: 'Company Contacts', layout: 'fit', items: crm_companytree, border: false, disabled: true },
      supplier_parts,
      supplier_orders
    ]
});

Ext.onReady(function(){
  tb.render('view-toolbar');
  supplier_tabs.render('view-tabs');
});


</script>
