Ext.define('Delta.controller.WorkorderHome', {
  extend: 'Ext.app.Controller',

  config: {
    
    refs: {
      workorderButton: 'workorderfield button',
      wofilterButton: 'workordersearch toolbar segmentedbutton',
      wofilterSelect: 'workordersearch toolbar selectfield',
      taskButton: 'taskfield button'
    },

    control: {
      workorderButton: { tap: 'openWorkorder' },
      wofilterButton: { toggle: 'filterWorkorders' },
      wofilterSelect: { change: 'filterWorkorderCat' },
      taskButton: { tap: 'openTask' }
    }
  },

  openWorkorder: function(but){
    var target = but.up('container');
    var sel = Ext.create('Delta.view.workordersearch', {
      targetField: target
    });
    Ext.Viewport.add(sel);
    sel.query('dataview')[0].getStore().clearFilter(true);
    sel.show();
  },

  filterWorkorderCat: function(sel, val){
    var store = Ext.ComponentQuery.query('workordersearch dataview')[0].getStore();
    var segs = sel.up('toolbar').query('segmentedbutton');

    store.clearFilter(true);
    for (var i = 0; i < 3; i++){
      var seg = segs[i];
      var items = seg.getPressedButtons();
      if (items.length == 1 && items[0].config.searchValue != undefined){
        store.filter(seg.config.searchField, items[0].config.searchValue);
      } else if (items.length == 1) {
        store.filter(seg.config.searchField, items[0].config.searchRegex);
      }
    }
    if (sel.getValue() == 'none'){
      store.filter('category_id', /0/);
    } else if (sel.getValue() != 'all'){
      store.filter('category_id', sel.getValue());
    }
    if (store.getCount() > 12){
      Ext.ComponentQuery.query('workordersearch container[docked="bottom"]')[1].show({ type: 'fade'});
    } else {
      Ext.ComponentQuery.query('workordersearch container[docked="bottom"]')[1].hide();
    }

  },

  filterWorkorders: function(origseg, but, ison){
    var store = Ext.ComponentQuery.query('workordersearch dataview')[0].getStore();
    var segs = origseg.up('toolbar').query('segmentedbutton');
    var sel = origseg.up('toolbar').query('selectfield')[0];
    store.clearFilter(true);
    store.setSorters([]);
    for (var i = 0; i < 3; i++){
      var seg = segs[i];
      var items = seg.getPressedButtons();
      if (items.length == 1 && items[0].config.searchValue != undefined){
        store.filter(seg.config.searchField, items[0].config.searchValue);
      } else if (items.length == 1) {
        store.filter(seg.config.searchField, items[0].config.searchRegex);
      }
    }
    if (sel.getValue() == 'none'){
      store.filter('category_id', /0/);
    } else if (sel.getValue() != 'all'){
      store.filter('category_id', sel.getValue());
    }
    if (store.getCount() > 12){
      Ext.ComponentQuery.query('workordersearch container[docked="bottom"]')[1].show({ type: 'fade'});
    } else {
      Ext.ComponentQuery.query('workordersearch container[docked="bottom"]')[1].hide();
    }
    if (origseg.config.searchField == 'lastnamefirst'){
      store.sort('lastnamefirst', 'ASC');
    } else if (origseg.config.searchField == 'boatname'){
      store.sort('boat_name', 'ASC');
    } else {
      store.sort('id', 'DESC');
    }
  },
    
  openTask: function(but){
    var target = Ext.ComponentQuery.query('taskfield')[0];
    wo_id = Ext.ComponentQuery.query('fieldset field[name="workorder_id"]')[0].getValue();
    if (wo_id){
      var sel = Ext.create('Delta.view.TaskSelector', {
        targetField: target,
        workorderValue: wo_id
      });
    } else {
      target = Ext.ComponentQuery.query('workorderfield')[0];
      var sel = Ext.create('Delta.view.workordersearch', {
        targetField: target
      });
    }

    Ext.Viewport.add(sel);
    sel.show();
  }

});
