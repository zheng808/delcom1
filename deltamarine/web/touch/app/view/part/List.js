Ext.define('Delta.view.part.List', {
  extend: 'Ext.Panel',
  xtype: 'partlist',

  config: {
    layout: 'fit',
    items: [{
      xtype: 'dataview',
      emptyText: 'No Parts found for this day',
      scrollable: { outOfBoundRestrictFactor: 0 },
      useComponents: true,
      defaultType: 'partlistitem'
    }],
    listeners: {
      'initialize': function(me){
        var mydataview = me.getInnerItems()[0];
        var thisdate = me.config.autoLoadDate;
        var thisweek = me.config.autoLoadType;
        if (!thisdate){ thisdate = Ext.Date.format(new Date(), 'Y-m-d'); }
        if (!thisweek){ thisweek = '0'; }
        mydataview.thisdate = thisdate;
        mydataview.thisweek = thisweek;
        mydataview.setStore(new Ext.data.Store({
          autoLoad: true,
          storeId: 'part-day-' + thisdate,
          model: 'Delta.model.PartInstance',
          proxy: {
            type: 'ajax',
            url: '/touch.php/rest/partinstance',
            extraParams: { employee_id: current_employee.get('id'), ondate: thisdate, onweek: thisweek },
            reader: {
              rootProperty: 'instances'
            }
          },
          sorters: [
            { property: 'date_used', direction: 'DESC' }
          ]
        }));
      }
    }
  }
});

Ext.define('Delta.view.part.ListRenderer', {
  extend: 'Ext.Panel',
  config: {
    margin: '0 0 8 0',
    layout: { type: 'hbox' },
    defaults: {xtype: 'container' },
    items: [
      { flex: 3, html: '...', style: 'background: #f7f7f7; border: 1px solid #ddd; font-weight: bold; line-height: 1.2em;' },
      { flex: 1, html: '...', padding: 3, style: 'background: #f3f3f3; font-size: 1em; text-align: center; border: 1px solid #ddd; border-left: none;' },
      { flex: 3, html: '...', padding: 3, style: 'background: #f3f3f3; font-size: 1em; text-align: center; border: 1px solid #ddd; border-left: none;' },
      {
        width: 150,
        items: [{
          xtype: 'segmentedbutton',
          centered: true,
          allowToggle: false,
          defaults: { height: 42},
          items: [
            { text: 'Edit'   },
            { text: 'Put Back' }
          ]
        }]
      }
    ]
  },

  setInfo: function(name){
    var me = this;
    var infos = me.parent.getRecord().data;
    var items = me.getInnerItems();

    data = '<div>' + infos.sku + ': '+ infos.part_name + '</div>';
    data = data + '<div style="font-size: 0.8em; font-weight: normal">' + (infos.category_hierarchy != '' ? infos.category_hierarchy + ' > ' : '') + infos.category_name + '</div>';
    items[0].setHtml(data);
    items[0].setPadding('4 4 4 10');

    data = '<div>' + infos.quantity + '</div>' + '<div style="font-size: 0.8em; font-style: italic; color: #666;">Qty.</div>';
    items[1].setHtml(data); 
    items[1].setPadding('4 4 4 10');

    data = '<div>#' + infos.workorder_id + ': ' + infos.customer_name + '</div>';
    data = data + '<div style="font-size: 0.8em; font-weight: normal">Task: ' + infos.task_name + '</div>';

    items[2].setHtml(data);
    items[2].setPadding('4 4 4 10');

    //EDIT BUTTON PRESSED
    items[3].query('button')[0].on('tap', function(but){
      var today = new Date();
      Ext.Date.clearTime(today);
      if (infos.date_used < Ext.Date.add(today, Ext.Date.DAY, -3)) {
        Ext.Msg.alert('Error', 'Cannot edit or put back parts older than 3 days here.');  
      } else {
        mainnav = Ext.ComponentQuery.query('#mainnav')[0];
        mainnav.push(Ext.create('Delta.view.part.Add', {
          title: 'Edit Part',
          existingRecord: me.parent.getRecord()
        }));
      }
      return false; //prevent from firing twice
    });

    //put back BUTTON PRESSED 
    items[3].query('button')[1].on('tap', function(but){
      var today = new Date();
      Ext.Date.clearTime(today);
      if (infos.date_used < Ext.Date.add(today, Ext.Date.DAY, -3)) {
        Ext.Msg.alert('Error', 'Cannot edit or put back parts older than 3 days here.');  
      } else {
        mainnav = Ext.ComponentQuery.query('#mainnav')[0];
        mainnav.push(Ext.create('Delta.view.part.PutBack', {
          title: 'Put Part Back',
          existingRecord: me.parent.getRecord()
        }));
      }
      return false; //prevent from firing twice
    });
  }
});

Ext.define('Delta.view.part.ListItem', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'partlistitem',

  config: {
    infoArea: true,

    dataMap: {
      getInfoArea: {
        setInfo: 'id'
      }
    }

  },

  applyInfoArea: function(config) {
    return Ext.factory(config, 'Delta.view.part.ListRenderer', this.getInfoArea());
  },

  updateInfoArea: function(newInfoArea, oldInfoArea) {
    if (oldInfoArea) { this.remove(oldInfoArea); }
    if (newInfoArea) { this.add(newInfoArea); }
  }

});
