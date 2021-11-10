<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Sales List', 'sale/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-person"><?php echo ($sale->getForRigging() ? '[Rigging] ' : ''); ?>Sale #<?php echo $sale->getId(); ?> to <?php echo $sale->getCustomer()->getName(); ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <?php
      include_partial('wfCRMPlugin/crm_show', array('contact' => $sale->getCustomer()->getCRM(),
        'include_title' => false));
?>
    <table class="infotable" style="width: 330px; float: left; margin-top: 10px;">
      <tr>
        <td class="label">Current Status:</td>
        <td><?php echo $sale->outputStatus(); ?></td>
      </tr>
      <tr>
        <td class="label">Company:</td>
        <td><?php echo ($sale->getForRigging() ? 'Delta Rigging and Welding' : 'Delta Marine Services'); ?></td>
      </tr>
      <tr>
        <td class="label">Ordered Date:</td>
        <td><?php echo ($sale->getDateOrdered() ? $sale->getDateOrdered('M j, Y - g:i a') : 'Not Yet Completed'); ?></td>
      </tr>
    <?php if ($sale->getGstExempt() || $sale->getPstExempt()): ?>
      <tr>
        <td class="label">PST Exempt:</td>
        <td><?php echo ($sale->getPstExempt() ? '<strong>YES</strong>' : 'No'); ?></td>
      </tr><tr>
        <td class="label">GST Exempt:</td>
        <td><?php echo ($sale->getGstExempt() ? '<strong>YES</strong>' : 'No'); ?></td>
      </tr>
    <?php endif; ?>
    <?php if ($sale->getDiscountPct()): ?> 
      <tr>
        <td class="label">Default Discount:</td>
        <td><strong><?php echo $sale->getDiscountPct(); ?>%</strong></td>
      </tr>
    <?php endif; ?>
    <?php if ($sale->getBoatName()): ?>
      <tr>
        <td class="label">Boat Name:</td>
        <td><strong><?php echo $sale->getBoatName(); ?></strong></td>        
      </tr>
    <?php endif; ?>
    <?php if ($sale->getPoNum()): ?>
      <tr>
        <td class="label">PO Number:</td>
        <td><strong><?php echo $sale->getPoNum(); ?></strong></td>
      </tr>    
    <?php endif; ?>
    <?php  $division = '<td class="label">Division</td><td>'.($sale->getDivision() == 1 ? 'Delta Marine' : 'Elite Marine').'</td>'; ?>
    <?php if($sale->getDivision() == '1' || $sale->getDivision() == '0'){
          echo '<tr>'.$division.'</tr>';
        } 
    ?>  
    </table>
    <div id="actions-buttons" style="float: left; padding: 15px 0 0 15px;"></div>

    <div class="clear"></div>

    <h3 style="margin-top: 15px;">Sale Items</h3>
    <div id="items-grid"></div>
    <div id="items-totals"></div>

    <h3>Invoice and Payments</h3>
    <div id="billing-grid"></div>
    <div id="billing-totals"></div>

  </div>
</div>


<script type="text/javascript">

var totals_template = new Ext.XTemplate(
  '<table class="totalstable">',
  '<tr><td class="label">Subtotal:</td><td class="subtotal">{subtotal}</td></tr>',
  '<tpl if="battery_levy &gt; 0"><tr><td class="label">Battery Levy:</td><td class="fee">{battery_levy}</td></tr></tpl>',
  '<tpl if="enviro_levy &gt; 0"><tr><td class="label">Enviro Levy:</td><td class="fee">{enviro_levy}</td></tr></tpl>',
  '<tpl if="hst &gt; 0"><tr><td class="label">HST:</td><td class="fee">{hst}</td></tr></tpl>',
  '<tpl if="pst &gt; 0"><tr><td class="label">PST:</td><td class="fee">{pst}</td></tr></tpl>',
  '<tpl if="gst &gt; 0"><tr><td class="label">GST:</td><td class="fee">{gst}</td></tr></tpl>',
  '<tr><td class="label">Total:</td><td class="total">{total}</td>',
  '</tr></table>'
);

var billtotals_template = new Ext.XTemplate(
  '<table class="totalstable">',
  '<tr><td class="label">Total Invoices:</td><td class="subtotal">{invoices}</td></tr>',
  '<tr><td class="label">Total Payments:</td><td class="fee">{payments}</td></tr>',
  '<tpl if="parseFloat(owing) == 0"><tr><td class="label">Amount Owing:</td><td class="total">{owing}</td></tr></tpl>',
  '<tpl if="parseFloat(owing) &gt; 0"><tr><td class="label">Amount Owing:</td><td class="total" style="color: #dd5555;">{owing}</td></tr></tpl>',
  '<tpl if="parseFloat(owing) &lt; 0"><tr><td class="label">Refund Owing:</td><td class="total" style="color: orange">{owing}</td></tr></tpl>',
  '</table>'
);

var itemsStore = new Ext.data.JsonStore({
  fields: ['customer_order_item', 'part_variant_id', 'part_id', 'name', 'custom_name', 'sku',
           'units', 'quantity', 'returned', 'delivered', 'unit_cost', 'unit_price', 
           'regular_price', 'regular_markup_pct', 'regular_markup', 'calc_discount',
           'taxable_gst', 'taxable_pst', 'taxable_hst', 'enviro_levy', 'battery_levy', 'total',
           'supplier_order_id', 'serial', 'has_serial_number', 'location', 'undelivered', 'broker_fees', 
           'shipping_fees', 'internal_notes', 'custom_origin'],  
  autoLoad: true,
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('sale/itemsDatagrid?id='.$sale->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'items',
      idProperty: 'customer_order_item'
    }
  },
  listeners: {
    'load': function(store){
      totals_template.overwrite(Ext.get('items-totals'), store.proxy.reader.jsonData);
    }
  }
});

var billingStore = new Ext.data.JsonStore({
  fields: ['invoice_id', 'return_id', 'payment_id', 'date', 'description', 'details', 'amount', 'is_original'],
  autoLoad: true,
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('sale/billingDatagrid?id='.$sale->getId()); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'items'
    }
  },
  listeners: {
    'load': function(store){
      billtotals_template.overwrite(Ext.get('billing-totals'), store.proxy.reader.jsonData);
    }
  }
});

var partSearchStore = new Ext.data.JsonStore({
  fields: ['part_id', 'part_variant_id', 'name', 'sku', 'units', 'available', 
           'track_inventory', 'has_serial_number', 'category_path', 'location'],
  remoteSort: true,
  pageSize: 25,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/datagrid'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'parts',
      idProperty: 'part_id',
      totalProperty: 'totalCount'
    }
  }
});

var suppliersStore = new Ext.data.JsonStore({
  fields: ['part_supplier_id', 'part_variant_id', 'supplier_id', 'supplier_name', 'supplier_sku', 'notes'] ,
  remoteSort: true,
  sorters: [{ property: 'supplier_name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/supplierdatagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'suppliers',
      idProperty: 'supplier_id',
      totalProperty: 'totalCount'
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

//called whenever quantity is changed (Also when selecting an item)
// takes care of showing/hiding special order and serial number panels
function updateItemAddQuantity(){
  qtyfield = Ext.getCmp('addwin_quantity');
  if (parseFloat(qtyfield.getValue()) == addwin_oldquantity){
    return;
  } else {
    addwin_oldquantity = parseFloat(qtyfield.getValue());
  }

  selected = additem_selected_data;
  if (selected && parseFloat(qtyfield.getValue()) > 0){
    //round down if not bulk
    if (selected.units == ''){
      if (parseFloat(qtyfield.getValue()) != Math.floor(parseFloat(qtyfield.getValue())))
      {
        qtyfield.setValue(Math.floor(parseFloat(qtyfield.getValue())));
      }
    }

    if (selected.track_inventory && (parseFloat(qtyfield.getValue()) > parseFloat(selected.available))){
      //select special order action
      if (parseFloat(selected.available) > 0){
        Ext.getCmp('addwin_outaction').setDisabled(false);
      } else {
        Ext.getCmp('addwin_outaction').setValue('all');
        Ext.getCmp('addwin_outaction').setDisabled(true);
      }

      //show special order screen and load supplier
      Ext.getCmp('addwin_specialorder').setVisible(true);
      suppfield = Ext.getCmp('addwin_supplier_id');
      if (suppfield.store.proxy.extraParams['id'] != selected.part_variant_id){
        suppfield.clearValue();
        suppfield.store.proxy.setExtraParam('id', selected.part_variant_id);
        suppfield.setDisabled(true);
        suppfield.getStore().load({
          params: { id: selected.part_variant_id },
          callback: function(){
            suppfield = Ext.getCmp('addwin_supplier_id');
            suppfield.setDisabled(false);
            if (suppfield.getStore().getCount() === 0){
              Ext.Msg.alert(
                'No Suppliers Available',
                'You have entered a quantity larger than the amount on hand, therefore require a special order to fullfill the sale of that quantity. However, this part doesn\'t have any suppliers set up with it! You will have to edit the part to add a supplier or correct the current inventory levels. The new quantity will be set to the maximum available quantity instead.',
                function (btn){
                  selected = additem_selected_data;
                  oldquantity = Ext.getCmp('addwin_quantity').getValue();
                  if (parseFloat(selected.available) === 0){
                    Ext.getCmp('addwin_quantity').setValue(0);
                    Ext.getCmp('addwin_quantity').setDisabled(true);
                  } else {
                    Ext.getCmp('addwin_quantity').setValue(parseFloat(selected.available));
                  }
                }
              );
            } else if (suppfield.getStore().getCount() == 1){
              suppfield.setValue(suppfield.getStore().getAt(0).id);
            } else {
              suppfield.validate();
            }
          } 
        });
      } else {
        Ext.getCmp('addwin_supplier_id').setDisabled(false);
      }
    } else {
      Ext.getCmp('addwin_supplier_id').setDisabled(true);
      Ext.getCmp('addwin_supplier_id').clearInvalid();
      Ext.getCmp('addwin_specialorder').setVisible(false);
    }
    if (selected.has_serial_number){
      serials_quantity = addwin_oldquantity;
      if (addwin_oldquantity > 10){
        serials_quantity = 10;
      }
      serials = Ext.getCmp('addwin_serials');
      serials.setVisible(true);
      
      //show the number of needed inputs
      results = serials.query('textfield');
      for (i=0; i<results.length; i++){
        if (i < serials_quantity){
          results[i].setVisible(true);
        } else {
          if (results[i].isVisible()){
            results[i].setVisible(false);
          }
        }
      }
      
    } else { 
      Ext.getCmp('addwin_serials').setVisible(false); 
    }
  } else {
    Ext.getCmp('addwin_specialorder').setVisible(false);
    Ext.getCmp('addwin_serials').setVisible(false);
  }
}

function AddItemSelect(record,focusquantity,setquantity){
  additem_selected_data = record;
  if (!setquantity) { setquantity = 1; }
  Ext.getCmp('addwin').getLayout().setActiveItem(1);

  //update info if different part selected than before.
  if (Ext.getCmp('addwin_varid').getValue() != record.part_variant_id)
  {
    Ext.getCmp('addwin_varid').setValue(record.part_variant_id);
    Ext.getCmp('addwin_name').el.dom.innerHTML = '<a href="<?php echo url_for('part/view?id=');?>'+record.part_id+'"><strong>'+record.name+'</strong></a>';
    Ext.getCmp('addwin_sku').el.dom.innerHTML = '<strong>'+record.sku+'</strong>';
    if (record.location !=""){
      Ext.getCmp('addwin_location').el.dom.innerHTML = '<strong>'+record.location+'</strong>';
    } else {
      Ext.getCmp('addwin_location').el.dom.innerHTML = '<em><span style="color: #777;">Not Set</span></em>';
    }
    if (record.track_inventory){
      Ext.getCmp('addwin_avail').el.dom.innerHTML = '<strong>'+record.available+' '+record.units+'</strong>';
    } else {
      Ext.getCmp('addwin_avail').el.dom.innerHTML = '<strong>Not Tracked</strong>';
    }
    Ext.getCmp('addwin_varid').validate();
    Ext.getCmp('addwin_quantity').setValue(setquantity);
    addwin_oldquantity = '';

    //serial'd parts have max quantity of 10 at a time
    if (record.has_serial_number){
      Ext.getCmp('addwin_quantity').setMaxValue(10);
      serials = Ext.getCmp('addwin_serials');
      
      //reset serial numbers
      results = serials.query('textfield');
      for (i=0; i<results.length; i++){
        results[i].setValue('');
      }
    } else {
      Ext.getCmp('addwin_quantity').setMaxValue(5000);
    }
  }
  Ext.getCmp('addwin_quantity').setDisabled(false);
  if (focusquantity){
    Ext.getCmp('addwin_quantity').focus(true, 100);
  }
  updateItemAddQuantity();
}

/*
* TODO
*/

function showAddCustomPartWin(partInstId){
  
    var pst_rate = 0;
    var gst_rate = 0;
    var pst_exempt = 0;
    var gst_exempt = 0;

    //config.title = 'Add One-Off Part';
  
  new Ext.ux.PartCustomEditWin(config);
  
};//showAddCustomPartWin()-----------------------------------------------------
//showPartCustomEditWin()----------------------------------------------------


var CustomPartEditWin = new Ext.Window({
  title: 'Add Custom Part',
  closable: false,
  width: 450,
  height: 400,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'customPartEditForm',
    url: '<?php echo url_for('sale/custompartedit?id='.$sale->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'hidden',
      id: 'customadd_idfield',
      name: 'customer_order_item',
      value: 'new',
    },{
      name: 'custom_name',
      id: 'customadd_labelfield',
      fieldLabel: 'Label/Description',
      xtype: 'textfield',
      allowBlank: false,
      anchor: '-25'
    },{
      xtype: 'numberfield',
      name: 'quantity',
      id: 'customadd_quantity',
      fieldLabel: 'Quantity',
      value: 1,
      forcePrecision: true,
      allowBlank: false,
      minValue: 0.001,
      anchor: '-25'
    },{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'unit_price',
          id: 'customadd_price',
          fieldLabel: 'Unit Price',
          minValue: 0,
          allowBlank: false,
          forcePrecision: true,
          anchor: '-25'
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'unit_cost',
          id: 'customadd_cost',
          fieldLabel: 'Unit Cost (Optional)',
          forcePrecision: true,
          minValue: 0,
          anchor: '-25'
        }]
      }]
    },{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'shipping_fees',
          id: 'customadd_shippingfees',
          fieldLabel: 'Shipping Fees',
          minValue: 0,
          allowBlank: true,
          forcePrecision: true,
          anchor: '-25'
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'broker_fees',
          id: 'customadd_brokerfees',
          fieldLabel: 'Broker Fees',
          forcePrecision: true,
          minValue: 0,
          anchor: '-25'
        }]
      }]
    },{
      name: 'serial_number',
      id: 'customadd_serialnumber',
      fieldLabel: 'Serial Number',
      xtype: 'textfield',
      allowBlank: true,
      anchor: '-25'
    },{
      name: 'custom_origin',
      id: 'customadd_origin',
      fieldLabel: 'Country of Origin',
      xtype: 'textfield',
      allowBlank: true,
      anchor: '-25'
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'PST',
      layout: 'hbox',
      width: 300,
      items: [{
        xtype: 'hidden',
        name: 'taxable_pst',
        value: <?php echo ($sale->getPstExempt() ? 0 : 1); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+ (value == '1' ? 1 : 0)+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{          
        xtype: 'button',
        toggleGroup: 'customaddpst',
        allowDepress: false,
        <?php if (!$sale->getPstExempt()): ?>
        pressed: true,
        <?php endif; ?>
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'customaddpst',
        allowDepress: false,
        <?php if ($sale->getPstExempt()): ?>
        pressed: true,
        <?php endif; ?>        
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
        name: 'taxable_gst',
        value: <?php echo ($sale->getGstExempt() ? 0 : 1); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+(value == '1' ? 1 : 0)+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'customaddgst',
        allowDepress: false,
        <?php if (!$sale->getGstExempt()): ?>
        pressed: true,
        <?php endif; ?>
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'customaddgst',
        allowDepress: false,
        <?php if ($sale->getGstExempt()): ?>
        pressed: true,
        <?php endif; ?>        
        flex: 1,
        cls: 'buttongroup-last',
        text: 'GST Exempt',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]        
    },{
      fieldLabel: 'Internal Notes',
      xtype: 'textarea',
      name: 'internal_notes',
      anchor: '-25',
      height: 85      
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        CustomPartEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            form.reset();
            items_grid.getStore().load();
          },
          failure:function(form,action){
            myMsg = '';
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            } else {
              myMsg = 'Could not save changes. Try again later!';
            }
            if (myMsg != ''){
              Ext.Msg.show({
                closable:false, 
                fn: function(){ CustomPartEditWin.show(); },
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


var SaleEditWin = new Ext.Window({
  title: 'Edit Sale Settings',
  closable: false,
  width: 400,
  height: 250,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',
  items: new Ext.FormPanel({
    id: 'saleeditform',
    url: '<?php echo url_for('sale/edit?id='.$sale->getId()); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelWidth: 160, labelAlign: 'left' },
    items: [{
      xtype: 'textfield',
      fieldLabel: 'PO Number',
      maxLength: 126,
      anchor: '-25',
      name: 'po_num',
      value: '<?php echo $sale->getPoNum(); ?>',
    },{     
      xtype: 'textfield',
      fieldLabel: 'Boat Name',
      maxLength: 126,
      anchor: '-25',
      name: 'boat_name',
      value: '<?php echo $sale->getBoatName(); ?>',
    },
    /*
    Removed for_rigging option
    {
      xtype: 'fieldcontainer',
      fieldLabel: 'Company',
      layout: 'hbox',
      anchor: '-25',
      items: [{
        xtype: 'hidden',
        name: 'for_rigging',
        value: <?php echo ($sale->getForRigging() ? '1' : '0'); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'newsalerigging',
        allowDepress: false,
        <?php if (!$sale->getForRigging()): ?>
        pressed: 1,
        <?php endif;?>
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Delta Services',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'newsalerigging',
        allowDepress: false,
        <?php if ($sale->getForRigging()): ?>
        pressed: 1,
        <?php endif;?>        
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
      fieldLabel: 'PST',
      layout: 'hbox',
      anchor: '-25',
      items: [{
        xtype: 'hidden',
        name: 'pst_exempt',
        value: <?php echo ($sale->getPstExempt() ? '1' : '0'); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'editsalepst',
        allowDepress: false,
        <?php if (!$sale->getPstExempt()): ?>
        pressed: 1,
        <?php endif;?>   
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'editsalepst',
        allowDepress: false,
        <?php if ($sale->getPstExempt()): ?>
        pressed: 1,
        <?php endif;?>           
        flex: 1,
        cls: 'buttongroup-last',
        text: 'PST Exempt',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]          
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'GST',
      layout: 'hbox',
      anchor: '-25',
      items: [{
        xtype: 'hidden',
        name: 'gst_exempt',
        value: <?php echo ($sale->getGstExempt() ? '1' : '0'); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+value+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'editsalegst',
        allowDepress: false,
        <?php if (!$sale->getGstExempt()): ?>
        pressed: 1,
        <?php endif;?>  
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'editsalegst',
        allowDepress: false,
        <?php if ($sale->getGstExempt()): ?>
        pressed: 1,
        <?php endif;?>          
        flex: 1,
        cls: 'buttongroup-last',
        text: 'GST Exempt',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]  
    },{
      xtype: 'numberfield',
      fieldLabel: 'Default Part Discount %',
      name: 'discount_pct',
      minValue: 0,
      maxValue: 100,
      value: <?php echo $sale->getDiscountPct(); ?>,
      anchor: '-120'      
    },{
      itemId: 'division',
      xtype: 'combo',
      layout: 'anchor',
      anchor: '-25',
      name: 'division',
      fieldLabel: 'Division',
      queryMode: 'local',
      store: [['1','Delta Marine'],['0','Elite Marine']],
      value: <?php echo ($sale->getDivision() == '1' || $sale->getDivision() == 'Delta Marine'  ?  '\''.'1'.'\'' : '\''.'0'.'\''); ?>,
    }],
    
    buttons: [{
      text: 'Save',
      formBind: true,
      handler:function(){
        SaleEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), { msg: "Sale Updated. Refreshing Page..."});
            myMask.show();
            location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
          },
          failure:function(form,action){
            myMsg = '';
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            } else {
              myMsg = 'Could not save changes. Try again later!';
            }
            if (myMsg != ''){
              Ext.Msg.show({
                closable:false, 
                fn: function(){ SaleEditWin.show(); },
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

var ExpenseEditWin = new Ext.Window({
  title: 'Edit Expense',
  closable: false,
  width: 450,
  height: 200,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'expenseeditform',
    url: '<?php echo url_for('sale/expenseedit?id='.$sale->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'hidden',
      id: 'expenseedit_idfield',
      name: 'customer_order_item',
      value: 'new',
    },{
      name: 'custom_name',
      id: 'expenseedit_labelfield',
      fieldLabel: 'Label/Description',
      xtype: 'textfield',
      allowBlank: false,
      anchor: '-25'
    },{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'unit_price',
          id: 'expenseedit_price',
          fieldLabel: 'Charged Price',
          minValue: 0,
          allowBlank: false,
          forcePrecision: true,
          anchor: '-25'
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'unit_cost',
          id: 'expenseedit_cost',
          fieldLabel: 'Cost (Optional)',
          forcePrecision: true,
          minValue: 0,
          anchor: '-25'
        }]
      }]
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'PST',
      layout: 'hbox',
      width: 300,
      items: [{
        xtype: 'hidden',
        name: 'taxable_pst',
        value: <?php echo ($sale->getPstExempt() ? 0 : 1); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+ (value == '1' ? 1 : 0)+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{          
        xtype: 'button',
        toggleGroup: 'editexpensepst',
        allowDepress: false,
        <?php if (!$sale->getPstExempt()): ?>
        pressed: true,
        <?php endif; ?>
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'editexpensepst',
        allowDepress: false,
        <?php if ($sale->getPstExempt()): ?>
        pressed: true,
        <?php endif; ?>        
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
        name: 'taxable_gst',
        value: <?php echo ($sale->getGstExempt() ? 0 : 1); ?>,
        listeners: { change: function(field, value){
          selBtn = field.next('button[valueField='+(value == '1' ? 1 : 0)+']');
          if (!selBtn.pressed) selBtn.toggle(true);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'expenseeditgst',
        allowDepress: false,
        <?php if (!$sale->getGstExempt()): ?>
        pressed: true,
        <?php endif; ?>
        flex: 1,
        cls: 'buttongroup-first',
        text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST',
        valueField: 1,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      },{
        xtype: 'button',
        toggleGroup: 'expenseeditgst',
        allowDepress: false,
        <?php if ($sale->getGstExempt()): ?>
        pressed: true,
        <?php endif; ?>        
        flex: 1,
        cls: 'buttongroup-last',
        text: 'GST Exempt',
        valueField: 0,
        listeners: { toggle: function(btn, pressed){
          if (pressed) btn.prev('hidden').setValue(btn.valueField);
        }}
      }]        
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        ExpenseEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            form.reset();
            items_grid.getStore().load();
          },
          failure:function(form,action){
            myMsg = '';
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            } else {
              myMsg = 'Could not save changes. Try again later!';
            }
            if (myMsg != ''){
              Ext.Msg.show({
                closable:false, 
                fn: function(){ ExpenseEditWin.show(); },
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


var ItemAddWin = new Ext.Window({
  width: 550,
  height: 550,
  modal: true,
  id: 'addwin',
  closable: false,
  closeAction: 'hide',
  title: 'Add Part to Sale',
  resizable: false,
  layout: 'card',
  activeItem: 0,
  items: [
      new Ext.grid.GridPanel({
        id: 'parts_grid',
        enableColumnMove: false,
        border: false,
        emptyText: 'No matching parts found',
        store: partSearchStore,
        viewConfig: { stripeRows: true, loadMask: true },
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
          width: 140
        },{
          header: "Qty Avail",
          dataIndex: 'available',
          renderer: function(value, metaData, record, rowIndex, colIndex, store) {
              if (value < record.get('min_quantity')){
                return '<span style="color:red;">' + value + '<\/span>';
              }else{
                 return value;
              }
            },
          sortable: true,
          align: 'center',
          width: 60
        }],

        selModel: new Ext.selection.RowModel({
          listeners: {
            selectionchange: function(sm, r){
              Ext.getCmp('addwin_nextbutton').setDisabled(sm.getCount() != 1);
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
            listeners: { keyup: filterPartGrid, change: filterPartGrid }
          },'-',{
            xtype: 'textfield',
            enableKeyEvents: true,
            name: 'skusearch',
            id: 'skusearch',
            paramField: 'sku',
            width: 120,
            emptyText: 'Search By SKU...',
            listeners: { keyup: filterPartGrid, change: filterPartGrid }
          },'-',{
            xtype: 'treecombo',
            paramField: 'category_id',
            displayField: 'text',
            rootVisible: true,
            width: 135,
            emptyText: 'Select Category...',
            store: categoriesStore,
            listeners: { change: filterPartGrid, blur: filterPartGrid }
          }]
        }),

        bbar: new Ext.PagingToolbar({
          id: 'parts_pager',
          store: partSearchStore,
          displayInfo: true,
          displayMsg: 'Dispaying Parts {0} - {1} of {2}',
          emptyMsg: 'No Matching Parts Found'
        }),

        listeners: { 
          afterrender: function(){ 
            partSearchStore.load({params: {start: 0, limit: 25}}); 
          },
          itemdblclick: function(grid,idx){
            AddItemSelect(grid.getSelectionModel().getSelection()[0].data, true);
          }
        },

        buttons: [{
          text: 'Next &gt;',
          id: 'addwin_nextbutton',
          disabled: true,
          handler: function(){
            AddItemSelect(Ext.getCmp('parts_grid').getSelectionModel().getSelection()[0].data, true);
          }
        },{
          text: 'Cancel',
          handler:function(){
            ItemAddWin.hide();
            Ext.getCmp('addwin_form').reset();
            Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
          }
        }]
      }),
    new Ext.form.FormPanel({
      id: 'addwin_form',
      border: false,
      forceLayout: true,
      url: '<?php echo url_for('sale/additem'); ?>',
      bodyStyle: 'padding: 10px 10px 0 10px;',
      items: [{
        id: 'addwin_partid',
        xtype: 'hidden',
        name: 'id',
        value: '<?php echo $sale->getId(); ?>'
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
        items: [{ id: 'addwin_name', html: '--', height: 25 }]
      },{
        layout: 'column',
        border: false,
        items: [{
          border: false,
          columnWidth: 0.5,
          items: [{
            xtype: 'fieldcontainer',
            fieldLabel: 'Part SKU',
            border: false,
            items: [{ id: 'addwin_sku', html: '--', height: 25 }]
          },{
            xtype: 'numberfield',
            id: 'addwin_quantity',
            fieldLabel: 'Quantity',
            name: 'quantity',
            value: 1,
            allowBlank: false,
            disableKeyFilter: true,
            minValue: 0.001,
            width: 175,
            enableKeyEvents: true,
            listeners: { keyup: updateItemAddQuantity, change: updateItemAddQuantity, blur: updateItemAddQuantity }
          }]
        },{
          border: false,
          columnWidth: 0.5,
          items: [{
            xtype: 'fieldcontainer',
            fieldLabel: 'Location',
            border: false,
            items: [{ id: 'addwin_location', html: '--', height: 25 }]
          },{
            xtype: 'fieldcontainer',
            fieldLabel: 'Qty Available',
            border: false,
            items: [{ id: 'addwin_avail', html: '--', height: 25 }]
          }]
        }]
      },{
        xtype: 'fieldset',
        id: 'addwin_specialorder',
        title: 'Special Order Options',
        layout: 'anchor',
        items: [{
          border: false,
          html: '<p><span style="color: #c00; font-weight: bold;">Insufficient Inventory!</span><br />You will need to add a special order (order from the supplier) in order to fullfill this sale item.</p>'
        },{
          id: 'addwin_outaction',
          xtype: 'radiogroup',
          fieldLabel: 'Action',
          columns: 1,
          items: [
            {name: 'orderaction', boxLabel: 'Use available inventory and create a special order for the rest', inputValue: 'split'},
            {name: 'orderaction', boxLabel: 'Create a special order for the entire quantity', inputValue: 'all', checked: true}
          ]
        },{
          xtype: 'combo',
          id: 'addwin_supplier_id',
          fieldLabel: 'Supplier',
          name: 'supplier_id',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          valueField: 'supplier_id',
          displayField: 'supplier_name',
          triggerAction: 'all',
          queryMode: 'remote',
          anchor: '-200',
          store: suppliersStore
        }]
      },{
        xtype: 'fieldset',
        id: 'addwin_serials',
        title: 'Serial Numbers',
        items: [{
          border: false,
          html: '<p>Enter the serial number(s) for the parts used below:</p>'
        },{
          layout: 'column',
          border: false,
          items: [{
            border: false,
            columnWidth: 0.5,
            items: [{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            }]
          },{
            border: false,
            columnWidth: 0.5,
            items: [{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            },{
              xtype: 'textfield',
              name: 'serials[]',
              listeners: { focus: barcode_focus, blur: barcode_blur }
            }]
          }]
        }]
      }],
  
      buttons: [{
        text: '&lt; Back',
        ctCls: 'left',
        handler: function(){
          Ext.getCmp('addwin').getLayout().setActiveItem(0);
          Ext.getCmp('addwin_supplier_id').store.proxy.setExtraParam('id', null);
        }
      },{
        text: 'Add',
        formBind: true,
        handler: function(){
          ItemAddWin.hide();
          Ext.getCmp('addwin_form').getForm().submit({
            waitTitle: 'Please Wait',
            waitMsg: 'Adding Item...',
            success: function(form,action){
              Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
              Ext.getCmp('addwin_form').getForm().reset();
              Ext.getCmp('addwin').getLayout().setActiveItem(0);
              items_grid.getStore().load();
            },
            failure: function(form,action){
              if(action.failureType == 'server'){
                obj = Ext.JSON.decode(action.response.responseText);
                myMsg = obj.errors.reason;
              }else{
                myMsg = 'Could not add item to sale. Try again later!';
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
          Ext.getCmp('addwin').getLayout().setActiveItem(0);
          Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
        }
      }]

    })
  ]
});

var QuickCheckoutWin = new Ext.Window({
  title: 'Quick Checkout',
  closable: false,
  width: 310,
  height: 220,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'checkout_form',
    url: '<?php echo url_for('sale/quickcheckout?id='.$sale->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },
    items: [{
      xtype: 'container',
      padding: '0 0 10 0',
      layout: 'fit',
      items: [{
        xtype: 'hidden',
        name: 'checkout_method',
        allowBlank: false,
        value: '',
        listeners: {
          change: function(field){
            if (field.getValue() == ''){
              Ext.getCmp('checkout_details').setDisabled(true);
              Ext.getCmp('checkout_noncashfields').setVisible(false);
              Ext.getCmp('checkout_tendered').setDisabled(true);
              Ext.getCmp('checkout_cashfields').setVisible(false);
            }
            else if (field.getValue() == 'Cash'){
              Ext.getCmp('checkout_details').setDisabled(true);
              Ext.getCmp('checkout_noncashfields').setVisible(false);
              Ext.getCmp('checkout_tendered').setDisabled(false);
              Ext.getCmp('checkout_cashfields').setVisible(true);
              Ext.getCmp('checkout_tendered').setValue(Ext.getCmp('checkout_amount').getValue());
              Ext.getCmp('checkout_change').setValue('0.00');
              Ext.getCmp('checkout_tendered').focus(true, 200);
            } else {
              Ext.getCmp('checkout_tendered').clearInvalid();
              Ext.getCmp('checkout_tendered').setDisabled(true);
              Ext.getCmp('checkout_cashfields').setVisible(false);
              Ext.getCmp('checkout_details').setDisabled(false);
              Ext.getCmp('checkout_noncashfields').setVisible(true);
              Ext.getCmp('checkout_details').focus(true, 200);
            }
          }
        }        
      },{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Visa',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-first buttongroup-top',
          valueField: 'Visa',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'MasterCard',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-middle buttongroup-top',
          valueField: 'MasterCard',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 2
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'American Express',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-last buttongroup-top',
          valueField: 'American Express',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 2
        }]
      },{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Cash',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-first buttongroup-bottom',
          valueField: 'Cash',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Cheque',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-middle buttongroup-bottom',
          valueField: 'Cheque',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Gift Cert',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-middle buttongroup-bottom',
          valueField: 'Gift Certificate',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1          
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Other',
          toggleGroup: 'checkoutmethod',
          cls: 'buttongroup-last buttongroup-bottom',
          valueField: 'Other',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1               
        }]
      }]
    },{      
      id: 'checkout_amount',
      xtype: 'numberfield',
      name: 'amount',
      allowBlank: false,
      forcePrecision: true,
      minValue: 0,
      anchor: '-75',
      fieldLabel: 'Payment Amount'
    },{
      id: 'checkout_cashfields',
      border: false,
      hidden: true,
      layout: 'anchor',
      items: [{
        id: 'checkout_tendered',
        xtype: 'numberfield',
        name: 'tendered',
        allowBlank: false,
        selectOnFocus: true,
        anchor: '-75',
        forcePrecision: true,
        enableKeyEvents: true,
        fieldLabel: 'Amount Tendered',
        listeners: {
          keyup: function(field){
            chg = field.getValue() - Ext.getCmp('checkout_amount').getValue();
            Ext.getCmp('checkout_change').setValue(chg.toFixed(2));
          }
        }
      },{
        id: 'checkout_change',
        xtype: 'textfield',
        name: 'change',
        anchor: '-75',
        fieldLabel: 'Change Given',
        readOnly: true
      }]
    },{
      id: 'checkout_noncashfields',
      border: false,
      hidden: true,
      layout: 'anchor',
      items: [{
        id: 'checkout_details',
        xtype: 'textfield',
        name: 'checkout_details',
        anchor: '-25',
        fieldLabel: 'Payment Details'
      }]
    }],

    buttons: [{
      text: 'Add',
      formBind: true,
      handler:function(){
        QuickCheckoutWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Completing Sale...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
            myMask.show();
            location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not complete sale. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ QuickCheckoutWin.show(); },
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
        Ext.getCmp('checkout_method').fireEvent('select');
      }
    }]
  })
});


var AddPaymentWin = new Ext.Window({
  title: 'Add Payment/Refund',
  closable: false,
  width: 310,
  height: 220,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'payment_form',
    url: '<?php echo url_for('sale/addpayment?id='.$sale->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },
    items: [{
      xtype: 'container',
      padding: '0 0 10 0',
      layout: 'fit',
      items: [{
        xtype: 'hidden',
        name: 'payment_method',
        allowBlank: false,
        value: '',
        listeners: {
          change: function(field){
            if (field.getValue() == ''){
              Ext.getCmp('payment_details').setDisabled(true);
              Ext.getCmp('payment_noncashfields').setVisible(false);
              Ext.getCmp('payment_tendered').setDisabled(true);
              Ext.getCmp('payment_cashfields').setVisible(false);
            }
            else if (field.getValue() == 'Cash'){
              Ext.getCmp('payment_details').setDisabled(true);
              Ext.getCmp('payment_noncashfields').setVisible(false);
              Ext.getCmp('payment_tendered').setDisabled(false);
              Ext.getCmp('payment_cashfields').setVisible(true);
              Ext.getCmp('payment_tendered').setValue(Ext.getCmp('payment_amount').getValue());
              Ext.getCmp('payment_change').setValue('0.00');
              Ext.getCmp('payment_tendered').focus(true, 200);
            } else {
              Ext.getCmp('payment_tendered').clearInvalid();
              Ext.getCmp('payment_tendered').setDisabled(true);
              Ext.getCmp('payment_cashfields').setVisible(false);
              Ext.getCmp('payment_details').setDisabled(false);
              Ext.getCmp('payment_noncashfields').setVisible(true);
              Ext.getCmp('payment_details').focus(true, 200);
            }
          }
        }        
      },{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Visa',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-first buttongroup-top',
          valueField: 'Visa',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'MasterCard',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-middle buttongroup-top',
          valueField: 'MasterCard',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 2
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'American Express',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-last buttongroup-top',
          valueField: 'American Express',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 2
        }]
      },{
        xtype: 'container',
        layout: 'hbox',
        items: [{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Cash',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-first buttongroup-bottom',
          valueField: 'Cash',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Cheque',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-middle buttongroup-bottom',
          valueField: 'Cheque',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Gift Cert',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-middle buttongroup-bottom',
          valueField: 'Gift Certificate',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1          
        },{
          xtype: 'button',
          enableToggle: true,
          allowDepress: false,
          text: 'Other',
          toggleGroup: 'paymentmethod',
          cls: 'buttongroup-last buttongroup-bottom',
          valueField: 'Other',
          listeners: { toggle: function(btn, pressed){
            if (pressed) btn.up().prev('hidden').setValue(btn.valueField);
          }},
          flex: 1               
        }]
      }]
    },{
      id: 'payment_amount',
      xtype: 'numberfield',
      name: 'amount',
      allowBlank: false,
      forcePrecision: true,
      anchor: '-75',
      fieldLabel: 'Payment Amount'
    },{      
      id: 'payment_cashfields',
      border: false,
      hidden: true,
      layout: 'anchor',
      items: [{
        id: 'payment_tendered',
        xtype: 'numberfield',
        name: 'tendered',
        allowBlank: false,
        selectOnFocus: true,
        anchor: '-75',
        forcePrecision: true,
        enableKeyEvents: true,
        fieldLabel: 'Amount Tendered',
        listeners: {
          keyup: function(field){
            chg = field.getValue() - Ext.getCmp('payment_amount').getValue();
            Ext.getCmp('payment_change').setValue(chg.toFixed(2));
          }
        }
      },{
        id: 'payment_change',
        xtype: 'textfield',
        name: 'change',
        anchor: '-75',
        fieldLabel: 'Change Given',
        readOnly: true
      }]
    },{
      id: 'payment_noncashfields',
      border: false,
      hidden: true,
      layout: 'anchor',
      items: [{
        id: 'payment_details',
        xtype: 'textfield',
        name: 'payment_details',
        anchor: '-25',
        fieldLabel: 'Payment Details'
      }]
    }],

    buttons: [{
      text: 'Add',
      formBind: true,
      handler:function(){
        AddPaymentWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Payment...',
          success:function(form,action){
            billing_grid.getStore().load();
            Ext.getCmp('payment_method').fireEvent('select');
            AddPaymentWin.hide();
            Ext.getCmp('payment_form').getForm().reset();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add payment. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ AddPaymentWin.show(); },
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
        Ext.getCmp('payment_method').fireEvent('select');
      }
    }]
  })
});

var pricing_source_field = null;

function changePricingMethod(field,val){
  if (val){
    pricing_source_field = field.id;
    Ext.each(Ext.getCmp('pricing_options').query('numberfield'), function(item){
      if (item.id == (field.id + '_amt')){
        item.setDisabled(false);
        item.setVisible(true);
        output = 0;
        regcost = parseFloat(Ext.getCmp('editregular_cost').getValue());
        regprice = parseFloat(Ext.getCmp('editregular_price').getValue());
        newprice = parseFloat(Ext.getCmp('editpricing_hidden').getValue());
        ispct = false;
        if (pricing_source_field == 'editpricing_markup_pct'){
          output = ((newprice / regcost) - 1)*100;
          ispct = true;
        } else if (pricing_source_field == 'editpricing_markup_amt'){
          output = newprice - regcost;
        } else if (pricing_source_field == 'editpricing_discount_pct'){
          output = ((regprice - newprice)/regprice)*100;
          ispct = true;
        } else if (pricing_source_field == 'editpricing_discount_amt'){
          output = regprice - newprice; 
        } else if (pricing_source_field == 'editpricing_custom'){
          output = newprice; 
        }
        output = Math.round(output*100)/100;
        if (!ispct){
          output.toFixed(2);
        }
        item.setValue(output);
      } else {
        item.clearInvalid();
        item.setDisabled(true);
        item.setVisible(false);
      }
    });
    updatePricingInfo(Ext.getCmp(field.id + '_amt'));
  }
}

function updatePricingInfo(field){
  output = '';
  regcost = parseFloat(Ext.getCmp('editregular_cost').getValue());
  regprice = parseFloat(Ext.getCmp('editregular_price').getValue());
  if (pricing_source_field != 'editpricing_normal'){
    amt = parseFloat(field.getValue());
  }
  if (pricing_source_field == 'editpricing_normal'){
    output = regprice;
  } else if (pricing_source_field == 'editpricing_markup_pct'){
    output = (1 + (amt/100)) * regcost;
  } else if (pricing_source_field == 'editpricing_markup_amt'){
    output = amt + regcost;
  } else if (pricing_source_field == 'editpricing_discount_pct'){
    output = regprice - (regprice*(amt/100));
  } else if (pricing_source_field == 'editpricing_discount_amt'){
    output = regprice - amt; 
  } else if (pricing_source_field == 'editpricing_custom'){
    output = amt; 
  }
  output = (Math.round(output*100)/100).toFixed(2);
  units = items_grid.getSelectionModel().getSelection()[0].data.units;
  if (units == ''){
    units = 'ea';
  }
  if (output == 'NaN'){
    Ext.get('editpricing_output').dom.innerHTML = 'ERROR: INVALID';
  } else {
    Ext.get('editpricing_output').dom.innerHTML = '$'+output+'/'+units;
  }
  Ext.getCmp('editpricing_hidden').setValue(output);
}

var ItemEditWin = new Ext.Window({
  title: 'Edit Sale Item',
  closable: false,
  width: 450,
  height: 525,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'itemeditform',
    url: '<?php echo url_for('sale/edititem?id='.$sale->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults:  { labelAlign: 'left' },
    items: [{
      xtype: 'hidden',
      id: 'itemeditid',
      name: 'customer_order_item'
    },{
      xtype: 'hidden',
      name: 'unit_price',
      id: 'editpricing_hidden'
    },{
      xtype: 'fieldcontainer',  
      name: 'name',
      fieldLabel: 'Part Name',
      border: false,
      items: [{ id: 'editpricing_name', html: '...', height: 25 }]
    },{
      layout: 'column',
      border: false,
      items: [{
        columnWidth: 0.5,
        border: false,
        layout: 'anchor',
        items: [{
          xtype: 'fieldcontainer',
          fieldLabel: 'Part SKU',
          border: false,
          items: [{ id: 'editpricing_sku', html: '--', height: 25 }]
        },{
          xtype: 'numberfield',
          name: 'quantity',
          id: 'editquantity',
          fieldLabel: 'Quantity',
          allowBlank: false,
          minValue: 0.01,
          anchor: '-25'
        }]
      },{
        columnWidth: 0.5,        
        border: false,
        layout: 'anchor',
        items: [{
          xtype: 'fieldcontainer',
          name: 'location',
          fieldLabel: 'Location',
          border: false,
          items: [{ id: 'editpricing_location', html: '...', height: 25 }]
        },{
          id: 'editserial',
          xtype: 'textfield',
          name: 'serial',
          fieldLabel: 'Serial Number',
          anchor: '-25',
          listeners: { focus: barcode_focus, blur: barcode_blur }
        }]
      }]
    },{
      xtype: 'fieldset',
      title: 'Edit Pricing',
      items: [{
        layout: 'column',
        border: false,
        items: [{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'textfield',
            name: 'unit_cost',
            id: 'editregular_cost',
            fieldLabel: 'Unit Cost',
            disabled: true,
            anchor: '-25'
          }]
        },{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'textfield',
            name: 'regular_price',
            id: 'editregular_price',
            fieldLabel: 'Regular Price',
            disabled: true,
            anchor: '-25'
          }]
        }]
      },{ 
        border: false,
        html: '<hr noshade>'
      },{
        layout: { type: 'table', columns: 3, tdAttrs: { width: '33%'} },
        id: 'pricing_options',
        border: false,
        items: [{
          xtype: 'radio',
          name: 'pricing_method',
          id: 'editpricing_normal',
          boxLabel: 'Use Normal Price',
          hideLabel: true,
          inputValue: 'normal',
          colspan: 2,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          rowspan: 6,
          html: 'Final Unit Price:<br /><span id="editpricing_output" style="font-weight: bold;">...</span>'
        },{
          xtype: 'radio',
          name: 'pricing_method', 
          id: 'editpricing_markup_pct',
          boxLabel: 'Specify Markup %',
          inputValue: 'markup_pct',
          hideLabel: true,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            id: 'editpricing_markup_pct_amt',
            allowBlank: false,
            name: 'editpricing_markup_pct',
            hideLabel: true,
            width: 80,
            minValue: 0,
            enableKeyEvents: true,
            listeners: { keyup: updatePricingInfo, change: updatePricingInfo }
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          id: 'editpricing_markup_amt',
          boxLabel: 'Specify Markup $',
          inputValue: 'markup_amt',
          hideLabel: true,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            id: 'editpricing_markup_amt_amt',
            allowBlank: false,
            name: 'editpricing_markup_amt',
            hideLabel: true,
            forcePrecision: true,
            width: 80,
            minValue: 0,
            enableKeyEvents: true,
            listeners: { keyup: updatePricingInfo, change: updatePricingInfo }
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          id: 'editpricing_discount_pct',
          boxLabel: 'Specify Discount %',
          inputValue: 'discount_pct',
          hideLabel: true,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            id: 'editpricing_discount_pct_amt',
            allowBlank: false,
            name: 'editpricing_discount_pct',
            hideLabel: true,
            minValue: 0,
            width: 80,
            enableKeyEvents: true,
            listeners: { keyup: updatePricingInfo, change: updatePricingInfo }
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          id: 'editpricing_discount_amt',
          boxLabel: 'Specify Discount $',
          inputValue: 'discount_amt',
          hideLabel: true,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            id: 'editpricing_discount_amt_amt',
            allowBlank: false,
            name: 'editpricing_discount_amt',
            hideLabel: true,
            forcePrecision: true,
            minValue: 0,
            width: 80,
            enableKeyEvents: true,
            listeners: { keyup: updatePricingInfo, change: updatePricingInfo }
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          id: 'editpricing_custom',
          boxLabel: 'Specify Custom Price',
          inputValue: 'custom',
          hideLabel: true,
          listeners: { change: changePricingMethod }
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            id: 'editpricing_custom_amt',
            allowBlank: false,
            minValue: 0,
            name: 'editpricing_custom',
            hideLabel: true,
            forcePrecision: true,
            width: 80,
            enableKeyEvents: true,
            listeners: { keyup: updatePricingInfo, change: updatePricingInfo }
          }]
        }]
      }]
    },{
      xtype: 'fieldset',
      title: 'Fees &amp; Taxes',
      items: [{
        layout: 'column',
        border: false,
        items: [{
          border: false,
          columnWidth: 0.5,
          layout: 'anchor',
          items: [{
            xtype: 'numberfield',
            name: 'enviro_levy',
            fieldLabel: 'Enviro Levy',
            forcePrecision: true,
            minValue: 0,
            anchor: '-25'
          },{
            xtype: 'numberfield',
            name: 'battery_levy',
            fieldLabel: 'Battery Levy',
            forcePrecision: true,
            minValue: 0,
            anchor: '-25'
          },{
            xtype: 'numberfield',
            name: 'shipping_fees',
            fieldLabel: 'Shipping Fees',
            minValue: 0,
            forcePrecision: true,
            anchor: '-25'
          },{
            xtype: 'numberfield',
            name: 'broker_fees',
            fieldLabel: 'Broker Fees',
            minValue: 0,
            forcePrecision: true,
            anchor: '-25'
          }]
        }]
      }]
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
            if (obj.specialmodified !== undefined){
              Ext.Msg.alert('Special Order', 'NOTE: The special order item associated with this Sale item had its quantity modified by the same amount.');
            } else if (obj.specialdeleted !== undefined){
              Ext.Msg.alert('Special Order', 'NOTE: The supplier order (special order) associated with this Sale item was removed since it was empty after adjusting for the new quantity entered.');
            }
          },
          failure:function(form,action){
            myMsg = '';
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              if (obj.errors.maximum !== undefined){
                Ext.Msg.alert('Not Enough Inventory', obj.errors.reason, function(but){
                  if (but=='ok'){
                    ItemEditWin.show();
                    Ext.getCmp('editquantity').setValue(obj.errors.maximum);
                  }
                });
              } else {
                myMsg = obj.errors.reason;
              }
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
  enableHdMenu: false,
  enableColumnMove: false,
  emptyText: 'No Items!',
  viewConfig: { loadMask: true },
  store:  itemsStore,
  columns: [{
    xtype: 'rownumberer',
  },{
    header: "Part Name",
    dataIndex: 'name',
    renderer: function(val,meta,r){
      extrainfo = '';
      if (r.data.quantity != r.data.returned){
        if (r.data.enviro_levy > 0){
          extrainfo += '<div style="color: #88cc88; padding-left: 15px;"><strong>+ Enviro Levy </strong>- $'+r.data.enviro_levy+' ea</div>';
        }
        if (r.data.battery_levy > 0){
          extrainfo += '<div style="color: #88cc88; padding-left: 15px;"><strong>+ Battery Levy </strong>- $'+r.data.battery_levy+' ea</div>';
        }
        if (r.data.shipping_fees > 0){
          extrainfo += '<div style="color: #88cc88; padding-left: 15px;"><strong>+ Shipping Fees </strong>- $'+r.data.shipping_fees+' ea</div>';
        }
        if (r.data.broker_fees> 0){
          extrainfo += '<div style="color: #88cc88; padding-left: 15px;"><strong>+ Broker Fees</strong>- $'+r.data.broker_fees+' ea</div>';
        }
        if (r.data.supplier_order_id != ''){
          extrainfo += '<div style="padding-left: 15px;"><span style="color: #e88;">SPECIAL ORDER - </span><a href="<?php echo url_for('supplier_order/view?id='); ?>'+r.data.supplier_order_id+'">Order #'+r.data.supplier_order_id+'</a></div>';
        }
        if (r.data.has_serial_number){
          extrainfo += '<div style="color: #999; padding-left: 15px;"><strong>Serial Number: </strong>';
          if (r.data.serial == '') {
            extrainfo += 'Not Set';
          } else {
            extrainfo += r.data.serial;
          }
          extrainfo += '</div>';
        }

        if (r.data.location != ''){
          extrainfo += '<div style="color: #777;">Location: ' + r.data.location + '</div>';
        } else {
          extrainfo += '<div style="color: #777;"><em>Part Location Not Set</em></div>';
        }

      }
      val = '<strong>'+val+'</strong>';
      if (extrainfo != ''){
        return val+'<br />'+extrainfo;
      } else {
        return val;
      }
    },
    flex: 1
  },{
    header: "SKU",
      dataIndex: 'sku',
      width: 80
  },{
    header: "Qty",
    dataIndex: 'quantity',
    align: 'center',
    width: 60
  },{
    header: "Delivered",
    dataIndex: 'delivered',
    hidden: <?php echo ($sale->getApproved() ? 'false' : 'true'); ?>,
    align: 'center',
    width: 60,
    renderer: function(val,meta,r){
      if (r.data.quantity == r.data.returned){
        return val;
      } else if (val > 0){
        return '<span style="color: green">Yes</span>';
      } else {
        return '<span style="color: red">No</span>';
      }
    }
  },{
    header: "Returned",
    dataIndex: 'returned',
    hidden: <?php echo ($sale->getSentSome() && $sale->hasReturnedItems() ? 'false' : 'true'); ?>,
    align: 'center',
    width: 60,
    renderer: function(val,meta,r){
      if (r.data.quantity == r.data.returned){
        return val;
      } else if (val > 0){
        return '<span style="color: red">'+val+'</span>';
      } else {
        return val;
      }
    } 
  },{
    header: "Discount",
    dataIndex: 'calc_discount',
    align: 'center',
    width: 65
  },{
    header: "Rate",
    dataIndex: 'unit_price',
    align: 'right',
    width: 65
  },{
    header: "Total",
    dataIndex: 'total',
    align: 'right',
    width: 80
  }],

<?php if (!$sale->getFinalized()): ?>

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Selected',
      id: 'editbutton',
      iconCls: 'inventory',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          selected = items_grid.getSelectionModel().getSelection()[0];
          if (selected.data.part_variant_id) {
            ItemEditWin.show();
            form = Ext.getCmp('itemeditform');
            form.form.loadRecord(selected);
            Ext.getCmp('editquantity').focus(true, 200);
            Ext.getCmp('editserial').setVisible(selected.data.has_serial_number);
            if (selected.data.regular_price == selected.data.unit_price){
              Ext.getCmp('editpricing_normal').setValue(true);
              Ext.getCmp('editpricing_normal').fireEvent('check');
            } else {
              <?php if ($sale->getDiscountPct() > 0): ?> 
                Ext.getCmp('editpricing_discount_pct').setValue(true);
                changePricingMethod(Ext.getCmp('editpricing_discount_pct'), true);
              <?php else: ?>
                Ext.getCmp('editpricing_custom').setValue(true);
                changePricingMethod(Ext.getCmp('editpricing_custom'), true);
              <?php endif; ?>
            }
            Ext.getCmp('editpricing_name').el.dom.innerHTML = '<a href="<?php echo url_for('part/view?id='); ?>'+selected.data.part_id+'">'+selected.data.name+'</a>';
            Ext.getCmp('editpricing_sku').el.dom.innerHTML = '<strong>'+selected.data.sku+'</strong>';
            if (selected.data.location !=""){
              Ext.getCmp('editpricing_location').el.dom.innerHTML = '<strong>'+selected.data.location+'</strong>';
            } else {
              Ext.getCmp('editpricing_location').el.dom.innerHTML = '<em><span style="color: #777;">Not Set</span></em>';
            }
            if (selected.data.has_serial_number){
              Ext.getCmp('editquantity').setMinValue(1);
              Ext.getCmp('editquantity').setMaxValue(1);
            } else {
              Ext.getCmp('editquantity').setMinValue(0.001);
              Ext.getCmp('editquantity').setMaxValue(5000);
            }
          } else if (selected.data.shipping_fees || selected.data.broker_fees || selected.data.internal_notes || selected.data.custom_origin || selected.data.serial){
            CustomPartEditWin.show();
            CustomPartEditWin.setTitle('Edit Custom Part / Expense');
            form = Ext.getCmp('customPartEditForm');
            form.form.loadRecord(selected);
          } else {
            ExpenseEditWin.show();
            ExpenseEditWin.setTitle('Edit Expense');
            form = Ext.getCmp('expenseeditform');
            form.form.loadRecord(selected);
          }
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales.');
        <?php endif; ?>
      }
    },'-',{
      text: 'Remove Selected Item',
      id: 'removebutton',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this item?<br /><br />If a special order was created for this item it will also be removed.',
            modal: true,
            title: 'Delete Sale Item',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({title:'Please Wait',msg:'Removing Item, please wait...', closable: false});
                Ext.Ajax.request({
                  url: '<?php echo url_for('sale/deleteitem?id='.$sale->getId().'&customer_order_item='); ?>'+items_grid.getSelectionModel().getSelection()[0].data.customer_order_item,
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
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales.');
        <?php endif; ?>
      }
    },'->',{
      text: 'Add Custom Part',
      iconCls: 'add',
      disabled: false,
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          CustomPartEditWin.show();
          CustomPartEditWin.setTitle('Add Custom Part');
          Ext.getCmp('customPartEditForm').getForm().reset();
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales.');
        <?php endif; ?>
      }
    },'-',{
      text: 'Add Part',
      iconCls: 'add',
      disabled: false,
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          ItemAddWin.show();
          Ext.getCmp('addwin_form').getForm().reset();
          Ext.getCmp('namesearch').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales.');
        <?php endif; ?>
      }
    },'-',{
    text: 'Add Expense',
      iconCls: 'add',
      disabled: false,
      handler: function(){
        <?php if ($sf_user->hasCredential('sales_edit')): ?>
          ExpenseEditWin.show();
          ExpenseEditWin.setTitle('Add Expense');
          Ext.getCmp('expenseeditform').getForm().reset();
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales.');
        <?php endif; ?>
      }
    }]
  }),

  listeners: { 
    itemdblclick: function(grid,idx){
      Ext.getCmp('editbutton').handler();
    } 
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      selectionchange: function (sm, r){
        Ext.getCmp('removebutton').setDisabled(sm.getCount() != 1);
        Ext.getCmp('editbutton').setDisabled(sm.getCount() != 1);
      }
    }
  }) 
<?php else: ?>
  listeners: {
    itemdblclick: function(grid,idx){
      Ext.Msg.alert('Cannot Edit', 'You cannot edit items once the sale is marked as finalized.<br /><br />You can un-finalize the sale to make changes, if you have permissions to do so.');
    }
  },

  selModel: false

<?php endif; ?>
});

var billing_grid = new Ext.grid.GridPanel({
  minHeight: 100,
  enableHdMenu: false,
  enableColumnMove: false,
  emptyText: 'No Items!',
  viewConfig: { loadMask: true },
  store: billingStore,
  columns: [{
    header: "Date",
    dataIndex: 'date',
    width: 130
  },{
    header: "Description",
    dataIndex: 'description',
    renderer: function(val,meta,r){
      outval = val;
      if (r.data.details != ''){
        outval += '<br /><span style="color: #999">'+r.data.details+'</span>';
      }
      return outval;
    },
    flex: 1
  },{
    header: 'Inv. ID',
    dataIndex: 'invoice_id',
    align: 'center',
    width: 60,
  },{
    header: 'Amount',
    dataIndex: 'amount',
    align: 'right',
    renderer: function(val,meta,r){
      if (parseFloat(val) < 0){
        return '<span style="color: red">'+val+'</span>';
      } else {
        return val;
      }
    },
    width: 80
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Delete Selected Item',
      id: 'billing_removebutton',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        selected = billing_grid.getSelectionModel().getSelection()[0];
        if (selected.data.is_original){
          Ext.Msg.alert('Cannot Delete', 'You cannot delete the original sale invoice!<br /><br />Note: If you want to make changes to the sale, you can Un-Finalize the sale. Once the sale is finalized and approved, a new invoice will be generated to reflect any changes made.');
        } else if (selected.data.payment_id != ''){
          <?php if ($sf_user->hasCredential('sales_payments')): ?>
            Ext.Msg.show({
              icon: Ext.MessageBox.QUESTION,
              buttons: Ext.MessageBox.OKCANCEL,
              msg: 'Are you sure you want to delete this payment record? The invoice or return this payment is associated with will be marked as upaid.',
              modal: true,
              title: 'Delete Payment',
              fn: function(butid){
                if (butid == 'ok'){
                  Ext.Msg.show({title:'Please Wait',msg:'Deleting Payment, please wait...', closable: false});
                  Ext.Ajax.request({
                    url: '<?php echo url_for('sale/deletepayment?id='.$sale->getId()); ?>',
                    params: {payment_id: selected.data.payment_id},
                    method: 'POST',
                    success: function(){
                      Ext.Msg.hide();
                      billing_grid.getStore().load();
                    },
                    failure: function(){
                      Ext.Msg.hide();
                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: 'Could not delete payment!',
                        modal: true,
                        title: 'Error'
                      });
                    }
                  });
                }
              }
            });
          <?php else: ?>
            Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete payments.');
          <?php endif; ?>
        } else {
          <?php if ($sf_user->hasCredential('sales_returns')): ?>
            Ext.Msg.show({
              icon: Ext.MessageBox.QUESTION,
              buttons: Ext.MessageBox.OKCANCEL,
              msg: 'Are you sure you want to delete this return?<br /><br />The associated payment records will also be deleted!<br /><br />Note: As an alternative to deleting a return, you can keep records of the previous refund by un-finalizing the sale, and adding more items/quantity back into un-do the return, and then Finalize and Approve the sale again, and record the latest payment.',
              modal: true,
              title: 'Delete Return',
              fn: function(butid){
                if (butid == 'ok'){
                  Ext.Msg.show({title:'Please Wait',msg:'Deleting Return, please wait...', closable: false});
                  Ext.Ajax.request({
                    url: '<?php echo url_for('sale/deletereturn?id='.$sale->getId()); ?>',
                    params: {return_id: selected.data.return_id},
                    method: 'POST',
                    success: function(){
                      Ext.Msg.hide();
                      var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading page..."});
                      myMask.show();
                      location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
                    },
                    failure: function(){
                      Ext.Msg.hide();
                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: 'Could not delete return!',
                        modal: true,
                        title: 'Error'
                      });
                    }
                  });
                }
              }
            });
          <?php else: ?>
            Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete returns.');
          <?php endif; ?>
        }
      }
   },'->',{
    text: 'Print Invoice/Statement',
    iconCls: 'inventory',
    handler: function(){
      <?php if ($sale->getFinalized()): ?>
        window.open('<?php echo url_for('sale/invoice?id='.$sale->getId()); ?>');
      <?php else: ?>
        Ext.Msg.alert('Sale not Finalized', 'To generate and print the invoice, you must Finalize the sale or use the Quick Checkout button.');
      <?php endif; ?>
     }
   }]
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      selectionchange: function (sm, r){
        Ext.getCmp('billing_removebutton').setDisabled(sm.getCount() != 1);
      }
    }
  }) 
});

var return_fields = new Array();

function returnValidate(){
  valid = false;
  for (i = 0; i < return_fields.length; i++){
    if (return_fields[i].getValue() > 0){
      valid = true;
    }
    if (!return_fields[i].isValid()){
      valid = false;
      break;
    }
  }
  Ext.getCmp('addreturn_submit').setDisabled(!valid);
}

var AddReturnWin = new Ext.Window({
  id: 'addreturn_win',
  width: 600,
  height: 500,
  layout: 'fit',
  title: 'Select Items to Return',
  closable: false,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',

  items: new Ext.grid.GridPanel({
    id: 'addreturn_grid',
    enableHdMenu: false,
    enableColumnMove: false,
    clicksToEdit: 1,
    columnLines: true,
    viewConfig: { stripeRows: true},
    store: itemsStore,
    columns: [{
      header: "Part Name",
      dataIndex: 'name',
      renderer: function(val,meta,r){
        extrainfo = '';
        if (r.data.supplier_order_id != ''){
          extrainfo += '<div style="padding-left: 15px;"><span style="color: #e88;">SPECIAL ORDER - </span><a href="<?php echo url_for('supplier_order/view?id='); ?>'+r.data.supplier_order_id+'">Order #'+r.data.supplier_order_id+'</a></div>';
        }
        if (r.data.has_serial_number){
          extrainfo += '<div style="color: #999; padding-left: 15px;"><strong>Serial Number: </strong>';
          if (r.data.serial == '') {
            extrainfo += 'Not Set';
          } else {
            extrainfo += r.data.serial;
          }
          extrainfo += '</div>';
        }

        if (r.data.location != ''){
          extrainfo += '<div style="color: #777;">Location: ' + r.data.location + '</div>';
        } else {
          extrainfo += '<div style="color: #777;"><em>Part Location Not Set</em></div>';
        }

        val = '<strong>'+val+'</strong>';
        if (extrainfo != ''){
          return val+'<br />'+extrainfo;
        } else {
          return val;
        }
      },
      flex: 1
    },{
      header: "SKU",
      dataIndex: 'sku',
      width: 80
    },{
      header: "Qty",
      dataIndex: 'quantity',
      align: 'center',
      width: 50
    },{
      header: "Delivered",
      dataIndex: 'delivered',
      align: 'center',
      width: 65
    },{
      header: "Returned",
      dataIndex: 'returned',
      align: 'center',
      width: 65
    },{
      header: "Return Now",
      editable: true,
      width: 70,
      dataIndex: 'delivered',
      align: 'center',
      renderer: function(val,meta,r){
        returnable = val - r.data.returned;
        if (returnable > 0){
          editfield = new Ext.form.NumberField({
            name: 'returns['+r.data.customer_order_item+']',
            value: 0,
            minValue: 0,
            maxValue: returnable,
            renderToLater: 'editval-'+r.data.customer_order_item,
            allowDecimals: !r.data.has_serial_number,
            width: 40,
            enableKeyEvents: true,
            listeners: { 
              keyup: returnValidate, 
              change: returnValidate
            }
          });
          return_fields.push(editfield);
          return '<div id="editval-'+r.data.customer_order_item+'"></div>';
        } else {
          return 'N/A';
        }
      }
    }],

    selModel: false,

    listeners: {
      afterrender: function(){
        var fillin_returnfields = function(){
          for (i=0; i<return_fields.length; i++){
            return_fields[i].render(return_fields[i].renderToLater);
          }
        };
        Ext.defer(fillin_returnfields, 200);
      }
    }

  }),
  buttons: [{
    id: 'addreturn_submit',
    text: 'Return Items ',
    disabled: true,
    handler: function(){
      Ext.getCmp('addreturn_win').hide();
      //compile selections
      sels = new Array();
      for (i = 0; i < return_fields.length; i++){
        if (return_fields[i].isValid() && (return_fields[i].getValue() > 0)){
          sels.push(return_fields[i].getName()+'='+return_fields[i].getValue());
        }
      }
      Ext.Msg.show({title:'Please Wait',msg:'Setting items as returned, please wait...', closable: false});
      Ext.Ajax.request({
        url: '<?php echo url_for('sale/returnitems?id='.$sale->getId()); ?>',
        method: 'POST',
        params: sels.join('&'),
        success: function(){
          barcodeListener.handleroverride = barcode_prefocus_handler;
          Ext.Msg.hide();
          <?php if (!$sale->hasReturnedItems()): ?>
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
            myMask.show();
            location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
          <?php else: ?>
            itemsStore.load();
            billingStore.load();
          <?php endif; ?>
        },
        failure: function(){
          Ext.Msg.hide();
          Ext.Msg.show({
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK,
            msg: 'Could not return all items!',
            modal: true,
            title: 'Error',
            fn: function(){
              Ext.getCmp('addreturn_win').show();
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
      for (i = 0; i < return_fields.length; i++){
        return_fields[i].reset();
      }
    }
  }]

});



var AddShipmentWin = new Ext.Window({
  id: 'addship_win',
  width: 600,
  height: 500,
  layout: 'fit',
  title: 'Select Items to Ship',
  closable: false,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',

  items: new Ext.grid.GridPanel({
    id: 'addship_grid',
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
        extrainfo = '';
        if (r.data.supplier_order_id != ''){
          extrainfo += '<div style="padding-left: 15px;"><span style="color: #e88;">SPECIAL ORDER - </span><a href="<?php echo url_for('supplier_order/view?id='); ?>'+r.data.supplier_order_id+'">Order #'+r.data.supplier_order_id+'</a></div>';
        }
        if (r.data.has_serial_number){
          extrainfo += '<div style="color: #999; padding-left: 15px;"><strong>Serial Number: </strong>';
          if (r.data.serial == '') {
            extrainfo += 'Not Set';
          } else {
            extrainfo += r.data.serial;
          }
          extrainfo += '</div>';
        }

        if (r.data.location != ''){
          extrainfo += '<div style="color: #777;">Location: ' + r.data.location + '</div>';
        } else {
          extrainfo += '<div style="color: #777;"><em>Part Location Not Set</em></div>';
        }

        val = '<strong>'+val+'</strong>';
        if (extrainfo != ''){
          return val+'<br />'+extrainfo;
        } else {
          return val;
        }
      },
      flex: 1
    },{
      header: "SKU",
      dataIndex: 'sku',
      width: 80
    },{
      header: "Qty",
      dataIndex: 'quantity',
      align: 'center',
      width: 50
    },{
      header: "Delivered",
      dataIndex: 'delivered',
      align: 'center',
      width: 65,
      renderer: function(val){
        if (val > 0){
          return '<span style="color: green">Yes</span>';
        } else {
          return '<span style="color: red">No</span>';
        }
      }
    },{
      header: "Returned",
      dataIndex: 'returned',
      align: 'center',
      width: 65
    },{
      header: "Deliver Now",
      editable: true,
      dataIndex: 'undelivered',
      align: 'center',
      css: 'background-color: #faf0f0; border: 1px solid #d99; vertical-align: middle; font-weight: bold;',
      renderer: function(val){
        if (val === 0){
          return 'N/A';
        } else {
          return val;
        }
      },
      width: 70
    }],

    selModel: new Ext.selection.CheckboxModel({
      listeners: {
        selectionchange: function(sm){
          Ext.getCmp('addship_submit').setDisabled(sm.getCount() === 0);
          selection = sm.getSelection();
          Ext.Array.each(selection, function(sel){
            if (sel.data.undelivered === 0){
              idx = itemsStore.find('customer_order_item',sel.data.customer_order_item);
              this.deselectRow(idx);
            }
          });
        }
      }
    })
  }),
  buttons: [{
    id: 'addship_submit',
    text: 'Mark Selected Items as Shipped',
    disabled: true,
    handler: function(){
      Ext.getCmp('addship_win').hide();
      //compile selections
      sels = new Array();
      selection = Ext.getCmp('addship_grid').getSelectionModel().getSelection();
      Ext.Array.each(selection, function(item){
        sels.push(item.data.customer_order_item);
      });
      Ext.Msg.show({title:'Please Wait',msg:'Setting items as shipped, please wait...', closable: false});
      Ext.Ajax.request({
        url: '<?php echo url_for('sale/shipitems?id='.$sale->getId()); ?>',
        method: 'POST',
        params: { items: sels.join(',') },
        success: function(){
          Ext.Msg.hide();
          <?php if (!$sale->getSentSome()): ?>
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
            myMask.show();
            location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
          <?php else: ?>
            itemsStore.load();
          <?php endif; ?>
        },
        failure: function(){
          Ext.Msg.hide();
          Ext.Msg.show({
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.MessageBox.OK,
            msg: 'Could not ship all items!',
            modal: true,
            title: 'Error',
            fn: function(){
              Ext.getCmp('addship_win').show();
            }
          });
        }
      });
    }
  },{
    text: 'Cancel',
    handler: function(){
      this.findParentByType('window').hide();
    }
  }]

});


var tb = new Ext.Toolbar({
  width: 'auto',
  height: 27,
  items: [{
    text: 'Edit Sale Settings',
    iconCls: 'personedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_edit')): ?>
        SaleEditWin.show();
      <?php else: ?> 
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit customer sales');
      <?php endif; ?>
    }
  },'-',{
    text: 'Go to Customer Details Page',
    iconCls: 'person',
    handler: function(){
      <?php if ($sf_user->hasCredential('customer_view')): ?>
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Loading Customer Info..."});
        myMask.show();
        location.href = '<?php echo url_for('customer/view?id='.$sale->getCustomerId()); ?>';
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to view customer details');
      <?php endif; ?>
    }
  },'-',{    
    text: 'Delete Sale',
    iconCls: 'delete',
    handler: function(){
      <?php if ((!$sale->getApproved() && $sf_user->hasCredential('sales_edit')) || $sf_user->hasCredential('sales_unfinalize')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this sale?<br /><br />Sales that have been paid can\'t be deleted! Instead, perform a return of all items so that inventory is properly adjusted.<br /<br /> Also, sales which contain special order items will NOT delete those special order items from the related supplier order, IF that order has already been marked as sent!',
          modal: true,
          title: 'Delete Sale',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Msg.show({title:'Please Wait',msg:'Deleting Sale, please wait...', closable: false});
              Ext.Ajax.request({
                url: '<?php echo url_for('sale/delete?id='.$sale->getId()); ?>',
                method: 'POST',
                success: function(){
                  Ext.Msg.hide();
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Redirecting..."});
                  myMask.show();
                  location.href = '<?php echo url_for('sale/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.hide();
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete sale!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete sales');
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
              Ext.Msg.alert('Not Found', 'Could not find any parts in this sale with that barcode,'+
                                         ' which were available to be returned.');
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

//this only fires if sale is inactive
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
    params: { code: code, symbid: symbid },
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
              if (ItemAddWin.isVisible() && ItemAddWin.getLayout().activeItem.id == 'addwin_form'){
                if (Ext.getCmp('addwin_varid').getValue() == additem_selected_data.part_variant_id){
                  //INCREASE QUANTITY
                  Ext.getCmp('addwin_quantity').setValue(parseFloat(Ext.getCmp('addwin_quantity').getValue()) + 1);
                  updateItemAddQuantity();

                  //FOCUS NEXT BLANK SERIAL FIELD
                  if (additem_selected_data.has_serial_number){
                    serials_quantity = addwin_oldquantity;
                    if (addwin_oldquantity > 10){
                      serials_quantity = 10;
                      Ext.Msg.alert('Maximum Quantity Reached', 'There is a maximum quantiy of 10 for any parts which record serial numbers. Please add in groups of 10 if necessary.');
                    }
                    serials = Ext.getCmp('addwin_serials');
                    results = serials.query('textfield');
                    for (i=0; i<serials_quantity; i++){
                      if (results[i].getValue() == ''){
                        results[i].focus(true, 200);
                        break;
                      }
                    }
                  }
                } else {
                  //ADD EXISTING ITEM AND RE-OPEN WINDOW
                  ItemAddWin.hide();
                  Ext.getCmp('addwin_form').getForm().submit({
                    waitTitle: 'Please Wait',
                    waitMsg: 'Adding Previous Item...',
                    success: function(form,action){
                      ItemAddWin.show();
                      AddItemSelect(additem_selected_data, false); 
                      //FOCUS SERIAL FIELD
                      if (additem_selected_data.has_serial_number){
                        serials = Ext.getCmp('addwin_serials');
                        results = serials.query('textfield');
                        results[0].focus(true, 200);
                      }
                      items_grid.getStore().load();
                    },
                    failure: function(form,action){
                      if(action.failureType == 'server'){
                        obj = Ext.JSON.decode(action.response.responseText);
                        myMsg = obj.errors.reason;
                      }else{
                        myMsg = 'Could not add item to sale. Try again later!';
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
                ItemAddWin.show();
                AddItemSelect(additem_selected_data, false);

                //FOCUS SERIAL FIELD
                if (additem_selected_data.has_serial_number){
                  serials = Ext.getCmp('addwin_serials');
                  results = serials.query('textfield');
                  results[0].focus(true, 200);
                }
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
    <?php if ($sale->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_edit')): ?>
        Ext.Msg.show({title:'Please Wait',msg:'Updating sale status, please wait...', closable: false});
        Ext.Ajax.request({
          url: '<?php echo url_for('sale/changestatus?status=finalize&id='.$sale->getId()); ?>',
          method: 'POST',
          success: function(){
            Ext.Msg.hide();
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
            myMask.show();
            location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
          },
          failure: function(){
            Ext.Msg.hide();
            Ext.Msg.show({
              icon: Ext.MessageBox.ERROR,
              buttons: Ext.MessageBox.OK,
              msg: 'Could not change sale status!',
              modal: true,
              title: 'Error'
            });
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit sales');
      <?php endif; ?>
    }
  },{
    text: 'Approve',
    width: 90,
    height: 45,
    iconCls: 'approve',
    <?php if ($sale->getApproved() || !$sale->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_approve')): ?>
        Ext.Msg.show({title:'Please Wait',msg:'Updating sale status, please wait...', closable: false});
        Ext.Ajax.request({
          url: '<?php echo url_for('sale/changestatus?status=approve&id='.$sale->getId()); ?>',
          method: 'POST',
          success: function(response){
            Ext.Msg.hide();
            obj = Ext.JSON.decode(response.responseText);
            if (obj.success){
              var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
              myMask.show();
              location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
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
              msg: 'Could not change sale status!',
              modal: true,
              title: 'Error'
            });
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to approve sales');
      <?php endif; ?>
    }
  },{
    text: 'Quick Checkout',
    width: 90,
    height: 45,
    iconCls: 'money',
    <?php if ($sale->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential(array('sales_edit','sales_approve','sales_ship','sales_payments'))): ?>
        QuickCheckoutWin.show();
        amt = 0;
        if (billing_grid && billing_grid.store.proxy.reader.jsonData.payments){
          amt = parseFloat(items_grid.store.proxy.reader.jsonData.total.replace(/,/g,'')) - parseFloat(billing_grid.store.proxy.reader.jsonData.payments.replace(/,/g,''));
        } else {
          amt = items_grid.store.proxy.reader.jsonData.total;
        }
        Ext.getCmp('checkout_amount').setValue(amt);
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to perform a quick checkout.');
      <?php endif; ?>
    }
  },{
    text: 'Un-Finalize',
    width: 90,
    height: 45,
    iconCls: 'reject',
    <?php if (!$sale->getFinalized()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ((!$sale->getApproved() && $sf_user->hasCredential('sales_edit')) || $sf_user->hasCredential('sales_unfinalize')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to unfinalize this sale? When re-finalized, the invoice will be replaced, meaning the amount owing may change. Be sure to apply another payment or refund when done editing.',
          modal: true,
          title: 'Un-Finalize Sale',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Msg.show({title:'Please Wait',msg:'Updating sale status, please wait...', closable: false});
              Ext.Ajax.request({
                url: '<?php echo url_for('sale/changestatus?status=unfinalize&id='.$sale->getId()); ?>',
                method: 'POST',
                success: function(){
                  Ext.Msg.hide();
                  var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page..."});
                  myMask.show();
                  location.href = '<?php echo url_for('sale/view?id='.$sale->getId()); ?>';
                },
                failure: function(){
                  Ext.Msg.hide();
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not change sale status!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to unfinalize this order.');
      <?php endif; ?>
    }
  },{
    text: 'Deliver/Ship',
    width: 90,
    height: 45,
    iconCls: 'ship',
    <?php if (!$sale->getApproved() || $sale->getSentAll()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_ship')): ?>
        AddShipmentWin.show();
        Ext.getCmp('addship_grid').getSelectionModel().selectAll();
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to set items as shipped.');
      <?php endif; ?>
    }
  },{
    text: 'Return Item(s)',
    width: 90,
    height: 45,
    iconCls: 'undo',
    <?php if (!$sale->getSentSome()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_returns')): ?>
        AddReturnWin.show();
        barcode_prefocus_handler = barcodeListener.handleroverride;
        barcodeListener.handleroverride = itemreturnlistener;
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to perform sales returns');
      <?php endif; ?>
    }
  },{
    text: 'Add Payment',
    iconCls: 'money',
    width: 90,
    height: 45,
    <?php if (!$sale->getApproved()) echo 'hidden: true,'; ?>
    handler: function(){
      <?php if ($sf_user->hasCredential('sales_payments')): ?>
      AddPaymentWin.show();
        Ext.getCmp('payment_amount').setValue(parseFloat(billing_grid.store.proxy.reader.jsonData.owing.replace(/,/g,'')));
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to add payments to a sale.');
      <?php endif; ?>
    }
  }]
});

Ext.onReady(function(){
  <?php if (!$sale->getFinalized()): ?>
    barcodeListener.handleroverride = itemaddlistener;
  <?php endif; ?>

  tb.render('view-toolbar');
  items_grid.render('items-grid');
  billing_grid.render('billing-grid');
  actions_buttons.render('actions-buttons');

  //override row display
  items_grid.getView().getRowClass = function(record,index){
    return (record.data.quantity == record.data.returned ? 'disabled-row' : '');
  };

  <?php if ($sf_request->getParameter('addvarid')): ?>
    //load a record via ajax and open window
    Ext.Ajax.request({
      url: '/part/datagrid',
      params: { part_variant_id: <?php echo $sf_request->getParameter('addvarid'); ?> },
      callback : function (opt,success,response){
        if (success){
          data = Ext.decode(response.responseText);
          if (data && data.parts.length > 0){
            if (data.parts && data.parts.length == 1){
              additem_selected_data = data.parts[0];

              //OPEN WINDOW
              ItemAddWin.show();
              AddItemSelect(additem_selected_data, false, <?php echo $sf_request->getParameter('addqty', 1); ?>);

              //FOCUS SERIAL FIELD
              if (additem_selected_data.has_serial_number){
                serials = Ext.getCmp('addwin_serials');
                results = serials.query('textfield');
                results[0].focus(true, 200);
              }
            }
          }
        }
      }
    });
  <?php endif; ?>

});
</script>
