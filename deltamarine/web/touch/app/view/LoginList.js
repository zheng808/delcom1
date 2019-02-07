Ext.define('LoginListButton', {
  extend: 'Ext.Button',
  xtype: 'loginlistbutton',
  config: {
    padding: '15'
   }
});

Ext.define('LoginListItem',{
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'loginlistitem',

  config: {
    style: 'display: inline-block; width: 150px; padding: 15px 10px;',

    nameButton: true,

    dataMap: {
      getNameButton: {
        setText: 'shortname'
      }
    }

  },

  applyNameButton: function(config) {
    return Ext.factory(config, 'LoginListButton', this.getNameButton());
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

Ext.define('Delta.view.LoginList', {
  extend: 'Ext.dataview.DataView',
  
  xtype: 'loginlist',

  config: {
    scrollable: false,
    useComponents: true,
    defaultType: 'loginlistitem',
    store: 'Employee',
    style: 'text-align: center;'
  }
});
