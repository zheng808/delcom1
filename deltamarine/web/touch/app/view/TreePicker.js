Ext.define('Delta.view.TreePicker', {
  extend: 'Ext.field.Select',
  xtype: 'treepickerfield',

  config: {
    usePicker: false, 
    autoSelect: false
  },

  getTabletPicker: function() {
    var config = this.getDefaultTabletPickerConfig();

    if (!this.listPanel) {
        this.listPanel = Ext.create('Ext.Panel', Ext.apply({
            left: 0,
            top: 0,
            modal: true,
            cls: Ext.baseCSSPrefix + 'select-overlay',
            layout: 'fit',
            hideOnMaskTap: true,
            width: Ext.os.is.Phone ? '14em' : '18em',
            height: Ext.os.is.Phone ? '12.5em' : '22em',
            items: {
                xtype: 'nestedlist',
                store: this.getStore(),
                displayField: 'name',
                itemTpl: '<span class="x-list-label">{' + this.getDisplayField() + ':htmlEncode}</span>',
                listeners: {
                    leafitemtap: this.onListTap,
                    scope  : this
                }
            }
        }, config));
    }

    return this.listPanel;
  },

  onListTap: function(tree,list,index,dom,record){
    this.callParent();
    if (record){
      this.setValue(record);
    }
  },

  showPicker: function() {
    var store = this.getStore();
    //check if the store is empty, if it is, return
    if (!store || store.getRoot().childNodes.length === 0) {
        return;
    }

    if (this.getReadOnly()) {
        return;
    }

    this.isFocused = true;

    if (this.getUsePicker()) {
        var picker = this.getPhonePicker(),
            name   = this.getName(),
            value  = {};

        value[name] = this.getValue();
        picker.setValue(value);
        if (!picker.getParent()) {
            Ext.Viewport.add(picker);
        }
        picker.show();
    } else {
        var listPanel = this.getTabletPicker(),
            list = listPanel.down('nestedlist'),
            index, record;

        store = list.getStore();
        index = store.find(this.getValueField(), this.getValue(), null, null, null, true);
        record = store.getAt((index == -1) ? 0 : index);

        if (!listPanel.getParent()) {
            Ext.Viewport.add(listPanel);
        }

        listPanel.showBy(this.getComponent());
    }
  },

  initialize: function(){
    var me = this;
    me.callParent();
    me.getStore().load();
  }
});
