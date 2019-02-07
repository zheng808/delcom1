Ext.define('Delta.view.workorder.TaskField', {
  extend: 'Ext.field.Field',
  xtype: 'taskfield',

  hiddenValue: null,
  editable: true,

  config: {
    isField: true,
    label: 'Task',
    name: 'task_id',

    component: {
      xtype: 'panel',
      layout: { type: 'hbox' },
      padding: 8,
      items: [{
        xtype: 'panel',
        padding: '2 0 3 0',
        html: '<span style="font-size: 0.9em; color: #999;">Not Selected</span>',
        flex: 1
      },{
        xtype: 'button',
        width: 100,
        text: 'Select',
        ui: 'decline'
      }]
    }
  },

  initialize: function(){
    var me = this;
    me.callParent();

    me.getComponent().getInnerItems()[1].on({
      scope: this,
      tap: 'loadWindow'
    });

    if (!me.editable) {
      me.getComponent().getInnerItems()[1].setHidden(true);
    }
  },

  reset: function() {
    this.hiddenValue = null;
    this.getComponent().getInnerItems()[0].setHtml('<span style="font-size: 0.9em; color: #999;">Not Selected</span>');
    this.getComponent().getInnerItems()[1].setText('Select').setUi('decline');
  },

  setValueFromRecord: function(record){
    this.hiddenValue = record.data.id;
    if (this.getComponent().getInnerItems().length){
      var details = '<div style="font-size: 0.9em;">' + record.data.name +'</div>';
      if (record.data.path){
        details = '<div style="padding-bottom: 4px; color: #666; font-size: 0.7em; font-style: italic;">' + record.data.path +' &gt;</div>' + details;
      }
      this.getComponent().getInnerItems()[0].setHtml(details);
      this.getComponent().getInnerItems()[1].setText('Change').setUi('normal');
    }
  },

  setValue: function(newValue){
    var me = this;
    this.hiddenValue = newValue;

    //fetch object from store
    if (newValue){
      var record = Ext.ModelManager.getModel('Delta.model.Task').load(newValue,{
        success: function(rec){
          me.setValueFromRecord(rec);
        }
      });
    }
  },

  getValue: function(){
    return this.hiddenValue;
  },

  loadWindow: function(but){
    this.open();
  },

  open: function(config){
    var me = this;
    var workorderval = me.up('fieldset').query('workorderfield')[0].getValue();
    if (workorderval){
      config == config || {};
      var thisconfig = {
        targetField: me,
        workorderValue: me.up('fieldset').query('workorderfield')[0].getValue()  
      };
      var sel = Ext.create('Delta.view.workorder.TaskSelector',Ext.merge({}, thisconfig, config));
      Ext.Viewport.add(sel);
      sel.show();
    } else {
      me.up('fieldset').query('workorderfield')[0].open();
    }
  }
});

Ext.define('Delta.view.workorder.TaskSelector', {
  extend: 'Ext.Panel',
  xtype: 'taskselector',
  
  targetField: null,
  workorderValue: null,

  config: {
    modal: true,
    width: 550,
    height: 650,
    layout: { type: 'fit' },
    centered: true,
    hideOnMaskTap: true,
    items: [{
      xtype: 'titlebar',
      align: 'center',
      docked: 'top',
      layout: { type: 'hbox', pack: 'right', align: 'center'},
      title: 'Select Workorder Task',
      items: [{
        xtype: 'button',
        text: 'Cancel',
        align: 'right',
        ui: 'decline',
        handler: function(but){
          but.up('panel').destroy();
        }
      }]
    },{
      itemId: 'scrollmsg',
      xtype: 'container',
      docked: 'bottom',
      height: 25,
      padding: 5,
      style: 'background-color: #000; font-size: 0.8em; text-align: center; color: #aaa;',
      html: 'Swipe list up to view more...'
    },{
      xtype: 'dataview',
      emptyText: 'No workorders tasks found!',
      useComponents: true,
      defaultType: 'taskitem',
      padding: '20 0 20 0',
      style: 'text-align: left'
    }],
    
    listeners: {
      'initialize': function(me){
        //todo
        var mydataview = me.getInnerItems()[0]
        if (me.config.workorderValue){
          mydataview.setStore(new Ext.data.Store({
          autoLoad: true,
          model: 'Delta.model.Task',
          proxy: {
            type: 'ajax',
            url: '/touch.php/rest/task',
            extraParams: { workorder_id: me.config.workorderValue},
            reader: {
              rootProperty: 'tasks'
            }
          }
        }));

        }     
      }
    }
  }
});

Ext.define('Delta.view.workorder.TaskItem', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'taskitem',

  config: {
    margin: '6 12 6 12',
    width: 400,
    style: 'display: inline-block; font-size: 0.8em;',

    detailRow: true,

    dataMap: {
      getDetailRow: {
        setDetails: 'id'
      }
    }
  },

  applyDetailRow: function(config) {
    return Ext.factory(config, 'Delta.view.workorder.TaskRenderer', this.getDetailRow());
  },

  updateDetailRow: function(newDetailRow, oldDetailRow) {
    if (oldDetailRow) { this.remove(oldDetailRow); }
    if (newDetailRow) { this.add(newDetailRow);    }
  }

});

Ext.define('Delta.view.workorder.TaskRenderer', {
  extend: 'Ext.SegmentedButton',
  config: {
    allowToggle: false,
    margin: '0 0 4 20',
    defaults: { ui: 'normal', padding: 3, style: 'text-align: left', height: 55 },
    items: [
      { width: 80, html: '...', padding: 5, style: 'font-weight: bold; line-height: 1.2em;' },
      { width: 240, html: '...', style: 'text-align: left;' },
      { width: 80, html: '...'  }
    ]
  },
  setDetails: function(info){
    var me = this;
    var items = me.getInnerItems();
    var data = me.parent.getRecord().data;
    var oldval = parseInt(Ext.ComponentQuery.query('fieldset field[name="task_id"]')[0].getValue());

    me.parent.setMargin('0 0 4 ' + (20 + data.level * 25));
    items[0].setText('Task ' + data.numbering); 
    items[1].setHtml('<div style="line-height: 1.2em; font-size: 1em; font-style: italic; white-space: normal">' + data.name + '</div>');
    items[1].setWidth(320 - (25 * data.level));
    items[2].setText('<span style="font-size: 0.8em;">' + (data.completed ? 'Completed' : 'In Progress' ) +'</span>');
    items[2].setStyle('background-image: none; background-color: ' + (data.completed ? '#f0fff0' : '#fffff0'));
    if (oldval == data.id){
      items[0].setUi('confirm');
      items[1].setUi('confirm');
    }

    var selecthandler = function(but){
      var tofield = but.up('taskselector').config.targetField;

      //save the value and clear the popup
      tofield.setValueFromRecord(but.up('taskitem').getRecord());
      var comp = but.up('taskselector').hide();

      //open up the labour rate window if needed
      var labfield = Ext.ComponentQuery.query('labourtypefield');
      if (labfield.length > 0) {
        if (!labfield[0].getValue()) {
          labfield[0].open({
            workorderHighlight: (but.up('taskselector').config.workorderHighlight ? but.up('taskselector').config.workorderHighlight : false),
            taskHighlight: tofield
          });
        }
      } else {
        //highlight field(s)
        Ext.Anim.run(tofield, highlightAnim, {duration: 2500});
        if (but.up('taskselector').config.workorderHighlight){
          Ext.Anim.run(but.up('taskselector').config.workorderHighlight, highlightAnim, { duration: 2500});
        }
      }

      //delay destroying, since otherwise 'but' above will be undefined
      comp.destroy();

      return false;
    }
    items[0].on('tap', selecthandler);
    items[1].on('tap', selecthandler);
    items[2].on('tap', selecthandler);
  }

});

