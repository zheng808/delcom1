Ext.onReady(function(){

/*********************************************/
/*      PRINTING STUFF                       */
/*********************************************/


whomStore = new Ext.data.JsonStore({
  fields: ['id','desc', 'name', 'taxable_hst', 'taxable_pst', 'taxable_gst'],
  autoLoad: false,
  proxy: {
    type: 'ajax',
    url: '/work_order/whomDatagrid/id/' + this_workorder_id,
    reader: {
      root: 'whoms'
    }
  }
});

progressStore = new Ext.data.JsonStore({
  fields: ['id', 'desc', 'name'],
  autoLoad: false,
  proxy: {
    type: 'ajax',
    url: '/work_order/progressDatagrid/id/' + this_workorder_id,
    reader: {
      root: 'progress'
    }
  }
});

Ext.define('Ext.ux.TimelogsPrintWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Print Timelogs List',
  width: 400,
  autoShow: true,
  closeAction: 'destroy',

  defaultFormConfig: {
    url: '/work_order/timelogsprint',
    download: !Ext.is.iOS,
    standardSubmit: Ext.is.iOS,
    params: {
      id: null
    },

    fieldDefaults: { labelAlign: 'left' },
    defaults: { anchor: '-25' },

    items: [{
      xtype: 'acbuttongroup',
      fieldLabel: 'Format',
      vertical: true,
      name: 'format',
      value: 'single',
      items: [
        { value: 'single',   text: 'Make a Single List of All Timelogs' },
        { value: 'toplevel', text: 'Group by Top-Level Tasks Only' },
        { value: 'alllevel', text: 'Group by Tasks (All levels)' }
      ],
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Sort By',
      margin: '20 0 5 0',
      name: 'sorting',
      value: 'date',
      items: [
        { value: 'date',     text: 'Date', flex: 2 },
        { value: 'labour',   text: 'Labour Type', flex: 3 },
        { value: 'employee', text: 'Employee', flex: 3 }
      ],
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Include Cost',
      margin: '20 0 5 0',
      name: 'cost',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Show Cost of Timelogs', flex: 3 },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Include Status',
      name: 'status',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Show Status of Timelogs', flex: 3 },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'panel',
      border: false,
      html: '<p style="padding-top: 20px;">Clicking OK will present you with a download of a PDF of the timelogs list for you to print out.</p>'
    }]

  }
});

Ext.define('Ext.ux.PartListPrintWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Print Parts List',
  width: 400,
  autoShow: true,
  closeAction: 'destroy',

  defaultFormConfig: {
    url: '/work_order/partslistprint',
    download: !Ext.is.iOS,
    standardSubmit: Ext.is.iOS,
    params: { 
      id: null
    },

    fieldDefaults: { labelAlign: 'left' },
    defaults: { anchor: '-25' },

    items: [{
      xtype: 'acbuttongroup',
      fieldLabel: 'Format',
      vertical: true,
      name: 'format',
      value: 'single',
      items: [
        { value: 'single',   text: 'Make a Single List of All Parts' },
        { value: 'toplevel', text: 'Group by Top-Level Tasks Only' },
        { value: 'alllevel', text: 'Group by Tasks (All levels)' }
      ],
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Sort By',
      margin: '20 0 5 0',
      name: 'sorting',
      value: 'name',
      items: [
        { value: 'name',     text: 'Part Name', flex: 4 },
        { value: 'sku',      text: 'SKU', flex: 3 },
        { value: 'category', text: 'Part Category', flex: 4 }
      ],
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Include Price',
      margin: '20 0 5 0',
      name: 'price',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Show Prices', flex: 3 },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Include Status',
      name: 'status',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Show Part Status', flex: 3 },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Include Category',
      name: 'category',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Show Part Category', flex: 3 },
        { value: '0', text: 'No' }
      ]            
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Combine Parts',
      name: 'combine',
      value: '1',
      items: [
        { value: '1', text: 'Yes, Combine Identical Parts', flex: 3 },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Show Origin',
      name: 'origin',
      value: '0',
      items: [
        { value: '1', text: 'Yes, Show Country of Origin', flex: 3 },
        { value: '0', text: 'No' }
      ]      
    },{
      xtype: 'panel',
      border: false,
      html: '<p style="padding-top: 10px;">Clicking OK will present you with a download of a PDF of the parts list for you to print out.</p>'
    }]

  }
});

Ext.define('Ext.ux.EstimatePrintWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Print Estimate',
  width: 550,
  autoShow: true,
  closeAction: 'destroy',

  workorder_id: null,
  pst_rate: null,
  gst_rate: null,
  pst_exempt: false,
  gst_exempt: false,
  shop_supplies_pct: null,
  moorage_amt: null,

  doneSetup: function(){
    var me = this;

    me.form.params.id = me.workorder_id;

    if (me.pst_exempt) me.form.down('#pstfield').setValue('0');
    if (me.gst_exempt) me.form.down('#gstfield').setValue('0');

    if (me.pst_rate)   me.form.down('#pstfield button').setText('Charge ' + me.pst_rate + '% PST');
    if (me.gst_rate)   me.form.down('#gstfield button').setText('Charge ' + me.gst_rate + '% GST');

    me.down('#shopfield button').setText('Charge ' + me.shop_supplies_pct + '% Shop Supplies');
    me.down('#mooragefield button').setText('Charge $' + me.moorage_amt + ' Moorage');

    if (me.shop_supplies_pct > 0)       me.down('#shopfield').setValue('1');
    if (parseFloat(me.moorage_amt) > 0) me.down('#mooragefield').setValue('1');
  },

  defaultFormConfig: {
    url: '/work_order/printestimate',
    download: !Ext.is.iOS,
    standardSubmit: Ext.is.iOS,
    params: {
      id: null
    },

    fieldDefaults: { labelAlign: 'left' },

    items: [{      
      xtype: 'fieldset',
      title: 'Estimate Detail',
      hideLabels: true,
      layout: 'anchor',
      defaults: { labelWidth: 120 },
      items: [{        
        xtype: 'acbuttongroup',
        fieldLabel: 'Listing Format',
        name: 'subtasks',
        value: '1',
        items: [
          { value: '1', text: 'Divide into Tasks'},
          { value: '0', text: 'Show Single List'}
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Empty Tasks',
        name: 'show_blank',
        value: '1',
        items: [
          { value: '1', text: 'Show Empty Tasks'},
          { value: '0', text: 'Hide Empty Tasks'}
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Show Parts',
        name: 'parts_detail',
        value: 'all',
        items: [
          { value: 'all', text: 'All Estimates', flex: 2},
          { value: 'allused', text: 'Both Estimated & Used', flex: 3},
          { value: 'total', text: 'Total Only', flex: 2}
        ]        
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Show Labour',
        name: 'labour_detail',
        value: 'all',
        items: [
          { value: 'all', text: 'All Estimates', flex: 2},
          { value: 'allused', text: 'Both Estimated & Used', flex: 3},
          { value: 'total', text: 'Total Only', flex: 2}
        ]        
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Show Expenses',
        name: 'other_detail',
        value: 'all',
        items: [
          { value: 'all', text: 'All Estimates', flex: 2},
          { value: 'allused', text: 'Both Estimated & Used', flex: 3},
          { value: 'total', text: 'Total Only', flex: 2}
        ]
      }]          
    },{
      xtype: 'fieldset',
      title: 'Extra Information',
      hideLabels: true,
      layout: 'anchor',
      defaults: { labelWidth: 120 },
      items: [{
        xtype: 'acbuttongroup',
        fieldLabel: 'Notes',
        name: 'estimate_notes',
        value: '1',
        items: [
          { value: '1', text: 'Show Estimate Notes'},
          { value: '0', text: 'Don\'t Show' }
        ]
      },{
        fieldLabel: 'Estimated Delivery',
        xtype: 'textfield',
        name: 'delivery_time',
        emptyText: 'eg, 2-3 weeks',
        anchor: '0'
      }]
    },{      
      xtype: 'fieldset',
      title: 'Billing Options',
      hideLabels: true,
      defaults: { labelWidth: 120, anchor: '-100' },
      layout: 'anchor',
      items: [{
        xtype: 'acbuttongroup',
        itemId: 'pstfield',
        fieldLabel: 'PST',
        name: 'taxable_pst',
        value: '1',
        items: [
          { value: '1', text: 'Charge PST', flex: 5 },
          { value: '0', text: 'PST Exempt', flex: 3 }
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'GST',
        itemId: 'gstfield',
        name: 'taxable_gst',
        value: '1',
        items: [
          { value: '1', text: 'Charge GST', flex: 5 },
          { value: '0', text: 'GST Exempt', flex: 3 }
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Shop Supplies',
        itemId: 'shopfield',
        name: 'shop_supplies',
        value: '0',
        items: [
          { value: '1', text: 'Charge Shop Supplies', flex: 5 },
          { value: '0', text: 'No Charge', flex: 3 }
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Power/Moorage',
        itemId: 'mooragefield',
        name: 'moorage',
        value: '0',
        items: [
          { value: '1', text: 'Charge Moorage', flex: 5 },
          { value: '0', text: 'No Charge', flex: 3 }
        ]        
      }]
    }]

  }
});

Ext.define('Ext.ux.WorkorderPrintWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Print Workorder',
  width: 720,
  autoShow: true,
  closeAction: 'destroy',

  workorder_id: null,
  pst_rate: null,
  gst_rate: null,
  pst_exempt: false,
  gst_exempt: false,
  shop_supplies_pct: null,
  moorage_amt: null,

  doneSetup: function(){
    var me = this;

    me.form.params.id = me.workorder_id;

    if (me.pst_exempt) me.down('#pstfield').setValue('0');
    if (me.gst_exempt) me.down('#gstfield').setValue('0');

    if (me.pst_rate)   me.down('#pstfield button').setText('Charge ' + me.pst_rate + '% PST');
    if (me.gst_rate)   me.down('#gstfield button').setText('Charge ' + me.gst_rate + '% GST');

    me.down('#shopfield button').setText('Charge ' + me.shop_supplies_pct + '% Shop Supplies');
    me.down('#mooragefield button').setText('Charge $' + me.moorage_amt + ' Moorage');

    if (me.shop_supplies_pct > 0)       me.down('#shopfield').setValue('1');
    if (parseFloat(me.moorage_amt) > 0) me.down('#mooragefield').setValue('1');

    var whomfield = me.form.down('#whomfield');
    me.mon(whomfield, 'change', me.whomChange, me);
    if (whomfield.getStore().getCount() == 0){
        whomfield.getStore().load({ scope: this, callback: function(records){
          var whomfield = this.down('#whomfield');
          whomfield.setValue(whomfield.getStore().getAt(0).data.id);
          whomfield.fireEvent('select', whomfield);
        }});
    } else {
      whomfield.setValue(whomfield.getStore().getAt(0).data.id);
      whomfield.fireEvent('select', whomfield);
    }

    var progressfield = me.form.down('#progressfield');
    me.mon(progressfield, 'change', me.progressChange, me);    
    if (progressfield.getStore().getCount() == 0){
        progressfield.getStore().load({ scope: this, callback: function(records){
          var progressfield = this.down('#progressfield');
          progressfield.setValue('final');
          progressfield.fireEvent('select', progressfield);
        }});
    } else {
      progressfield.setValue('final');
      progressfield.fireEvent('select', progressfield);
    }
  },

  whomChange: function(f,newval,oldval){
    var me = this;
    var r = f.findRecordByValue(newval);

    if (f.getValue() && r){
      me.form.down('#pstfield').setValue(r.data.taxable_pst);
      me.form.down('#gstfield').setValue(r.data.taxable_gst);
      me.form.down('#shopfield').setValue(newval == 'cust');
      me.form.down('#mooragefield').setValue(newval == 'cust');
      me.form.down('#discountsfield').setValue(newval == 'cust');
    }
  },

  progressChange: function(f,newval,oldval){
    var me = this;
    var r = f.findRecordByValue(newval);
    var whomfield = me.form.down('#whomfield');

    if (f.getValue() && r)
    {
      me.form.down('#showprogress').setValue(r.data.id != 'final');
      me.form.down('#mooragefield').setValue(whomfield.getValue() == 'cust' && r.data.id == 'final');
    }
  },


  defaultFormConfig: {
    url: '/work_order/print',
    params: {
      id: null
    },
    download: !Ext.is.iOS,
    standardSubmit: Ext.is.iOS,

    fieldDefaults: { labelAlign: 'left' },
    items: [{
      layout: 'hbox',
      border: false,
      items: [{
        border: false,
        flex: 6,
        items: [{
          xtype: 'fieldset',
          title: 'Billing Options',
          fieldDefaults: { labelWidth: 120 },
          items: [{
            itemId: 'whomfield',
            fieldLabel: 'Generate Invoice For',
            xtype: 'combo',
            anchor: '0',
            allowBlank: false,
            forceSelection: true,
            queryMode: 'local',
            editable: false,
            name: 'whom_id',
            valueField: 'id',
            displayField: 'name',
            listConfig: { 
              minWidth: 300, 
              tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{name} ({desc})</li></tpl>'
            },
            triggerAction: 'all',
            listeners: {
              //makes sure form validity is changed as soon as selection is made
              select: function(me) { me.validate(); }
            },
            store: whomStore
          },{
            itemId: 'progressfield',
            fieldLabel: 'Invoice to Print',
            xtype: 'combo',
            anchor: '0',
            allowBlank: false,
            forceSelection: true,
            editable: false,
            queryMode: 'local',
            name: 'invoice_id',
            valueField: 'id',
            displayField: 'name',
            value: 'final',
            listConfig: { 
              tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{name} ({desc})</li></tpl>'
            },
            triggerAction: 'all',
            listeners: {
              //makes sure form validity is changed as soon as selection is made
              select: function(me) { me.validate(); }
            },
            store: progressStore
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Show New Charges Separately',
            itemId: 'showprogress',
            name: 'show_progress',
            value: '0',
            items: [
              { value: '1', text: 'Yes, New Items Separate', flex: 5 },
              { value: '0', text: 'No', flex: 3 }
            ]               
          },{
            xtype: 'acbuttongroup',
            itemId: 'pstfield',
            fieldLabel: 'PST',
            name: 'taxable_pst',
            value: '1',
            items: [
              { value: '1', text: 'Charge PST', flex: 5 },
              { value: '0', text: 'PST Exempt', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'GST',
            itemId: 'gstfield',
            name: 'taxable_gst',
            value: '1',
            items: [
              { value: '1', text: 'Charge GST', flex: 5 },
              { value: '0', text: 'GST Exempt', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Shop Supplies',
            itemId: 'shopfield',
            name: 'shop_supplies',
            value: '0',
            items: [
              { value: '1', text: 'Charge Shop Supplies', flex: 5 },
              { value: '0', text: 'No Charge', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Power/Moorage',
            itemId: 'mooragefield',
            name: 'moorage',
            value: '0',
            items: [
              { value: '1', text: 'Charge Moorage', flex: 5 },
              { value: '0', text: 'No Charge', flex: 3 }
            ]        
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Discounts',
            itemId: 'discountsfield',
            name: 'show_discounts',
            value: '1',
            items: [
              { value: '1', text: 'Show Discount Amounts', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Show Origin',
            name: 'origin',
            value: '0',
            items: [
              { value: '1', text: 'Show Origin Country of Parts', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]
          }]
        },{
          xtype: 'fieldset',
          title: 'Layout Options',
          layout: 'anchor',
          fieldDefaults: { labelWidth: 120 },
          items: [{
            xtype: 'acbuttongroup',
            fieldLabel: 'Task Notes',
            name: 'tasks_notes',
            value: '1',
            items: [
              { value: '1', text: 'Show Public Task Notes', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Tasks Summary',
            name: 'summary_tasks',
            value: '1',
            items: [
              { value: '1', text: 'Show Costs By Task', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Parts Summary',
            name: 'summary_parts',
            value: '1',
            items: [
              { value: '1', text: 'Show Parts Cost by Category', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]            
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Labour Summary',
            name: 'summary_labour',
            value: '1',
            items: [
              { value: '1', text: 'Show Labour Cost by Type', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]            
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Payments Page',
            itemId: 'paymentspage',
            name: 'payments',
            value: '1',
            items: [
              { value: '1', text: 'Show Payments Page', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]            
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'Previous Payments',
            name: 'payments_existing',
            value: '1',
            visibleIf: 'paymentspage',
            items: [
              { value: '1', text: 'Show Previous Payments', flex: 5 },
              { value: '0', text: 'Don\'t Show', flex: 3 }
            ]                        
          }]
        }]
      },{
        border: false,
        flex: 4,
        padding: 0,
        margin: '0 0 0 15',

        items: [{
          xtype: 'fieldset',
          title: 'Parts Details',
          items: [{
            xtype: 'acbuttongroup',
            name: 'parts_detail',
            itemId: 'partsdetail',
            vertical: true,
            value: 'all',
            items: [
              { value: 'none', text: 'No Detail' },
              { value: 'all', text: 'Show All Parts Used' },
              { value: 'cat', text: 'Show Only Totals by Part Category' },
              { value: 'value', text: 'Show Parts Above a Certain Value...'}
            ]
          },{
            xtype: 'numberfield',
            name: 'parts_value_min',
            fieldLabel: 'Min Parts Value to Show:',
            visibleIf: {
              itemId: 'partsdetail',
              compareType: '==',
              compareValue: 'value',
              onlyDisable: true
            },
            labelWidth: 150,
            minValue: 0,
            disabled: true,
            value: 10,
            width: 210,
            padding: '0 0 10 0'
          }]
        },{
          xtype: 'fieldset',
          title: 'Labour Details',
          layout: 'anchor',
          items: [{
            xtype: 'acbuttongroup',
            name: 'labour_detail',
            vertical: true,
            value: 'cat',
            items: [
              { value: 'none', text: 'No Detail' },
              { value: 'cat', text: 'Subtotal by Labour Type' },              
              { value: 'all', text: 'Individual Logs' },
              { value: 'allnotes', text: 'Individual Logs w/ Notes' }
            ]
          }]
        },{
          xtype: 'fieldset',
          title: 'Expenses Details',
          layout: 'anchor',
          items: [{
            xtype: 'acbuttongroup',
            name: 'expense_detail',
            vertical: true,
            value: 'all',
            items: [
              { value: 'none', text: 'No Detail' },
              { value: 'all', text: 'Individual Expenses' },
              { value: 'allnotes', text: 'Individual Expenses w/ Notes' }
            ]
          }]
        }]
      }]        
    },{
      xtype: 'panel',
      border: false,
      html: '<p style="padding-top: 10px;">Clicking OK will present you with a download of a PDF of the invoice for you to print out. Please be patient, large work orders may take <strong>30 seconds</strong> or longer to generate a full invoice.</p>'        
    }]

  }
});


/*********************************************/
/*      TASKS STUFF                          */
/*********************************************/


Ext.define('Ext.ux.ItemEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Task',
  width: 900,
  closeAction: 'destroy',
  autoShow: true,

  workorder_id: null,
  updatingPercent: false,
  color_codes: null,
 
  doneSetup: function(){
    var me = this;

    if (me.workorder_id) me.form.params.id = me.workorder_id;
    if (me.down)

    me.updateCustomerPercent();

    Ext.Array.forEach(me.form.query('numberfield[updateCustomerPercent]'), function(f){ 
      f.on({ 
        change: { fn: me.updateCustomerPercent, scope: me },
        keyup: { fn: me.updateCustomerPercent, scope: me }
      });
    });

    if (me.form.params.item_id == 'new' && (tree = Ext.ComponentQuery.query('#workorder_tree')[0])){
      if (selitem = tree.getSelectionModel().getSelection()[0]){
        while (selitem && !(/^[0-9]+$/.test(selitem.data.id))){
          selitem = selitem.parentNode;
        }
        if (selitem){
          me.form.down('#parentfield').setValue(selitem.data.id);
        }
      } else {
        var root = me.form.down('#parentfield').store.getRootNode();
        me.form.down('#parentfield').setValue(root.getId());
      }
    }

    if (me.color_codes) {
      var colorfield = me.down('#colorcode');
      colorfield.removeAll();
      Ext.Array.forEach(me.color_codes, function(i){
        colorfield.add(i);
      });
      colorfield.initValue();
    }

  },

  updateCustomerPercent: function(){
    var me = this;

    if (me.updatingPercent) return;
    me.updatingPercent = true;

    customer_parts = 100 - parseFloat(me.form.down('#manu_parts_pct').getValue()) - parseFloat(me.form.down('#supp_parts_pct').getValue()) - parseFloat(me.form.down('#ih_parts_pct').getValue());
    customer_labour = 100 - parseFloat(me.form.down('#manu_labour_pct').getValue()) - parseFloat(me.form.down('#supp_labour_pct').getValue()) - parseFloat(me.form.down('#ih_labour_pct').getValue());
    me.form.down('#cust_parts_pct').body.update('<strong>'+(Math.round(100*customer_parts) / 100)+'%</strong>');
    me.form.down('#cust_labour_pct').body.update('<strong>'+(Math.round(100*customer_labour) / 100)+'%</strong>');

    me.updatingPercent = false;
  },

  defaultFormConfig: {
    url: '/work_order/itemedit',
    params: { 
      id: null,
      item_id: 'new',
    },
    waitMsg: 'Saving Task...',
    submitButtonText: 'Save',

    formSuccess: function(){
      reload_tree();
    },

    formLoad: function(r){
      var me = this;

      //manually set the manufacturer and supplier autocomplete fields, since it can't be loaded
      if (r.manufacturer_id){
        mf = me.down('#bill_manufacturerfield');
        mf.getStore().add({id: r.manufacturer_id, name: r.manufacturer_name});
        mf.setValue(r.manufacturer_id);
      }
      if (r.supplier_id){
        bf = me.down('#bill_supplierfield');
        bf.getStore().add({id: r.supplier_id, name: r.supplier_name});
        bf.setValue(r.supplier_id);
      }
      if (r.completed){
        me.down('#completeinfo').setValue('<strong>'+r.completed_date+' by '+r.completed_by+'</strong>');
      }

      me.parentWin.updateCustomerPercent();
    },

    fieldDefaults: { labelAlign: 'left' },

    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.4,
        layout: 'anchor',
        items: [{
          fieldLabel: 'Task Label',
          xtype: 'textfield',
          name: 'label',
          anchor: '-25',
          allowBlank: false,
          initialFocus: true,
          minChars: 2
        },{
          itemId: 'parentfield',
          fieldLabel: 'Parent Task',
          xtype: 'treecombo',
          anchor: '-25',
          treeWidth: 300,
          name: 'parent_id',
          valueField: 'id',
          displayField: 'text',
          forceSelection: true,
          allowBlank: false,
          rootVisible: true,
          selectChildren: false,
          canSelectFolders: true,
          store: foldersStore
        },{
          itemId: 'colorcode',
          xtype: 'acbuttongroup',
          fieldLabel: 'Color Code',
          name: 'color_code',
          width: 300,
          value: 'FFFFFF',
          items: ['None']
        },{
          xtype: 'acbuttongroup',
          fieldLabel: 'Task Status',
          width: 300,
          name: 'completed',
          value: '0',
          items: [
            { value: '0', text: 'Task In Progress' },
            { value: '1', text: 'Task Completed' }
          ]          
        },{
          itemId: 'completeinfo',
          xtype: 'displayfield',
          fieldLabel: 'Completed On',
          value: 'N/A'
        },{
          xtype: 'numberfield',
          name: 'amount_paid',
          fieldLabel: 'Amount Paid',
          forcePrecision: 2,
          minValue: 0,
          width: 200,
          hideTrigger: true          
        },{
          xtype: 'fieldset',
          title: 'Estimates (Optional)',
          layout: 'anchor',
          defaults: {
            minValue: 0,
            forcePrecision: true,
            xtype: 'numberfield',
            labelWidth: 150
          },
          items: [{
            xtype: 'label',
            text: 'Note: If you plan on adding specific parts as part of the estimate, don\'t include those amounts here. They\'ll be added automatically to the estimate total.',
            cls: 'x-form-item-label x-form-item',
            style: 'color: #aa0000;',
          },{            
            fieldLabel: 'Estimated Parts',
            itemId: 'partestimate',
            name: 'part_estimate',
            anchor: '-25'
          },{            
            fieldLabel: 'Estimated Labour',
            itemId: 'labourestimate',
            name: 'labour_estimate',
            anchor: '-25'
          },{
            fieldLabel: 'Estimated Expenses',
            itemId: 'expenseestimate',
            name: 'other_estimate',
            anchor: '-25'
          }]
        }]
      },{
        border: false,
        columnWidth: 0.05,
        html: '&nbsp;',
      },{
        border: false,
        columnWidth: 0.55,
        layout: 'anchor',
        items: [{
          fieldLabel: 'Customer Notes',
          labelAlign: 'top',
          xtype: 'textarea',
          name: 'customer_notes',
          anchor: '0',
          height: 80          
        },{
          xtype: 'fieldset',
          layout: 'anchor',
          title: 'Split Billing',
          style: 'margin-top: 3px',
          defaults: { labelWidth: 150 },
          items: [{
            xtype: 'label',
            text: 'You can assign this task to be billed in part or in full to someone other than the customer. Enter the percentages below to enable split billing.',
            cls: 'x-form-item-label x-form-item'
          },{
            border: false,
            layout: { type: 'table', columns: 3},
            defaults: { labelWidth: 150, bodyStyle: 'padding: 5px;' },
            items: [{
              border: false,
              html: '',
            },{
              border: false,
              html: 'Parts (%)',
              height: 25
            },{
              border: false,
              html: 'Other (%)',
              height: 25
            },{
              xtype: 'combo',
              fieldLabel: 'Charge to a Manufacturer',
              itemId: 'bill_manufacturerfield',
              name: 'manufacturer_id',
              queryMode: 'remote',
              labelWidth: 150,
              listConfig: { minWidth: 200 },
              minChars: 2,
              forceSelection: true,
              valueField: 'id',
              displayField: 'name',
              hideTrigger: true,
              store: manufacturerStore,
              width: 300
            },{
              itemId: 'manu_parts_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'manufacturer_parts_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              itemId: 'manu_labour_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'manufacturer_labour_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              xtype: 'combo',
              fieldLabel: 'Charge to a Supplier',
              itemId: 'bill_supplierfield',
              name: 'supplier_id',
              queryMode: 'remote',
              labelWidth: 150,
              listConfig: { minWidth: 200 },
              minChars: 2,
              forceSelection: true,
              valueField: 'id',
              displayField: 'name',
              hideTrigger: true,
              store: supplierStore,
              width: 300
            },{
              itemId: 'supp_parts_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'supplier_parts_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              itemId: 'supp_labour_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'supplier_labour_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              xtype: 'label',
              text: 'Charge to Delta Marine:',
              cls: 'x-form-item-label',
              style: 'font: 12px tahoma,arial,helvetica,sans-serif;'
            },{
              itemId: 'ih_parts_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'in_house_parts_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              itemId: 'ih_labour_pct',
              xtype: 'numberfield',
              hideLabel: true,
              value: 0,
              minValue: 0,
              maxValue: 100,
              width: 50,
              name: 'in_house_labour_percent',
              selectOnFocus: true,
              enableKeyEvents: true,
              updateCustomerPercent: true
            },{
              xtype: 'label',
              text: 'Charge to Customer (default):',
              cls: 'x-form-item-label',
              style: 'font: 12px tahoma,arial,helvetica,sans-serif;'
            },{
              border: false,
              itemId: 'cust_parts_pct',
              html: '-',
              height: 25,
              style: 'padding: 5px 0 0 10px;'
            },{
              border: false,
              itemId: 'cust_labour_pct',
              html: '-',
              height: 25,
              style: 'padding: 5px 0 0 10px;'
            }]
          },{
            style: 'margin-top: 10px;',
            xtype: 'checkbox',
            fieldLabel: 'Apply to Sub-Tasks',
            name: 'recurse',
            inputValue: '1',
            checked: true
          }]
        }]
      }]
    }]
  }
});



/*********************************************/
/*      PARTS STUFF                          */
/*********************************************/

var supplierStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  proxy: {
    type: 'ajax',
    url: '/supplier/datagrid',
    simpleSortMode: true,
    reader: {
      root: 'suppliers',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});

var manufacturerStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  proxy: {
    type: 'ajax', 
    url: '/manufacturer/datagrid',
    reader: {
      root: 'manufacturers'
    }
  }
});

var partsupplierStore = new Ext.data.JsonStore({
  fields: ['part_supplier_id', 'part_variant_id', 'supplier_id', 'supplier_name', 'supplier_sku', 'notes'],
  sorters: [{ property: 'supplier_name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '/part/supplierdatagrid',
    simpleSortMode: true,
    reader: {
      root: 'suppliers',
      totalProperty: 'totalCount',
      idProperty: 'supplier_id'
    }
  }
});



Ext.define('Ext.ux.PartCustomEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Custom One-Off Part',
  width: 450,
  autoShow: true,
  closeAction: 'destroy',

  pst_rate: null,
  gst_rate: null,
  pst_exempt: false,
  gst_exempt: false,
  workorder_id: null,
  workorder_estimate: false,

  doneSetup: function(){
    var me = this;

    me.form.params.id = me.workorder_id;

    if (me.form.params.instance_id == 'new' && (tree = Ext.ComponentQuery.query('#workorder_tree')[0])){
      if (selitem = tree.getSelectionModel().getSelection()[0]){
        while (selitem && !(/^[0-9]+$/.test(selitem.data.id))){
          selitem = selitem.parentNode;
        }
        if (selitem){
          me.form.down('#parentfield').setValue(selitem.data.id);
        }
      } else {
        var root = me.form.down('#parentfield').store.getRootNode();
        if (root.childNodes.length == 1) {
          me.form.down('#parentfield').setValue(root.childNodes[0].getId());  
        }
      }         
    }

    if (me.pst_exempt) me.form.down('#pstfield').setValue('0');
    if (me.gst_exempt) me.form.down('#gstfield').setValue('0');

    if (me.pst_rate)   me.form.down('#pstfield button').setText('Charge ' + me.pst_rate + '% PST');
    if (me.gst_rate)   me.form.down('#gstfield button').setText('Charge ' + me.gst_rate + '% GST');

    me.form.down('#estimate').setValue(me.workorder_estimate ? '1' : '0');

  },

  defaultFormConfig: {
    waitMsg: 'Saving One-Off Part...',
    submitButtonText: 'Save',  

    url: '/work_order/partcustomEdit',
    params: {
      workorder_id: null,
      instance_id: 'new'
    },

    formSuccess: function(form,action,obj){
      partslistStore.load();
      reload_tree();
    },

    fieldDefaults: { labelWidth: 120 },

    items: [{
      fieldLabel: 'Part Label',
      xtype: 'textfield',
      allowBlank: false,
      initialFocus: true,
      name: 'custom_name',
      anchor: '-25'
    },{
      itemId: 'parentfield',
      fieldLabel: 'Parent Task',
      lazyRender: false,
      xtype: 'treecombo',
      treeWidth: 300,
      width: 300,
      anchor: '-25',
      name: 'workorder_item_id',
      valueField: 'id',
      displayField: 'text',
      rootVisible: false,
      selectChildren: false,
      canSelectFolders: true,
      forceSelection: true,
      allowBlank: false,
      store: foldersStore
    },{      
      xtype: 'numberfield',
      name: 'quantity',
      fieldLabel: 'Quantity',
      value: 1,
      minValue: 0.01,
      allowBlank: false,
      width: 225
    },{
      xtype: 'numberfield',
      name: 'unit_price',
      fieldLabel: 'Unit Price',
      minValue: 0,
      allowBlank: false,
      forcePrecision: true,
      width: 225
    },{
      xtype: 'numberfield',
      name: 'unit_cost',
      fieldLabel: 'Unit Cost (Optional)',
      forcePrecision: true,
      minValue: 0,
      width: 225
    },{
      itemId: 'estimate',
      xtype: 'acbuttongroup',
      fieldLabel: 'Estimate or Invoice',
      name: 'estimate',
      anchor: '-25',
      value: '0',
      items: [
          { value: '1', text: 'Estimate Only', flex: 5},
          { value: '0', text: 'Invoice Only', flex: 4 },
          { value: '2', text: 'Estimate & Invoice', flex: 6 },
      ]
    },{
      fieldLabel: 'Details/Serial Number',
      xtype: 'textfield',
      name: 'serial_number',
      anchor: '-25'
    },{
      xtype: 'textfield',
      name: 'custom_origin',
      fieldLabel: 'Country of Origin',
      anchor: '-25'
    },{      
      itemId: 'pstfield',
      xtype: 'acbuttongroup',
      fieldLabel: 'PST',
      anchor: '-25',
      name: 'taxable_pst',
      value: '1',
      items: [
          { value: '1', text: 'Charge PST', flex: 5 },
          { value: '0', text: 'PST Exempt', flex: 3 }
      ]
    },{
      itemId: 'gstfield',
      xtype: 'acbuttongroup',
      fieldLabel: 'GST',
      anchor: '-25',
      name: 'taxable_gst',
      value: '1',
      items: [
          { value: '1', text: 'Charge GST', flex: 5 },
          { value: '0', text: 'GST Exempt', flex: 3 }
      ]
    },{
      fieldLabel: 'Internal Notes',
      xtype: 'textarea',
      name: 'internal_notes',
      anchor: '-25',
      height: 85      
    }]
  }

});



Ext.define('Ext.ux.PartQuickaddWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Quick Add New Part',
  width: 400,
  autoShow: true,
  closeAction: 'destroy',

  close: function(){
    var me = this;
    barcodeListener.handleroverride = barcode_default_handler;
    me.callParent();
  },

  defaultFormConfig: {
    waitMsg: 'Adding Part...',
    submitButtonText: 'Add',

    url: '/part/add',
    params: {
      cost_calculation_method: 'lifo',
      minimum_on_hand: '0',
      taxable_pst: '1',
      taxable_gst: '1',
      track_inventory: '1',
      initial_cost: '0'
    },

    items: [{
      xtype: 'fieldset',
      title: 'General Information',
      layout: 'anchor',
      items: [{
        xtype: 'textfield',
        fieldLabel: 'Part Name',
        allowBlank: false,
        initialFocus: true,
        name: 'name',
        anchor: '-25'
      },{
        xtype: 'treecombo',
        fieldLabel: 'Category',
        allowBlank: false,
        anchor: '-25',
        name: 'part_category_id',
        valueField: 'id',
        displayField: 'text',
        rootVisible: false,
        panelMaxHeight: 400,
        store: categoriesStore
      },{
        xtype: 'textfield',
        fieldLabel: 'Delta SKU',
        allowBlank: false,
        name: 'internal_sku',
        anchor: '-25',
        listeners: { focus: barcode_focus, blur: barcode_blur }
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Track Serial Numbers',
        width: 250,
        name: 'has_serial_number',
        value: '0',
        items: [
            { value: '1', text: 'Yes' },
            { value: '0', text: 'No' }
        ]
      },{
        xtype: 'acbuttongroup',
        fieldLabel: 'Part Status',
        width: 250,
        name: 'active',
        value: '1',
        items: [
            { value: '1', text: 'Active' },
            { value: '0', text: 'Inactive' }
        ]
      },{
        xtype: 'combo',
        fieldLabel: 'Manufacturer',
        name: 'manufacturer_id',
        queryMode: 'remote',
        anchor: '-25',
        minChars: 2,
        forceSelection: true,
        valueField: 'id',
        displayField: 'name',
        hideTrigger: true,
        store: manufacturerStore
      },{
        xtype: 'textfield',
        fieldLabel: 'Manufacturer SKU',
        name: 'manufacturer_sku',
        anchor: '-25',
        listeners: { focus: barcode_focus, blur: barcode_blur }
      },{
        xtype: 'textarea',
        name: 'description',
        fieldLabel: 'Description',
        height: 40,
        anchor: '-25'
      },{
        xtype: 'textfield',
        fieldLabel: 'Country of Origin',
        name: 'origin',
        anchor: '-25'
      }]
    },{
      xtype: 'fieldset',
      title: 'Pricing & Inventory',
      bodyStyle: 'padding: 5px',
      layout: 'anchor',
      items: [{
        xtype: 'numberfield',
        name: 'unit_cost',
        fieldLabel: 'Cost',
        forcePrecision: true,
        anchor: '-100',
        minValue: 0
      },{
        xtype: 'numberfield',
        name: 'broker_fees',
        fieldLabel: 'Broker Fees',
        minValue: 0,
        //allowBlank: false,
        anchor: '-100',
        forcePrecision: true
      },{
        xtype: 'numberfield',
        name: 'shipping_fees',
        fieldLabel: 'Shipping Fees',
        minValue: 0,
        //allowBlank: false,
        anchor: '-100',
        forcePrecision: true
      },{
        xtype: 'numberfield',
        name: 'unit_price',
        fieldLabel: 'Price',
        forcePrecision: true,
        allowBlank: false,
        anchor: '-100',
        minValue: 0
      },{
        xtype: 'combo',
        name: 'units',
        fieldLabel: 'Units', 
        anchor: '-100',
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
        xtype: 'numberfield',
        name: 'initial_quantity',
        fieldLabel: 'Initial Stock Qty',
        anchor: '-100',
        value: '1',
        minValue: 0
      }]
    }],

    formSuccess: function(form,action,obj){
      Ext.Msg.wait("Loading Part Info...");
      Ext.Ajax.request({
        url: '/part/datagrid',
        params: { part_id: obj.newid, show_inactive: 1},
        callback : function (opt,success,response){
          Ext.Msg.hide(); 
          if (success){
            data = Ext.decode(response.responseText);
            if (data && data.parts.length > 0){
              if (data.parts && data.parts.length == 1){
                showPartEditWin(null, data.parts[0]);
              } else {
                Ext.Msg.alert('Multiple Parts Found', 'Error: could not select part; matched multiple parts!');
              }      
            }
          } else {
            Ext.Msg.alert('Error', 'Could not find newly-added part! Reload page and try looking it up again.');
          }
        }
      })
    }
  }

});



Ext.define('Ext.ux.PartEditWin', {
  extend: 'Ext.ux.acFormWindow',
  alias: 'widget.parteditwin',

  title: 'Edit Part',
  width: 525,
  //height: 800,
  autoShow: true,
  autoHeight: true,
  closeAction: 'destroy',

  pst_rate: null,
  gst_rate: null,
  pst_exempt: false,
  gst_exempt: false,
  workorder_id: null,
  workorder_estimate: false,

  doneSetup: function(){
    var me = this;

    me.form.params.id = me.workorder_id;

    if (me.pst_exempt) me.form.down('#pstfield').setValue('0');
    if (me.gst_exempt) me.form.down('#gstfield').setValue('0');

    if (me.pst_rate)   me.form.down('#pstfield button').setText('Charge ' + me.pst_rate + '% PST');
    if (me.gst_rate)   me.form.down('#gstfield button').setText('Charge ' + me.gst_rate + '% GST');

    me.form.down('#estimate').setValue(me.workorder_estimate ? '1' : '0');
    me.form.down('#statusaction').setValue(me.workorder_estimate ? 'estimate' : 'delivered');

    Ext.Array.forEach(me.form.query('radio[priceMethod]'), function(f){ 
      f.on({ 
        change: { fn: me.changePricingMethod, scope: me }
      });
    });

    Ext.Array.forEach(me.form.query('numberfield[priceAmt]'), function(f){ 
      f.on({ 
        change: { fn: me.updatePricingInfo, scope: me },
        keyup: { fn: me.updatePricingInfo, scope: me }
      });
    });    

  },

  changePricingMethod: function(field, val){
    var me = this;

    if (val){
      var pricing_source_field = field.itemId;
      Ext.Array.forEach(me.form.query('numberfield[priceAmt]'), function(f){ 
        if (f.itemId == (field.itemId + '_amt')){
          f.setDisabled(false);
          f.setVisible(true);

          var output = 0;
          var regcost = parseFloat(me.form.down('#regular_cost').getValue());
          var regprice = parseFloat(me.form.down('#regular_price').getValue());
          var newprice = parseFloat(me.form.params.unit_price);
          var ispct = false;

          if (pricing_source_field == 'pricing_markup_pct'){
            output = ((newprice / regcost) - 1)*100;
            ispct = true;
          } else if (pricing_source_field == 'pricing_markup_amt'){
            output = newprice - regcost;
          } else if (pricing_source_field == 'pricing_discount_pct'){
            output = ((regprice - newprice)/regprice)*100;
            ispct = true;
          } else if (pricing_source_field == 'pricing_discount_amt'){
            output = regprice - newprice; 
          } else if (pricing_source_field == 'pricing_custom'){
            output = newprice; 
          }

          output = Math.round(output*100)/100;
          if (!ispct){
            output.toFixed(2);
          }
          f.setValue(output);
        } else {
          f.clearInvalid();
          f.setDisabled(true);
          f.setVisible(false);
        }
      });

      if (field.itemId == 'pricing_normal') me.updatePricingInfo(me.form.down('#' + field.itemId));
    }
  },

  updatePricingInfo: function(field,val){
    var me = this;
    var pricing_source_field = (field ? field.itemId : 'pricing_normal');

    var output = '';
    var regcost = parseFloat(me.form.down('#regular_cost').getValue());
    var regprice = parseFloat(me.form.down('#regular_price').getValue());

    if (pricing_source_field != 'pricing_normal'){
      var amt = parseFloat(field.getValue());
    }
    if (pricing_source_field == 'pricing_normal'){
      output = regprice;
    } else if (pricing_source_field == 'pricing_markup_pct_amt'){
      output = (1 + (amt/100)) * regcost;
    } else if (pricing_source_field == 'pricing_markup_amt_amt'){
      output = amt + regcost;
    } else if (pricing_source_field == 'pricing_discount_pct_amt'){
      output = regprice - (regprice*(amt/100));
    } else if (pricing_source_field == 'pricing_discount_amt_amt'){
      output = regprice - amt; 
    } else if (pricing_source_field == 'pricing_custom_amt'){
      output = amt; 
    }
    output = (Math.round(output*100)/100).toFixed(2);
    me.form.params.unit_price = output;

    var units = me.form.params.units;
    if (units == ''){
      units = 'ea';
    }

    output = (output == 'NaN' ? 'ERROR: INVALID' : '$' + output + '/' + units);
    me.form.down('#pricing_output').body.update('Final Unit Price:<br /><strong>' + output + '</strong>');
  },

  defaultFormConfig: {
    url: '/work_order/partedit',
    waitMsg: 'Saving Part...',
    params: {
      id: null,
      part_variant_id: null,
      instance_id: null,
      unit_price: null,
      units: null,
      orderaction: null,
      supplier_id: null
    },

    qty_avail: 0,
    qty_orig: 0,
    qty_min: 0,
    qty_max: 0,

    updateQuantity: function(){
      var me = this;
      var qty = me.down('#quantity');

      var msg = '<span style="' + (qty.getValue() > (me.qty_avail + me.qty_orig) ? 'font-weight:bold; color: #c33;' : 'color: #090;') +'">' + me.qty_avail;
      msg += (me.qty_orig > 0 ? ' more' : '') + ' available</span>';
      msg += ' (Min: '+me.qty_min+', Max: '+me.qty_max+')'
      me.down('#available').body.update(msg);

      return false;
    },

    formLoad: function(r){
      var me = this;
          
      me.params.units = r.units;
      me.params.instance_id = (r.instance_id ? r.instance_id : 'new');
      me.params.part_variant_id = (r.part_variant_id ? r.part_variant_id : null);

      me.down('#serial').setVisible(r.has_serial_number);
      me.qty_orig = parseFloat(r.quantity ? r.quantity : 0);
      me.qty_min = r.min_quantity;
      me.qty_max = r.max_quantity;
      var qtyfield = me.down('#quantity');
      if (r.has_serial_number) {
        qtyfield.setMinValue(1);
        qtyfield.setMaxValue(1);
        qtyfield.maxText = 'Parts with a serial number must be entered one at a time';
      } else {
        qtyfield.on('change', me.updateQuantity, me);
      }

      me.qty_avail = parseFloat(r.available);
      me.updateQuantity();

      if (!r.unit_price) r.unit_price = r.regular_price;
      me.params.unit_price = r.unit_price;

      if (!r.parent_id){
        var tree,selitem;
        if (tree = Ext.ComponentQuery.query('#workorder_tree')[0]){
          if (selitem = tree.getSelectionModel().getSelection()[0]){
            while (selitem && !(/^[0-9]+$/.test(selitem.data.id))){
              selitem = selitem.parentNode;
            }
            if (selitem){
              me.down('#parentfield').setValue(selitem.data.id);
            }
          } else {
            var root = me.down('#parentfield').store.getRootNode();
            if (root.childNodes.length == 1) {
              me.down('#parentfield').setValue(root.childNodes[0].getId());  
            }
          }             
        }
      }

      if (me.parentWin.workorder_estimate && r.statusaction == 'estimate'){
          me.down('#statusaction button[value=hold]').setDisabled(true);
          me.down('#statusaction button[value=delivered]').setDisabled(true);
      }

      //set other values as well
      if (r.regular_price == r.unit_price){
        me.down('#pricing_normal').setValue(true);
        me.parentWin.changePricingMethod(me.down('#pricing_normal'), true);
      } else {
        me.down('#pricing_custom').setValue(true);
        me.down('#pricing_custom_amt').setValue(r.unit_price);
        me.parentWin.changePricingMethod(me.down('#pricing_custom'), true);
      }

      me.down('#info_name').setValue('<a href="/part/view/id/'+r.part_id+'"><strong>'+r.name+'</strong></a>');
      me.down('#info_sku').setValue('<strong>'+r.sku+'</strong>');
      if (r.who) {
        me.down('#info_who').setVisible(true).setValue('<strong>'+r.who+'</strong>');
      }

      me.down('#info_location').setValue((r.location != '') ? '<strong>'+r.location+'</strong>' : '<em><span style="color: #777;">Not Set</span></em>');
  
    },

    formSuccess: function(form,action,obj){
      partslistStore.load();
      reload_tree();
      if (obj.specialmodified !== undefined){
        Ext.Msg.alert('Special Order', 'NOTE: The special order item associated with this part had its quantity modified by the same amount.');
      } else if (obj.specialdeleted !== undefined){
        Ext.Msg.alert('Special Order', 'NOTE: The supplier order (special order) associated with this part was removed since it was empty after adjusting for the new quantity entered.');
      }  else if (obj.deleted !== undefined){
        Ext.Msg.alert('Part Deleted', 'Quantity was set to zero so part was deleted.');
      }
    },

    formFailure: function(errors){
       var me = this, redisplay;

        redisplay = (this.parentWin ? this.parentWin.showOnError : false);
        if (errors.maximum !== undefined){
          Ext.Msg.alert('Not Enough Inventory', errors.reason, function(but){
            if (but == 'ok'){
              me.showParentWin();
              me.down('#quantity').setValue(errors.maximum);
              me.down('#quantity').setMaxValue(errors.maximum);
              me.down('#quantity').focus(true, 200);
            }
          });      
        }        
        else if (errors.reason){
            me.formErrorMessage(errors.reason, redisplay);
        } else if (redisplay) {
            me.showParentWin();
        } else {
            me.reset();
        }      
    },

    fieldDefaults: { labelAlign: 'left' },

    items: [{
      itemId: 'info_name',
      xtype: 'displayfield',
      name: 'name',
      fieldLabel: 'Part Name',
      html: '...',
    },{      
      itemId: 'info_sku',
      xtype: 'displayfield',
      fieldLabel: 'Part SKU',
      name: 'sku'
    },{
      itemId: 'info_location',
      xtype: 'displayfield',
      fieldLabel: 'Location',
      name: 'location'      
    },{
      itemId: 'info_who',
      xtype: 'displayfield',
      name: 'name',
      hidden: true,
      fieldLabel: 'Added By',
    },{ 
      xtype: 'fieldset',
      layout: 'anchor',
      title: 'Settings',
      fieldDefaults: { labelWidth: 120 },
      items: [{
        itemId: 'parentfield',
        fieldLabel: 'Parent Task',
        lazyRender: false,
        xtype: 'treecombo',
        treeWidth: 300,
        width: 300,
        anchor: '-25',
        name: 'parent_id',
        valueField: 'id',
        displayField: 'text',
        rootVisible: false,
        selectChildren: false,
        canSelectFolders: true,
        forceSelection: true,
        allowBlank: false,
        store: foldersStore
      },{
        layout: {
          type: 'hbox',
          align: 'stretch'
        },
        border: false,
        items: [{
          border: false,
          layout: 'anchor',
          width: 220,
          items: [{
            xtype: 'numberfield',
            name: 'quantity',
            itemId: 'quantity',
            fieldLabel: 'Quantity',
            initialFocus: true,
            allowBlank: false,
            minValue: 0,
            maxValue: 5000,
            anchor: '-25',
            value: 1
          }]
        },{
          itemId: 'available',
          xtype: 'panel',
          border: false,
          flex: 1,
          padding: 5,
          html: ' '
        }]
      },{
        itemId: 'serial',
        xtype: 'textfield',
        name: 'serial',
        fieldLabel: 'Serial Number',
        width: 400,
        initialFocus: true,
        hidden: true,
        listeners: { focus: barcode_focus, blur: barcode_blur }
      },{
        itemId: 'estimate',
        xtype: 'acbuttongroup',
        fieldLabel: 'Include in Estimate',
        width: 400,
        name: 'estimate',
        value: '1',
        items: [
            { value: '1', text: 'Yes' },
            { value: '0', text: 'No' }
        ]
      },{
        itemId: 'statusaction',
        xtype: 'acbuttongroup',
        fieldLabel: 'Part Status',
        width: 400,
        name: 'statusaction',
        value: 'estimate',
        items: [
            { value: 'estimate', text: 'Estimate Only' },
            { value: 'hold', text: 'On Hold' },
            { value: 'delivered', text: 'Utilized' }
        ]
      }]
    },{
      xtype: 'fieldset',
      layout: 'anchor',
      title: 'Pricing',
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
            itemId: 'regular_cost',
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
            itemId: 'regular_price',
            fieldLabel: 'Regular Price',
            disabled: true,
            anchor: '-25'
          }]
        }]
      },{ 
        border: false,
        html: '<hr noshade>'
      },{
        id: 'pricing_options',
        layout: { type: 'table', columns: 3, tdAttrs: { width: '33%' } },
        border: false,
        items: [{
          xtype: 'radio',
          name: 'pricing_method',
          itemId: 'pricing_normal',
          boxLabel: 'Use Normal Price',
          hideLabel: true,
          inputValue: 'normal',
          colspan: 2,
          priceMethod: true
        },{
          border: false,
          rowspan: 6,
          items: [{
            height: 40,
            itemId: 'pricing_output',
            border: false
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method', 
          itemId: 'pricing_markup_pct',
          boxLabel: 'Specify Markup %',
          inputValue: 'markup_pct',
          hideLabel: true,
          priceMethod: true
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            itemId: 'pricing_markup_pct_amt',
            allowBlank: false,
            minValue: 0,
            name: 'partedit_pricing_markup_pct',
            hideLabel: true,
            width: 80,
            enableKeyEvents: true,
            priceAmt: true
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          itemId: 'pricing_markup_amt',
          boxLabel: 'Specify Markup $',
          inputValue: 'markup_amt',
          hideLabel: true,
          priceMethod: true
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            itemId: 'pricing_markup_amt_amt',
            allowBlank: false,
            minValue: 0,
            name: 'partedit_pricing_markup_amt',
            hideLabel: true,
            forcePrecision: true,
            width: 80,
            enableKeyEvents: true,
            priceAmt: true
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          itemId: 'pricing_discount_pct',
          boxLabel: 'Specify Discount %',
          inputValue: 'discount_pct',
          hideLabel: true,
          priceMethod: true
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            itemId: 'pricing_discount_pct_amt',
            allowBlank: false,
            minValue: 0,
            name: 'partedit_pricing_discount_pct',
            hideLabel: true,
            width: 80,
            enableKeyEvents: true,
            priceAmt: true
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          itemId: 'pricing_discount_amt',
          boxLabel: 'Specify Discount $',
          inputValue: 'discount_amt',
          hideLabel: true,
          priceMethod: true
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            itemId: 'pricing_discount_amt_amt',
            allowBlank: false,
            minValue: 0,
            name: 'partedit_pricing_discount_amt',
            hideLabel: true,
            forcePrecision: true,
            width: 80,
            enableKeyEvents: true,
            priceAmt: true
          }]
        },{
          xtype: 'radio',
          name: 'pricing_method',
          itemId: 'pricing_custom',
          boxLabel: 'Specify Custom Price',
          inputValue: 'custom',
          hideLabel: true,
          priceMethod: true
        },{
          border: false,
          height: 25,
          items: [{
            xtype: 'numberfield',
            itemId: 'pricing_custom_amt',
            allowBlank: false,
            minValue: 0,
            name: 'partedit_pricing_custom',
            hideLabel: true,
            forcePrecision: true,
            width: 80,
            enableKeyEvents: true,
            priceAmt: true
          }]
        }]
      }]
    },{
      xtype: 'fieldset',
      layout: 'anchor',
      title: 'Fees &amp; Taxes',
      items: [{
        layout: 'hbox',
        border: false,
        items: [{
          border: false,
          layout: 'anchor',
          flex: 3,
          items: [{
            xtype: 'numberfield',
            name: 'enviro_levy',
            fieldLabel: 'Enviro Levy',
            labelWidth: 70,
            anchor: '-5',
            forcePrecision: true,
            minValue: 0,
            value: 0
          },{
            xtype: 'numberfield',
            name: 'battery_levy',
            fieldLabel: 'Battery Levy',
            anchor: '-5',
            labelWidth: 70,
            forcePrecision: true,
            minValue: 0,
            value: 0
          },{
            xtype: 'numberfield',
            name: 'shipping_fees',
            fieldLabel: 'Shipping Fees',
            minValue: 0,
            labelWidth: 70,
            forcePrecision: true,
            anchor: '-5'
          },{
            xtype: 'numberfield',
            name: 'broker_fees',
            fieldLabel: 'Broker Fees',
            minValue: 0,
            labelWidth: 70,
            forcePrecision: true,
            anchor: '-5'
          }]
        },{
          border: false,
          layout: 'anchor',
          margin: '0 0 0 25',
          flex: 4,
          items: [{
            xtype: 'acbuttongroup',
            itemId: 'pstfield',
            fieldLabel: 'PST',
            name: 'taxable_pst',
            labelWidth: 40,
            value: '1',
            items: [
              { value: '1', text: 'Charge PST', flex: 5 },
              { value: '0', text: 'PST Exempt', flex: 3 }
            ]
          },{
            xtype: 'acbuttongroup',
            fieldLabel: 'GST',
            itemId: 'gstfield',
            name: 'taxable_gst',
            labelWidth: 40,
            value: '1',
            items: [
              { value: '1', text: 'Charge GST', flex: 5 },
              { value: '0', text: 'GST Exempt', flex: 3 }
            ]
          }]
        }]
      }]
    }]

  }
});

/*********************************************/
/*      EXPENSES STUFF                       */
/*********************************************/

Ext.define('Ext.ux.ExpenseEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Edit Expense',
  width: 450,
  closeAction: 'destroy',
  autoShow: true,

  workorder_id: null,
  workorder_estimate: false,
  pst_exempt: false,
  gst_exempt: false,
  pst_rate: null,
  gst_rate: null,

  doneSetup: function(){
    var me = this;
    var tree,selitem;

    if (me.form.params.expense_id == 'new' && (tree = Ext.ComponentQuery.query('#workorder_tree')[0])){
      if (selitem = tree.getSelectionModel().getSelection()[0]){
        while (selitem && !(/^[0-9]+$/.test(selitem.data.id))){
          selitem = selitem.parentNode;
        }
        if (selitem){
          me.form.down('#parentfield').setValue(selitem.data.id);
        }
      } else {
        var root = me.form.down('#parentfield').store.getRootNode();
        if (root.childNodes.length == 1) {
          me.form.down('#parentfield').setValue(root.childNodes[0].getId());  
        }
      }         
    }

    if (me.pst_exempt) me.form.down('#pstfield').setValue('0');
    if (me.gst_exempt) me.form.down('#gstfield').setValue('0');

    if (me.pst_rate)   me.form.down('#pstfield button').setText('Charge ' + me.pst_rate + '% PST');
    if (me.gst_rate)   me.form.down('#gstfield button').setText('Charge ' + me.gst_rate + '% GST');

    if (me.workorder_estimate) me.form.down('#estimatefield').setValue('1');

    me.form.params.id = me.workorder_id;
  },

  defaultFormConfig: {
    url: '/work_order/expenseedit',
    params: { 
      id: null,
      expense_id: 'new'
    },
    waitMsg: 'Saving Expense...',
    submitButtonText: 'Save',

    formSuccess: function(){
      reload_tree();
    },

    fieldDefaults: { labelWidth: 120, labelAlign: 'right' },

    items: [{
      name: 'label',
      fieldLabel: 'Expense Label',
      xtype: 'textfield',
      allowBlank: false,
      initialFocus: true,
      anchor: '-25'
    },{
      itemId: 'parentfield',
      fieldLabel: 'Parent Task',
      lazyRender: false,
      xtype: 'treecombo',
      treeWidth: 300,
      width: 300,
      anchor: '-25',
      name: 'workorder_item_id',
      valueField: 'id',
      displayField: 'text',
      rootVisible: false,
      selectChildren: false,
      canSelectFolders: true,
      forceSelection: true,
      allowBlank: false,
      store: foldersStore
    },{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'price',
          fieldLabel: 'Charged Price',
          minValue: 0,
          allowBlank: false,
          forcePrecision: true,
          anchor: '0'
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'numberfield',
          name: 'cost',
          fieldLabel: 'Cost (Optional)',
          forcePrecision: true,
          minValue: 0,
          anchor: '-25'
        }]
      }]
    },{
      xtype: 'textfield',
      name: 'origin',
      fieldLabel: 'Country of Origin',
      anchor: '-25'      
    },{      
      itemId: 'estimatefield',
      xtype: 'acbuttongroup',
      fieldLabel: 'Estimate or Invoice',
      anchor: '-25',
      name: 'estimate',
      value: '0',
      items: [
          { value: '1', text: 'Estimate Only', flex: 5},
          { value: '0', text: 'Invoice Only', flex: 4 },
          { value: '2', text: 'Estimate & Invoice', flex: 6 },
      ]
    },{
      itemId: 'pstfield',
      xtype: 'acbuttongroup',
      fieldLabel: 'PST',
      width: 300,
      name: 'taxable_pst',
      value: '1',
      items: [
        { value: '1', text: 'Charge PST' },
        { value: '0', text: 'PST Exempt' }
      ]
    },{
      itemId: 'gstfield',
      xtype: 'acbuttongroup',
      fieldLabel: 'GST',
      width: 300,
      name: 'taxable_gst',
      value: '1',
      items: [
        { value: '1', text: 'Charge GST' },
        { value: '0', text: 'GST Exempt' }
      ]
    },{      
      fieldLabel: 'Customer Notes',
      xtype: 'textarea',
      name: 'customer_notes',
      anchor: '-25',
      height: 85
    },{
      fieldLabel: 'Internal Notes',
      xtype: 'textarea',
      name: 'internal_notes',
      anchor: '-25',
      height: 85
    }]

  }
});



/*********************************************/
/*      GENERAL STUFF                        */
/*********************************************/

var otherWorkordersStore = new Ext.data.JsonStore({
  fields: ['id','summary', 'customer','boat','date','status'],
  pageSize: 15,
  proxy: {
    type: 'ajax',
    url: '/work_order/datagrid',
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount'
    }
  }
});


Ext.define('Ext.ux.NotesEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Edit Workorder Notes',
  width: 700,
  autoShow: true,
  closeAction: 'destroy',

  defaultFormConfig: { 
    url:  '/work_order/notesedit',

    formSuccess: function(form,action,obj){
      Ext.getCmp('notes_panel_customer').update(obj.data.customer_notes);
      Ext.getCmp('notes_panel_internal').update(obj.data.internal_notes);
      if (obj.data.empty){
        Ext.getCmp('notes_panel').setTitle('Notes');
      } else {
        Ext.getCmp('notes_panel').setTitle('Notes (*)');
      }
    },

    items: [{
      fieldLabel: 'Notes for Customer',
      xtype: 'textarea',
      name: 'customer_notes',
      anchor: '-25',
      height: 250
    },{
      fieldLabel: 'Notes for Internal Use',
      xtype: 'textarea',
      name: 'internal_notes',
      anchor: '-25',
      height: 250
    }]

  }
});

Ext.define('Ext.ux.ItemCopyWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Copy Workorder Task',
  width: 700,
  autoShow: true,
  closeAction: 'destroy',

  itemtext: '',
  item_id: 0,
  workorder_id: 0,

  doneSetup: function(){
    var me = this;
  
    me.down('#itemcopy_text').body.update('<strong>' + me.itemtext + '</strong>');
    me.form.params.item_id = me.item_id;
    me.form.params.id = me.workorder_id;
  },

  defaultFormConfig: { 
    url:  '/work_order/itemcopy',
    params: {
      item_id: null,
      id: null
    },

    formSuccess: function() {
      Ext.Msg.alert('Success', 'Item was copied.');
    },

    fieldDefaults: { labelAlign: 'left', labelWidth: 150 },

    items: [{
      xtype: 'fieldcontainer',
      fieldLabel: 'Task to Copy',
      items: [{
        itemId: 'itemcopy_text',
        border: false,
        height: 30,
        html: ''
      }]
    },{
      fieldLabel: 'Workorder to Copy To',
      xtype: 'combo',
      name: 'workorder_id',
      anchor: '-25',
      forceSelection: true,
      allowBlank: false,
      queryMode: 'remote',
      valueField: 'id', 
      displayField: 'summary',
      hideTrigger: true,
      minChars: 1,
      pageSize: 15,
      listConfig: { 
        minWidth: 500,
        getInnerTpl: function(){
          return '<a class="search-item" style="display: block; border-top: 1px dotted #ccc; color: #000;">' +
                 '<span style="font-weight: bold; font-size: 13px;">#{id}: {boat}</span>' +
                 '<span style="font-weight: bold; padding-left: 5px;">({customer})</span><br />' +
                 '<span style="padding-left: 20px; color: green">{date} - {status}<span>' +
                 '</a>';
        }
      },
      emptyText: 'Customer/Boat',
      store: otherWorkordersStore,
      margin: '0 0 15 0'
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Parts Used',
      anchor: '-25',
      name: 'p',
      value: 2,
      items: [
          { value: 1, flex: 2, text: 'Duplicate all Parts Used' },
          { value: 2, flex: 4, text: 'Copy Total of Parts Used to new Parts Estimate' },
          { value: 3, text: 'Don\'t Copy' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Expenses',
      anchor: '-25',
      name: 'e',
      value: 2,
      items: [
          { value: 1, flex: 2, text: 'Duplicate all Expenses' },
          { value: 2, flex: 4, text: 'Copy Total of Expenses to new Expenses Estimate' },
          { value: 3, text: 'Don\'t Copy' }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Labour',
      anchor: '-25',
      name: 'l',
      value: 2,
      items: [
          { value: 1, flex: 2, text: 'Duplicate Timelogs (N/A)', disabled: true },
          { value: 2, flex: 4, text: 'Copy Total of Labour to new Labour Estimate' },
          { value: 3, text: 'Don\'t Copy' }
      ]
    }]      
  }
});



Ext.define('Ext.ux.WorkorderAddPaymentWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Payment/Refund',
  width: 400,
  autoShow: true,
  closeAction: 'destroy',

  workorder_id: null,

  doneSetup: function() {
    var me = this;

    me.form.params.id = me.workorder_id;

    var whomfield = me.form.down('#whomfield');
    if (whomfield.getStore().getCount() == 0){
        whomfield.getStore().load({ scope: this, callback: function(records){
          var whomfield = this.down('#whomfield');
          whomfield.setValue(whomfield.getStore().getAt(0).data.id);
          whomfield.fireEvent('select', whomfield);
        }});
    } else {
      whomfield.setValue(whomfield.getStore().getAt(0).data.id);
    }
  },

  defaultFormConfig: {
    url: '/work_order/addpayment',
    params: {
      id: null
    },

    formSuccess: function(){
      Ext.getCmp('billing_grid').getStore().load();
    },

    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },

    items: [{
      itemId: 'whomfield',
      name: 'whom_id',
      fieldLabel: 'Payment From',
      xtype: 'combo',
      value: 'cust',
      anchor: '0',
      allowBlank: false,
      forceSelection: true,
      queryMode: 'local',
      valueField: 'id',
      displayField: 'name',
      listConfig: { 
        minWidth: 300, 
        tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{name} ({desc})</li></tpl>'
      },
      triggerAction: 'all',
      listeners: {
        //makes sure form validity is changed as soon as selection is made
        select: function(me) { me.validate(); },
      },
      store: whomStore
    },{
      xtype: 'numberfield',
      name: 'amount',
      allowBlank: false,
      anchor: '-100',
      forcePrecision: true,
      initialFocus: true,
      fieldLabel: 'Payment Amount'
    },{
      fieldLabel: 'Date',
      xtype: 'datefield',
      anchor: '-100',
      format: 'M j, Y',
      name: 'date',
      value: new Date()
    }]
            
  }
});

Ext.define('Ext.ux.WorkorderAddInvoiceWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Progress Billing',
  width: 400,
  autoShow: true,
  closeAction: 'destroy',

  workorder_id: null,

  doneSetup: function() {
    var me = this;

    me.form.params.id = me.workorder_id;
  },

  defaultFormConfig: {
    url: '/work_order/addinvoice',
    params: {
      id: null
    },

    formSuccess: function(){
      Ext.getCmp('billing_grid').getStore().load();
      progressStore.load();
    },

    fieldDefaults: { labelAlign: 'left', labelWidth: 125 },

    items: [{
      fieldLabel: 'Billing Date',
      xtype: 'datefield',
      anchor: '-100',
      format: 'M j, Y',
      name: 'date',
      maxValue: new Date(),
      value: new Date()
    },{
      xtype: 'panel',
      bodyPadding: 20,
      border: false,
      html: 'Note: all expenses, parts, and workorders from before the selected date will be assigned to this progress billing'
    }]
            
  }
});

var colors = ['#5759FF',
              '#E4C04F',
              '#C31616',
              '#16C31F'];

Ext.chart.theme.WorkorderReportTheme = Ext.extend(Ext.chart.theme.Base, {
    constructor: function(config) {
        Ext.chart.theme.Base.prototype.constructor.call(this, Ext.apply({
            colors: colors
        }, config));
    }
});

Ext.define('Ext.ux.WorkorderReportPanel', {
  extend: 'Ext.panel.Panel',

  autoScroll: true,
  border: false,
  layout: 'anchor',

  workorder_id: 0,
  loaded_data: null,
  current_store: null,
  pieChart: null,
  pieStore: null,

  initComponent: function(){
    var me = this;

    me.current_store = new Ext.data.JsonStore({
      fields: ['task','name','profit','unknown','cost','discounts','parts','labour','expenses','total','price','raw']
    });

    me.callParent();

    me.pieStore = Ext.create('Ext.data.JsonStore', {
        fields: ['name', 'data']
    });
    
    me.pieChart = Ext.create('Ext.chart.Chart', {
        width: 200,
        height: 200,
        animate: false,
        store: me.pieStore,
        shadow: false,
        insetPadding: 0,
        margin: '10 0 0 25',
        theme: 'Base:gradients',
        series: [{
            type: 'pie',
            field: 'data',
            showInLegend: false,
            label: {
                field: 'name',
                display: 'rotate',
                contrast: true,
                font: '12px Arial'
            }
        }]
    });
  },

  initItems: function(){
    var me = this;

    me.callParent();

    config = me.generateChartConfig();
    config.store = me.current_store;
    me.add(config);

    Ext.Array.forEach(me.query('combo'), function(f){ 
      f.on({ 
        change: { fn: me.updateChart, scope: me }
      });
    });

    me.loadData();
  },

  tbar: [{
    xtype: 'label',
    text: 'Report Options: ',
    margin: '0 10 0 15'
  },{
    itemId: 'filter_tasks',
    xtype: 'combo',
    width: 125,
    allowBlank: false,
    forceSelection: true,
    editable: false,
    queryMode: 'local',
    valueField: 'id',
    displayField: 's',
    value: 'all',
    listConfig: {  
      minWidth: 200,
      tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{l}</li></tpl>'
    },
    store: {
      xtype: 'store.store',
      fields: ['id', 's', 'l'],
      data: [
        { id: 'all',   s: 'All Tasks', l: 'All Tasks Combined' },
        { id: 'tasks', s: 'By Task',   l: 'Divide into Top-Level Tasks' }
      ]
    }
  },'-',{
    itemId: 'filter_subtotal',
    xtype: 'combo',
    width: 160,
    allowBlank: false,
    forceSelection: true,
    editable: false,
    queryMode: 'local',
    valueField: 'id',
    displayField: 's',
    value: 'finance',
    listConfig: {  
      minWidth: 250,
      tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{l}</li></tpl>'
    },
    store: {
      xtype: 'store.store',
      fields: ['id', 's', 'l'],
      data: [
        { id: 'all',     s: 'Don\'t Subtotal',       l: 'Don\'t Subtotal' },
        { id: 'finance', s: 'Cost/Profits',          l: 'Divide into Cost/Profit/Discounts' },
        { id: 'type',    s: 'Parts/Labour/Expenses', l: 'Divide into Parts/Labour/Expenses' }
      ]
    }
  },'-',{
    itemId: 'filter_type',
    xtype: 'combo',
    width: 125,
    allowBlank: false,
    forceSelection: true,
    editable: false,
    queryMode: 'local',
    valueField: 'id',
    displayField: 's',
    value: 'all',
    listConfig: {  
      minWidth: 250,
      tpl: '<tpl for="."><li role="option" class="x-boundlist-item">{l}</li></tpl>'
    },
    store: {
      xtype: 'store.store',
      fields: ['id', 's', 'l'],
      data: [
        { id: 'all',        s: 'All Amounts', l: 'Show All Amounts' },
        { id: 'cost',       s: 'Costs',       l: 'Only Show Costs' },
        { id: 'discounts',  s: 'Discounts',   l: 'Only Show Discounts' },
        { id: 'profit',     s: 'Profits',     l: 'Only Show Profits' },
        { id: 'total_disc', s: 'Total w/Discount', l: 'Only Show Total After Discount' },
        { id: 'total',      s: 'Total',       l: 'Only Show Total Before Discount' }
      ]    
    }
  }],

  loadData: function(){
    var me = this;
    Ext.Ajax.request({
        url: '/work_order/report',
        params: { id: me.workorder_id },
        success: function(response){
          var text = response.responseText;
          me.loaded_data = Ext.JSON.decode(text);
          me.updateChart();
        }
      });
  },

  updateChart: function(){
    var me = this;
    var json = me.loaded_data;

    var show_tasks = (me.down('#filter_tasks').getValue() == 'tasks');

    //figure out which data point to grab from
    var opt_what = me.down('#filter_type');
    var type_index = 'price';
    opt_what = (opt_what ? opt_what.getValue() : 'all');
    if (opt_what == 'all' || opt_what == 'total') type_index = 'price';
    else if (opt_what == 'total_disc') type_index = 'total_disc';
    else type_index = opt_what;

    me.remove(me.down('chart'), true);
    var newdata = [];

    var newconfig = me.generateChartConfig();

    //calculate profitability percentages
    if (show_tasks){
      var i = 0;
      for (var idx in json.tasks)
      {
        i++;
        var thisinfo = json.tasks[idx];
        var thistask = {
          task: 'Task #' + i,
          name: thisinfo.name,
          cost: thisinfo.cost,
          profit: thisinfo.profit,
          price: thisinfo.price,
          unknown: thisinfo.unknown,
          discounts: thisinfo.discounts,
          parts: (opt_what == 'total_disc' ? thisinfo.parts['price'] - thisinfo.parts['discounts'] : thisinfo.parts[type_index]),
          labour: (opt_what == 'total_disc' ? thisinfo.labour['price'] - thisinfo.labour['discounts'] : thisinfo.labour[type_index]),
          expenses: (opt_what == 'total_disc' ? thisinfo.expenses['price'] - thisinfo.expenses['discounts'] : thisinfo.expenses[type_index]),
          total: (opt_what == 'total' ? thisinfo.price : thisinfo.price - thisinfo.discounts),
          raw: thisinfo
        };
        newdata.unshift(thistask);
      }
      newconfig.height = i*30;
    } else {
      newdata = [{
        task: 'All Tasks', 
        profit: json.profit, 
        cost: json.cost,
        discounts: json.discounts, 
        unknown: json.unknown, 
        price: json.price,
        parts: (opt_what == 'total_disc' ? json.parts['price'] - json.parts['discounts'] : json.parts[type_index]),
        labour: (opt_what == 'total_disc' ? json.labour['price'] - json.labour['discounts'] : json.labour[type_index]),
        expenses:(opt_what == 'total_disc' ? json.expenses['price'] - json.expenses['discounts'] : json.expenses[type_index]),
        total: (opt_what == 'total' ? json.price : json.price - json.discounts),
        raw: json
      }];
      newconfig.height = 250;
    }

    me.current_store.loadData(newdata);
    me.add(newconfig);
  },

  generateChartConfig: function()
  {
    var me = this;
    var config = me.baseChartConfig;

    var show_tasks = (me.down('#filter_tasks').getValue() == 'tasks');
    var opt_subtotal = me.down('#filter_subtotal');
    opt_subtotal = (opt_subtotal ? opt_subtotal.getValue() : 'finance');
    var opt_what = me.down('#filter_type');
    opt_what = (opt_what ? opt_what.getValue() : 'all');

    var fields;
    if (opt_subtotal == 'finance')
    {
      if (opt_what == 'all') fields = ['cost','discounts','unknown','profit'];
      else if (opt_what == 'total_disc') fields = ['total'];
      else fields = [opt_what];
    }
    else if (opt_subtotal == 'type')
    {
      fields = ['parts','labour','expenses'];
    }
    else if (opt_subtotal  == 'all')
    {
      if (opt_what == 'all' || opt_what == 'total_disc') fields = ['total'];
      else fields = [opt_what];
    }

    config.axes[0].fields = fields;
    config.series[0].yField = fields;
    config.legend = (opt_subtotal == 'all' ? false : { position: 'right' });

    if (me.pieChart){
      config.series[0].tips.items[1].items.push(me.pieChart);
      config.series[0].tips.height = 300;
    }
    config.series[0].tips.renderer = function(storeItem,item){
      this.setTitle('<span style="font-size: 1.5em;">' + storeItem.data.task + (storeItem.data.name ? ': ' + storeItem.data.name : '')+ '</span>');
      
      var fieldname = Ext.util.Format.capitalize(item.yField);
      var data = [];
      if (opt_subtotal == 'finance' || opt_subtotal == 'all')
      {
        if (storeItem.data.raw.parts[item.yField] > 0) {
          var pct = (100 * storeItem.data.raw.parts[item.yField] / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Parts (' + pct + '%)', data: storeItem.data.raw.parts[item.yField]});
        }
        if (storeItem.data.raw.labour[item.yField] > 0) {
          var pct = (100 * storeItem.data.raw.labour[item.yField] / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Labour (' + pct + '%)', data: storeItem.data.raw.labour[item.yField]});
        }
        if (storeItem.data.raw.expenses[item.yField] > 0) {
          var pct = (100 * storeItem.data.raw.expenses[item.yField] / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Expenses (' + pct + '%)', data: storeItem.data.raw.expenses[item.yField]});
        }
        this.down('#pietitle').update('<div style="text-align:center; font-weight: bold; font-size: 1.2em;">Breakdown of '+fieldname+' by Type</div>');
      } else {
        if (storeItem.data.raw[item.yField].profit > 0){
          var pct = (100 * storeItem.data.raw[item.yField].profit / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Profit (' + pct + '%)', data: storeItem.data.raw[item.yField].profit});
        }
        if (storeItem.data.raw[item.yField].cost > 0){
          var pct = (100 * storeItem.data.raw[item.yField].cost / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Cost (' + pct + '%)', data: storeItem.data.raw[item.yField].cost});
        }
        if (storeItem.data.raw[item.yField].unknown > 0){
          var pct = (100 * storeItem.data.raw[item.yField].unknown / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Unknown (' + pct + '%)', data: storeItem.data.raw[item.yField].unknown});
        }
        if (storeItem.data.raw[item.yField].discounts > 0){
          var pct = (100 * storeItem.data.raw[item.yField].discounts / item.value[1]).toFixed(1).replace(/\.0$/,'');
          data.push({name: 'Discounts (' + pct + '%)', data: storeItem.data.raw[item.yField].discounts});
        }

        this.down('#pietitle').update('<div style="text-align:center; font-weight: bold; font-size: 1.2em;">Breakdown of '+fieldname+' by Costs</div>');
      }
      if (data.length > 0)
      {
        this.down('chart').getStore().loadData(data);
        this.down('chart').show();
        this.down('#pietitle').show(); 
      }
      else
      {
        this.down('chart').hide();
        this.down('#pietitle').hide();
      }

      var text = '<div style="font-size: 1.2em; margin-top: 50px;"><p><strong>' +
        (show_tasks ? 'Task Value: ' : 'Total Value of all Tasks: ') + '</strong><br />' + Ext.util.Format.usMoney(storeItem.data.price) + '</p>' +
        '<p style="color: #aa0000;"><strong>Total of ' + fieldname + ':</strong><br /> ' + Ext.util.Format.usMoney(item.value[1]) + '</p>';
      var portion = item.value[1] / storeItem.data.price;
      text += '<p><strong>' + fieldname + ' as Pct of Total:</strong><br />' + Math.round(portion*100,1) +'%</p></div>';
      this.down('#infoarea').update(text);
    };

    return config;
  },

  baseChartConfig: {
    minHeight: 200,
    anchor: '100%',
    itemId: 'thechart',
    border: true,
    xtype: 'chart',
    animate: true,
    theme: 'WorkorderReportTheme',

    legend: {
      position: 'right'
    },

    axes: [{
        type: 'Numeric',
        position: 'bottom',
        label: {
            renderer: function(v){
              return '$' + v.toFixed(1).replace(/\.[0]+$/,'').replace(/(.)00$/, '.$1k').replace(/\.0k/,'k');
            }
        },
        grid: true
    },{
        type: 'Category',
        position: 'left',
        fields: ['task'],
        title: false        
    }],

    series: [{
      highlight: true,
      type: 'bar',
      shadow: true,
      axis: 'bottom',
      xField: 'task',
      stacked: true,
      tips: {
          trackMouse: true,
          width: 450,
          height: 100,
          layout: {
            type: 'hbox',
            align: 'stretch'
          },
          items: [{
            itemId: 'infoarea',
            xtype: 'container',
            border: false,
            html: '&nbsp;',
            width: 200,
            padding: 10
          },{
            itemId: 'piearea',
            xtype: 'container',
            border: false,
            html: '&nbsp;',
            width: 250,
            padding: 0,
            layout: 'vbox',
            items: [{
              itemId: 'pietitle',
              xtype: 'container',
              border: false,
              html: 'Test Title',
              width: 250
            }]
          }]
      },
      label: {
        constrast: true
      }  
    }]
  }
});



});
