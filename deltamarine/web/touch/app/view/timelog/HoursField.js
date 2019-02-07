Ext.define('Delta.view.timelog.HoursField', {
  extend: 'Ext.field.Field',
  xtype: 'hoursfield',

  config: {
    isField: true,
    label: 'Hours + Minutes',
    name: 'payroll_hours',

    component: {
      xtype: 'panel', 
      padding: 8,
      items: [{
        xtype: 'segmentedbutton',
        margin: '0 0 10 0',
        defaults: { height: 40, width: 42, padding: 2 },
        items: [
          { text: '0h'}, { text: '1h' }, { text: '2h' }, { text: '3h' }, { text: '4h' },
          { text: '5h' }, { text: '6h'}, { text: '7h' }, { text: '8h' }
        ]
      },{
        xtype: 'segmentedbutton',
        ui: 'action',
        defaults: { height: 40, width: 94, padding: 2 },
        items: [
          { text: '00m' }, { text: '15m' }, { text: '30m' }, { text: '45m' }
        ]
      }]
    }
  },


  initialize: function(){
    var me = this;
    me.callParent();

    me.getComponent().getInnerItems()[0].on({
      scope: this,
      toggle : 'onToggleHour'
    });

    me.getComponent().getInnerItems()[1].on({
      scope: this,
      toggle: 'onToggleMinute'
    });
  },

  onToggleHour: function (seg,but,val){
    but.setUi( val ? 'confirm' : 'normal');
    //auto-fill in minutes when clicked
    if (this.getComponent().getInnerItems()[1].getPressedButtons().length == 0){
      this.getComponent().getInnerItems()[1].setPressedButtons(0);
    }
  },

  onToggleMinute: function (seg,but,val){
    but.setUi( val ? 'confirm' : 'normal');
    //auto fill in hours when clicked
    if (this.getComponent().getInnerItems()[0].getPressedButtons().length == 0){
      this.getComponent().getInnerItems()[0].setPressedButtons(0);
    }
  },

  setValue: function(newValue){
    var hoursidx = Math.floor(newValue);
    var minsidx  = Math.round(4 * (newValue - hoursidx));
    this.getComponent().getInnerItems()[0].setPressedButtons(hoursidx);
    this.getComponent().getInnerItems()[1].setPressedButtons(minsidx);
  },

  getValue: function(){
    var hrssel = this.getComponent().getInnerItems()[0].getPressedButtons();
    var minsel = this.getComponent().getInnerItems()[1].getPressedButtons();
    var hrs = (hrssel.length ? parseInt(hrssel[0].getText().substr(0,1)) : 0);
    var mins = (minsel.length ? parseInt(minsel[0].getText().substr(0,2)) : 0);
    var result = Math.round((hrs + (mins/60))*100) / 100;

    return result;
  }

});
