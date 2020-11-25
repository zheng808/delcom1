Ext.define('Delta.view.part.Add', {
  extend: 'Ext.Panel',
  requires: [
    'Delta.view.part.Field',
    'Delta.view.workorder.Field',
    'Delta.view.workorder.TaskField'
  ],
  xtype: 'partadd',

  config: {
    title: 'Add New Part',
    layout: { type: 'vbox', align: 'center', pack: 'center' },
    items: [{
      flex: 1,
      width: 600,
      xtype: 'formpanel',
      items: [{ 
        xtype: 'fieldset',
        items: [{
          itemId: 'addpart_date',
          xtype: 'datepickerfield',
          name: 'date_used',
          label: 'Date',
          dateFormat: 'l F j, Y',
          listeners: {
            change: function(me, newval){
              if (new Date(newval).getTime() > (Ext.Date.now() + 3600))
              {
                me.setValue(new Date());
                var warning = Ext.create('Ext.Panel', {
                  html: 'Time travel not allowed',
                  left: 0,
                  padding: 5
                });
                Ext.defer(function(){ warning.showBy(me, 'bl-br?'); }, 500);
                Ext.defer(function(){ warning.destroy(); }, 3000);
              }
            }
          },
          picker: {
            fullscreen: false,
            yearFrom: 2011,
            yearTo: parseInt(Ext.Date.format(new Date(), 'Y')),
            hideOnMaskTap: true
          }
        },{
          itemId: 'quantity',
          xtype: 'spinnerfield',
          label: 'Quantity',
          name: 'quantity',
          stepValue: 1,
          value: 1,
          minValue: 0,
          component: { disabled: false }
        },{
          xtype: 'partfield'
        },{
          xtype: 'workorderfield'
        },{
          xtype: 'taskfield'
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
          text: 'Save Part',
          name: 'addpart_submit'
        }]
      }]
    }],


    listeners: { 
      'show': function(me){
        if (me.config.existingRecord != undefined){
          me.query('formpanel')[0].setValues(me.config.existingRecord.data);
        }
        else
        {
          //set default date value
          var datefield = me.query('datepickerfield')[0];
          if (me.config.defaultdate && (me.config.defaultdate.getTime() <= (Ext.Date.now())) && (Ext.Date.format(me.config.defaultdate, 'Ymd') != Ext.Date.format(new Date(), 'Ymd'))) {
            datefield.setValue(me.config.defaultdate);
            Ext.defer(function() { Ext.Anim.run(datefield, highlightAnim, {duration: 1500}); }, 500);
          } else {
            datefield.setValue(new Date());
          }

          //see if we've been sent along a workorder or task id
          if (me.config.defaultWo){
            me.query('workorderfield')[0].setValue(me.config.defaultWo);
          }
          if (me.config.defaultWoi){
            me.query('taskfield')[0].setValue(me.config.defaultWoi);
          }
          if (me.config.defaultPart){
            me.query('partfield')[0].setValue(me.config.defaultPart);
          }
        }
      }
    }           
  }
});
