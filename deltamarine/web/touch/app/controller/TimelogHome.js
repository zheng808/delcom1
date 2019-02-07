currentTimelogDate = new Date();

Ext.define('Delta.controller.TimelogHome', {
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
      prevDayButton: 'timelogdaychartarea segmentedbutton #timelogdaychartprev',
      nextDayButton: 'timelogdaychartarea segmentedbutton #timelogdaychartnext',
      calendarButton: 'timelogdaychartarea segmentedbutton #timelogdaycharttext',
      cardholder:    'timelogdaychartholder'
    },
    control: {
      prevDayButton: { tap: 'previousDay' },
      nextDayButton: { tap: 'nextDay' },
      calendarButton: { tap: 'pickDay' },
      cardholder:    { initialize: 'setupToday' }
    }
  },

  pickDay: function(){
    yearTo = parseInt(Ext.Date.format(new Date(), 'Y'));
    var picker = Ext.create('Ext.picker.Date', {
      value: currentTimelogDate,
      yearFrom: 2011,
      yearTo: yearTo,
      hideOnMaskTap: true,
      listeners: {
        'change': function(pick, val){
           var cardholder = Ext.ComponentQuery.query('timelogdaychartholder')[0];
           currentTimelogDate = val;
           var formatteddate = Ext.Date.format(val, 'Y-m-d');
           var found = cardholder.query('#timelogdayouter-' + formatteddate);
           if (found.length == 1){
             cardholder.animateActiveItem(found[0], { type: 'slide', direction: 'down', duration: 200 });
           } else {
             cardholder.animateActiveItem({
               xtype: 'container',
               id: 'timelogdayouter-' + formatteddate,
               layout: { type: 'vbox', align: 'center'},
               items: [
                 { height: 80, width: 700, xtype: 'timelogdaychart', id: 'timelogdaychart-' + formatteddate },
                 { flex: 1, width: 700, xtype: 'timeloglist', autoLoadDate: formatteddate }
               ]
             },{type: 'slide', direction: 'down', duration: 200});
           }
           var is_today = (Ext.Date.format(currentTimelogDate, 'Ymd') == Ext.Date.format(new Date(), 'Ymd'));
           Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaycharttext')[0].setText(is_today ? 'Today' : Ext.Date.format(currentTimelogDate, 'l M j, Y'));
           Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaycharttext')[0].setDisabled(is_today);
        }
      }
    });
    Ext.ComponentQuery.query('timeloghome')[0].add(picker);
    picker.show();
  },

  setupToday: function(but){
    var cardholder = Ext.ComponentQuery.query('timelogdaychartholder')[0];
    var formatteddate = Ext.Date.format(new Date(), 'Y-m-d');
    cardholder.setActiveItem({
      xtype: 'container',
      id: 'timelogdayouter-' + formatteddate,
      layout: { type: 'vbox', align: 'center'},
      items: [
        { height: 80, width: 700, xtype: 'timelogdaychart', id: 'timelogdaychart-' + formatteddate },
        { flex: 1, width: 700, xtype: 'timeloglist', autoLoadDate: formatteddate }
      ]
    });
  },

  previousDay: function(but){
    var cardholder = Ext.ComponentQuery.query('timelogdaychartholder')[0];
    currentTimelogDate = Ext.Date.add(currentTimelogDate, Ext.Date.DAY, -1);
    var formatteddate = Ext.Date.format(currentTimelogDate, 'Y-m-d');
    var found = cardholder.query('#timelogdayouter-' + formatteddate);
    if (found.length == 1){
      cardholder.animateActiveItem(found[0], {type: 'slide', direction: 'right', duration: 200});
    } else {
      cardholder.animateActiveItem({
        xtype: 'container',
        id: 'timelogdayouter-' + formatteddate,
        layout: { type: 'vbox', align: 'center'},
        items: [
          { height: 80, width: 700, xtype: 'timelogdaychart', id: 'timelogdaychart-' + formatteddate },
          { flex: 1, width: 700, xtype: 'timeloglist', autoLoadDate: formatteddate }
        ]
      }, { type: 'slide', direction: 'right', duration: 200});
    }
    Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaycharttext')[0].setText(Ext.Date.format(currentTimelogDate, 'l M j, Y'));
    Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaychartnext')[0].setDisabled(false);
  },

  nextDay: function(but){
    var cardholder = Ext.ComponentQuery.query('timelogdaychartholder')[0];
    currentTimelogDate = Ext.Date.add(currentTimelogDate, Ext.Date.DAY, 1);
    var formatteddate = Ext.Date.format(currentTimelogDate, 'Y-m-d');
    var found = cardholder.query('#timelogdayouter-' + formatteddate);
    if (found.length == 1){
      cardholder.animateActiveItem(found[0], { type: 'slide', direction: 'left', duration: 200 });
    } else {
      cardholder.animateActiveItem({
        xtype: 'container',
        id: 'timelogdayouter-' + formatteddate,
        layout: { type: 'vbox', align: 'center'},
        items: [
          { height: 80, width: 700, xtype: 'timelogdaychart', id: 'timelogdaychart-' + formatteddate },
          { flex: 1, width: 700, xtype: 'timeloglist', autoLoadDate: formatteddate }
        ]
      }, {type: 'slide', direction: 'left', duration: 200 });
    }
    var is_today = (Ext.Date.format(currentTimelogDate, 'Ymd') == Ext.Date.format(new Date(), 'Ymd'));
    Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaycharttext')[0].setText(is_today ? 'Today' : Ext.Date.format(currentTimelogDate, 'l M j, Y'));
    Ext.ComponentQuery.query('timelogdaychartarea segmentedbutton #timelogdaychartnext')[0].setDisabled(is_today);
  }

});
