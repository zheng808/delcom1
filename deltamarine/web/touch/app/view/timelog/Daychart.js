Ext.define('Delta.view.timelog.Daychart', {
  extend: 'Ext.Panel',
  xtype: 'timelogdaychart',
  config: {
    xtype: 'container',
    width: 300,
    items: [
    { 
      xtype: 'container',
      centered: true,
      layout: { type: 'hbox' },
      defaults: { xtype: 'field', width: 200, labelWidth: 130, margin: 5},
      items: [
        { 
          label: 'Billable Hours', 
          style: 'border: 1px solid #ddd;',
          component: { xtype: 'panel', html: '-', baseCls: 'x-form-label', style: 'background-color: #fff; text-align: center'} 
        },{ 
          xtype: 'panel', width: 12, padding: '12 0 0 0', html: ' + ', style: 'text-align: center' 
        },{ 
          label: 'Non-Billable Hours', 
          style: 'border: 1px solid #ddd;',
          component: { xtype: 'panel', html: '-', baseCls: 'x-form-label', style: 'background-color: #fff; text-align: center'} 
        },{ 
          xtype: 'panel', width: 12, padding: '12 0 0 0', html: ' = ', style: 'text-align: center'
       },{ 
          label: 'Total Hours', 
          style: 'border: 2px solid #ddd;',
          component: { xtype: 'panel', html: '-', baseCls: 'x-form-label', style: 'background-color: #fff; text-align: center; font-weight: bold;'}
        }
      ]
    }
/*
      xtype: 'chart',
      width: 500,
      height: 100,
      flipXY: true,
      store: {
        fields: ['end_date','billable','nonbillable'],
        proxy: {
          type: 'ajax',
          url: '/touch.php/rest/timelogStat',
          extraParams: {employee_id: current_employee.get('id'), onweek: 0, ondate: this.date },
          reader: { rootProperty: 'daystats' }
        }
      },
      axes: [{
        type: 'numeric',
        position: 'left',
        grid: true
      },{
        type: 'category',
        position: 'left'
      }],
      series: [{
        type: 'bar',
        xField: 'date',
        yField: ['billable', 'nonbillable'],
        axis: 'bottom',
        showInLegend: true,
        style: {
          maxBarWidth: 30
        }
      }]
    }
*/
    ],

    listeners: {
      'initialize': function(me){  
        if (me.date == null){
          me.date = Ext.Date.format(new Date(), 'Y-m-d');
        }
      }
    }
  }
});
