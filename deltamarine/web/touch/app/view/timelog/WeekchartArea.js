Ext.define('Delta.view.timelog.WeekchartArea', {
  extend: 'Ext.Panel',
  xtype: 'timelogweekchartarea',
  config: {
    layout: {type: 'vbox', align: 'center' },
    items: [{
      height: 60,
      xtype: 'segmentedbutton',
      allowToggle: false,
      align: 'center',
      defaults: { height: 50 },
      items: [
        { id: 'timelogweekchartprev', width: 50, iconCls: 'arrow_left', iconMask: true },
        { id: 'timelogweekcharttext', width: 250, text: 'This Week', disabled: true},
        { id: 'timeloweekchartnext', width: 50, iconCls: 'arrow_right', iconMask: true, disabled: true }
      ]
    },{
      id: 'timelogweekchartcardholder',
      xtype: 'container',
      layout: 'card',
      items: [
        { xtype: 'timelogweekchart' }
      ]
    }]
  }
});

