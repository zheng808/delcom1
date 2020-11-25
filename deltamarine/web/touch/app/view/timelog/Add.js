Ext.define('Delta.view.timelog.Add', {
  extend: 'Ext.Panel',
  requires: [
    'Delta.view.timelog.BillableField',
    'Delta.view.timelog.HoursField',
    'Delta.view.workorder.Field',
    'Delta.view.workorder.TaskField',
    'Delta.view.timelog.LabourField'
  ],
  xtype: 'timelogadd',

  config: {
    title: 'Add New Timelog',
    layout: { type: 'vbox', align: 'center' },
    items: [{
      flex: 1,
      width: 600,
      xtype: 'formpanel',
      items: [{ 
        xtype: 'fieldset',
        items: [{
          itemId: 'addtimelog_date',
          xtype: 'datepickerfield',
          name: 'end_date',
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
          xtype: 'billablefield'
        },{
          xtype: 'hoursfield'
        },{
          itemId: 'workorder',
          xtype: 'workorderfield'
        },{
          itemId: 'task',
          xtype: 'taskfield'
        },{
          xtype: 'labourtypefield',
          itemId: 'labourtype'
        },{
          itemId: 'nonbilltype',
          xtype: 'selectfield',
          label: 'Description',
          usePicker: false,
          name: 'nonbill_type_id',
          store: 'NonbillType',
          displayField: 'name',
          valueField: 'id'
        },{
          label: 'Notes',
          xtype: 'textareafield',
          name: 'notes',
          placeHolder: 'Tap here to type notes...',
          maxRows: 6
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
          text: 'Save Timelog',
          name: 'addtimelog_submit'
        }]
      }]
    }],


    listeners: { 
      'show': function(me){
        if (me.config.existingRecord != undefined){
          //apply the data from the store
          me.query('formpanel')[0].setValues(me.config.existingRecord.data);
        } else {
          //set default date value
          var datefield = me.query('datepickerfield')[0];
          if (me.config.defaultdate && (me.config.defaultdate.getTime() <= (Ext.Date.now())) && (Ext.Date.format(me.config.defaultdate, 'Ymd') != Ext.Date.format(new Date(), 'Ymd'))) {
            datefield.setValue(me.config.defaultdate);
            Ext.defer(function() { Ext.Anim.run(datefield, highlightAnim, {duration: 1500}); }, 500);
          } else {
            datefield.setValue(new Date());
          }

          //set the billable type to billable by default;
          me.query('billablefield')[0].setValue(1);

          //see if we've been sent along a workorder or task id
          if (me.config.defaultWo){
            me.query('workorderfield')[0].setValue(me.config.defaultWo);
          }
          if (me.config.defaultWoi){
            me.query('taskfield')[0].setValue(me.config.defaultWoi);
          }
        }

      }
    }           
  }
});
