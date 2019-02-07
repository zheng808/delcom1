Ext.define('Delta.controller.Home', {
  extend: 'Ext.app.Controller',

  config: {
    refs: {
      addTimelogButton: '#mainaddtimelog',
      addPartButton: '#mainaddpart'
    },
    control: {
      addTimelogButton: { tap: 'addTimelog' },
      addPartButton: { tap: 'addPart' }
    }
  },
  
  addTimelog: function(but){
    //load up date from timelogs view if open
    var defaultDate = null;
    var defaultWo = null;
    var defaultWoi = null;
    mainnav = Ext.ComponentQuery.query('#mainnav')[0];
    items = mainnav.innerItems;
    if (items.length > 1){
      if (items[1].xtype == 'timeloghome'){
        defaultDate = currentTimelogDate;
      } else if (items[1].xtype == 'partshome'){
        defaultDate = currentPartDate;
      } else if (items[1].xtype == 'workordershome'){
        //TODO set default workorder here
        if (items.length > 2 && items[2].xtype == 'taskdetails'){
          //TODO set default workorder item here
        }
      }
    }
    if (items[items.length - 1].xtype == 'timelogadd' || items[items.length - 1].xtype == 'partadd')
    { 
      mainnav.pop();
    }
    mainnav.push(Ext.create('Delta.view.timelog.Add', {
        defaultdate: defaultDate,
        defaultWo: defaultWo,
        defaultWoi: defaultWoi
      })
    );
    
  },

  addPart: function(but){
    var defaultDate = null;
    var defaultWo = null;
    var defaultWoi = null;
    var defaultPart = null;
    mainnav = Ext.ComponentQuery.query('#mainnav')[0];
    items = mainnav.innerItems;
    if (items.length > 1){
      if (items[1].xtype == 'timeloghome'){
        defaultDate = currentTimelogDate;
      } else if (items[1].xtype == 'partshome'){
        defaultDate = currentPartDate;
      } else if (items[1].xtype == 'workordershome'){
        //TODO set default workorder here
        if (items.length > 2 && items[2].xtype == 'taskdetails'){
          //TODO set default workorder item here
          //TODO check for part details page
        }
      }
    }
    if (items[items.length - 1].xtype == 'timelogadd' || items[items.length - 1].xtype == 'partadd')
    { 
      mainnav.pop();
    }
    mainnav.push(Ext.create('Delta.view.part.Add', {
        defaultdate: defaultDate,
        defaultWo: defaultWo,
        defaultWoi: defaultWoi
      })
    );
  }

});
