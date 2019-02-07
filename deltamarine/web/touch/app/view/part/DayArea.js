Ext.define('Delta.view.part.DayArea', {
  extend: 'Ext.Panel',
  xtype: 'partdayarea',
  config: {
    layout: {type: 'fit' },
    items: [{
      height: 60,
      xtype: 'container',
      docked: 'top',
      margin: '10 0 10 0',
      items: [{
        xtype: 'segmentedbutton',
        allowToggle: false,
        centered: true,
        defaults: { height: 50 },
        items: [
          { itemId: 'partdayprev', width: 50, iconCls: 'arrow_left', iconMask: true },
          { itemId: 'partdaytext', width: 250, text: 'Today'},
          { itemId: 'partdaynext', width: 50, iconCls: 'arrow_right', iconMask: true, disabled: true }
        ]
      }]
    },{
      xtype: 'partdayholder'
    }]
  }
});

Ext.define('Delta.view.part.DayHolder', {
  extend: 'Ext.Panel',
  xtype: 'partdayholder',
  config: {
    layout: { type: 'card'}
  }
});

