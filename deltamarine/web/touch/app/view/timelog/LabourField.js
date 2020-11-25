Ext.define('Delta.view.timelog.LabourField', {
  extend: 'Ext.field.Field',
  xtype: 'labourtypefield',

  hiddenValue: null,
  workorderHighlight: false,
  taskHighlight: false,

  config: {
    isField: true,
    label: 'Labour Type',
    name: 'labour_type_id',

    component: {
      xtype: 'panel',
      layout: { type: 'hbox' },
      padding: 8,
      items: [{
        xtype: 'panel',
        padding: '8 0 0 0',
        html: '<span style="color: #999; font-size: 0.9em;">Not Selected</span>',
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
  },

  reset: function(){
    this.hiddenValue = null;
    this.getComponent().getInnerItems()[0].setHtml('<span style="font-size: 0.9em; color: #999;">Not Selected</span>');
    this.getComponent().getInnerItems()[1].setText('Select').setUi('decline');
  },

  setValueFromRecord: function(record){
    this.hiddenValue = record.data.id;
    if (this.getComponent().getInnerItems().length){
      this.getComponent().getInnerItems()[0].setHtml('<span style="font-size: 0.9em;">' + record.data.name + '</span>');
      this.getComponent().getInnerItems()[1].setText('Change').setUi('normal');
    }
  },

  setValue: function(newValue){
    var me = this;
    this.hiddenValue = newValue;
    
    if (newValue){
      //fetch object from store
      if (record = Ext.StoreManager.get('LabourType').getById(newValue)){
        this.setValueFromRecord(record);
      }
    }
  },

  getValue: function(){
    return this.hiddenValue;
  },

  loadWindow: function(){
    this.open();
  },

  open: function(config){
    var me = this;
    config = config || {};
    thisconfig = { targetField: me };
    var sel = Ext.create('Delta.view.timelog.LabourTypeSelector', Ext.merge({}, thisconfig, config));
    Ext.Viewport.add(sel);
    sel.show();
  }

});


Ext.define('Delta.view.timelog.LabourTypeSelector', {
  extend: 'Ext.Panel',
  xtype: 'labourtypeselector',

  config: {
    modal: true,
    width: 700,
    height: 650,
    layout: { type: 'fit' },
    title: 'Select Labour Type',
    centered: true,
    hideOnMaskTap: true,
    items: [{
      xtype: 'titlebar',
      align: 'center',
      docked: 'top',
      layout: { type: 'hbox', pack: 'right', align: 'center'},
      title: 'Select Labour Type',
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
      xtype: 'dataview',
      useComponents: true,
      defaultType: 'labourtypeitem',
      store: 'LabourType',
      style: 'text-align: center'
    }]
  }
});

Ext.define('Delta.view.timelog.LabourTypeItem', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'labourtypeitem',

  config: {
    margin: 6,
    width: 280,
    style: 'display: inline-block; font-size: 0.7em;',

    nameButton: true,

    dataMap: {
      getNameButton: {
        setText: 'name'
      }
    }
  },

  applyNameButton: function(config) {
    button = Ext.factory(config, 'Delta.view.timelog.LabourTypeButton', this.getNameButton());
    if (this.getRecord().data.id == parseInt(Ext.ComponentQuery.query('labourtypefield')[0].getValue()))
    {
      button.setUi('confirm');
    }
    button.setText(this.getRecord().data.name);
    return button;
  },

  updateNameButton: function(newNameButton, oldNameButton) {
    if (oldNameButton) {
        this.remove(oldNameButton);
    }

    if (newNameButton) {
        this.add(newNameButton);
    }
  }

});

Ext.define('Delta.view.timelog.LabourTypeButton', {
  extend: 'Ext.Button',
  xtype: 'labourtypebutton',
  config: {
    padding: 10,
    listeners: {
      tap: function(but){
        var data = but.getParent().getRecord().data;
        var tofield = but.up('labourtypeselector').config.targetField;

        //set the field values
        tofield.setValueFromRecord(but.getParent().getRecord());

        //close the window and stuff
        var comp = but.up('labourtypeselector').hide();
        if (comp.config.workorderHighlight){
          Ext.Anim.run(comp.config.workorderHighlight, highlightAnim, {duration: 1500});
        }
        if (comp.config.taskHighlight){
          Ext.Anim.run(comp.config.taskHighlight, highlightAnim, {duration: 1500});
        }
        Ext.Anim.run(tofield, highlightAnim, {duration: 1500});
        tofield.up('fieldset').query('field[name="notes"]')[0].focus();
      }
    }
  }
});

