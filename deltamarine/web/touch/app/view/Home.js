Ext.define('Delta.view.Home', {
  extend: 'Ext.Panel',
  xtype: 'home',
  requires: [
    'Delta.view.Mainmenu',
    'Delta.view.RecentActivity',
    'Delta.view.timelog.Add',
    'Delta.view.part.Add',
    'Delta.view.part.PutBack',
    'Delta.view.TreePicker',
    'Ext.TitleBar',
    'Ext.List',
    'Ext.dataview.NestedList',
    'Ext.plugin.ListPaging',
    'Ext.navigation.View',
    'Ext.form.Panel',
    'Ext.form.FieldSet',
    'Ext.field.DatePicker',
    'Ext.field.Hidden',
    'Ext.field.Select',
    'Ext.field.Number',
    'Ext.field.Spinner',
    'Ext.field.Search',
    'Ext.picker.Date'
  ],
  config: {
    layout: 'fit',
    fullscreen: true,
    listeners: {
      'initialize': function(){
         Ext.ComponentQuery.query('#namebutton')[0].setText('&nbsp;&nbsp;Logged in as: ' + current_employee.get('fullname')); 
      }
    },  

    items: [{
      id: 'bottomtool',
      xtype: 'titlebar',
      docked: 'bottom',
      items: [{
        xtype: 'segmentedbutton',
        align: 'left',
        allowToggle: false,
        defaults: { xtype: 'button', iconCls: 'add', iconMask: true},
        items: [
          { id: 'mainaddtimelog', text: ' Add Timelog', width: 180 },
          { id: 'mainaddpart', text: ' Add Part', width: 150 }
        ]
      },{
        xtype: 'segmentedbutton',
        allowToggle: false,
        align: 'right',
        items: [
          { id: 'namebutton', iconCls: 'user', iconMask: true, disabled: true, style: 'padding-right: 10px;' },
          { text: 'Logout',  handler: function(){ window.location.reload(false); }, ui: 'decline', width: 80 }
        ]
      }]
    },{
      layout: { type: 'hbox' },
      items: [{
        layout: { type: 'fit' },
        width: 200,
        docked: 'left',
        items: [{  
          xtype: 'toolbar', title: '', docked: 'top' 
        },{ 
          xtype: 'mainmenu', cls: 'mainmenulist' 
        }]
      },{
        flex: 1,
        id: 'mainnav',
        xtype: 'navigationview',
        autoDestroy: true,

        navigationBar:  { ui: 'dark', docked: 'top' },
        items: [{ xtype: 'recentactivity' }],

        listeners: {
          'push': function(nav){
            items = nav.getInnerItems();
            menu = Ext.ComponentQuery.query('mainmenu')[0];
            //check for mismatch of menu and content
            if (items.length > 1 && menu.getSelection().length > 0 && menu.getSelection()[0].data.objxtype != items[1].xtype){
            }
          },
          'back': function(nav){
            if (nav.getInnerItems().length == 2) Ext.ComponentQuery.query('mainmenu')[0].select(0);
          }
        }
      }]
    }]
  }
});

var highlightAnim = new Ext.Anim({
    type: 'highlight',
    before: function(el) {
        var fromColor = '#aaccaa',
            toColor = el.getStyle('background-color') ? el.getStyle('background-color') : '#ffffff';

        this.from = {
            'background-color': fromColor
        };
        this.to = {
            'background-color': toColor
        };
    }
});
