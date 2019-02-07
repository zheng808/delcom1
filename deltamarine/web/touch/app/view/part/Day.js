Ext.define('Delta.view.part.Day', {
  extend: 'Ext.Panel',
  xtype: 'partday',
  config: {
    xtype: 'container',
    width: 300,
    items: [],

    listeners: {
      'initialize': function(me){
        if (me.date == null){
          me.date = Ext.Date.format(new Date(), 'Y-m-d');
        }
      }
    }
  }
});

