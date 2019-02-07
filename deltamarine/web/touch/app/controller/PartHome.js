currentPartDate = new Date();

Ext.define('Delta.controller.PartHome', {
  extend: 'Ext.app.Controller',

  config: {
    models: [
      'Delta.model.NonbillType',
      'Delta.model.LabourType'
    ],
    stores: [
      'Delta.store.NonbillType',
      'Delta.store.LabourType'
    ],

    refs: {
      prevDayButton: 'partdayarea segmentedbutton #partdayprev',
      nextDayButton: 'partdayarea segmentedbutton #partdaynext',
      calendarButton: 'partdayarea segmentedbutton #partdaytext',
      cardholder:    'partdayholder',
      partfilterName: 'partsearch #partname',
      partfilterSku: 'partsearch #partsku',
      partfilterCat: 'partsearch #partcat'
    },
    control: {
      prevDayButton: { tap: 'previousDay' },
      nextDayButton: { tap: 'nextDay' },
      calendarButton: { tap: 'pickDay' },
      cardholder:    { initialize: 'setupToday' },
      partfilterName: { keyup: 'doFilter', change: 'doFilter'},
      partfilterSku: { keyup: 'doFilter', change: 'doFilter'},
      partfilterCat: { change: 'doFilter' }
    }
  },

  pickDay: function(){
    yearTo = parseInt(Ext.Date.format(new Date(), 'Y'));
    var picker = Ext.create('Ext.picker.Date', {
      value: currentPartDate,
      yearFrom: 2011,
      yearTo: yearTo,
      hideOnMaskTap: true,
      listeners: {
        'change': function(pick, val){
           var cardholder = Ext.ComponentQuery.query('partdayholder')[0];
           currentPartDate = val;
           var formatteddate = Ext.Date.format(val, 'Y-m-d');
           var found = cardholder.query('#partdayouter-' + formatteddate);
           if (found.length == 1){
             cardholder.animateActiveItem(found[0], { type: 'slide', direction: 'down', duration: 200 });
           } else {
             cardholder.animateActiveItem({
               xtype: 'container',
               id: 'partdayouter-' + formatteddate,
               layout: { type: 'vbox', align: 'center'},
               items: [
                 { height: 5, width: 700, xtype: 'partday', id: 'partday-' + formatteddate },
                 { flex: 1, width: 700, xtype: 'partlist', autoLoadDate: formatteddate }
               ]
             },{type: 'slide', direction: 'down', duration: 200 });
           }
           var is_today = (Ext.Date.format(currentPartDate, 'Ymd') == Ext.Date.format(new Date(), 'Ymd'));
           Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaytext')[0].setText(is_today ? 'Today' : Ext.Date.format(currentPartDate, 'l M j, Y'));
           Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaytext')[0].setDisabled(is_today);
        }
      }
    });
    Ext.ComponentQuery.query('parthome')[0].add(picker);
    picker.show();
  },

  setupToday: function(but){
    var cardholder = Ext.ComponentQuery.query('partdayholder')[0];
    var formatteddate = Ext.Date.format(new Date(), 'Y-m-d');
    cardholder.setActiveItem({
      xtype: 'container',
      id: 'partdayouter-' + formatteddate,
      layout: { type: 'vbox', align: 'center'},
      items: [
        { height: 80, width: 700, xtype: 'partday', id: 'partday-' + formatteddate },
        { flex: 1, width: 700, xtype: 'partlist', autoLoadDate: formatteddate }
      ]
    });
  },

  previousDay: function(but){
    var cardholder = Ext.ComponentQuery.query('partdayholder')[0];
    currentPartDate = Ext.Date.add(currentPartDate, Ext.Date.DAY, -1);
    var formatteddate = Ext.Date.format(currentPartDate, 'Y-m-d');
    var found = cardholder.query('#partdayouter-' + formatteddate);
    if (found.length == 1){
      cardholder.animateActiveItem(found[0], {type: 'slide', direction: 'right', duration: 200});
    } else {
      cardholder.animateActiveItem({
        xtype: 'container',
        id: 'partdayouter-' + formatteddate,
        layout: { type: 'vbox', align: 'center'},
        items: [
          { height: 80, width: 700, xtype: 'partday', id: 'partday-' + formatteddate },
          { flex: 1, width: 700, xtype: 'partlist', autoLoadDate: formatteddate }
        ]
      }, {type: 'slide', direction: 'right', duration: 200});
    }
    Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaytext')[0].setText(Ext.Date.format(currentPartDate, 'l M j, Y'));
    Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaynext')[0].setDisabled(false);
  },

  nextDay: function(but){
    var cardholder = Ext.ComponentQuery.query('partdayholder')[0];
    currentPartDate = Ext.Date.add(currentPartDate, Ext.Date.DAY, 1);
    var formatteddate = Ext.Date.format(currentPartDate, 'Y-m-d');
    var found = cardholder.query('#partdayouter-' + formatteddate);
    if (found.length == 1){
      cardholder.animateActiveItem(found[0], {type: 'slide', direction: 'left', duration: 200});
    } else {
      cardholder.animateActiveItem({
        xtype: 'container',
        id: 'partdayouter-' + formatteddate,
        layout: { type: 'vbox', align: 'center'},
        items: [
          { height: 80, width: 700, xtype: 'partday', id: 'partday-' + formatteddate },
          { flex: 1, width: 700, xtype: 'partlist', autoLoadDate: formatteddate }
        ]
      },{type: 'slide', direction: 'left', duration: 200});
    }
    var is_today = (Ext.Date.format(currentPartDate, 'Ymd') == Ext.Date.format(new Date(), 'Ymd'));
    Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaytext')[0].setText(is_today ? 'Today' : Ext.Date.format(currentPartDate, 'l M j, Y'));
    Ext.ComponentQuery.query('partdayarea segmentedbutton #partdaynext')[0].setDisabled(is_today);
  },

  doFilter: function(ref){
    var store = Ext.ComponentQuery.query('partsearch dataview')[0].getStore();
    var namefield = ref.parent.query('#partname')[0];
    var skufield = ref.parent.query('#partsku')[0];
    var catfield = ref.parent.query('#partcat')[0];
    store.clearFilter(true);
    if (namefield.getValue()){ store.filter('name', namefield.getValue()); }
    if (skufield.getValue()){ store.filter('sku', skufield.getValue()); }
    if (catfield.getValue()){ store.filter('cat', catfield.getValue()); }
    store.load();
  }

});
