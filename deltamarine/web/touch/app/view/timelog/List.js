Ext.define('Delta.view.timelog.List', {
  extend: 'Ext.Panel',
  xtype: 'timeloglist',

  config: {
    layout: 'fit',
    items: [{
      xtype: 'dataview',
      emptyText: 'No Timelogs found for this day',
      scrollable: { outOfBoundRestrictFactor: 0 },
      useComponents: true,
      defaultType: 'timeloglistitem',
      listeners: { 
        refresh: function(list){
          if (list.getStore().getProxy().getReader().rawData){
            var stats = list.getStore().getProxy().getReader().rawData.stats;
            total_billable = stats.billable.total;
            total_nonbill  = stats.nonbillable.total;
            var items = Ext.ComponentQuery.query('timelogdaychartholder')[0].getActiveItem().query('timelogdaychart field');
            items[0].getComponent().setHtml(stats.billable.total);
            items[1].getComponent().setHtml(stats.nonbillable.total);
            items[2].getComponent().setHtml(stats.billable.total + stats.nonbillable.total);
          }
        }
      }
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
          storeId: 'timelog-day-' + thisdate,
          model: 'Delta.model.Timelog',
          proxy: {
            type: 'ajax',
            url: '/touch.php/rest/timelog',
            extraParams: { employee_id: current_employee.get('id'), ondate: thisdate, onweek: thisweek },
            reader: {
              rootProperty: 'timelogs'
            }
          },
          sorters: [{ property: 'id', direction: 'DESC' }]
        }));
      }
    }
  }
});

Ext.define('Delta.view.timelog.ListRenderer', {
  extend: 'Ext.Panel',
  config: {
    margin: '0 0 8 0',
    layout: { type: 'hbox' },
    defaults: {xtype: 'container' },
    items: [
      { flex: 1, html: '...', style: 'background: #f7f7f7; border: 1px solid #ddd; font-weight: bold; line-height: 1.2em;' },
      { width: 80, html: '...', padding: 3, style: 'font-size: 1em; text-align: center; border: 1px solid #ddd; border-left: none;' },
      { 
        width: 80, 
        items: [{
          xtype: 'segmentedbutton',
          centered: true,
          allowToggle: false,
          defaults: { height: 42},
          items: [
            { text: 'Edit'   }
          ]
        }]
      }
    ]
  },

  setInfo: function(id){
    var me = this;
    var infos = me.parent.getRecord().data;
    var line1 = infos.line1;
    var items = me.getInnerItems();

    if (infos.billable){
      line1 = '<div>#' + infos.workorder_id + ': ' + infos.boat_type + (infos.boat_name ? ' - ' + infos.boat_name : '') + '</div>';
      line1 = line1 + '<div style="font-size: 0.8em; font-weight: normal">Task:' + infos.task_name + '<br />Type: ' + infos.labour_type_name;
    } else {
      line1 = '<div>' + infos.nonbill_type_name + '</div><div style="font-size: 0.8em; font-weight: normal">Non-Billable Time</div>';
    }

    items[0].setHtml(line1);
    items[0].setPadding('4 4 4 10');
    items[1].setHtml(infos.payroll_hourmins + '<br /><span style="font-size: 0.8em; color: #999; font-style: italic;">' + (infos.approved ? 'Approved' : 'Unapproved'));
    items[1].setStyle('background-color: ' + (infos.approved ? '#f0fff0' : '#fffff0'));

    //EDIT BUTTON PRESSED
    items[2].query('button')[0].on('tap', function(but){
      var today = new Date();
      Ext.Date.clearTime(today);
      if (infos.end_date < Ext.Date.add(today, Ext.Date.DAY, -3)) {
        Ext.Msg.alert('Error', 'Cannot edit or put back parts older than 3 days here.');  
      } else if (infos.approved) {
        Ext.Msg.alert('Error', 'This timelog has already been approved and cannot be edited by you any longer.');
      } else {
        mainnav = Ext.ComponentQuery.query('#mainnav')[0];
        mainnav.push(Ext.create('Delta.view.timelog.Add', {
          title: 'Edit Timelog',
          existingRecord: me.parent.getRecord()
        }));
        return false; //prevent from firing twice
      }
    });
   
  }

});

Ext.define('Delta.view.timelog.ListItem', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'timeloglistitem',

  config: {
    infoArea: true,

    dataMap: {
      getInfoArea: {
        setInfo: 'id'
      }
    }
  },

  applyInfoArea: function(config) {
    return Ext.factory(config, 'Delta.view.timelog.ListRenderer', this.getInfoArea());
  },

  updateInfoArea: function(newInfoArea, oldInfoArea) {
    if (oldInfoArea) { this.remove(oldInfoArea); }
    if (newInfoArea) { this.add(newInfoArea); }
  }

});
