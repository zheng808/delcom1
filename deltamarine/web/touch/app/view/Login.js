Ext.define('Delta.view.Login', {
  extend: 'Ext.Panel',
  requires: [
    'Ext.SegmentedButton',
    'Ext.dataview.DataView',
    'Ext.TitleBar',
    'Delta.view.LoginList'
  ],

  xtype:  'loginpage',

  config: {
    layout: 'vbox',
    styleHtmlContent: true,

    items: [
    {
      docked: 'top',
      xtype: 'titlebar',
      ui: 'light',
      title: 'Login to Delta Touch System'
    },{
      xtype: 'toolbar',
      docked: 'top',
      layout: {
        type: 'hbox',
        pack: 'center'
      },
      items: [{
        xtype: 'container',
        html: 'Employee Type: ',
        style: 'color: #fff; padding: 0 25px 0 0;'
      },{
        id: 'filterloginlist',
        xtype: 'segmentedbutton',
        allowDepress: false,
        items: [
          { id: 'filterloginlist_emp', text: 'Employees', pressed: true },
          { id: 'filterloginlist_con', text: 'Contractors' }
        ]
      }]
    },{
      html: 'Touch your name below to log in. You will be prompted for your password.',
      height: 40,
      style: 'text-align: center;'
    },{
      id: 'loginerror',
      html: '',
      flex: 0,
      style: 'text-align: center; color: #cc3333;'
    },{
      id: 'loginlistcontainer',
      scrollable: { outOfBoundRestrictFactor: 0 },
      layout: { type: 'hbox', pack: 'center', align: 'center' },
      items: [ {xtype: 'loginlist', flex: 1, maxWidth: 800 }],
      flex: 1
      
    }]
  }
});
