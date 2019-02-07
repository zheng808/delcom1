Ext.define('Delta.view.timelog.Weekchart', {
  extend: 'Ext.Panel',
  xtype: 'timelogweekchart',
  config: {
  
    date: null,

    updateChart: function(){
    },
 
    items: [], //filled in by chart once drawn

    listeners: {
      'initialize': function(me){  }
    }
  }

});
