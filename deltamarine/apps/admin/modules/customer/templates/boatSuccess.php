<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Customer Details', 'customer/view?id='.$customer->getId(),
      array('class' => 'button tabbutton'));
    echo link_to('Return to Customer List', 'customer/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-boat">Boat: <?php echo $boat; ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">

    <table class="infotable">
      <tr>
        <td class="label">Boat Owner:</td>
        <td><?php echo link_to($customer, 'customer/view?id='.$customer->getId()); ?></td>
        <td class="label">Boat Name:</td>
        <td><?php echo $boat->getName(); ?></td>
      </tr>
      <tr>
        <td class="label">Boat Make:</td>
        <td><?php echo $boat->getMake(); ?></td>
        <td class="label">Boat Model:</td>
        <td><?php echo $boat->getModel(); ?></td>
      </tr>
      <tr>
        <td class="label">Serial Number:</td>
        <td><?php echo $boat->getSerialNumber(); ?></td>
        <td class="label">Registration:</td>
        <td><?php echo $boat->getRegistration(); ?></td>
      </tr>
      <?php if ($boat->getNotes()): ?>
        <tr>
          <td class="label">Boat Notes:</td>
          <td colspan="3"><?php echo nl2br($boat->getNotes()); ?>
        </tr>
      <?php endif; ?>
      <?php if ($boat->getFire_Date()): ?>
        <tr>
          <td class="label">Fire Certification Date:</td>
          <td colspan="3"><?php echo nl2br($boat->getFire_Date()); ?>
        </tr>
      <?php endif; ?>
    </table>

    <div id="view-tabs"></div>

  </div>
</div>


<script type="text/javascript">

var workordersStore = new Ext.data.JsonStore({
  fields: ['id', 'date', 'status', 'haulout'],
  pagesize: 50,
  remoteSort: true,
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid?boat_id='.$boat->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
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
  height: 275,
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
      xtype: 'hidden',
      name: 'customer_boat_id',
      value: '<?php echo $boat->getId(); ?>'
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
      value: 0,
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

var boat_workorders = new Ext.grid.GridPanel({
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
    width: 40
  },{
    header: "Start Date",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 120
  },{
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    flex: 1,
    width: 120
  },{
    header: "Haulout Date",
    dataIndex: 'haulout',
    sortable: true,
    flex: 1,
    width: 80
  }],

  bbar: new Ext.PagingToolbar({
    store: workordersStore,
    emptyMsg: 'No Work Orders for this Boat',
    items: ['->',{
      text: 'Create a New Work Order',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_estimates')): ?>
          WorkOrderAddWin.show();
          Ext.getCmp('workorderadd_category').setValue('-1');
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to create a work order');
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
          Ext.Msg.alert('Permission Denied','Your user does not have permission to view work orders');
        <?php endif; ?>
      }
    }
  })
});
//add new fire date
var BoatEditWin = new Ext.Window({
  title: 'Edit Boat Info',
  closable: false,
  width: 350,
  height: 450,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'boateditform',
    monitorValid: true,
    url: '<?php echo url_for('customer/boatEdit?id='.$boat->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'top' },
    items: [{
      xtype: 'textfield',
      fieldLabel: 'Boat Name',
      allowBlank: false,
      name: 'name',
      anchor: '-75'
    },{
      xtype: 'textfield',
      fieldLabel: 'Make or Manufacturer',
      name: 'make',
      anchor: '-75'
    },{
      xtype: 'textfield',
      fieldLabel: 'Boat Model and Length',
      name: 'model',
      anchor: '-75'
    },{
      xtype: 'textfield',
      fieldLabel: 'Boat/Hull Serial Number',
      name: 'serial_number',
      anchor: '-75'
    },{
      xtype: 'textfield',
      fieldLabel: 'Registration #',
      name: 'registration',
      anchor: '-75'
    },{
      xtype: 'textarea',
      name: 'notes',
      fieldLabel: 'Boat Notes',
      anchor: '-25',
      height: 85
    },{
      xtype: 'datefield',
      name: 'fire_date',
      format: 'Y-m-d H:i:s',
      fieldLabel: ' Fire /Certification Date',
      anchor: '-1',
      height: 40
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        BoatEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
            myMask.show();
            location.href = '<?php echo url_for('customer/boat?id='.$boat->getId()); ?>';
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
              fn: function(){ BoatEditWin.show(); },
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
    text: 'Update Boat Information',
    iconCls: 'personedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('customer_edit')): ?>
        BoatEditWin.show();
        Ext.getCmp('boateditform').setDisabled(true);
        Ext.getCmp('boateditform').load({
          url: '<?php echo url_for('customer/boatLoad?id='.$boat->getId()); ?>',
          failure: function (form, action){
            Ext.Msg.alert("Load Failed", "Could not load boat info for editing");
            Ext.getCmp('boateditform').setDisabled(false);
            BoatEditWin.hide();
          },
          success: function (){
            Ext.getCmp('boateditform').setDisabled(false);
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user does not have permission to edit customer information');
      <?php endif; ?>
    }
  },'-',{
    text: 'Delete Boat',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('customer_edit')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this boat?<br /><br />Boats with past work orders cannot be deleted.',
          modal: true,
          title: 'Delete Boat',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('customer/boatDelete?id='.$boat->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg: "Deleting Boat..."});
                  myMask.show();
                  location.href = '<?php echo url_for('customer/view?id='.$customer->getId()); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete boat!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user does not have permission to edit customer information');
      <?php endif; ?>
}
  },'->',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }]
});

var view_tabs = new Ext.TabPanel({
  activeTab: 0,
  height:300,
  plain:true,
  padding: '15 0 0 0',
  items:[
    boat_workorders
  ]
});


Ext.onReady(function(){

    tb.render('view-toolbar');
    view_tabs.render('view-tabs');

});
</script>
