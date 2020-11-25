Ext.define('Delta.view.part.PutBack', {
  extend: 'Ext.Panel',
  requires: [
    'Delta.view.part.Field',
    'Delta.view.workorder.Field',
    'Delta.view.workorder.TaskField'
  ],
  xtype: 'partputback',

  config: {
    title: 'Put Back Part',
    layout: { type: 'vbox', align: 'center', pack: 'center' },
    items: [{
      flex: 1,
      width: 600,
      xtype: 'formpanel',
      items: [{ 
        xtype: 'fieldset',
        items: [{
          xtype: 'partfield',
          editable: false
        },{
          xtype: 'workorderfield',
          editable: false
        },{
          xtype: 'taskfield',
          editable: false
        },{
          itemId: 'quantity',
          xtype: 'field',
          label: 'Original Quantity',
          component: {
            html: '..',
            styleHtmlContent: true
          }
        }]
      },{
        xtype: 'fieldset',
        items: [{
          itemId: 'quantity_back',
          xtype: 'spinnerfield',
          label: 'Quantity to put Back',
          name: 'quantity',
          stepValue: 1,
          value: 1,
          minValue: 0,
          maxValue: 1
        },{
          xtype: 'hiddenfield',
          name: 'id'
        }]
      },{
        xtype: 'container',
        itemId: 'buttonholder',
        layout: { type: 'hbox' },
        defaults: { margin: 5 },
        items:[{
          flex: 1,
          xtype: 'button',
          ui: 'action',
          text: 'Put Back Part',
          name: 'putbackpart_submit'
        }]
      }]
    }],


    listeners: { 
      'show': function(me){
        if (me.config.existingRecord) {
          me.query('formpanel')[0].setValues(me.config.existingRecord.data);

          //set the max and value of the quantity field
          me.query('formpanel #quantity')[0].getComponent().setHtml(me.config.existingRecord.data.quantity);
          me.query('formpanel #quantity_back')[0].setMaxValue(me.config.existingRecord.data.quantity);
          me.query('formpanel #quantity_back')[0].setValue(me.config.existingRecord.data.quantity);
        }
      }
    }           
  }
});
