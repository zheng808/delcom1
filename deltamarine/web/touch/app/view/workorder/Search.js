Ext.define('Delta.view.workorder.Search', {
  extend: 'Ext.Panel',
  xtype: 'workordersearch',
  
  targetField: null,

  config: {
    layout: { type: 'fit' },
    items: [{
      xtype: 'toolbar',
      ui: 'light',
      docked: 'bottom',
      layout: { type: 'vbox' },
      items: [
        {
          xtype: 'container',
          height: 55,
          layout: { type: 'hbox', pack: 'left', align: 'center' },
          items: [{ 
            xtype: 'container', html: '<span style="color: #fff;">Category:</span>', margin: '0 5 0 0'
          },{ 
            xtype: 'selectfield',
            usePicker: false,
            store: 'Category',
            displayField: 'name',
            valueField: 'id',
            width: 180
          },{
            xtype: 'container', html: '<span style="color: #fff;">Shop:</span>', margin: '0 5 0 40'
          },{
            xtype: 'segmentedbutton',
            allowMultiple: true,
            searchField: 'rigging',
            defaults: { width: 100 },
            items: [
              { text: 'Marine<br />Services', searchRegex: /0/, pressed: true, style: 'line-height: 0.6em; font-size: 0.9em;' },
              { text: 'Rigging<br /> Welding', searchRegex: /1/, pressed: true, style: 'line-height: 0.6em; font-size: 0.9em;' }
            ]
          }]
        },{
          xtype: 'container',
          height: 55,
          layout: { type: 'hbox', pack: 'left', align: 'center' },
          items: [{ 
            xtype: 'container', styleHtmlContent: true, html: '<span style="color: #fff;">Customer:</span>'
          },{ 
            xtype: 'segmentedbutton',
            allowDepress: true,
            allowToggle: true,
            searchField: 'lastnamefirst',
            defaults: {},
            items: [
              { text: 'A-E', searchRegex: /^[abcde]/i   },
              { text: 'F-K', searchRegex: /^[fghijk]/i  },
              { text: 'L-O', searchRegex: /^[lmno]/i    },
              { text: 'P-S', searchRegex: /^[pqrs]/i    },
              { text: 'T-Z', searchRegex: /^[tuvwxyz]/i }
            ]
          },{
            xtype: 'container', styleHtmlContent: true, html: '<span style="color: #fff;">Boat:</span>', margin: '0 0 0 10'
          },{
            xtype: 'segmentedbutton',
            allowDepress: true,
            searchField: 'boat_name',
            allowToggle: true,
            defaults: {},
            items: [
              { text: 'A-E', searchRegex: /^[abcde]/i   },
              { text: 'F-J', searchRegex: /^[fghijk]/i  },
              { text: 'K-O', searchRegex: /^[lmno]/i    },
              { text: 'P-S', searchRegex: /^[pqrs]/i    },
              { text: 'T-Z', searchRegex: /^[tuvwxyz]/i }
            ]
          }]
        }
      ]
    },{
      itemId: 'scrollmsg',
      xtype: 'container',
      docked: 'bottom',
      height: 25,
      padding: 5,
      style: 'background-color: #000; font-size: 0.8em; text-align: center; color: #aaa;',
      html: 'Swipe list up to view more...'
    },{
      xtype: 'dataview',
      emptyText: 'No workorders found. Check the filter settings and try again.',
      useComponents: true,
      defaultType: 'workorderitem',
      padding: '20 0 20 0',
      store: 'Workorder',
      style: 'text-align: center'
    }],
    
    listeners: {
      'initialize': function(me){
        var mydataview = me.getInnerItems()[0];
        mydataview.getStore().load();
      }
    }
  }
});

Ext.define('Delta.view.workorder.Item', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'workorderitem',

  config: {
    margin: '6 12 6 12',
    width: 400,
    style: 'display: inline-block; font-size: 0.8em;',

    detailRow: true,

    dataMap: {
      getDetailRow: {
        setDetails: 'id'
      }
    }
  },

  applyDetailRow: function(config) {
    return Ext.factory(config, 'Delta.view.workorder.Renderer', this.getDetailRow());
  },

  updateDetailRow: function(newDetailRow, oldDetailRow) {
    if (oldDetailRow) { this.remove(oldDetailRow); }
    if (newDetailRow) { this.add(newDetailRow);    }
  }

});

Ext.define('Delta.view.workorder.Renderer', {
  extend: 'Ext.SegmentedButton',
  config: {
    allowToggle: false,
    margin: '0 0 4 0',
    defaults: { ui: 'normal', padding: 3, style: 'text-align: left', height: 55 },
    items: [
      { width: 80, html: '...', padding: 5, style: 'font-weight: bold; line-height: 1.2em;' },
      { width: 240, html: '...', style: 'text-align: left' },
      { width: 80, html: '...'  }
    ]
  },

  setDetails: function(info){
    var me = this;
    var items = me.getInnerItems();
    var data = me.parent.getRecord().data;
    var oldval = Ext.ComponentQuery.query('workorderfield').length ? parseInt(Ext.ComponentQuery.query('workorderfield')[0].getValue()) : 0;

    var infotext = '<div style="line-height: 1.2em; font-size: 1em; color: '+ (oldval == data.id ? '#000' : '#696')+'; font-style: italic;">' + data.lastnamefirst + '</div>';
    infotext = infotext + '<div>' + data.boat_name + (data.boat_type ? ' - ' + data.boat_type : '') + '</div>';
    if (data.category_id || data.rigging){
      infotext = infotext + '<div style="line-height: 1em; font-size: 0.8em; color: #666; font-style: italic;">';
      if (data.category_id){ infotext = infotext + data.category_name + ' '; }
      if (data.rigging) { infotext = infotext + '<span style="color: #aa8888;">(Delta Rigging)</span>'; }
      infotext = infotext + '</div>';
    }

    items[0].setText('#' + data.id); 
    items[1].setHtml(infotext);
    items[2].setText(data.completion + '<br /><span style="color: #666; font-size: 0.7em; font-style: italic;">tasks done</span>');
    if (oldval == data.id){
      items[0].setUi('confirm');
      items[1].setUi('confirm');
      items[2].setUi('confirm');
    }

    var selecthandler = function(but){

      //set the value and clear the popup
      var tofield = but.up('workordersearch').config.targetField;
      tofield.setValueFromRecord(but.getParent().getParent().getRecord());
      var comp = but.up('workordersearch').up('panel').hide();
      comp.destroy();

      //clear the task and open up the task window
      var taskfield = Ext.ComponentQuery.query('taskfield')[0];
      taskfield.reset();
      taskfield.open({ workorderHighlight: tofield });

      return false; //prevent double-firing
    }
    items[0].on('tap', selecthandler);
    items[1].on('tap', selecthandler);
    items[2].on('tap', selecthandler);
  }

});

