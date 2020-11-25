Ext.define('Delta.view.part.Home', {
  extend: 'Ext.Panel',
  xtype: 'parthome',
  requires: [
    'Delta.view.part.DayArea',
    'Delta.view.part.Day',
    'Delta.view.part.List'
  ],

  config: {
    title: 'View Your Parts Used',
    styleHtmlContent: true,
    padding: 10,
    layout: { type: 'fit', align: 'center' },
    items: [{
      xtype: 'partdayarea'
    }]
  }
});
