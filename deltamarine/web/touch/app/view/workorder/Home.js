Ext.define('Delta.view.workorder.Home', {
  extend: 'Ext.Panel',
  xtype: 'workorderhome',
  requires: [],

  config: {
    title: 'Workorder Home',
    layout: 'fit',
    items: [{ xtype: 'workordersearch', modal: false }]
  }

});
