Ext.define('Delta.view.timelog.DaychartArea', {
  extend: 'Ext.Panel',
  xtype: 'timelogdaychartarea',
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
          { itemId: 'timelogdaychartprev', width: 50, iconCls: 'arrow_left', iconMask: true },
          { itemId: 'timelogdaycharttext', width: 250, text: 'Today'},
          { itemId: 'timelogdaychartnext', width: 50, iconCls: 'arrow_right', iconMask: true, disabled: true }
        ]
      }]
    },{
      itemId: 'testing',
      xtype: 'timelogdaychartholder'
    }]
  }
});

Ext.define('Delta.view.timelog.DaychartHolder', {
  extend: 'Ext.Panel',
  xtype: 'timelogdaychartholder',
  config: {
    layout: { type: 'card' }
  }
});
