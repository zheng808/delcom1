Ext.define('Delta.view.timelog.Home', {
  extend: 'Ext.Panel',
  xtype: 'timeloghome',

  requires: [
    'Delta.view.timelog.DaychartArea',
    'Delta.view.timelog.Daychart',
    'Delta.view.timelog.WeekchartArea',
    'Delta.view.timelog.Weekchart',
    'Delta.view.timelog.List'
  ],

  config: {
    title: 'View Timelogs',
    styleHtmlContent: true,
    padding: 10,
    layout: { type: 'fit', align: 'center'},
    items: [
/*
    {
      xtype: 'container',
      docked: 'top',
      height: 30,
      margin: '10 0 10 0',
      items: [{
        xtype: 'segmentedbutton',
        defaults: { width: 175 },
        centered: true,
        items: [
          { id: 'timeloghomeday', text: 'View By Day', pressed: true},
          { id: 'timeloghomeweek', text: 'View By Week' }
        ]
      }]
    },
*/
    {
      id: 'timelogchartcardholder',
      flex: 1,
      xtype: 'container',
      layout: 'card',
      animation: {duration: 300},
      items: [
        { xtype: 'timelogdaychartarea' },
        { xtype: 'timelogweekchartarea' }
      ]
    }]
  }
});
