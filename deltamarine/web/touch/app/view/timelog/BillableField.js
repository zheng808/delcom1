Ext.define('Delta.view.timelog.BillableField', {
  extend: 'Ext.field.Field',
  xtype: 'billablefield',

  config: {
    isField: true,
    label: 'Timelog Type',
    name: 'billable',

    component: {
      xtype: 'segmentedbutton',
      align: 'center',
      padding: 8,
      defaults: { height: 25, width: 150 },
      items: [
        { text: 'Billable' },
        { text: 'Nonbillable' }
      ]
    }
  },

  initialize: function(){
    var me = this;
    me.callParent();

    me.getComponent().on({
        scope: this,
        toggle : 'onToggle'
    });
  },

  onToggle: function(seg,but,val){
    if (val) {
      but.setUi('confirm');
      var fields = this.up('fieldset');
      var bill = (but.getText() == 'Billable');
      fields.query('#workorder')[0].setHidden(!bill);
      fields.query('#task')[0].setHidden(!bill);
      fields.query('#labourtype')[0].setHidden(!bill);
      fields.query('#nonbilltype')[0].setHidden(bill);
    } else {
      but.setUi('normal');
    }
  },

  setValue: function(newValue){
    this.getComponent().setPressedButtons( newValue == 1 ? 0 : 1 );
  },

  getValue: function(){
    return (this.getComponent().getPressedButtons()[0].getText() == 'Billable' ? 1 : 0);
  }

});
