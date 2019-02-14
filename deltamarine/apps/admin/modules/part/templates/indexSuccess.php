<?php
  $belowmin = ($sf_request->getParameter('filter') == 'belowmin');
  $onhold = ($sf_request->getParameter('filter') == 'onhold');
  $ondupe = ($sf_request->getParameter('filter') == 'ondupe');
?>

<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-part" id="gridtitle"><?php echo ($onhold ? 'Parts On Hold' : ($ondupe ? 'Duplicate SKUs' : 'Parts')); ?></h1>
  <div id="index-tabs"></div>
</div>

<script type="text/javascript">
var global_code = null;
var is_resetting = false;

var partVariantId = null;
var partAvailable = null;
var partName = null;
var partLocation = null;
var unitPrice = null;
var unitCost = null;
var partSku = null;
var partBatteryLevy = 0;
var partEnviroLevy = 0;
var partStatus = 'estimate';
var partPstExempt = 0;
var partGstExempt = 0;
var includeEstimate = 0;
var partBrokerFees = 0;
var partShippingFees = 0;

var dupepartsStore = new Ext.data.JsonStore({
  fields: ['sku', 'part1id', 'part1name', 'part2id', 'part2name', 'part3id', 'part3name'],
  remoteSort: true,
  autoLoad: false,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/dupeDatagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'parts',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  } 
});//dupepartsStore()----------------------------------------------------------

var holdpartsStore = new Ext.data.JsonStore({
  fields: ['id', 'name', 'sku', 'manufacturer_sku', 'date', 'description', 'description_url', 'onhand', 'available'],
  remoteSort: true,
  autoLoad: false,
  sorters: [{property: 'date', direction: 'DESC'}],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/partinstanceDatagrid'); ?>',
    extraParams: { onhold: '1'},
    simpleSortMode: true,
    reader: {
      root: 'instances',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  } 
});//holdpartsStore()----------------------------------------------------------

var partsStore = new Ext.data.JsonStore({
  fields: ['part_id', 'part_variant_id', 'name', 'sku', 'regular_price', 'manufacturer_sku','onhand', 'available', 'min_quantity', 'max_quantity', 'category_path', 'active', 'on_order', 
           'date_expected', 'origin', 'location', 'supplier', 'standard_package_qty', 'stocking_notes', 'created_at'],
  pageSize: 25,
  remoteSort: true,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/datagrid'); ?>',
    simpleSortMode: true,
    extraParams: { 
      show_pricing : '1',
      show_inactive: '1' <?php echo ($belowmin ? ",\ninv: 'under'" : ''); ?>
    },
    reader: {
      root: 'parts',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});//partsStore()--------------------------------------------------------------

var supplierStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier/datagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'suppliers',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});//supplierStore()-----------------------------------------------------------

var manufacturerStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  proxy: {
    type: 'ajax', 
    url: '<?php echo url_for('manufacturer/datagrid'); ?>',
    reader: {
      root: 'manufacturers'
    }
  }
});//manufacturerStore()-------------------------------------------------------

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
});//categoriesStore()---------------------------------------------------------

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
});//workordersStore()---------------------------------------------------------

var workorderItemsStore = new Ext.data.JsonStore({
  fields: ['id','workorder_id','label','text'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('/work_order/workorderItems'); ?>',
    reader: {
      root: 'items',
      idProperty: 'id'
    }
  }
});//workorderItemsStore()-----------------------------------------------------


var AddToWorkorderWin = new Ext.Window({
  width: 550,
  height: 550,
  border: false,
  resizable: false,
  modal: true,
  id: 'addPart',
  closeAction: 'hide',
  title: 'Add Part to Work Order',
  layout: 'fit',
  items: new Ext.FormPanel({
    autoWidth: true,
    id: 'partmoveform',
    url: '<?php echo url_for('work_order/partmovewo'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [
      {
        border: false,
        name: 'Settings',
        columnWidth: 0.7,
        layout: 'anchor',
        bodyStyle: 'padding: 5px 5px 5px 5px',
        items: [
      {
            xtype: 'textfield',
            fieldLabel: 'Part Name',
            name: 'name',
            id: 'part_name',
            value: partName,
            width: 250,
            anchor: '-25',
            disabled: true
          },{
            xtype: 'textfield',
            fieldLabel: 'Part SKU',
            name: 'part_sku',
            id: 'part_sku',
            value: partSku,
            width: 250,
            anchor: '-25',
            disabled: true
          },{
            xtype: 'textfield',
            fieldLabel: 'Location',
            name: 'location',
            id: 'part_location',
            value: partLocation,
            width: 300,
            anchor: '-25',
            disabled: true
          }]},{
        border: false,
        name: 'Settings',
        columnWidth: 0.7,
        layout: 'anchor',
        bodyStyle: 'padding: 5px 5px 5px 5px',
        items: [{
          xtype: 'combo',
          width: 350,
          itemId: 'workorderField',
          id: 'workorderField',
          fieldLabel: 'Workorder',
          name: 'workorder_id',
          forceSelection: true,
          allowBlank: false,
          valueField: 'id',
          displayField: 'text',
          emptyText: 'Select Workorder...',
          minChars: 2,
          anyMatch: true,
          store: workordersStore,
          listConfig: { minWidth: 300 },
          queryMode: 'local',
          listeners: {
            'select': function(field,r){
              var itemField = field.up('form').down('#itemField');
              itemField.clearValue();
              itemField.setDisabled(false);
              itemField.getStore().proxy.setExtraParam('workorder_id', field.getValue());
              itemField.getStore().load({
                callback: function(){
                  var itemField = field.up('form').down('#itemField');
                  if (itemField.getStore().getCount() === 0){
                    Ext.Msg.alert(
                      'No Tasks Available', 
                      'This workorder ('+field.getValue()+') does not have any tasks');
                  }
                  else if (itemField.getStore().getCount() == 1){
                    itemField.setValue( itemField.getStore().getAt(0).data.id);
                  } else {
                    itemField.onTriggerClick();
                  }
                }
              });

              var pstField = field.up('form').down('#pstField');
              r = field.findRecordByValue(field.getValue());
              pstField.setValue((r && r.data.pst_exempt == 'Y' ? 1 : 0));

              var gstField = field.up('form').down('#gstField');
              gstField.setValue((r && r.data.gst_exempt == 'Y' ? 1 : 0));
              
            },
            'blur': function(field){
              if (field.getValue() == '')
              {
                var itemField = field.up('form').down('#itemField');
                itemField.clearValue();
                itemField.setDisabled(true);
                itemField.getStore().proxy.setExtraParam('workorder_id', null);
                field.up('form').down('#itemField').setDisabled(true);
              }
            }
          }
        },{
          xtype: 'combo',
          width: 350,
          itemId: 'itemField',
          id: 'itemField',
          fieldLabel: 'Parent Task',
          name: 'wo_item_id',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          valueField: 'id',
          displayField: 'text',
          disabled: true,
          triggerAction: 'all',
          emptyText: 'Select New Task...',
          minChars: 1,
          store: workorderItemsStore,
          listConfig: { minWidth: 300 },
          queryMode: 'local'
        },{
          xtype: 'numberfield',
          name: 'quantity',
          id: 'quantity',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Quantity',
          value: 1,
          anchor: '-300',
          allowBlank: false
        },{
          itemId: 'estimate',
          xtype: 'acbuttongroup',
          fieldLabel: 'Include in Estimate',
          anchor: '-100',
          name: 'estimate',
          value: '0',
          items: [
            { value: '1', flex: 3, text: 'Yes' },
            { value: '0', flex: 3, text: 'No' }
          ],
          listeners: { 
            change: function(field){
              var value = field.getValue();
              includeEstimate = value;
            }
          }
          },{
          itemId: 'part_status',
          xtype: 'acbuttongroup',
          fieldLabel: 'Part Status',
          anchor: '-100',
          name: 'part_status',
          value: '2',
          items: [
            { value: '0', flex: 2, text: 'Estinamte Only' },
            { value: '1', flex: 2, text: 'On Hold' },
            { value: '2', flex: 2, text: 'Utilized' }
          ],
          listeners: { 
            change: function(field){
              var value = field.getValue();
              
              if (value == '0') {partStatus = 'estimate';}
              else if (value == '1') {partStatus = 'hold';}
              else {partStatus = 'delivered';}
            }
          }
        }
        ]},
        {
        border: false,
        name: 'Settings',
        columnWidth: 0.7,
        layout: 'anchor',
        bodyStyle: 'padding: 5px 5px 5px 5px',
        items: [
          {
            xtype: 'numberfield',
            fieldLabel: 'Unit Price',
            name: 'unit_price',
            id: 'unit_price',
            value: unitPrice,
            anchor: '-300',
            minValue: 0,
            //disabled: true,
            forcePrecision: true,
            allowBlank: false
          },{
          xtype: 'numberfield',
          name: 'shipping_fees',
          id: 'shipping_fees',
          fieldLabel: 'Shipping Fees',
          value: partShippingFees,
          minValue: 0,
          forcePrecision: true,
          anchor: '-300'
        },{
          xtype: 'numberfield',
          name: 'broker_fees',
          id: 'broker_fees',
          fieldLabel: 'Broker Fees',
          value: partBrokerFees,
          minValue: 0,
          forcePrecision: true,
          anchor: '-300'
        },{
          xtype: 'numberfield',
          name: 'enviro_levy',
          id: 'enviro_levy',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Environment Levy',
          renderer: function(value, record){
            return partEnviroLevy;
          },
          anchor: '-300',
          allowBlank: false
        },{
          xtype: 'numberfield',
          name: 'battery_levy',
          id: 'battery_levy',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Battery Levy',
          value: partBatteryLevy,
          anchor: '-300',
          allowBlank: false
        },{
          itemId: 'pstField',
          xtype: 'acbuttongroup',
          name: 'pstField',
          value: 0,
          fieldLabel: 'PST Exempt',
          items: [
                { value: '1', flex: 5, text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST' },
                { value: '0', flex: 3, text: 'PST Exempt' }],
          listeners: { 
            change: function(field){
              var value = field.getValue();
              partPstExempt = value;
            }
          }
        },{
          itemId: 'gstField',
          xtype: 'acbuttongroup',
          name: 'gstField',
          value: 0,
          fieldLabel: 'GST Exempt',
          items: [
                { value: '1', flex: 5, text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST' },
                { value: '0', flex: 3, text: 'GST Exempt' }],
          listeners: { 
            change: function(field){
              var value = field.getValue();
              partGstExempt = value;
            }
          }
        }]}
  ],

    buttons: [{
      text: 'OK',
      formBind: true,
      handler: function(btn){

        var workorderId = Ext.getCmp('workorderField').getValue();
        var workorderItemId = Ext.getCmp('itemField').getValue();
        var woQuantity = Ext.getCmp('quantity').getValue();
        var woEnviroLevy = Ext.getCmp('enviro_levy').getValue();
        var woBatteryLevy = Ext.getCmp('battery_levy').getValue();
        var woShippingFees = Ext.getCmp('shipping_fees').getValue();
        var woBrokerFees = Ext.getCmp('broker_fees').getValue();
        var woUnitPrice = Ext.getCmp('unit_price').getValue();

//        var partStatus = 'estimate';
//        if (partAvailable >= woQuantity){
//          partStatus = 'delivered';
//        }

        Ext.Msg.wait("Adding Part to Workorder " + workorderId);

        Ext.Ajax.request({
            url: '<?php echo url_for('work_order/partedit'); ?>',
            method: 'POST',
            params: { 
              id: workorderId, 
              workorder_id: workorderId, 
              instance_id: 'new',
              quantity: woQuantity,
              unit_price: woUnitPrice,
              unit_cost: unitCost,
              parent_id: workorderItemId,
              part_variant_id: partVariantId,
              enviro_levy: woEnviroLevy,
              battery_levy: woBatteryLevy,
              shipping_fees: woShippingFees,
              broker_fees: woBrokerFees,
              estimate: includeEstimate,
              taxable_pst: partPstExempt,
              taxable_gst: partGstExempt, 
              statusaction: partStatus,

            },
            success: function(){
              Ext.Msg.hide();
              AddToWorkorderWin.hide();
              Ext.Msg.hide();
              reload_tree();
              partslistStore.load();
            },
            failure: function(){
              AddToWorkorderWin.hide();
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not move part! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });

      }
    },{
      text: 'Cancel',
      handler:function(){
        AddToWorkorderWin.hide();
      }
    }
  ]

  })
});//AddToWorkorderWin()-------------------------------------------------------




var MaxMinWin = new Ext.Window({
  title: 'Change Max/Min Quantities',
  closable: false,
  width: 350,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
      fieldDefaults: { 
        labelAlign: 'left',
        labelWidth: 125
      },
      url: '<?php echo url_for('part/minmax'); ?>',
      bodyStyle: 'padding:15px',
      items: [{
        itemId: 'part_id',
        name: 'part_id',
        xtype: 'hidden',
      },{
        xtype: 'displayfield',
        itemId: 'name',
        fieldLabel: 'Part Name',
        name: 'name',
        value: ''
      },{        
        xtype: 'displayfield',
        itemId: 'sku',
        fieldLabel: 'Part SKU',
        name: 'sku',
        value: ''
      },{
        xtype: 'fieldset',
        title: 'Inventory Settings',
        items: [{
          xtype: 'numberfield',
          itemId: 'minval',
          name: 'min_quantity',
          fieldLabel: 'Minimum On Hand',
          width: 200,
          minValue: 0
        },{        
          xtype: 'numberfield',
          itemId: 'maxval',
          name: 'max_quantity',
          fieldLabel: 'Maximum On Hand',
          labelWidth: 125,
          width: 200,
          minValue: 0
        },{
          xtype: 'numberfield',
          itemId: 'stdval',
          name: 'standard_package_qty',
          fieldLabel: 'Standard Pkg Qty',
          labelWidth: 125,
          width: 200,
          minValue: 0
        }]
      },{
        xtype: 'fieldset',
        title: 'Current Inventory',
        items: [{
          xtype: 'numberfield',
          itemId: 'available',
          name: 'onhand',
          fieldLabel: 'Current On-Hand',
          labelWidth: 125,
          width: 200,
          minValue: 0
        },{
          xtype: 'displayfield',
          itemId: 'qty',
          fieldLabel: 'Current Available (excluding holds)',
          name: 'available',
          value: ''                
        }],
      },{
        border: false,
        html: 'Note: updating Current On-Hand value will create an inventory adjustment record'
      }],

      buttons:[{
          text: 'Update',
          formBind: true,
          handler:function(){
              this.findParentByType('form').getForm().submit({
                  waitTitle: 'Please Wait',
                  waitMsg: 'Updating Quantities...',
                  success:function(form,action)
                  {
                    form.reset();                    
                    MaxMinWin.hide();
                    parts_grid.getStore().reload();
                  },
                  failure:function(form,action){
                      if(action.failureType == 'server'){
                        obj = Ext.JSON.decode(action.response.responseText);
                        myMsg = obj.errors.reason;
                      }else{
                        myMsg = 'Could not update quantities. Try again later!';
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
      },{
          text: 'Cancel',
          handler:function(){
              this.findParentByType('window').hide();
              this.findParentByType('form').getForm().reset();
          }
      }]
  })
});//MaxMinWin()---------------------------------------------------------------


var ManufacturerAddWin = new Ext.Window({
  title: 'Add Manufacturer',
  closable: false,
  width: 450,
  height: 350,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
      fieldDefaults: { 
        labelAlign: 'top' 
      },
      id: 'manufactureraddform',
      url: '<?php echo url_for('manufacturer/add'); ?>',
      bodyStyle: 'padding:15px 10px 0 10px',
      items: [{
        xtype: 'textfield',
        id: 'manuadd_namefield',
        fieldLabel: 'Manufacturer Name',
        allowBlank: false,
        name: 'department_name',
        anchor: '-25'
      },{
        layout: 'column',
        border: false,
        items: [{
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
          fieldLabel: 'Manufacturer Notes',
          xtype: 'textarea',
          name: 'private_notes',
          anchor: '-25',
          height: 85
      }],

      buttons:[{
          text: 'Add',
          formBind: true,
          handler:function(){
              this.findParentByType('form').getForm().submit({
                  waitTitle: 'Please Wait',
                  waitMsg: 'Adding Manufacturer...',
                  success:function(form,action){
                    ManufacturerAddWin.hide();
                    obj = Ext.JSON.decode(action.response.responseText);
                    Ext.getCmp('manufactureraddform').getForm().reset();
                    cf = Ext.getCmp('manufacturerfield');
                    cf.getStore().add({id: obj.newid, name: obj.newname});
                    cf.setValue(obj.newid);
                    cf.fireEvent('select', cf);
                  },
                  failure:function(form,action){
                      if(action.failureType == 'server'){
                        obj = Ext.JSON.decode(action.response.responseText);
                        myMsg = obj.errors.reason;
                      }else{
                        myMsg = 'Could not add manufacturer. Try again later!';
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
      },{
          text: 'Cancel',
          handler:function(){
              this.findParentByType('window').hide();
              this.findParentByType('form').getForm().reset();
          }
      }]
  })
});


var PartAddWin = new Ext.Window({
  title: 'Add Part',
  closable: false,
  width: 850,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    fieldDefaults: { 
      labelAlign: 'left',
      labelWidth: 125
    },
    forceLayout: true,
    id: 'partaddform',
    url: '<?php echo url_for('part/add'); ?>',
    bodyStyle: 'padding: 0 10px 0 10px',
    items: [{
      xtype: 'fieldset',
      title: 'General Information',
      items: [{
        layout: 'column',
        border: false, 
        items: [{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          defaults: {
            anchor: '-40'
          },
          items: [{
            xtype: 'textfield',
            id: 'newpartname',
            fieldLabel: 'Part Name',
            allowBlank: false,
            emptyText: 'Required!',
            name: 'name'
          },{
            id: 'addpart_category',
            xtype: 'treecombo',
            panelMaxHeight: 300,
            fieldLabel: 'Category',
            allowBlank: false,
            name: 'part_category_id',
            emptyText: 'Required!',
            rootVisible: false,
            selectChildren: false,
            canSelectFolders: true,
            store: categoriesStore
          },{
            xtype: 'textfield',
            id: 'location',
            name: 'location',
            fieldLabel: 'Storage Location'
          },{
            xtype: 'textarea',
            name: 'description',
            fieldLabel: 'Description',
            height: 45
          },{
            xtype: 'textarea',
            name: 'stocking_notes',
            fieldLabel: 'Stocking Notes',
            height: 30
          },{            
            xtype: 'textfield',
            name: 'origin',
            fieldLabel: 'Country of Origin'            
          }]
        },{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'textfield',
            id: 'addwin_internalsku',
            anchor: '-25',
            fieldLabel: 'Delta SKU',
            name: 'internal_sku',
            listeners: { focus: barcode_focus, blur: barcode_blur }
          },{
            xtype: 'textfield',
            id: 'addwin_manufacturersku',
            fieldLabel: 'Manufacturer SKU',
            name: 'manufacturer_sku',
            anchor: '-25',
            listeners: { focus: barcode_focus, blur: barcode_blur }
          },{
            layout: 'column',
            border: false, 
            items: [{
              border: false,
              columnWidth: 1,
              layout: 'anchor',
              items: [{
                xtype: 'combo',
                fieldLabel: 'Manufacturer',
                name: 'manufacturer_id',
                id: 'manufacturerfield',
                queryMode: 'remote',
                anchor: '-25',
                minChars: 2,
                forceSelection: true,
                valueField: 'id',
                displayField: 'name',
                hideTrigger: true,
                matchFieldWidth: false,
                listConfig: { minWidth: 300, maxHeight: 200 },
                store: manufacturerStore
              }]
            },{
              border: false,
              items: [{
                xtype: 'button',
                text: 'New',
                iconCls: 'add',
                labelSeparator: '',
                fieldLabel: ' ',
                handler: function(){
                  ManufacturerAddWin.show();
                }
              }]
            }]
          },{
            xtype: 'fieldcontainer',
            fieldLabel: 'Track Serial Numbers',
            layout: 'hbox',
            width: 250,
            items: [{
              xtype: 'hidden',
              name: 'has_serial_number',
              value: 0,
              listeners: { change: function(field, value){
                selBtn = field.next('button[valueField='+value+']');
                if (!selBtn.pressed) selBtn.toggle(true);
              }}
            },{
              xtype: 'button',
              toggleGroup: 'newpartserial',
              allowDepress: false,
              flex: 1,
              cls: 'buttongroup-first',
              text: 'Yes',
              valueField: 1,
              listeners: { toggle: function(btn, pressed){
                if (pressed) btn.prev('hidden').setValue(btn.valueField);
              }}
            },{
              xtype: 'button',
              toggleGroup: 'newpartserial',
              allowDepress: false,
              pressed: true,
              flex: 1,
              cls: 'buttongroup-last',
              text: 'No',
              valueField: 0,
              listeners: { toggle: function(btn, pressed){
                if (pressed) btn.prev('hidden').setValue(btn.valueField);
              }}
            }]            
          },{
            xtype: 'fieldcontainer',
            fieldLabel: 'Part Status',
            layout: 'hbox',
            width: 250,
            items: [{
              xtype: 'hidden',
              name: 'active',
              value: 1,
              listeners: { change: function(field, value){
                selBtn = field.next('button[valueField='+value+']');
                if (!selBtn.pressed) selBtn.toggle(true);
              }}
            },{
              xtype: 'button',
              toggleGroup: 'newpartactive',
              allowDepress: false,
              pressed: true,
              flex: 1,
              cls: 'buttongroup-first',
              text: 'Active',
              valueField: 1,
              listeners: { toggle: function(btn, pressed){
                if (pressed) btn.prev('hidden').setValue(btn.valueField);
              }}
            },{
              xtype: 'button',
              toggleGroup: 'newpartactive',
              allowDepress: false,
              flex: 1,
              cls: 'buttongroup-last',
              text: 'Inactive',
              valueField: 0,
              listeners: { toggle: function(btn, pressed){
                if (pressed) btn.prev('hidden').setValue(btn.valueField);
              }}
            }]
          }]
        }]
      }]
    },{
      layout: 'column',
      border: false,
      bodyStyle: 'padding-top: 10px;',
      items: [{
        xtype: 'fieldset',
        columnWidth: 0.4,
        minHeight: 220,
        title: 'Costing & Pricing',
        bodyStyle: 'padding: 5px',
        defaults: { 
          anchor: '-25',
          xtype: 'numberfield',
          minValue: 0,
          hideTrigger: true
        },
        items: [{
          xtype: 'container',
          padding: '5 0 10 0',
          anchor: 0,
          layout: 'hbox',
          items: [{
            xtype: 'hidden',
            name: 'cost_calculation_method',
            value: 'lifo',
            listeners: { change: function(field, value){
              selBtn = field.next('button[valueField='+value+']');
              if (!selBtn.pressed) selBtn.toggle(true);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'newpartcosting',
            allowDepress: false,
            pressed: true,
            flex: 1,
            cls: 'buttongroup-first',
            text: 'Last In First Out',
            valueField: 'lifo',
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'newpartcosting',
            allowDepress: false,
            flex: 1,
            cls: 'buttongroup-last',
            text: 'First In First Out',
            valueField: 'fifo',
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'newpartcosting',
            allowDepress: false,
            flex: 1,
            cls: 'buttongroup-last',
            text: 'Average Cost',
            valueField: 'average',
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
            }}
          }]
        },{
          name: 'unit_cost',
          fieldLabel: 'Specify Cost ($)',
          emptyText: 'Overrides above...',
          minValue: 0,
          forcePrecision: true
        },{
          name: 'markup_amount',
          fieldLabel: 'Markup Amount ($)',
          minValue: 0,
          forcePrecision: true
        },{
          name: 'markup_percent',
          fieldLabel: 'Markup Percent (%)',
          minValue: 0
        },{
          name: 'unit_price',
          fieldLabel: 'Specify Price ($)',
          emptyText: 'Overrides Markups...',
          minValue: 0,
          forcePrecision: true
        },{
          //xtype: 'numberfield',
          name: 'shipping_fees',
          fieldLabel: 'Shipping Fees',
          minValue: 0,
          forcePrecision: true
        },{
          //xtype: 'numberfield',
          name: 'broker_fees',
          fieldLabel: 'Broker Fees',
          minValue: 0,
          forcePrecision: true
        },{
          name: 'enviro_levy',
          fieldLabel: 'Enviro Levy ($)',
          minValue: 0,
          forcePrecision: true
        },{
          name: 'battery_levy',
          fieldLabel: 'Battery Levy ($)',
          minValue: 0,
          forcePrecision: true
        }]
      },{
        width: 10,
        border: false,
        html: '&nbsp;'
      },{
        xtype: 'fieldset',
        columnWidth: 0.30,
        minHeight: 270,
        title: 'Inventory Settings',
        bodyStyle: 'padding: 5px 5px 33px 5px',
        defaults: {
          anchor: '-20',
          xtype: 'numberfield',
          minValue: 0,
          hideTrigger: true
        },
        items: [{
          id: 'unitscombo',
          xtype: 'combo',
          name: 'units',
          fieldLabel: 'Units', 
          displayField: 'text',
          valueField: 'val',
          triggerAction: 'all',
          hideTrigger:false,
          matchFieldWidth: false,
          value: 'Items',
          queryMode: 'local',
          store: new Ext.data.ArrayStore({
            fields: ['group','val','text'],
            idIndex: 1,
            data: [
              [ false, '', 'Items' ],
              [ 'Lengths:', 'm', 'm' ],
              [ 'Lengths:', 'cm', 'cm' ],
              [ 'Lengths:', 'mm', 'mm' ],
              [ 'Lengths:', 'yd', 'yd' ],
              [ 'Lengths:', 'ft', 'ft' ],
              [ 'Lengths:', 'in', 'in' ],
              [ 'Weights:', 'kg', 'kg' ],
              [ 'Weights:', 'g', 'g' ],
              [ 'Weights:', 'lb', 'lb' ],
              [ 'Weights:', 'oz', 'oz' ],
              [ 'Volumes:', 'L', 'L' ],
              [ 'Volumes:', 'ml', 'ml' ],
              [ 'Volumes:', 'gal', 'gal' ],
              [ 'Volumes:', 'qt', 'qt' ],
              [ 'Volumes:', 'fl. oz.', 'fl. oz.' ]
            ]
          }),
          listConfig: {
            autoHeight: true,
            minWidth: 120,
            tpl: Ext.create('Ext.XTemplate',
              '<ul><tpl for=".">',
                '<tpl if="!this.getGroupStr(values)">',
                  '<li role="option" class="x-boundlist-item">{text}</li>',
                '<tpl else>',
                  '<tpl if="xindex == 1 || this.getGroupStr(parent[xindex - 2]) != this.getGroupStr(values)">',
                    '<li class="x-combo-list-group"><b>{[this.getGroupStr(values)]}</b></li>',
                  '</tpl>',
                  '<li role="option" class="x-boundlist-item" style="padding-left: 12px">{text}</li>',
                '</tpl>',
              '</tpl>',
              '</ul>',
              {
                getGroupStr: function (values) {
                  return values.group
                }
              }
            )
          }      
        },{
          xtype: 'fieldcontainer',
          fieldLabel: 'Track Inventory',
          layout: 'hbox',
          items: [{
            xtype: 'hidden',
            name: 'track_inventory',
            value: 1,
            listeners: { change: function(field, value){
              selBtn = field.next('button[valueField='+value+']');
              if (!selBtn.pressed) selBtn.toggle(true);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'newpartinventory',
            allowDepress: false,
            pressed: true,       
            flex: 1,
            cls: 'buttongroup-first',
            text: 'Yes',
            valueField: 1,
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('minimum_on_hand').setDisabled(!pressed);
              Ext.getCmp('maximum_on_hand').setDisabled(!pressed);
              Ext.getCmp('initial_quantity').setDisabled(!pressed);
              Ext.getCmp('initial_cost').setDisabled(!pressed);              
            }}
          },{
            xtype: 'button',
            toggleGroup: 'newpartinventory',
            allowDepress: false,
            flex: 1,
            cls: 'buttongroup-last',
            text: 'No',
            valueField: 0,
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
            }}
          }]
        },{
          name: 'minimum_on_hand',
          id: 'minimum_on_hand',
          fieldLabel: 'Min On Hand',
          allowBlank: false,
          value: '0'
        },{
          name: 'maximum_on_hand',
          id: 'maximum_on_hand',
          fieldLabel: 'Max On Hand'
        },{
          name: 'standard_package_qty',
          id: 'standard_package_qty',
          fieldLabel: 'Std Package Qty'
        },{
          name: 'initial_quantity',
          id: 'initial_quantity',
          fieldLabel: 'Initial Quantity'
        },{
          name: 'initial_cost',
          id: 'initial_cost',
          fieldLabel: 'Initial Qty Unit Cost'
        }]
      },{
        width: 10,
        border: false,
        html: '&nbsp;'
      },{
        xtype: 'fieldset',
        columnWidth: 0.30,
        minHeight: 270,
        title: 'Main Supplier',
        bodyStyle: 'padding: 5px 5px 6px 5px;',
        defaults: {
          anchor: '-15'
        },
        items: [{
          xtype: 'combo',
          id: 'addpart_supplier',
          fieldLabel: 'Supplier',
          name: 'supplier_id',
          queryMode: 'remote',
          allowBlank: false,
          minChars: 2,
          matchFieldWidth: false,
          listConfig: { minWidth: 200, maxHeight: 200 },
          forceSelection: true,
          valueField: 'id',
          displayField: 'name',
          hideTrigger: true,
          store: supplierStore
        },{
          xtype: 'textfield',
          id: 'addwin_supplier1sku',
          fieldLabel: 'Supplier SKU',
          name: 'supplier_sku',
          listeners: { focus: barcode_focus, blur: barcode_blur }
        },{
          xtype: 'fieldset',
          border: false,
          style: 'margin: 0; padding: 0;',
          items: [{
            xtype: 'textarea',
            labelAlign: 'top',
            fieldLabel: 'Supplier Notes',
            name: 'supplier_notes',
            emptyText: 'Enter price breaks, special order info, etc.',
            height: 120,
            anchor: '-3',
            style: 'margin-left: 20px;'
          }]
        },{
          border: false,
          html: 'Note: you can add additional suppliers later',
          style: 'margin: 5px;'
        }]
      }]
    }],

    buttons:[{
      text: 'Add then View...',
      formBind: true,
      handler:function(){
        PartAddWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Part...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Part Info..."});
            myMask.show();
            obj = Ext.JSON.decode(action.response.responseText);
            location.href = '<?php echo url_for('part/view?id='); ?>' + obj.newid;
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add part. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ PartAddWin.show(); },
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
      text: 'Add',
      formBind: true,
      handler:function(){
        PartAddWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Part...',
          success:function(form,action){
            form.reset();
            parts_grid.getStore().load();
            barcodeListener.handleroverride = barcode_default_handler;
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add part. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ PartAddWin.show(); },
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
        barcodeListener.handleroverride = barcode_default_handler;
      }
    }]
  })
});

var CycleInventorySheetWin = new Ext.Window({
  title: 'Cycle Counts Inventory Sheet',
  closable: false,
  width: 300,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.form.FormPanel({
    fieldDefaults: {
      labelAlign: 'left'
    },
    url: '<?php echo url_for('part/inventorySheet'); ?>',
    target: 'iframe',
    bodyStyle: 'padding: 20px',
    standardSubmit: true,
    items: [{
      border: false,
      html: 'Click the Download button below to download an Excel (XLS) of the specified number of inventory items selected randomly (based on the current location and category search settings)<br /><br/>&nbsp;'
    },{
      xtype: 'hidden',
      name: 'cycle',
      value: 1
    },{
      xtype: 'numberfield',
      name: 'limit',
      fieldLabel: 'Number of SKUs to Include',
      minValue: 5,
      maxValue: 200,
      value: 25,
      labelWidth: 170,
      width: 250
    },{
      xtype: 'numberfield',
      name: 'min_age',
      fieldLabel: 'Minimum time since last inventory adjustment (in months)',
      minValue: 0,      
      value: 6,
      
      labelWidth: 170,
      width: 250
    },{
      border: false,
      html: '<br /><br />NOTE: This can take a long time to generate, as long as 1-2 minutes. Please be patient during this time. You\'ll be presented with a download window when the file is ready.'
    },{
      xtype: 'hidden',
      value: '',
      name: 'name',
      itemId: 'name'
    },{
      xtype: 'hidden',
      value: '',
      name: 'location',
      itemId: 'location'
    },{
      xtype: 'hidden',
      value: '',
      name: 'supplier',
      itemId: 'supplier'      
    },{
      xtype: 'hidden',
      value: '',
      name: 'category',
      itemId: 'category'      
    }],
    buttons:[{
      text: 'Download',
      formBind: true,
      handler:function(){
        CycleInventorySheetWin.hide();
        supform = this.findParentByType('form').submit();
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

var InventorySheetWin = new Ext.Window({
  title: 'Download Inventory Sheet',
  closable: false,
  width: 300,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.form.FormPanel({
    fieldDefaults: {
      labelAlign: 'left'
    },
    url: '<?php echo url_for('part/inventorySheet'); ?>',
    target: 'iframe',
    bodyStyle: 'padding: 20px',
    standardSubmit: true,
    items: [{
      border: false,
      html: 'Click the Download button below to download an Excel (XLS) spreadsheet containing information on all current parts and their inventory levels.<br /><br />NOTE: This can take a long time to generate, as long as 1-2 minutes. Please be patient during this time. You\'ll be presented with a download window when the file is ready.'
    },{
      xtype: 'hidden',
      value: '',
      name: 'name',
      itemId: 'name'
    },{
      xtype: 'hidden',
      value: '',
      name: 'location',
      itemId: 'location'
    },{
      xtype: 'hidden',
      value: '',
      name: 'supplier',
      itemId: 'supplier'            
    },{
      xtype: 'hidden',
      value: '',
      name: 'category',
      itemId: 'category'      
    }],
    buttons:[{
      text: 'Download',
      formBind: true,
      handler:function(){
        InventorySheetWin.hide();
        supform = this.findParentByType('form').submit();
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


var holdgrid = new Ext.grid.GridPanel({
  enableColumnMove: false,
  autoRender: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'Part Name',
    flex: 2,
    dataIndex: 'name'
  },{
    header: 'Delta SKU',
    width: 90,
    dataIndex: 'sku'
  },{
    header: 'Mfr SKU',
    width: 90,
    dataIndex: 'manufacturer_sku',
    hidden: true
  },{
    header: 'Used In',
    hideable: false,
    dataIndex: 'description',
    sortable: false,
    flex: 3
  },{
    header: 'Qty',
    width: 40,
    align: 'center',
    dataIndex: 'onhand',
    hideable: false,
    sortable: true,
  },{
    header: 'Date',
    dataIndex: 'date',
    sortable: true,
    width: 120
  }],

  store: holdpartsStore,
  
  viewConfig: { forceFit: true },

  bbar: new Ext.PagingToolbar({
    pagesize: 25,
    store: holdpartsStore,
    displayInfo: true,
    emptyMsg: 'No Parts on Hold'
  }),

  listeners: {
    afterlayout: {
      scope: this, 
      single: true, 
      fn: function() {
        holdpartsStore.load({params:{start:0, limit:25}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential(array('sales_view','workorder_view'),false)): ?>
          if (record.data.description_url == '') {
              Ext.Msg.alert('Not Viewable', 'The selected item does not have any additional information!');
          } else {
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Workorder..."});
            myMask.show();
            location.href= record.data.description_url;
          }
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to view sales or work orders.');
        <?php endif; ?>
      }
    }
  }) 
});

var dupegrid = new Ext.grid.GridPanel({
  enableColumnMove: false,
  autoRender: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'SKU',
    width: 150,
    dataIndex: 'sku'
  },{
    header: 'Part 1',
    hideable: false,
    dataIndex: 'part1name',
    sortable: false,
    flex: 3,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if (value == ''){
        return 'N/A';
      } else {
        return '<a href="<?php echo url_for('part/view?id='); ?>'+record.data.part1id+'">' + value + '</a>';
      }
    }
  },{
    header: 'Part 2',
    hideable: false,
    dataIndex: 'part2name',
    sortable: false,
    flex: 3,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if (value == ''){
        return 'N/A';
      } else {
        return '<a href="<?php echo url_for('part/view?id='); ?>'+record.data.part2id+'">' + value + '</a>';
      }
    }
  },{
    header: 'Part 3',
    hideable: false,
    dataIndex: 'part3name',
    sortable: false,
    flex: 3,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if (value == ''){
        return 'N/A';
      } else {
        return '<a href="<?php echo url_for('part/view?id='); ?>'+record.data.part3id+'">' + value + '</a>';
      }
    }
  }],

  store: dupepartsStore,
  
  viewConfig: { forceFit: true },

  bbar: new Ext.PagingToolbar({
    pagesize: 25,
    store: dupepartsStore,
    displayInfo: true,
    emptyMsg: 'No Parts on Hold'
  }),

  listeners: {
    afterlayout: {
      scope: this, 
      single: true, 
      fn: function() {
        dupepartsStore.load({params:{start:0, limit:25}});
      }
    }
  } 
});

var parts_grid = new Ext.grid.GridPanel({
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  minHeight: 500,
  deferEmptyText: false,
  emptyText: 'No matching parts found',

  viewConfig: { stripeRows: true, loadMask: true },

  store: partsStore,

  columns:[{
    header: "Part Name",
    dataIndex: 'name',
    hideable: false,
    flex: 1,
    sortable: true,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if(record.get('active') != 1){
        if (store.proxy.extraParams.show_inactive == '2'){
          return '<span style="color: red;">' + value + '</span>';
        } else {
          return '<span style="color: red; text-decoration: line-through;">' + value + '</span>';
        }
      } else {
        return value;
      }
    }
  },{
    header: "Delta SKU",
    width: 100,
    dataIndex: 'sku',
    sortable: true
},{
    itemId: 'parts_mfrsku',
    header: 'Mfr SKU',
    width: 100,
    dataIndex: 'manufacturer_sku',
    hidden: true    
  },{
    itemId: 'parts_category',
    header: "Category",
    dataIndex: 'category_path',
    hideable: true,
    sortable: true,
    flex: <?php echo ($belowmin ? '0.5' : '1'); ?>
  },{
    itemId: 'parts_location',
    header: 'Location',
    dataIndex: 'location',
    sortable: true,
    flex: 1,
    hidden: true    
  },{
    itemId: 'parts_supplier',
    header: 'Supplier',
    dataIndex: 'supplier',
    sortable: true,
    flex: 1,
    hidden: true    
  },{
    itemId: 'parts_origin',
    header: 'Origin',
    dataIndex: 'origin',
    sortable: true,
    flex: 0.5
  },{
    itemId: 'add_workorder',
    header: "WO+",
    dataIndex: 'part_variant_id',
    renderer: function(value) {
      return '<span style="text-decoration:underline;color:red;cursor:pointer;"><img src="/images/silkicon/folder_go.png" title="Add to Workorder" alt="'+value+'"/><\/span>';
    },
    width: 40,
    align: 'center',
    sortable: false
  },{
    header: "Qty",
    dataIndex: 'available',
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
      if (parseFloat(value) < parseFloat(record.get('min_quantity'))){
        return '<span style="text-decoration:underline;color:red;cursor:pointer;">' + value + '<\/span>';
      }else{
         return '<span style="text-decoration:underline;color:#CC8400;cursor:pointer;">' + value + '<\/span>';
      }
    },
    width: 40,
    align: 'center',
    sortable: true
  },{
    header: "Min",
    dataIndex: 'min_quantity',
    width: 40,
    align: 'center',
    xtype: 'numbercolumn',
    format: 0,
    sortable: true,
    renderer: function (value){
      if (value){
        return '<span style="text-decoration:underline;color:#CC8400;cursor:pointer;">' + value + '<\/span>';
      }    
    }
  },{
    header: "Max",
    dataIndex: 'max_quantity',
    width: 40,
    align: 'center',
    sortable: true,
    renderer: function (value){
      if (value){
        return '<span style="text-decoration:underline;color:#CC8400;cursor:pointer;">' + value + '<\/span>';
      }
    }
  },{
    itemId: 'parts_onorder',
    header: "Ordered",
    dataIndex: 'on_order',
    width: 55,
    align: 'center',
    xtype: 'numbercolumn',
    format: 0,
    sortable: true,
    hidden: <?php echo ($belowmin ? 'false' : 'true'); ?>,
    renderer: function(value){
      if (value){
        return '<span style="color:green;">' + value + '<\/span>';
      }
    }
  },{
    itemId: 'parts_expected',
    header: 'Expected',
    dataIndex: 'date_expected',
    width: 80,
    align: 'center',
    xtype: 'numbercolumn',
    format: 0,
    sortable: true,
    hidden: <?php echo ($belowmin ? 'false' : 'true'); ?>
  },{
    itemId: 'standard_package_qty',
    header: 'Pkg Qty',
    dataIndex: 'standard_package_qty',
    width: 70,
    align: 'center',
    format: 0,
    sortable: false,
    hidden: true,
    renderer: function (value){
      if (value){
        return '<span style="text-decoration:underline;color:#CC8400;cursor:pointer;">' + value + '<\/span>';
      }
    }    
  },{
    itemId: 'created_at',
    header: 'Date Added',
    dataIndex: 'created_at',
    width: 100,
    align: 'left',
    sortable: true,
    hidden: true    
  },{
    itemId: 'stocking_notes',
    header: 'Stock Notes',
    dataIndex: 'stocking_notes',
    width: 150,
    align: 'left',
    sortable: false,
    hidden: true        
  }],

  selModel: new Ext.selection.RowModel(),

  listeners: {
    'beforerender': function(grid){
      parts_grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('show_inactive', 0);
        $inst->getRequest()->setParameter('show_pricing', 1);
        $inst->getRequest()->setParameter('limit', 25);
        $inst->getRequest()->setParameter('sort', 'name');
        $inst->getRequest()->setParameter('dir', 'ASC');
        if ($belowmin) $inst->getRequest()->setParameter('inv', 'under');
        $inst->getController()->getPresentationFor('part','datagrid');
     ?>);
    },
    cellclick: function(grid, td, cellIndex, record, tr, rowIndex) {      
      var fieldName = grid.getHeaderCt().getHeaderAtIndex(cellIndex)
      console.log(fieldName);
      if (fieldName && (fieldName.dataIndex == 'max_quantity' || fieldName.dataIndex == 'min_quantity' || fieldName.dataIndex == 'standard_package_qty' || fieldName.dataIndex == 'available'))
      {
        //open up quick edit window.
        MaxMinWin.show();
        MaxMinWin.down('form').loadRecord(record);
      }
      else if (fieldName && fieldName.dataIndex == 'part_variant_id')
      {
        partVariantId = record.data.part_variant_id;
        partAvailable = record.data.available;
        partName = record.data.name;
        partLocation = record.data.location;
        unitPrice = record.data.regular_price;
        unitCost = record.data.unit_cost;
        partSku = record.data.sku;
        if (record.data.battery_levy >= 0) { partBatteryLevy = record.data.battery_levy }else{partBatteryLevy = 0};
        if (record.data.enviro_levy >= 0) { partEnviroLevy = record.data.enviro_levy }else{partEnviroLevy = 0};
        if (record.data.shipping_fees >= 0) { partShippingFees = record.data.shipping_fees }else{partShippingFees = 0};
        if (record.data.broker_fees >= 0) { partBrokerFees = record.data.broker_fees }else{partBrokerFees = 0};

        partStatus = 'estimate';
        
        if (partAvailable > 0){
          partStatus = 'delivered';
        }

        Ext.getCmp('part_name').setValue(partName);
        Ext.getCmp('part_location').setValue(partLocation);
        Ext.getCmp('part_sku').setValue(partSku);
        Ext.getCmp('unit_price').setValue(unitPrice);
        Ext.getCmp('battery_levy').setValue(partBatteryLevy);
        Ext.getCmp('enviro_levy').setValue(partEnviroLevy);
        Ext.getCmp('shipping_fees').setValue(partShippingFees);
        Ext.getCmp('broker_fees').setValue(partBrokerFees);

        <?php if ($sf_user->hasCredential('workorder_add')): ?>
          
          workordersStore.load();
          AddToWorkorderWin.show();
        <?php else: ?>    
          Ext.Msg.alert('Permission Denied','You do not have permission to edit worklogs');
        <?php endif; ?>
      }
      else
      {
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Part Information..."});
        myMask.show();
        location.href= '<?php echo url_for('part/view?id='); ?>' + record.data.part_id ;
      }
    }
    
  },

  dockedItems: [{
    dock: 'top',
    xtype: 'toolbar',
    items: [{
      text: 'Download Inventory Sheet',
      iconCls: 'info',
      handler: function(){
        InventorySheetWin.down('#location').setValue(Ext.getCmp('filter_location').getValue());
        InventorySheetWin.down('#supplier').setValue(Ext.getCmp('filter_supplier').getValue());
        InventorySheetWin.down('#name').setValue(Ext.getCmp('filter_name').getValue());
        InventorySheetWin.down('#category').setValue(Ext.getCmp('filter_category').getValue());
        InventorySheetWin.show();
      }
    },'-',{
      text: 'Cycle Count',
      iconCls: 'info',
      handler: function(){
        CycleInventorySheetWin.down('#location').setValue(Ext.getCmp('filter_location').getValue());
        CycleInventorySheetWin.down('#supplier').setValue(Ext.getCmp('filter_supplier').getValue());
        CycleInventorySheetWin.down('#name').setValue(Ext.getCmp('filter_name').getValue());
        CycleInventorySheetWin.down('#category').setValue(Ext.getCmp('filter_category').getValue());
        CycleInventorySheetWin.show();
      }
    },'-',{      
      text: 'Bulk Inventory Update',
      iconCls: 'info',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_inventory')): ?>
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:'Loading Categories...'});
            myMask.show();
            location.href = '<?php echo url_for('part/bulkInventory'); ?>';
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to edit part inventory levels.');
        <?php endif; ?>
      }
    },'->',{ 
      text: 'Edit Categories',
      iconCls: 'dept',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_category_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:'Loading Categories...'});
          myMask.show();
          location.href = '<?php echo url_for('part/category'); ?>';
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to view part categories.');
        <?php endif; ?>
      }
    },'-',{ 
      text:'Add Part',
      iconCls: 'partadd',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_edit')): ?>
          PartAddWin.show();
          Ext.getCmp('addpart_category').validate();
          Ext.getCmp('addpart_supplier').validate();
          Ext.getCmp('newpartname').focus(true, 200);
          barcodeListener.handleroverride = function(code, symbid){
            global_code = code;
            AddBarcodeWin.show();
          };
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to edit parts.');
        <?php endif; ?>
      }
    }] 
  },{
    id: 'index_pager',
    dock: 'bottom',
    xtype: 'pagingtoolbar',
    displayInfo: true,
    store: partsStore
  }]
});//parts_grid()--------------------------------------------------------------

var updateFilterButtonVal = function (btn, pressed){
  if (pressed) {
    newval = (btn.valueField == 'All' ? '' : btn.valueField);
    parts_grid.store.proxy.setExtraParam(btn.toggleGroup, newval);
    if (!is_resetting)
    {
      Ext.getCmp('index_pager').moveFirst();
    }
    if (btn.toggleGroup == 'inv')
    {
      parts_grid.down('#parts_category').flex = (btn.valueField == 'under' ? 0.5 : 1);
      parts_grid.down('#parts_expected').setVisible(btn.valueField == 'under');
      parts_grid.down('#parts_onorder').setVisible(btn.valueField == 'under');
    }
  } 
};//updateFilterButtonVal()----------------------------------------------------

var updateFilterVal = function(field){
  if (parts_grid.store.proxy.extraParams[field.paramField]){
    oldval = parts_grid.store.proxy.extraParams[field.paramField];
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
    var show_location = (field.id == 'filter_location' && field.getValue());
    parts_grid.down('#parts_location').setVisible(show_location);
    var show_supplier = (field.id == 'filter_supplier' && field.getValue());
    parts_grid.down('#parts_supplier').setVisible(show_supplier);
    var show_mfrsku = (field.id == 'filter_sku' && field.getValue());
    parts_grid.down('#parts_mfrsku').setVisible(show_mfrsku);

    parts_grid.down('#parts_category').setVisible(!show_supplier && !show_location);

    parts_grid.store.proxy.setExtraParam(field.paramField, newval);
    if (!is_resetting)
    {
      Ext.getCmp('index_pager').moveFirst();
    }
  }
};//updateFilterVal()----------------------------------------------------------

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Parts',
  bodyStyle: 'padding: 10px;',
  items: [{
    xtype: 'panel',
    layout: 'anchor',
    id: 'filter_form',
    hideMode: 'visibility',
    border: false,
    fieldDefaults: { labelWidth: 60 },
    hidden: <?php echo (($onhold || $ondupe) ? 'true' : 'false'); ?>,
    items: [{
      id: 'filter_name',
      xtype: 'textfield',
      fieldLabel: 'Name',
      paramField: 'name',
      anchor: '-1',
      enableKeyEvents: true,
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_sku',
      xtype: 'textfield',
      fieldLabel: 'SKU',
      paramField: 'sku',
      anchor: '-1',
      enableKeyEvents: true,
      listeners: { keyup: updateFilterVal, 'blur': updateFilterVal } 
    },{
      id: 'filter_location',
      xtype: 'textfield',
      fieldLabel: 'Location',
      paramField: 'location',
      anchor: '-1',
      enableKeyEvents: true,
      listeners: { keyup: updateFilterVal, 'blur': updateFilterVal }       
    },{
      id: 'filter_category',
      xtype: 'treecombo',
      panelWidth: 300,
      panelMaxHeight: 500,
      fieldLabel: 'Category',
      paramField: 'category_id',
      anchor: '-1',
      rootVisible: false,
      selectChildren: false,
      canSelectFolders: true,
      store: categoriesStore,
      listeners: { 'itemclick': updateFilterVal, 'select': updateFilterVal, 'blur': updateFilterVal }
    },{
      id: 'filter_supplier',
      xtype: 'textfield',
      fieldLabel: 'Supplier',
      paramField: 'supplier',
      anchor: '-1',
      enableKeyEvents: true,
      listeners: { keyup: updateFilterVal, 'blur': updateFilterVal }       
    },{      
      id: 'filter_invgroup',
      xtype: 'container',
      padding: '15 5 5 5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        toggleGroup: 'inv',
        <?php if (!$belowmin) echo "pressed: true,\n"; ?>
        isDefault: true,
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '',
        flex: 1
      },{        
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Below Min',
        toggleGroup: 'inv',
        <?php if ($belowmin) echo "pressed: true,\n"; ?>
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'under',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Above Max',
        toggleGroup: 'inv',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'over',
        flex: 2
      }]
    },{
      id: 'filter_stockgroup',
      xtype: 'container',
      padding: '5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        toggleGroup: 'stock',
        pressed: true,
        isDefault: true,
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '',
        flex: 1
      },{        
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'In Stock',
        toggleGroup: 'stock',
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'in',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Out of Stock',
        toggleGroup: 'stock',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: 'out',
        flex: 2
      }]
    },{
      id: 'filter_activegroup',
      xtype: 'container',
      padding: '5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'All',
        toggleGroup: 'show_inactive',
        cls: 'buttongroup-first',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '1',
        flex: 1
      },{        
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Active Only',
        toggleGroup: 'show_inactive',
        pressed: true,
        isDefault: true,
        cls: 'buttongroup-middle',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '0',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Inactive Only',
        toggleGroup: 'show_inactive',
        cls: 'buttongroup-last',
        listeners: { 'toggle' : updateFilterButtonVal },
        valueField: '2',
        flex: 2
      }]      
    }]
  },{    
    xtype: 'panel',
    layout: 'anchor',
    border: false,
    items: [{
      id: 'filter_holdgroup',
      xtype: 'container',
      padding: '5',
      layout: 'hbox',
      items: [{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        isDefault: true,
        text: 'All',
        toggleGroup: 'show_onhold',
        pressed: <?php echo (($onhold || $ondupe) ? 'false' : 'true'); ?>,
        cls: 'buttongroup-first',
        listeners: { 'toggle' : function(btn, pressed){
          if (pressed){
            part_tabs.getLayout().setActiveItem(parts_grid);
            Ext.get('gridtitle').dom.innerHTML = 'Parts';
            Ext.getCmp('filter_form').show();
          }
        }},
        valueField: '1',
        flex: 1
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Parts on Hold',
        toggleGroup: 'show_onhold',
        pressed: <?php echo ($onhold ? 'true' : 'false'); ?>,
        cls: 'buttongroup-last',
        listeners: { 'toggle' : function(btn, pressed){
          if (pressed) {
            part_tabs.getLayout().setActiveItem(holdgrid);
            Ext.get('gridtitle').dom.innerHTML = 'Parts On Hold';
            Ext.getCmp('filter_form').hide();  
          }
        }},
        valueField: '2',
        flex: 2
      },{
        xtype: 'button',
        enableToggle: true,
        allowDepress: false,
        text: 'Dupe SKUs',
        toggleGroup: 'show_onhold',
        pressed: <?php echo ($ondupe ? 'true' : 'false'); ?>,
        cls: 'buttongroup-last',
        listeners: { 'toggle' : function(btn, pressed){
          if (pressed) {
            part_tabs.getLayout().setActiveItem(dupegrid);
            Ext.get('gridtitle').dom.innerHTML = 'Duplicate SKUs';
            Ext.getCmp('filter_form').hide();  
          }
        }},
        valueField: '3',
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
        parts_grid.store.proxy.setExtraParam('show_inactive', 0);
        Ext.getCmp('filter_name').reset();
        parts_grid.store.proxy.setExtraParam('name', null);
        Ext.getCmp('filter_sku').reset();
        parts_grid.store.proxy.setExtraParam('sku', null);
        Ext.getCmp('filter_location').reset();
        parts_grid.store.proxy.setExtraParam('location', null);
        Ext.getCmp('filter_supplier').reset();
        parts_grid.store.proxy.setExtraParam('supplier', null);        
        Ext.getCmp('filter_category').reset();
        parts_grid.store.proxy.setExtraParam('category_id', null);
        Ext.getCmp('filter_invgroup').down('button[isDefault]').toggle(true, false);
        Ext.getCmp('filter_stockgroup').down('button[isDefault]').toggle(true, false);
        Ext.getCmp('filter_activegroup').down('button[isDefault]').toggle(true, false);
        Ext.getCmp('filter_holdgroup').down('button[isDefault]').toggle(true, false);
        Ext.get('gridtitle').dom.innerHTML = 'Parts';
        parts_grid.down('#parts_location').hide();
        parts_grid.down('#parts_supplier').hide();
        parts_grid.down('#parts_mfrsku').hide();
        parts_grid.down('#parts_category').show();
        is_resetting = false;
        
        Ext.getCmp('index_pager').moveFirst();
      }
    }]
  })
});//filter()------------------------------------------------------------------

var AddBarcodeWin = new Ext.Window({
  constrain: true,
  width: 300,
  height: 160,
  plain: true,
  title: 'New Barcode Scanned',
  closable: true,
  modal: true,
  closeAction: 'hide',
  items: [
    new Ext.Button({
      text: 'Add as Delta SKU for this Part',
      height: '25%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('addwin_internalsku').setValue(global_code);
          AddBarcodeWin.hide();
        }
      }
    }),
    new Ext.Button({
      text: 'Add as Manufacturer SKU for this Part',
      height: '25%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('addwin_manufacturersku').setValue(global_code);
          AddBarcodeWin.hide();
        }
      }
    }),
    new Ext.Button({
      text: 'Add as Supplier #1 SKU for this Part',
      height: '25%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('addwin_supplier1sku').setValue(global_code);
          AddBarcodeWin.hide();
        }
      }
    }),
    new Ext.Button({
      text: 'Add as Supplier #2 SKU for this Part',
      height: '25%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('addwin_supplier2sku').setValue(global_code);
          AddBarcodeWin.hide();
        }
      }
    })
  ]
});//AddBarcodeWin()-----------------------------------------------------------


var part_tabs = new Ext.Panel({
  layout: 'card',
  activeItem: <?php echo ($onhold ? '1' : ($ondupe ? '2' : '0')); ?>,
  height: 610,
  plain: true,
  tabPosition: 'right',
  items: [ parts_grid, holdgrid, dupegrid ]
});


Ext.onReady(function(){

  //create iframe for printing pdfs
  var body = Ext.getBody();
  var frame = body.createChild({
    tag:'iframe',
    cls:'x-hidden',
    id:'iframe',
    name:'iframe'
  });

  filter.render("index-filter");
  part_tabs.render("index-tabs");


});//onReady()-----------------------------------------------------------------

</script>
