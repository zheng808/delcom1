Ext.define('Delta.view.part.Field', {
  extend: 'Ext.field.Field',
  requires: [
    'Delta.view.part.Search'
  ],
  xtype: 'partfield',
  
  hiddenValue: null,
  editable: true,

  config: {
    isField: true,
    label: 'Part',
    name: 'part_variant_id',

    component: {
      xtype: 'panel',
      layout: { type: 'hbox' },
      padding: 8,
      items: [{
        xtype: 'panel',
        html: '<span style="font-size: 0.9em; color: #999;">Not Selected (Use Scanner Now)</span>',
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
    this.getComponent().getInnerItems()[0].setHtml('<span style="font-size: 0.9em; color: #999;">Not Selected (Use Scanner Now)</span>');
    this.getComponent().getInnerItems()[1].setText('Select').setUi('decline');
  },

  setValueFromRecord: function(record){
    this.hiddenValue = record.data.part_variant_id;
    if (this.getComponent().getInnerItems().length){
      var units = (record.data.units ? record.data.units : 'ea');
      this.getComponent().getInnerItems()[0].setHtml('<div style="color: #666; padding-bottom: 4px; font-size: 0.7em; font-style: italic;">' + record.data.category_path +'</div><div style="font-size: 0.9em;">' + record.data.name + '<div style="font-size: 0.8em; color: #666;padding-top: 4px;">'+record.data.price+'/'+units+'</div>');
      this.getComponent().getInnerItems()[1].setText('Change').setUi('normal');
    }
  },

  setValue: function(newValue){
    var me = this;
    this.hiddenValue = newValue;

    //fetch object from store
    if (newValue){
      var record = Ext.ModelManager.getModel('Delta.model.Part').load(newValue, {
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
  
  open: function(){
    var me = this;
    var sel = Ext.create('Ext.Panel', {
      modal: true,
      width: 900,
      height: 650,
      layout: { type: 'fit' },
      centered: true,
      hideOnMaskTap: true,
      items: [{
        xtype: 'titlebar',
        align: 'center',
        docked: 'top',
        layout: { type: 'hbox', pack: 'right', align: 'center'},
        title: 'Select Part',
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
        xtype: 'partsearch',
        targetField: me
      }]
    });
    Ext.Viewport.add(sel);
    sel.query('dataview')[0].getStore().clearFilter(true);
    sel.show();
  }
});

