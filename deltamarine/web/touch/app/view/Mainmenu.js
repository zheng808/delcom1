Ext.define('Delta.view.Mainmenu', {
  extend: 'Ext.dataview.List',
  xtype: 'mainmenu',

  requires: [
    'Delta.view.timelog.Home',
    'Delta.view.part.Home',
    'Delta.view.workorder.Home'
  ],

  config: {
    scrollable: { disabled: true },
    store: {
      id: 'mainmenu',
      fields: [
        { name: 'label', type: 'string' },
        { name: 'classname', type: 'string' },
        { name: 'objxtype', type: 'string' }
      ],
      data: [
        { label: 'Home',            classname: '',                objxtype: '' },
        { label: 'Timelogs',        classname: 'timelog.Home',     objxtype: 'timeloghome' },
        { label: 'Parts',           classname: 'part.Home',        objxtype: 'parthome' }
      ]
    },
    itemTpl: '{label}',
    listeners: {
      'initialize': function(list){
        list.select(0);
      },
      'itemtap' : function(list, index, el, record) {
        //get the navigation view
        var navview = Ext.ComponentQuery.query('#mainnav')[0];
        var thisviewcls = record.data.view_id;
        var items = navview.innerItems;
        //check to see if active view is different from selected one
        if (items.length > 1 && navview.innerItems[1].xtype == record.get('objxtype')) {
          if (items.length > 2) {
            navview.pop(items.length - 2);
          }
        } else {
          //delete all except the first
          navview.pop(items.length - 1);
          //instantiate and add the new view to the navivation view
          if (record.data.classname){
            navview.push(Ext.create('Delta.view.' + record.data.classname));
          }
        }
      }
    }
  }
});
