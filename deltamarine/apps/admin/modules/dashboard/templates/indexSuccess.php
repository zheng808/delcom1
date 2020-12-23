<?php
  if ( count($dashboard_notes) < 1 )
  {
    $notes = '<p>There are no any notifications.</p>';
  }
  else 
  {
    $notes = '<ul class="notifications">';
    foreach ($dashboard_notes as $dashboard_note )
    {
      $notes .= '<li>'.link_to($dashboard_note['text'], $dashboard_note['link']).'</li>';
    }
    $notes .= '</ul>';
  }
?>

<h1 class="headicon headicon-home">Dashboard</h1>
<div class="pagebox">
  <div id="leftside"></div>
  <div id="right-panel">
  <div id="dashboard"></div>
  <div id="dashboardNextRow"></div>
  <div id="dashboardThird"></div>
  </div>
</div>

<script type="text/javascript">

var haulout_store = new Ext.data.JsonStore({
  fields: ['id','customer','boat','boattype','date', 'status','haulout','haulin','color','for_rigging','category_name','progress'],
  sorters: [{ property: 'haulout', direction: 'ASC' }],
  remoteSort: true,
  pageSize: 100,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/haulouts'); ?>',
    extraParams: { haulout: 1 },
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var haulin_store = new Ext.data.JsonStore({
  fields: ['id','customer','boat','boattype','date', 'status','haulout','haulin','color','for_rigging','category_name','progress'],
  sorters: [{ property: 'haulout', direction: 'ASC' }],
  remoteSort: true,
  autoLoad: true,
  pageSize: 100,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/haulins'); ?>',
    extraParams: { haulin: 1 },
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var expire_store = new Ext.data.JsonStore({
  fields: ['id','customer','boat','boattype','date', 'status','haulout','haulin','color','for_rigging','category_name','progress', 'expire'],
  sorters: [{ property: 'expire', direction: 'ASC' }],
  remoteSort: true,
  autoLoad: true,
  pageSize: 100,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/expire'); ?>',
    extraParams: { haulin: 1 },
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var pickup_store = new Ext.data.JsonStore({
  fields: ['id','customer','boat','boattype','date', 'status','haulout','haulin','color','for_rigging','category_name','progress', 'pickup'],
  sorters: [{ property: 'pickup', direction: 'ASC' }],
  remoteSort: true,
  autoLoad: true,
  pageSize: 100,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/pickup'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var delivery_store = new Ext.data.JsonStore({
  fields: ['id','customer','boat','boattype','date', 'status','haulout','haulin','color','for_rigging','category_name','progress', 'delivery'],
  sorters: [{ property: 'delivery', direction: 'ASC' }],
  remoteSort: true,
  autoLoad: true,
  pageSize: 100,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/delivery'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var endoftoday = new Date();
endoftoday.setHours(24,0,0,0);

var haulout_datagrid = new Ext.grid.GridPanel({
  width: 651,
  minHeight: 300,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No Scheduled Haul-Outs',
  viewConfig: { stripeRows: true, loadMask: true },
  store: haulout_store,
  columns: [{
    header: 'Date/Time',
    dataIndex: 'haulout',
    sortable: true,
    width: 130,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if(Date.parse(record.get('haulout')) < endoftoday){
        return '<span style="color: red;">' + value + '</span>';
      } else {
        return value;
      }
    }
  },{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45,
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: false,
    flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    flex: 1
  },{
    header: "Customer",
    dataIndex: 'customer',
    sortable: false,
    flex: 1
  }],

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php 
        //load the initial data
        $inst = sfContext::getInstance();;
        $inst->getRequest()->setParameter('sort', 'haulout');
        $inst->getRequest()->setParameter('dir', 'ASC');
        $inst->getController()->getPresentationFor('work_order','haulouts');
     ?>);
    }
  }
});

var haulin_datagrid = new Ext.grid.GridPanel({
  width: 651,
  minHeight: 300,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No Scheduled Relaunches',
  viewConfig: { stripeRows: true, loadMask: true },
  store: haulin_store,
  columns: [{
    header: 'Date/Time',
    dataIndex: 'haulin',
    sortable: true,
    width: 135,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if(Date.parse(record.get('haulin')) < endoftoday){
        return '<span style="color: red;">' + value + '</span>';
      } else {
        return value;
      }
    }    
  },{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45,
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: false,
    flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    flex: 1
  },{
    header: "Customer",
    dataIndex: 'customer',
    sortable: false,
    flex: 1
  }],

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  })

});

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}
var sixetyDays = new Date()
sixetyDays = sixetyDays.addDays(60);
//expire data grid
var expire_datagrid = new Ext.grid.GridPanel({
  width: 651,
  minHeight: 300,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No Expire Date',
  viewConfig: { stripeRows: true, loadMask: true },
  store: expire_store,
  columns: [{
    header: 'Date/Time',
    dataIndex: 'expire',
    sortable: true,
    width: 135,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      if(Date.parse(record.get('expire')) < sixetyDays){
        return '<span style="color: red;">' + value + '</span>';
      } else {
        return value;
      }
    }    
  },{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45,
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: false,
    flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    flex: 1
  },{
    header: "Customer",
    dataIndex: 'customer',
    sortable: false,
    flex: 1
  }],

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  })

});

var pickup_datagrid = new Ext.grid.GridPanel({
  width: 651,
  minHeight: 300,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No PickUp Date',
  viewConfig: { stripeRows: true, loadMask: true },
  store: pickup_store,
  columns: [{
    header: 'Date/Time',
    dataIndex: 'pickup',
    sortable: true,
    width: 135,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      console.log(value);
      if(Date.parse(record.get('pickup')) < sixetyDays){
        return '<span style="color: red;">' + value + '</span>';
      } else {
        return value;
      }
    }    
  },{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45,
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: false,
    flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    flex: 1
  },{
    header: "Customer",
    dataIndex: 'customer',
    sortable: false,
    flex: 1
  }],

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  })

});

var delivery_datagrid = new Ext.grid.GridPanel({
  width: 651,
  minHeight: 300,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No Delivery Date',
  viewConfig: { stripeRows: true, loadMask: true },
  store: delivery_store,
  columns: [{
    header: 'Date/Time',
    dataIndex: 'delivery',
    sortable: true,
    width: 135,
    renderer: function (value, metaData, record, rowIndex, colIndex, store) {
      console.log(value);
      if(Date.parse(record.get('delivery')) < sixetyDays){
        return '<span style="color: red;">' + value + '</span>';
      } else {
        return value;
      }
    }    
  },{
    header: "ID",
    dataIndex: 'id',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 45,
  },{
    header: "Boat Name",
    dataIndex: 'boat',
    sortable: false,
    flex: 1
  },{
    header: "Boat Type",
    dataIndex: 'boattype',
    sortable: false,
    flex: 1
  },{
    header: "Customer",
    dataIndex: 'customer',
    sortable: false,
    flex: 1
  }],

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Work Order Details..."});
        myMask.show();
        location.href= '<?php echo url_for('work_order/view?id='); ?>' + record.data.id ;
      }
    }
  })

});

var notes_col = new Ext.Container({
  flex: 1,
  margin: '0 10 0 0',
  items: [{
    html: '<h1>Notifications</h1>',
    border: false
  },{
    html: '<?php echo $notes; ?>',
    border: false
  }]
});

var haulouts_col = new Ext.Container({
  flex: 1,
  margin: '0 10 0 0',
  items: [{
    html: '<h2>Scheduled Haul-Outs',
    border: false,
  },
    haulout_datagrid
  ]
});

var haulins_col = new Ext.Container({
flex: 1,
  items: [{
    html: '<h2>Scheduled Relaunches',
    border: false,
  },
    haulin_datagrid
  ]
});

var expire_col = new Ext.Container({
margin: '0 10 0 0',
flex: 1,
  items: [{
    html: '<h2>E29 Expire Date',
    border: false,
  },
    expire_datagrid
  ]
});

var pick_col = new Ext.Container({
flex: 1,
  items: [{
    html: '<h2>Pick Up Date',
    border: false,
  },
    pickup_datagrid
  ]
});

var delivery_col = new Ext.Container({
flex: 1,
  items: [{
    html: '<h2>Delivery Date',
    border: false,
  },
    delivery_datagrid
  ]
});

var notes = new Ext.Container({
  border: false,
  layout: 'vbox',
  items: [notes_col]
});

var dashboard = new Ext.Container({
  border: false,
  layout: 'hbox',
  items: [haulouts_col, haulins_col]
});

var dashboard2 = new Ext.Container({
  border: false,
  layout: 'hbox',
  items: [expire_col, pick_col]
});

var dashboard3 = new Ext.Container({
  border: false,
  layout: 'hbox',
  items: [delivery_col]
});

Ext.onReady(function(){
  dashboard.render('dashboard');
  dashboard2.render('dashboardNextRow');
  notes.render('leftside');
  dashboard3.render('dashboardThird');
});

</script>
