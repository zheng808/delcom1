<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Customer List', 'customer/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-person">Customer: <?php echo $customer; ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <?php
      $append_info = false;
      if ($customer->getPstNumber())
      {
        $append_info = '<tr><td class="label">PST Number:</td><td>'.$customer->getPstNumber().'</td><td colspan="2" class="label"></td></tr>';
      }
      include_partial('wfCRMPlugin/crm_show', 
        array(
          'contact' => $customer->getCRM(),
          'include_title' => false,
          'append_to_table' => $append_info
        )
      );
    ?>

    <div id="view-tabs"></div>

  </div>
</div>


<script type="text/javascript">

var boatsStore = new Ext.data.JsonStore({
  fields: ['id', 'name', 'make', 'model', 'lastworkorder'],
  remoteSort: true,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/boatsdatagrid?customer_id='.$customer->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'boats',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var boatmodelsStore = new Ext.data.JsonStore({
  fields: ['info'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/boattypes'); ?>',
    extraParams: { modelonly: '1' },
    reader: { 
      root: 'types',
      totalProperty: 'totalCount'
    }
  }
});

var boatmakesStore = new Ext.data.JsonStore({
  fields: ['info'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/boattypes'); ?>',
    extraParams: { makeonly: '1' },
    reader: {
      root: 'types',
      totalProperty: 'totalCount'
    }
  }
});

var workordersStore =  new Ext.data.JsonStore({
  fields: ['id', 'boat', 'date', 'status'],
  remoteSort: true,
  pagesize: 25,
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid?customer_id='.$customer->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var salesStore = new Ext.data.JsonStore({
  fields: ['id', 'date', 'status'],
  remoteSort: true,
  pagesize: 25,
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('sale/datagrid?customer_id='.$customer->getId()); ?>',
    reader: {
      root: 'sales',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});

catsStore = new Ext.data.JsonStore({
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

var WorkOrderAddWin = new Ext.Window({
  title: 'Create Work Order',
  closable: false,
  width: 350,
  height: 300,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',
  items: new Ext.FormPanel({
    id: 'workorderaddform',
    url: '<?php echo url_for('work_order/add'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'hidden',
      name: 'customer_id',
      value: '<?php echo $customer->getId(); ?>'
    },{
      xtype: 'combo',
      id: 'boatfield',
      fieldLabel: 'Boat',
      name: 'customer_boat_id',
      forceSelection: true,
      editable: false,
      allowBlank: false,
      valueField: 'id',
      displayField: 'name',
      triggerAction: 'all',
      emptyText: 'Select Customer Boat...',
      minChars: 1,
      store: boatsStore,
      anchor: '-25',
      queryMode: 'local'
    },{
      xtype: 'combo',
      id: 'workorderadd_category',
      fieldLabel: 'Category',
      name: 'workorder_category_id',
      anchor: '-25',
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      displayField: 'name',
      valueField: 'id',
      triggerAction: 'all',
      store: catsStore,
      listConfig: { minWidth: 200 }
    },{
      xtype: 'combo',
      fieldLabel: 'Company',
      name: 'for_rigging',
      anchor: '-25',
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      allowBlank: false,
      store: [[0,'Delta Services'],[1,'Delta Rigging']],
      value: 0,
      triggerAction: 'all'
    },{
      xtype: 'combo',
      fieldLabel: 'Status',
      name: 'status',
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      store: ['In Progress', 'Estimate'],
      value: 'Estimate',
      triggerAction: 'all',
      anchor: '-25'
    },{
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
      name: 'moorage_surcharge',
      minValue: 0,
      maxValue: 100,
      anchor: '50%',
      value: 0
    },{
      id: 'addwo_taxable_pst',
      xtype: 'checkbox',
      fieldLabel: 'PST Taxable',
      name: 'taxable_pst',
      value: 1,
      height: 22,
      checked: <?php 
        $addr = $customer->getWfCRM()->getWfCRMAddresss();
        echo ($addr && isset($addr[0]) && $addr[0]->getCountry() != '' && $addr[0]->getCountry() != 'CA' ? 'false' : 'true'); 
      ?>
    },{
      id: 'addwo_taxable_gst',
      xtype: 'checkbox',
      fieldLabel: 'GST Taxable',
      name: 'taxable_gst',
      value: 1,
      height: 22,
      checked: <?php 
        $addr = $customer->getWfCRM()->getWfCRMAddresss();
        echo ($addr && isset($addr[0]) && $addr[0]->getCountry() != '' && $addr[0]->getCountry() != 'CA' ? 'false' : 'true'); 
      ?>
    }],
  
    buttons:[{
      text: 'Add',
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
});

var customer_boats = new Ext.grid.GridPanel({
  title: 'Boats',
  store: boatsStore,
  enableColumnMove: false,

  columns:[{
    header: "Boat Name",
    dataIndex: 'name',
    hideable: false,
    sortable: true,
    flex: 1
  },{
    header: "Make",
    dataIndex: 'make',
    sortable: true,
    width: 100
  },{
    header: "Model",
    dataIndex: 'model',
    sortable: true,
    width: 100
  },{
    header: "Last Workorder",
    dataIndex: 'lastworkorder',
    sortable: true,
    align: 'center',
    width: 120
  }],

  viewConfig: { stripeRows: true, loadMask: true },

  bbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add a New Boat',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('customer_edit')): ?>
          new Ext.ux.BoatEditWin({
            customer_id: <?php echo $customer->getId(); ?>,
            formConfig: { formSuccess: function(){ boatsStore.load(); } }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to edit customer info');
        <?php endif; ?>
      }
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        boatsStore.load();
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Boat Information..."});
        myMask.show();
        location.href= '<?php echo url_for('customer/boat?id='); ?>' + record.data.id ;
      }
    }
  })

});

var customer_workorders = new Ext.grid.GridPanel({
  title: 'Work Orders',
  store: workordersStore,
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
    header: "Start Date",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 120
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    width: 150
  },{
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    flex: 1
  }],

  bbar: new Ext.PagingToolbar({
    store: workordersStore,
    emptyMsg: 'No Work Orders for this Customer',
    items: ['->',{
      text: 'Create a New Work Order',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_estimates')): ?>
          if (boatsStore.getCount() === 0){
            Ext.Msg.alert("No Boats are defined! Create a boat first for this customer then try again.");
          } else {
            WorkOrderAddWin.show();
            Ext.getCmp('workorderadd_category').setValue('-1');
            if (boatsStore.getCount() == 1){
              Ext.getCmp('boatfield').setValue(boatsStore.getAt(0).data.id);
            } else {
              Ext.getCmp('boatfield').onTriggerClick();
            }
          }
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to create work orders');
        <?php endif; ?>
      }
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        workordersStore.load({params:{start:0, limit:25}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential('workorder_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Information..."});
          myMask.show();
          location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to view work orders.');
        <?php endif; ?>
      }
    }
  })
});

var customer_sales =  new Ext.grid.GridPanel({
  title: 'Parts Sales',
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
    width: 120
  },{
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    flex: 1
  }],

  bbar: new Ext.PagingToolbar({
    store: salesStore,
    emptyMsg: 'No Sales for this Customer',
    items: ['->',{
      text: 'Create a New Sale',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Create a New Parts Sale for this Customer?',
            modal: true,
            title: 'Create Sale',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({
                  msg: 'Creating Sale...',
                  width: 300,
                  wait: true
                });
                Ext.Ajax.request({
                  url: '<?php echo url_for('sale/add?customer_id='.$customer->getId()); ?>',
                  method: 'POST',
                  success:function(response){
                    Ext.Msg.hide();
                    var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Sale..."});
                    myMask.show();
                    obj = Ext.JSON.decode(response.responseText);
                    location.href = '<?php echo url_for('sale/view?id='); ?>' + obj.newid;
                  },
                  failure:function(response){
                    if(response.responseText != ''){
                      obj = Ext.JSON.decode(response.responseText);
                      myMsg = obj.errors.reason;
                    }else{
                      myMsg = 'Could not add sale. Try again later!';
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
          Ext.Msg.alert('Permission Denied','Your user does not have permission to create customer sales');
        <?php endif; ?>
      }
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        salesStore.load({params:{start:0, limit:25}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential('sales_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Sale Information..."});
          myMask.show();
          location.href= '<?php echo url_for('sale/view?id='); ?>' + record.data.id;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to view parts sales');
        <?php endif; ?>
      }
    }
  })
});

var CustomerEditWin = new Ext.Window({
  title: 'Edit Customer Info',
  closable: false,
  width: 700,
  height: 400,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'customereditform',
    url: '<?php echo url_for('customer/edit?id='.$customer->getId()); ?>',
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
              xtype: 'fieldcontainer',
              fieldLabel: 'Customer Type',
              layout: 'hbox',
              anchor: '-25',
              items: [{
                id: 'customeredit_custtype',                
                xtype: 'hidden',
                name: 'custtype',
                value: 'Individual',
                listeners: { change: function(field){
                  selBtn = field.next('button[valueField='+field.value+']');
                  if (!selBtn.pressed) selBtn.toggle(true);
                }}
              },{
                xtype: 'button',
                toggleGroup: 'newcusttype',
                allowDepress: false,
                pressed: true,
                isDefault: true,
                flex: 1,
                cls: 'buttongroup-first',
                text: 'Individual',
                valueField: 'Individual',
                listeners: { toggle: function(btn, pressed){
                  if (pressed) btn.prev('hidden').setValue(btn.valueField);
                  homeph_panel = Ext.getCmp('customeredit_homephone_panel');
                  homeph = Ext.getCmp('customeredit_homephone');
                  name_panel = Ext.getCmp('customeredit_name_panel');
                  lastname = Ext.getCmp('customeredit_last_name');
                  firstname = Ext.getCmp('customeredit_first_name');
                  company_panel = Ext.getCmp('customeredit_company_panel');
                  companyname = Ext.getCmp('customeredit_company');

                  if (pressed){
                    homeph_panel.setVisible(true);
                    homeph.setDisabled(false);

                    company_panel.setVisible(false);
                    companyname.clearInvalid();
                    companyname.setDisabled(true);

                    name_panel.setVisible(true);
                    firstname.setDisabled(false);
                    lastname.setDisabled(false);
                  } else {
                    homeph_panel.setVisible(false);
                    homeph.clearInvalid();
                    homeph.setDisabled(true);

                    name_panel.setVisible(false);
                    firstname.clearInvalid();
                    lastname.clearInvalid();
                    firstname.setDisabled(true);
                    lastname.setDisabled(true);

                    company_panel.setVisible(true);
                    companyname.setDisabled(false);
                  }      
                }}
              },{
                xtype: 'button',
                toggleGroup: 'newcusttype',
                allowDepress: false,
                flex: 1,
                cls: 'buttongroup-last',
                text: 'Company',
                valueField: 'Company',
                listeners: { toggle: function(btn, pressed){
                  if (pressed) btn.prev('hidden').setValue(btn.valueField);
                }}
              }]  
            },{
              id: 'customeredit_name_panel',
              xtype: 'container',
              border: false,
              hidden: false,
              layout: 'anchor',
              items: [{
                id: 'customeredit_first_name',
                xtype: 'textfield',
                fieldLabel: 'First Name',
                allowBlank: false,
                name: 'first_name',
                anchor: '-25'
              },{
                id: 'customeredit_last_name',
                xtype: 'textfield',
                fieldLabel: 'Last Name',
                allowBlank: false,
                name: 'last_name',
                anchor: '-25'
              }]
            },{
              id: 'customeredit_company_panel',
              xtype: 'container',
              border: false,
              hidden: true,
              layout: 'anchor',
              items: [{
                id: 'customeredit_company',
                xtype: 'textfield',
                fieldLabel: 'Company Name',
                allowBlank: false,
                disabled: true,
                name: 'company_name',
                anchor: '-25'
              }]
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
              fieldLabel: 'Work Phone',
              name: 'work_phone',
              anchor: '-25'
            },{
              xtype: 'textfield',
              fieldLabel: 'Mobile Phone',
              name: 'mobile_phone',
              anchor: '-25'
            },{
              id: 'customeredit_homephone_panel',
              xtype: 'container',
              border: false,
              hidden: false,
              layout: 'anchor',
              items: [{
                id: 'customeredit_homephone',
                xtype: 'textfield',
                fieldLabel: 'Home Phone',
                name: 'home_phone',
                anchor: '-25'
              }]
            },{
              xtype: 'textfield',
              fieldLabel: 'Fax',
              name: 'fax',
              anchor: '-25'
            }]
          }],
        },{
          fieldLabel: 'Customer Notes',
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
        },{
          fieldLabel: 'PST Number',
          xtype: 'textfield',
          name: 'pst_number',
          anchor: '-25'
        }]
      }]
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        CustomerEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
            myMask.show();
            location.href = '<?php echo url_for('customer/view?id='.$customer->getId()); ?>';
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
              fn: function(){ CustomerEditWin.show(); },
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
    text: 'Update Customer Information',
    iconCls: 'personedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('customer_edit')): ?>
        new Ext.ux.CustomerEditWin({
          formConfig: {
            params: { id: <?php echo $customer->getId(); ?>},
            autoLoadUrl: '<?php echo url_for('customer/load?id='.$customer->getId()); ?>',
            formSuccess: function(){
              var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
              myMask.show();
              location.href = '<?php echo url_for('customer/view?id='.$customer->getId()); ?>';
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user does not have permission to edit customers');
      <?php endif; ?>
    }
  },'-',{
    text: 'Edit Login/Password',
    disabled: true,
    iconCls: 'personedit'
  },'-',{
    text: 'Delete Customer',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('customer_edit')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this customer?<br /><br />Customers with past work orders or part sales cannot be properly deleted.',
          modal: true,
          title: 'Delete Customer',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('customer/delete?id='.$customer->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg: "Deleting Customer..."});
                  myMask.show();
                  location.href = '<?php echo url_for('customer/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete customer!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user does not have permission to edit customers');
      <?php endif; ?>
    }
  },'->',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }]
});

var tabs = new Ext.TabPanel({
  activeTab: 0,
  height:300,
  plain:true,
  padding: '15 0 0 0',
  items:[
    customer_boats,
    customer_workorders,
    customer_sales
  ]
});


Ext.onReady(function(){

    tb.render('view-toolbar');
    tabs.render('view-tabs');

});
</script>
