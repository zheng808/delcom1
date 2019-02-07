Ext.define('Delta.view.RecentActivity', {
  extend: 'Ext.Panel',
  xtype: 'recentactivity',
  requires: [],

  config: {
    title: 'Recent Activity',
    styleHtmlContent: true,
    items: [{
      centered: true,
      padding: 100,
      html: 'Welcome to the Delta Touchscreen Application.<br /><br />Browse the menu at left or just use the buttons in the bottom-left corner. When done, use the Logout button in the bottom-right corner.'
    }]
  }
});
