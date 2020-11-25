<script type="text/javascript">
</script>

<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Parts List', 'part/index', 
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-part">Part: <?php echo $part->getName(); ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <?php if (!$part->getActive()): ?>
    <h2 style="font-size: 30px; margin: 5px; color: #aa0000;">PART INACTIVE</h2>
    <?php endif; ?>
    <table class="infotable">
      <tr>
        <td class="label">Part Name:</td>
        <td><?php echo $part->getName(); ?></td>
        <td class="label">Delta SKU:</td>
        <td><?php echo $part->getDefaultVariant()->getInternalSku(); ?></td>
      </tr>
      <tr>
        <td class="label">Manufacturer:</td>
        <td><?php echo ($part->getManufacturer() ? link_to($part->getManufacturer(), 'manufacturer/view?id='.$part->getManufacturerId()) : 'Unknown'); ?></td>
        <td class="label">Manufacturer SKU:</td>
        <td><?php echo ($part->getDefaultVariant()->getManufacturerSku() ? $part->getDefaultVariant()->getManufacturerSku() : ''); ?></td>
      </tr>
      <?php if ($part->getDescription()): ?>
        <tr><td class="label">Description:</td><td colspan="3"><?php echo nl2br($part->getDescription()); ?></td></tr>
      <?php endif; ?>
      <?php if ($part->getDefaultVariant()->getStockingNotes()): ?>
        <tr><td class="label">Stocking Notes:</td><td colspan="3"><?php echo nl2br($part->getDefaultVariant()->getStockingNotes()); ?></td></tr>
      <?php endif; ?>
      <tr>
        <td class="label">Unit Cost:</td>
        <td><?php echo $part->getDefaultVariant()->outputUnitCost(); ?></td>
        <td class="label">Unit Price:</td>
        <td><?php echo $part->getDefaultVariant()->outputUnitPrice(); ?></td>
      </tr>
      <?php if (($part->getDefaultVariant()->getBrokerFees() && $part->getDefaultVariant()->getBrokerFees() > 0) || ($part->getDefaultVariant()->getShippingFees() && $part->getDefaultVariant()->getShippingFees() > 0)): ?>
        <tr>
          <td class="label">Broker Fees:</td><td>$<?php echo number_format($part->getDefaultVariant()->getBrokerFees(), 2); ?></td>
          <td class="label">Shipping Fees:</td><td>$<?php echo number_format($part->getDefaultVariant()->getShippingFees(), 2); ?></td>
        </tr>
      <?php endif; ?>
      <?php if ($part->getDefaultVariant()->getEnviroLevy() || $part->getDefaultVariant()->getBatteryLevy()): ?>
        <tr>
          <?php if ($part->getDefaultVariant()->getEnviroLevy()): ?>
          <td class="label">Enviro Levy:</td><td>$<?php echo number_format($part->getDefaultVariant()->getEnviroLevy(), 2); ?></td>
          <?php endif; ?>
          <?php if ($part->getDefaultVariant()->getBatteryLevy()): ?>
          <td class="label">Battery Levy:</td><td>$<?php echo number_format($part->getDefaultVariant()->getBatteryLevy(), 2); ?></td>
          <?php endif; ?>
        </tr>
      <?php endif; ?>
      <?php if ($part->getDefaultVariant()->getLocation()): ?>
        <tr>
          <td class="label">Location:</td>
          <td><?php echo $part->getDefaultVariant()->getLocation(); ?></td>
          <td class="label"></td>
          <td class="label"></td>
      <?php endif; ?>
      <?php if ($part->getDefaultVariant()->getTrackInventory()): ?>
        <tr>
          <td class="label">Current In Stock:</td>
          <td><?php echo $part->getQuantity('onhand'); ?></td>
          <td class="label">Minimum In Stock:</td>
          <td><?php echo $part->getDefaultVariant()->getQuantity('minimum');?></td>
        </tr>
        <tr>
          <td class="label">Current On HOLD:</td>
          <td>
<?php
                $part->getDefaultVariant()->calculateCurrentOnHand();
              $hold = $part->getQuantity('onhold', false); 
              if ($hold > 0) echo '<span style="font-weight: bold; color:#e00;">'.$part->getQuantity('onhold').'</span>';
              else echo $part->getQuantity('onhold');
            ?>
          </td>
          <td class="label">Maximum In Stock:</td>
          <td><?php echo $part->getDefaultVariant()->getQuantity('maximum'); ?></td>
        </tr>
        <tr>
          <td class="label">Current Available:</td>
          <td class="highlighted"><?php echo $part->getQuantity('available'); ?></td>
          <td class="label">Current On Order:</td>
          <td>
            <?php
              $order = $part->getQuantity('onorder', false); 
              if ($order > 0) echo '<span style="font-weight: bold;color:#e00;">'.$part->getQuantity('onorder').'</span>';
              else echo $part->getQuantity('onorder');
            ?>
          </td>
        </tr>
        <tr>
        <td class="label"></td>
          <td class="label"></td>
          <td class="label">Date Added:</td>
          <td><?php echo $part->getDefaultVariant()->getCreatedAt('M j, Y'); ?></td>
        </tr>                      
        
      <?php endif; ?>
    </table>

    <div id="view-tabs"></div>

  </div>
</div>


<script type="text/javascript">

var partSuppliersStore =  new Ext.data.JsonStore({
  fields: ['part_supplier_id', 'part_variant_id', 'supplier_id', 'supplier_name', 'supplier_sku', 'notes'],
  autoLoad: true,
  remoteSort: true,
  sorters: [{ property: 'supplier_name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/supplierdatagrid?id='.$part->getDefaultVariant()->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'suppliers',
      totalProperty: 'totalCount',
      idProperty: 'part_supplier_id'
    }
  }
});

var lotsStore = new Ext.data.JsonStore({
  fields: ['id', 'supplier_order_id', 'supplier_id', 'supplier_name', 
           'part_id', 'part_variant_id', 'part_description', 'quantity_received', 
           'quantity_remaining', 'received_date'],
  remoteSort: true,
  sorters: [{property: 'id', direction: 'DESC'}],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/lotsDatagrid?id='.$part->getId()); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'lots',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var purchasesStore =  new Ext.data.JsonStore({
  fields: ['id', 'date', 'description', 'description_url', 'quantity', 'status'],
  remoteSort: true,
  sorters: [{property: 'id', direction: 'DESC'}],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/partinstanceDatagrid?id='.$part->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'instances',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var ordersStore = new Ext.data.JsonStore({
  fields: ['id', 'supplier_order_id', 'supplier_id', 'supplier_name', 
           'part_id', 'part_variant_id', 'part_description', 'quantity_requested', 
           'quantity_completed', 'date_ordered', 'date_expected', 'order_status'],
  remoteSort: true,
  sorters: [{property: 'supplier_order_id', direction: 'DESC'}],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/supplierorderitemDatagrid?id='.$part->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'orderitems',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var customersStore = new Ext.data.JsonStore({
  autoLoad: true,
  fields: ['id','name'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/datagrid'); ?>',
    baseParams: {firstlast: '0'},
    remoteSort: true,
    simpleSortMode: true,
    reader: {
      root: 'customers'
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
    url: '<?php echo url_for('part/categoriestree?selected_node='.$part->getPartCategoryId()); ?>',
    reader: {
      root: 'categories'
    }
  }
});

var supplierStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('supplier/datagrid'); ?>',
    simpleSortMode: true,
    remoteSort: true,
    reader: {
      root: 'suppliers'
    }
  }
});


/*
 * 
 * Add Part directly to Task
 * 
 * 
 */
var partVariantId = <?php echo $part->getDefaultVariant()->getId(); ?>;
var partAvailable = '<?php echo $part->getDefaultVariant()->getCurrentAvailable(); ?>';
var includeEstimate = 0;

var partStatus = 'delivered';

var partLocation = '';
<?php if ($part->getDefaultVariant()->getLocation()): ?>
  //partLocation = "<?php echo $part->getDefaultVariant()->getLocation(); ?>";
  partLocation = '<?php echo str_replace('"', '\"', str_replace("'", "\'",  $part->getDefaultVariant()->getLocation())); ?>';
<?php endif; ?>


//var partName = "<?php echo $part->getName(); ?>";
var partName = '<?php echo str_replace('"', '\"', str_replace("'", "\'",  $part->getName())); ?>';

var unitPrice = null;
<?php if ($part->getDefaultVariant()->calculateUnitPrice()): ?>
  unitPrice = <?php echo $part->getDefaultVariant()->calculateUnitPrice(); ?>;
<?php endif; ?>

var displayUnitPrice = '<?php echo $part->getDefaultVariant()->outputUnitPrice(); ?>';

var unitCost = null;
<?php if ($part->getDefaultVariant()->calculateUnitCost()): ?>
  unitCost = <?php echo $part->getDefaultVariant()->calculateUnitCost(); ?>;
<?php endif; ?>

var partSku = '';
<?php if ($part->getDefaultVariant()->getInternalSku()): ?>
  //partSku = '<?php echo $part->getDefaultVariant()->getInternalSku(); ?>';
  partSku = '<?php echo str_replace('"', '\"', str_replace("'", "\'",  $part->getDefaultVariant()->getInternalSku())); ?>';

<?php endif; ?>

var partBatteryLevy = 0;
<?php if ($part->getDefaultVariant()->getBatteryLevy()): ?>
  partBatteryLevy = '<?php echo $part->getDefaultVariant()->getBatteryLevy(); ?>';
<?php endif; ?>

var partEnviroLevy = 0;
<?php if ($part->getDefaultVariant()->getEnviroLevy()): ?>
  partEnviroLevy = '<?php echo $part->getDefaultVariant()->getEnviroLevy(); ?>';
<?php endif; ?>

var partShippingFees = 0;
<?php if ($part->getDefaultVariant()->getShippingFees()): ?>
partShippingFees = '<?php echo $part->getDefaultVariant()->getShippingFees(); ?>';
<?php endif; ?>

var partBrokerFees = 0;
<?php if ($part->getDefaultVariant()->getBrokerFees()): ?>
partBrokerFees = '<?php echo $part->getDefaultVariant()->getBrokerFees(); ?>';
<?php endif; ?>


var workordersStore = new Ext.data.JsonStore({
  fields: ['id', 'customer', 'boat', 'boattype', 'date', 'status','haulout','haulin','color','for_rigging','category_name', 'progress', 'pst_exempt', 'gst_exempt','tax_exempt','text'],
  remoteSort: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid'); ?>',
    extraParams: { status: 'In Progress', sort: 'id', dir: 'DESC' },
    //simpleSortMode: true,
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


var PartAddWin = new Ext.Window({
  width: 500,
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
        columnWidth: 1,
        layout: 'anchor',
        bodyStyle: 'padding: 5px 5px 5px 5px',
        items: [
      {
            xtype: 'textfield',
            fieldLabel: 'Part Name',
            name: 'name',
            value: partName,
            anchor: '-100',
            disabled: true
          },{
            xtype: 'textfield',
            fieldLabel: 'Part SKU',
            name: 'part_sku',
            value: partSku,
            anchor: '-100',
            disabled: true
          },{
            xtype: 'textfield',
            fieldLabel: 'Location',
            name: 'location',
            value: partLocation,
            anchor: '-100',
            disabled: true
          }]},{
        border: false,
        name: 'Settings',
        columnWidth: 1,
        layout: 'anchor',
        bodyStyle: 'padding: 5px 5px 5px 5px',
        items: [{
          xtype: 'combo',
          width: 360,
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
          store: workordersStore,
          listConfig: { minWidth: 350 },
          queryMode: 'local',
          anyMatch: true,
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
          width: 360,
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
          listConfig: { minWidth: 350 },
          queryMode: 'local'
        },{
          xtype: 'numberfield',
          name: 'quantity',
          id: 'quantity',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Quantity',
          value: 1,
          anchor: '-250',
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
            anchor: '-250',
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
          anchor: '-250'
        },{
          xtype: 'numberfield',
          name: 'broker_fees',
          id: 'broker_fees',
          fieldLabel: 'Broker Fees',
          value: partBrokerFees,
          minValue: 0,
          forcePrecision: true,
          anchor: '-250'
        },{
          xtype: 'numberfield',
          name: 'enviro_levy',
          id: 'enviro_levy',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Environment Levy',
          value: partEnviroLevy,
          anchor: '-250',
          forcePrecision: true,
          allowBlank: false
        },{
          xtype: 'numberfield',
          name: 'battery_levy',
          id: 'battery_levy',
          minValue: 0,
          maxValue: 99999,
          fieldLabel: 'Battery Levy',
          value: 0,
          anchor: '-250',
          forcePrecision: true,
          allowBlank: false
        },{
          itemId: 'pstField',
          xtype: 'acbuttongroup',
          name: 'pstField',
          value: '1',
          anchor: '-100',
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
          value: '1',
          anchor: '-100',
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


        if (partAvailable >= woQuantity || partStatus == 'estimate'){

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
                
                PartAddWin.hide();
                Ext.Msg.hide();
                location.reload(true);


                //reload_tree();
                //partslistStore.load();
              },
              failure: function(){
                Ext.Msg.hide();
                PartAddWin.hide();
                Ext.Msg.hide();
                Ext.Msg.show({
                  icon: Ext.MessageBox.ERROR,
                  buttons: Ext.MessageBox.OK,
                  msg: 'Could not add part! Reload page and try again.',
                  modal: true,
                  title: 'Error'
                });
                reload_tree();
              }
            });
          } else {
            Ext.Msg.show({
                  icon: Ext.MessageBox.ERROR,
                  buttons: Ext.MessageBox.OK,
                  msg: 'There is not enough quantity in stock for this item. There are only ' + partAvailable + '. Please ensure Inventory is available, or add this part as Estimate only',
                  modal: true,
                  title: 'Not Enough Stock'
                });
          }
      }
    },{
      text: 'Cancel',
      handler:function(){
        PartAddWin.hide();
      }
    }
  ]

  })
});//PartAddWin()--------------------------------------------------------------



var PartBarcodeWin = new Ext.Window({
  title: 'Print Barcodes',
  closable: false,
  width: 500,
  height: 400,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.form.FormPanel({
    fieldDefaults: { labelAlign: 'left' },
    standardSubmit: true,
    id: 'partbarform',
    url: '<?php echo url_for('part/barcodes'); ?>',
    target: 'iframe',
    bodyStyle: 'padding: 15px 10px 0 10px;',
    items: [{
      xtype: 'hidden',
      name: 'id',
      value: <?php echo $part->getId(); ?>
    },{
      xtype: 'radiogroup',
      fieldLabel: 'Format',
      columns: 1,
      anchor: '-25',
      items: [
        {name: 'format', boxLabel: 'Dymo 30336 Single Labels (25mm x 65mm)', inputValue: 'single25', checked: true},
        {name: 'format', boxLabel: 'Dymo 30252 Single Labels (28mm x 65mm)', inputValue: 'single28'},
        {name: 'format', boxLabel: 'Dymo 30321 Single Labels (36mm x 89mm)', inputValue: 'single36'},
        {name: 'format', boxLabel: '30 Per Page (Avery 5160 Compatible)', inputValue: '30up'},
        {name: 'format', boxLabel: '80 Per Page (Avery 5167 Compatible)', inputValue: '80up'}
      ],
      listeners: {
        change: function(grp,chkd){
          if (chkd.inputValue == 'single25' || chkd.inputValue == 'single28' || chkd.inputValue=='single36'){
            Ext.getCmp('offsetfield').setValue(0);
            Ext.getCmp('offsetfield').setDisabled(true);
          } else {
            Ext.getCmp('offsetfield').setDisabled(false);
          }
        },
        afterrender: function(grp){
          chkd = grp.getValue();
          if (chkd && (chkd.inputValue == 'single25' || chkd.inputValue == 'single28' || chkd.inputValue == 'single36')){
            Ext.getCmp('offsetfield').setValue(0);
            Ext.getCmp('offsetfield').setDisabled(true);
          } else {
            Ext.getCmp('offsetfield').setDisabled(false);
          }
        }
      }
    },{
      xtype: 'checkbox',
      fieldLabel: 'Price',
      name: 'price',
      boxLabel: 'Yes, Show Price on Barcode',
      inputValue: '1',
      checked: true,
      anchor: '-25'
    },{
      xtype: 'checkbox',
      fieldLabel: 'Name',
      name: 'name',
      boxLabel: 'Yes, Show Name on Barcode',
      inputValue: '1',
      checked: true,
      anchor: '-25'
    },{
      xtype: 'numberfield',
      name: 'offset',
      id: 'offsetfield',
      minValue: 0,
      maxValue: 79,
      fieldLabel: 'Label Offset',
      value: 0,
      anchor: '-300',
      allowBlank: false
    },{
      xtype: 'numberfield',
      name: 'quantity',
      minValue: 1,
      fieldLabel: 'Quantity',
      allowBlank: false,
      anchor: '-300',
      value: 1
    },{
      xtype: 'panel',
      border: false,
      html: '<p style="padding-top: 10px;">Clicking OK will present you with a download of PDF labels for you to print out. To ensure the PDF file lines up with the labels as designed, you should make sure options such as "Auto-Rotate and Center" and "Scale to Fit" are all TURNED OFF in the Adobe Acrobat Reader print dialog.</p>'
    }],

    buttons:[{
      text: 'OK',
      formBind: true,
      handler:function(){
        PartBarcodeWin.hide();
        this.findParentByType('form').getForm().submit();
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
               
                
var PartInvWin = new Ext.Window({
  title: 'Adjust Inventory Level',
  closable: false,
  width: 300,
  height: 125,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.form.FormPanel({
    fieldDefaults: { labelAlign: 'left', labelWidth: 150 },
    id: 'partinvform',
    url: '<?php echo url_for('part/inventory'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px;',
    items: [{
      xtype: 'hidden',
      name: 'id'
    },{
      id: 'partinv_field',
      xtype: 'numberfield',
      name: 'current_on_hand',
      minValue: 0,
      anchor: '-25',
      fieldLabel: 'NEW In-Stock Inventory',
      allowBlank: false
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        PartInvWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Updating Inventory...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Inventory Updated, Reloading Info..."});
            myMask.show();
            location.href = '<?php echo url_for('part/view?id='.$part->getId()); ?>';
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save part. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ PartInvWin.show(); },
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

var PartEditWin = new Ext.Window({
  title: 'Edit Part',
  closable: false,
  width: 700,
  height: 530,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },
    forceLayout: true,
    id: 'parteditform',
    url: '<?php echo url_for('part/edit'); ?>',
    bodyStyle: 'padding: 0 10px 0 10px',
    items: [{
      xtype: 'hidden',
      name: 'id'
    },{
      xtype: 'fieldset',
      title: 'General Information',
      items: [{
        layout: 'column',
        border: false, 
        items: [{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'treecombo',
            panelMaxHeight: 300,
            fieldLabel: 'Category',
            allowBlank: false,
            name: 'part_category_id',
            selectChildren: false,
            canSelectFolders: true,
            rootVisible: false,
            store: categoriesStore,
            anchor: '-25'
          },{
            xtype: 'textfield',
            fieldLabel: 'Part Name',
            allowBlank: false,
            name: 'name',
            anchor: '-25'
          },{
            xtype: 'textarea',
            height: 50,
            name: 'description',
            fieldLabel: 'Description',
            anchor: '-25'
          },{
            xtype: 'textfield',
            name: 'origin',
            fieldLabel: 'Country of Origin',
            anchor: '-25'
          }]
        },{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'textfield',
            id: 'editwin_internalsku',
            fieldLabel: 'Delta SKU',
            name: 'internal_sku',
            anchor: '-25',
            listeners: { focus: barcode_focus, blur: barcode_blur }
          },{
            xtype: 'textfield',
            id: 'editwin_manufacturersku',
            fieldLabel: 'Manufacturer SKU',
            name: 'manufacturer_sku',
            anchor: '-25',
            listeners: { focus: barcode_focus, blur: barcode_blur }
          },{
            xtype: 'combo',
            id: 'manufacturer_field',
            fieldLabel: 'Manufacturer',
            name: 'manufacturer_id',
            queryMode: 'remote',
            anchor: '-25',
            minChars: 2,
            forceSelection: true,
            valueField: 'id',
            displayField: 'name',
            hideTrigger: true,
            listConfig: { minWidth: 300, maxHeight: 200 },
            store: new Ext.data.JsonStore({
              fields: ['id','name'],
              proxy: {
                type: 'ajax', 
                url: '<?php echo url_for('manufacturer/datagrid'); ?>',
                reader: {
                  root: 'manufacturers'
                }
              }
            })
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
              toggleGroup: 'editpartserial',
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
              toggleGroup: 'editpartserial',
              allowDepress: false,
              pressed: true,
              isDefault: true,
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
              toggleGroup: 'editpartactive',
              allowDepress: false,
              pressed: true,
              isDefault: true,
              flex: 1,
              cls: 'buttongroup-first',
              text: 'Active',
              valueField: 1,
              listeners: { toggle: function(btn, pressed){
                if (pressed) btn.prev('hidden').setValue(btn.valueField);
              }}
            },{
              xtype: 'button',
              toggleGroup: 'editpartactive',
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
        columnWidth: 0.5,
        minHeight: 220,
        title: 'Costing & Pricing',
        bodyStyle: 'padding: 5px',
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
            toggleGroup: 'editpartcosting',
            allowDepress: false,
            pressed: true,
            isDefault: true,            
            flex: 1,
            cls: 'buttongroup-first',
            text: 'Last In First Out',
            valueField: 'lifo',
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'editpartcosting',
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
            toggleGroup: 'editpartcosting',
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
          xtype: 'numberfield',
          name: 'unit_cost',
          fieldLabel: 'Specify Cost',
          emptyText: 'Overrides above...',
          forcePrecision: true,
          minValue: 0,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'markup_amount',
          fieldLabel: 'Markup Amount',
          forcePrecision: true,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'markup_percent',
          fieldLabel: 'Markup Percent',
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'shipping_fees',
          fieldLabel: 'Shipping Fees',
          minValue: 0,
          forcePrecision: true,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'broker_fees',
          fieldLabel: 'Broker Fees',
          minValue: 0,
          forcePrecision: true,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'unit_price',
          emptyText: 'Overrides Markups...',
          fieldLabel: 'Specify Price',
          minValue: 0,
          forcePrecision: true,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'enviro_levy',
          minValue: 0,
          fieldLabel: 'Enviro Levy',
          forcePrecision: true,
          anchor: '-50'
        },{
          xtype: 'numberfield',
          name: 'battery_levy',
          minValue: 0,
          fieldLabel: 'Battery Levy',
          forcePrecision: true,
          anchor: '-50'
        }]
      },{
        width: 10,
        border: false,
        html: '&nbsp;'
      },{
        xtype: 'fieldset',
        columnWidth: 0.5,
        anchor: '-10',
        minHeight: 270,
        title: 'Inventory Settings',
        bodyStyle: 'padding: 5px 5px 30px 5px',
        items: [{
          xtype: 'combo',
          id: 'unitscombo',
          name: 'units',
          fieldLabel: 'Units', 
          anchor: '-50',
          displayField: 'text',
          valueField: 'val',
          groupField: 'group',
          triggerAction: 'all',
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
            minWidth: 130,
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
          anchor: '-50',
          items: [{
            xtype: 'hidden',
            id: 'trackinv_field',
            name: 'track_inventory',
            value: 1,
            listeners: { change: function(field, value){
              selBtn = field.next('button[valueField='+value+']');
              if (!selBtn.pressed) selBtn.toggle(true);
            }}
          },{
            xtype: 'button',
            toggleGroup: 'editpartinventory',
            allowDepress: false,
            pressed: true,
            isDefault: true,            
            flex: 1,
            cls: 'buttongroup-first',
            text: 'Yes',
            valueField: 1,
            listeners: { toggle: function(btn, pressed){
              if (pressed) btn.prev('hidden').setValue(btn.valueField);
              Ext.getCmp('minimum_on_hand').setDisabled(!pressed);
              Ext.getCmp('maximum_on_hand').setDisabled(!pressed);
              Ext.getCmp('minimum_on_hand').validate();
            }}
          },{
            xtype: 'button',
            toggleGroup: 'editpartinventory',
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
          xtype: 'numberfield',
          name: 'minimum_on_hand',
          id: 'minimum_on_hand',
          fieldLabel: 'Min On Hand',
          allowBlank: false,
          minValue: 0,
          value: '0',
          anchor: '-100'
        },{
          xtype: 'numberfield',
          name: 'maximum_on_hand',
          id: 'maximum_on_hand',
          fieldLabel: 'Max On Hand',
          allowBlank: true,
          minValue: 0,
          anchor: '-100'
        },{
          xtype: 'numberfield',
          name: 'standard_package_qty',
          id: 'standard_package_qty',
          fieldLabel: 'Standard Package Qty',
          allowBlank: true,
          minValue: 0,
          anchor: '-100'
        },{
          xtype: 'textfield',
          id: 'location',
          name: 'location',
          fieldLabel: 'Storage Location',
          anchor: '-25',
        },{
          xtype: 'textarea',
          id: 'stocking_notes',
          name: 'stocking_notes',
          fieldLabel: 'Stocking Notes',
          height: 80,
          anchor: '-25'          
        }]
      }]
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        PartEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Part...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Part Updated, Reloading Info..."});
            myMask.show();
            location.href = '<?php echo url_for('part/view?id='.$part->getId()); ?>';
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save part. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ PartEditWin.show(); },
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

var SupplierEditWin = new Ext.Window({
  title: 'Supplier',
  closable: false,
  width: 450,
  height: 225,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    fieldDefaults: { labelAlign: 'left', labelWidth: 120 },
    id: 'suppliereditform',
    url: '<?php echo url_for('part/supplierEdit?id='.$part->getId()); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    items: [{
      xtype: 'combo',
      id: 'supplieredit_supfield',
      fieldLabel: 'Supplier',
      name: 'supplier_id',
      queryMode: 'remote',
      minChars: 2,
      anchor: '-100',
      forceSelection: true,
      valueField: 'id',
      allowBlank: false,
      displayField: 'name',
      hideTrigger: true,
      store: supplierStore
    },{
      xtype: 'textfield',
      id: 'supplieredit_skufield',
      fieldLabel: 'Supplier SKU',
      anchor: '-100',
      name: 'supplier_sku'
    },{
      xtype: 'textarea',
      fieldLabel: 'Supplier Notes',
      name: 'notes',
      emptyText: 'Enter price breaks, special order instructions, etc. here...',
      height: 70,
      anchor: '-25'
    },{
      xtype: 'hidden',
      name: 'part_supplier_id'
    },{
      xtype: 'hidden',
      name: 'part_variant_id'
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        SupplierEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Part Supplier...',
          success:function(form,action){
            partSuppliersStore.load();
            form.reset();
            barcode_blur();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save part supplier. Try again later!';
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
        barcode_blur();
      }
    }]
  })
});

var suppliers_grid = new Ext.grid.GridPanel({
  title: 'Suppliers',
  enableColumnHide: false,
  enableHdMenu: false,
  enableColumnMove: false, 
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [
  { 
    xtype: 'rownumberer'
  },{
    header: "Supplier Name",
    flex: 1,
    dataIndex: 'supplier_name',
    sortable: false,
    renderer: function(value,metaData,record){
      if (record.data.notes != ''){
        return value + '<br /><strong>Notes:</strong> '+record.data.notes;
      } else {
        return value;
      }
    }
  },{
    header: 'Supplier SKU',
    dataIndex: 'supplier_sku',
    width: '100',
    sortable: false
  }],

  store: partSuppliersStore,

  bbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Selected\'s SKU/Notes',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_edit')): ?>
          SupplierEditWin.show();
          form = Ext.getCmp('suppliereditform');
          selected = suppliers_grid.getSelectionModel().getSelection()[0];
          form.form.loadRecord(selected);
          supfield = Ext.getCmp('supplieredit_supfield');
          supfield.setRawValue(selected.data.supplier_name);
          supfield.setDisabled(true);
          barcode_focus(Ext.getCmp('supplieredit_skufield'));
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to update part details.');
        <?php endif; ?>
      }
    },'-',{
      text: 'Remove Selected Supplier',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this supplier for this part?<br /><br />This will not work if there are any supplier orders that were made with this part in it, as records are required to be kept of those orders.',
            modal: true,
            title: 'Remove Supplier',
            fn: function(butid){
              if (butid == 'ok'){
                selected = suppliers_grid.getSelectionModel().getSelection()[0].data.part_supplier_id;
                Ext.Ajax.request({
                  url: '<?php echo url_for('part/supplierRemove?id='.$part->getId().'&part_supplier_id='); ?>' + selected,
                  method: 'POST',
                  success: function(){
                    partSuppliersStore.load();
                  },
                  failure: function(){
                    Ext.Msg.show({
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: 'Could not remove supplier!',
                      modal: true,
                      title: 'Error'
                    });
                  }
                });
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to update part details.');
        <?php endif; ?>
      }
    },'->',{
      text: 'Add Part Supplier',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_edit')): ?>
          SupplierEditWin.show();
          Ext.getCmp('supplieredit_supfield').setDisabled(false);
          Ext.getCmp('supplieredit_supfield').focus(true, 200);
          barcode_focus(Ext.getCmp('supplieredit_skufield'));
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to update part details.');
        <?php endif; ?>
      }
    }]
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function (sm, record){
        Ext.getCmp('delete_button').setDisabled(!record);
        Ext.getCmp('edit_button').setDisabled(!record);
      }
    }
  })
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
    },
    /*
    ,{
      xtype: 'acbuttongroup',
      fieldLabel: 'Company',
      anchor: '-100',
      name: 'for_rigging',
      value: '0',
      items: [
        { value: '0', text: 'Delta Services' },
        { value: '1', text: 'Delta Rigging' }
      ]
    },
    */
    {
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
    },{
      xtype: 'numberfield',
      id: 'customerqtyfield',
      fieldLabel: 'Quantity of this part to Add to Order',
      name: 'quantity',
      minValue: 0,
      maxValue: 5000,
      anchor: '-250',
      value: 1
    },{
      xtype: 'hidden',
      name: 'part_variant_id',
      value: '<?php echo $part->getDefaultVariant()->getId(); ?>'      
    }]
  }
});


var purchases_grid = new Ext.grid.GridPanel({
  title: 'Past Purchases',
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'Used In',
    hideable: false,
    dataIndex: 'description',
    sortable: false,
    flex: 1
  },{
    header: 'Qty',
    width: 40,
    align: 'center',
    dataIndex: 'quantity',
    hideable: false,
    sortable: true,
  },{
    header: 'Date',
    dataIndex: 'date',
    sortable: true,
    width: 120
  },{
    header: 'Status',
    dataIndex: 'status',
    sortable: false,
    width: 80
  }],

  store: purchasesStore,
  
  viewConfig: { forceFit: true },

  bbar: new Ext.PagingToolbar({
    pagesize: 25,
    store: purchasesStore,
    emptyMsg: 'No Purchases for this Item',
    items: ['->',{
      text: 'Create a Customer Sale',
      iconCls: 'add',
      handler: function(){ 
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          new Ext.ux.SaleAddWin({
            formConfig:{
              formSuccess: function(form,action,obj){
                if (obj.failed_add){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Sale to complete Adding Item..."});
                  myMask.show();
                  location.href = '<?php echo url_for('sale/view?id='); ?>' + obj.newid + '/addvarid/' + obj.failed_add + '/addqty/' + obj.failed_qty;
                } else {
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Sale..."});
                  myMask.show();
                  location.href = '<?php echo url_for('sale/view?id='); ?>' + obj.newid;
                }
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to create customer sales.');
        <?php endif; ?>
      }
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        purchasesStore.load({params:{start:0, limit:25}});
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
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Information..."});
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

var OrderAddWin = new Ext.Window({
  title: 'Add Order',
  closable: false,
  width: 450,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',
  items: new Ext.FormPanel({
    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },
    id: 'orderaddform',
    url: '<?php echo url_for('supplier_order/add'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    items: [{
      xtype: 'combo',
      id: 'supplierfield',
      fieldLabel: 'Supplier',
      name: 'supplier_id',
      forceSelection: true,
      allowBlank: false,
      valueField: 'supplier_id',
      displayField: 'supplier_name',
      triggerAction: 'all',
      minChars: 2,
      store: partSuppliersStore,
      anchor: '-25',
      queryMode: 'local'
    },{
      xtype: 'fieldset',
      layout: 'anchor', 
      title: 'Stocking Information', 
      items: [{ 
        xtype: 'fieldcontainer', 
        fieldLabel: 'Current Inventory',
        items: [{
          border: false,
          html: '<strong><?php echo $part->getDefaultVariant()->getCurrentOnHand(); ?></strong>'
        }]
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Maximum Inventory',
        items: [{
          border: false,
          html: '<strong><?php echo ($part->getDefaultVariant()->getQuantity('maximum', false) ? $part->getDefaultVariant()->getQuantity('maximum', false) : 'Not Set'); ?></strong>'
        }]
  <?php $default = 1; ?>
  <?php if ($part->getDefaultVariant()->getQuantity('maximum', false)): ?>
      <?php $default = max(0, ($part->getDefaultVariant()->getQuantity('maximum', false) - $part->getDefaultVariant()->getQuantity('onhand', false))); ?>
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Max Order Quantity',
        items: [{
          border: false,
          html: '<strong><?php echo $default; ?></strong>'
        }]
  <?php endif; ?>
  <?php if ($part->getDefaultVariant()->getQuantity('standard', false)): ?>
      <?php
        $std = $part->getDefaultVariant()->getQuantity('standard', false);
        $pkgs = max(1,round($default/$std));
        $default = $pkgs * $std;
      ?>
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Standard Package Quantity',
        items: [{
          border: false,
          html: '<strong><?php echo $part->getDefaultVariant()->getQuantity('standard', false); ?></strong>'
        }]
  <?php endif; ?>  
      },{
        xtype: 'fieldcontainer',
        fieldLabel: 'Stocking Notes',
        items: [{
          border: false,
          html: "<?php echo addslashes(str_replace( "\n", '<br />', $part->getDefaultVariant()->getStockingNotes())); ?>"
        }]
      }]
    },{
      xtype: 'numberfield',
      id: 'supplierqtyfield',
      fieldLabel: 'Quantity to Order',
      name: 'quantity',
      minValue: 0,
      size: 3,
      anchor: '-100',
      value: '<?php echo $default; ?>'
    },{
      xtype: 'hidden',
      name: 'part_variant_id',
      value: '<?php echo $part->getDefaultVariant()->getId(); ?>'
    }],

    buttons:[{
      text: 'Add',
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

var orders_grid = new Ext.grid.GridPanel({
  title: 'Past Orders',
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'Order',
    dataIndex: 'supplier_order_id',
    align: 'center',
    sortable: true,
    width: 50
  },{
    header: 'Supplier',
    dataIndex: 'supplier_name',
    hideable: false,
    sortable: true,
    flex: 1
  },{
    header: 'Qty',
    align: 'center',
    dataIndex: 'quantity_requested',
    hideable: false,
    sortable: true,
    width: 40
  },{
    header: 'Qty Rec\'d',
    align: 'center',
    dataIndex: 'quantity_completed',
    hideable: true,
    sortable: true,
    width: 60,
    renderer: function(val,meta,record){
      if (val < record.data.quantity_requested){
        return '<span style="color:red">'+val+'</span>';
      } else { 
        return val; 
      }
    }
  },{
    header: 'Ordered Date',
    dataIndex: 'date_ordered',
    sortable: true,
    width: 90
  },{
    header: 'Expected Date',
    dataIndex: 'date_expected',
    hideable: true,
    sortable: true,
    width: 90
  },{
    header: 'Order Status',
    dataIndex: 'order_status',
    sortable: false,
    width: 160
  }],

  store: ordersStore,
  
  viewConfig: { forceFit: true },

  bbar: new Ext.PagingToolbar({
    pagesize: 25,
    store: ordersStore, 
    emptyMsg: 'No Orders for this Supplier',
    items: ['->',{
      text: 'Create a New Order',
      iconCls: 'add',
      handler: function(){ 
      <?php if ($sf_user->hasCredential('orders_edit')): ?>
        sf = Ext.getCmp('supplierfield');
        if (sf.getStore().getCount() === 0){
          Ext.Msg.alert("Create Supplier Order Failed", "There are no suppliers defined for this part, so it is not possible to create a supplier order for this part at this time.");
        } else {
          OrderAddWin.show();
          if (sf.getStore().getCount() == 1){
            singleentry = sf.getStore().getAt(0);
            sf.setValue(singleentry.data.supplier_id);
            Ext.getCmp('supplierqtyfield').focus(true, 200);
          }
          else
          {
            Ext.getCmp('supplierfield').focus(true, 200);
          }
        }
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user not have permission to create supplier orders.');
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
          location.href= '<?php echo url_for('supplier_order/view?id='); ?>' + record.data.supplier_order_id ;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to view supplier orders.');
        <?php endif; ?>
      }
    }
  }) 
}); 

var lots_grid = new Ext.grid.GridPanel({
  title: 'Part Lots',
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'Lot',
    dataIndex: 'id',
    align: 'center',
    sortable: true,
    width: 60
  },{
    header: 'Order',
    dataIndex: 'supplier_order_id',
    sortable: true,
    align: 'center',
    width: 60,
    renderer: function(val){
      if (val == '') {
        return ' - ';
      } else { 
        return val; 
      }
    }
  },{
    header: 'Supplier',
    dataIndex: 'supplier_name',
    hideable: false,
    sortable: true,
    flex: 1,
    renderer: function(val){
      if (val == ''){
        return '<span style="color:#999">[Inventory Adjustment]</span>';
      } else {
        return val;
      }
    }
  },{
    header: 'Qty Rec\'d',
    width: 100,
    dataIndex: 'quantity_received',
    hideable: false,
    sortable: true,
    align: 'center'
  },{
    header: 'Qty Left',
    dataIndex: 'quantity_remaining',
    hideable: true,
    sortable: true,
    align: 'center',
    width: 100,
    renderer: function(val,meta,record){
      if (val == '0'){
        return '<span style="color:red"> - </span>';
      } else {
        return val;
      }
    }
  },{
    header: 'Received Date',
    dataIndex: 'received_date',
    sortable: true,
    width: 120
  }],

  store: lotsStore,
  
  viewConfig: { forceFit: true },

  bbar: new Ext.PagingToolbar({
    pagesize: 25,
    store: lotsStore,
    emptyMsg: 'No Lots for this part'
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        lotsStore.load({params:{start:0, limit:25}});
      }
    }
  },

});

var tb = new Ext.Toolbar({
  width: 'auto',
  height: 27,
  items: [{
    text: 'Edit Part Information',
    iconCls: 'partedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_edit')): ?>
        PartEditWin.show();
        Ext.getCmp('parteditform').setDisabled(true);
        Ext.getCmp('parteditform').load({
          url: '<?php echo url_for('part/load?id='.$part->getId()); ?>',
          failure: function (form, action){
            Ext.Msg.alert("Load Failed", "Could not load part info for editing");
            Ext.getCmp('parteditform').setDisabled(false);
            PartEditWin.hide();
          },
          success: function (form, action){
            Ext.getCmp('parteditform').setDisabled(false);
            //manually set the manufacturer combobox, since it can't be loaded
            mf = Ext.getCmp('manufacturer_field');
            mf.getStore().add({id: action.result.data.manufacturer_id, name: action.result.data.manufacturer_name});
            mf.setValue(action.result.data.manufacturer_id);
            ti = Ext.getCmp('trackinv_field');
            ti.fireEvent('check', ti, action.result.data.track_inventory);
            barcodeListener.handleroverride = function(code, symbid){
              global_code = code;
              EditBarcodeWin.show();
            };
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user not have permission to update part details.');
      <?php endif; ?>
    }
  },'-',{
    text: 'Add Part to Workorder',
    iconCls: 'foldergo',
    handler: function(){
        <?php if ($sf_user->hasCredential('workorder_add')): ?>
          
          workordersStore.load();
          PartAddWin.show();
        <?php else: ?>    
          Ext.Msg.alert('Permission Denied','You do not have permission to edit worklogs');
        <?php endif; ?>
      }
    },'-',{
    text: 'Print Barcodes',
    iconCls: 'barcode',
    handler: function(){
      PartBarcodeWin.show();
    }
  },'-',{
    text: 'Update Inventory',
    iconCls: 'inventory',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_inventory')): ?>
        PartInvWin.show();
        Ext.getCmp('partinvform').setDisabled(true);
        Ext.getCmp('partinvform').load({
          url: '<?php echo url_for('part/load?id='.$part->getId()); ?>',
          failure: function (form, action){
            Ext.Msg.alert("Load Failed", "Could not load part info for editing");
            Ext.getCmp('partinvform').setDisabled(false);
            PartInvWin.hide();
          },
          success: function (form, action){
            Ext.getCmp('partinvform').setDisabled(false);
            Ext.getCmp('partinv_field').focus(true, 200);
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user not have permission to update part inventory.');
      <?php endif; ?>
    }
  },'-',{
    text: 'Delete Part',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_delete')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to attempt to delete this part?<br /><br />Note that this process will fail if there have been any purchases or orders made with this part, for recordkeeping purposes. To deactivate this part, set the minimum and current inventory levels to 0.',
          modal: true,
          title: 'Delete Part',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('part/delete?id='.$part->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Part Deleted..."});
                  myMask.show();
                  location.href = '<?php echo url_for('part/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete part!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','Your user not have permission to update part inventory.');
      <?php endif; ?>
    }
  },'->',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }]
});

var part_tabs = new Ext.TabPanel({
    activeTab: 2,
    height:300,
    padding: '15 0 0 0',
    plain:true,
    items:[
      suppliers_grid,
      purchases_grid,
      orders_grid,
      lots_grid,
    {
      title: 'Reports',
      disabled: true
    }]
});


var global_code = '';
var global_type = '';

var addBarcodeAs = function(type, supplier_id) {
  supplier_id = (supplier_id != 'undefined' ? supplier_id : '');
  if (type == 'supplier_sku' && supplier_id == ''){
    Ext.Msg.Alert('Error', 'Supplier Must be Selected!');
    return false;
  }  
  NewBarcodeWin.hide();
  Ext.Ajax.request({
    url: '<?php echo url_for('part/addBarcode'); ?>',
    method: 'POST',
    params: {
      code: global_code, 
      part_variant_id: <?php echo $part->getDefaultVariant()->getId(); ?>,
      type: type,
      supplier_id: supplier_id
    },
    success: function() {
      if (global_type == 'supplier_sku'){
        partSuppliersStore.load();
      } else { 
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Reloading Part Information..."});
        myMask.show();
        location.href = location.href;
      }
    },
    failure: function(){
      Ext.Msg.alert('Error', 'There was a problem saving the barcode. Nothing was changed.');
    }
  });
};

var BarcodeSupplierWin = new Ext.Window({
  width: 350,
  height: 150,
  title: 'Select Supplier to add Barcode To',
  modal: true,
  border: false,
  resizable: false,
  closable: false,
  layout: 'fit',
  closeAction: 'hide',
  items: new Ext.form.FormPanel({
    fieldDefaults: { labelAlign: 'left' },
    bodyStyle: 'padding: 15px 10px 0 10px',
    items: [{
      xtype: 'combo',
      id: 'barcode_supplier',
      forceSelection: true,
      fieldLabel:'Select a supplier',
      editable: false,
      triggerAction: 'all',
      store: partSuppliersStore,
      valueField: 'supplier_id',
      displayField: 'supplier_name',
      allowBlank: false,
      queryMode: 'local',
      anchor: '-25'
    }],

    buttons: [{
      text: 'Ok',
      formBind: true,
      handler: function(){
        this.findParentByType('window').hide();
        addBarcodeAs('supplier_sku', Ext.getCmp('barcode_supplier').getValue());
      }
    },{
      text: 'Cancel',
      handler:function(){
        this.findParentByType('window').hide();
      }
    }]
  })
});

var BarcodeButtonClick = function(type) { 
  global_type = type;
  if (type == 'supplier_sku'){
    suppcount = partSuppliersStore.getCount();
    if (suppcount === 0){
      Ext.Msg.alert('No Suppliers Available', 'There are no suppliers for this part. Add a supplier before adding a supplier SKU!');
    } else if (suppcount == 1){
      addBarcodeAs('supplier_sku', partSuppliersStore.getAt(0).data.supplier_id);
    } else {
      BarcodeSupplierWin.show();
    }
  } else {
    addBarcodeAs(type);
  }
};

var EditBarcodeWin = new Ext.Window({
  constrain: true,
  width: 300,
  height: 80,
  plain: true,
  title: 'New Barcode Scanned',
  closable: true,
  modal: true,
  closeAction: 'hide',
  items: [
    new Ext.Button({
      text: 'Add as Delta SKU for this Part',
      height: '50%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('editwin_internalsku').setValue(global_code);
          EditBarcodeWin.hide();
        }
      }
    }),
    new Ext.Button({
      text: 'Add as Manufacturer SKU for this Part',
      height: '50%',
      width: '100%',
      listeners: {
        click: function() {
          Ext.getCmp('editwin_manufacturersku').setValue(global_code);
          EditBarcodeWin.hide();
        }
      }
    })
  ]
});

var NewBarcodeWin = new Ext.Window({
  constrain: true,
  width: 300,
  height: 125,
  plain: true,
  title: 'New Barcode Scanned',
  closable: true,
  modal: true,
  closeAction: 'hide',
  items: [
    new Ext.Button({
      text: 'Add as Delta SKU for this Part',
      height: '33%',
      width: '100%',
      listeners: {
        click: function() {BarcodeButtonClick('internal_sku');}
      }
    }),
    new Ext.Button({
      text: 'Add as Supplier SKU for this Part',
      height: '33%',
      width: '100%',
      listeners: {
        click: function() {BarcodeButtonClick('supplier_sku');}
      }
    }),
    new Ext.Button({
      text: 'Add as Manufacturer SKU for this Part',
      height: '34%',
      width: '100%',
      listeners: {
        click: function() {BarcodeButtonClick('manufacturer_sku');}
      }
    })
  ]
});



Ext.onReady(function(){
  barcodeListener.misshandleroverride = function(data, code, symbid){
    global_code = code;
    NewBarcodeWin.show();
  };

  //create iframe for barcode download
  var body = Ext.getBody();
  var frame = body.createChild({
    tag:'iframe',
    cls:'x-hidden',
    id:'iframe',
    name:'iframe'
  });

  tb.render('view-toolbar');
  part_tabs.render('view-tabs');
});


</script>
