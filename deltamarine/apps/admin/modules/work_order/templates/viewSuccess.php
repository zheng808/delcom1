<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Workorders List', 'work_order/index',
      array('class' => 'button tabbutton'));
  ?>
  <div id="treetips" style="margin: 230px 0 0 10px;">
    <p style="font-size: 0.8em">Tips:</p>
    <ul style="font-size: 0.8em;">
      <li>Hold Ctrl while clicking the plus or minus box beside a task to quickly expand or collapse all items beneath it.</li>
      <li>The collapsed/expanded state of this view will be saved next time you come back to view this workorder.</li>
    </ul>
  </div>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-person">Work Order #<?php echo $workorder->getId(); ?> for <?php echo $workorder->getCustomer()->getName(); ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <table class="infotable">
      <tr>
        <td class="label">Customer Name:</td>
        <td><?php echo link_to($workorder->getCustomer()->getName(), 'customer/view?id='.$workorder->getCustomerId()); ?></td>
        <td class="label">Category:</td>
        <td><?php echo ($workorder->getWorkorderCategoryId() ? $workorder->getWorkorderCategory()->getName() : 'Uncategorized'); ?></td>        
      </tr>
      <tr>
        <td class="label">Company:</td>
        <td><?php echo ($workorder->getForRigging() ? 'Delta Rigging and Welding' : 'Delta Marine Services'); ?></td>
        <td class="label">Boat Name:</td>
        <td><?php echo link_to($workorder->getCustomerBoat()->getName(), 'customer/boat?id='.$workorder->getCustomerBoatId()); ?></td>        
      </tr>
      <tr>
        <td class="label">Work Order Status:</td>
        <td>
            <div style="float:left; width: 15px; height: 15px; margin-right: 8px; border: 1px solid #000000; background-color: #<?php echo $workorder->getSummaryColor(); ?>;">&nbsp;</div>
            <?php echo $workorder->getStatus(); ?>
        </td>
        <td class="label">Boat Make/Model:</td>
        <td><?php echo $workorder->getCustomerBoat()->getMakeModel(); ?></td>
      </tr>
      <?php 
        $created =    '<td class="label">Created On:</td><td>'.$workorder->getCreatedOn('M j, Y').'</td>';
        $started =    '<td class="label">Started On:</td><td>'.$workorder->getStartedOn('M j, Y').'</td>';
        $completed =  '<td class="label">Completed On:</td><td>'.$workorder->getCompletedOn('M j, Y').'</td>';
        $haulout =    '<td class="label">Haulout Date:</td><td>'.$workorder->getHauloutDateTime('M j, Y').'</td>';
        $haulin  =    '<td class="label">Relaunch Date:</td><td>'.$workorder->getHaulinDateTime('M j, Y').'</td>';
        $status =     '<td class="label">Tasks Complete:</td><td>'.implode('/',$workorder->getItemsProgress()).'</td>';
        $blank = '<td class="label"></td><td class="blank"></td>';

        if ($workorder->getPstExempt() || $workorder->getGstExempt())
        {
          echo '<tr>';
          echo '<td class="label">PST Exempt:</td><td>'.($workorder->getPstExempt() ? '<strong>YES</strong>' : 'No').'</td>';
          echo '<td class="label">GST Exempt:</td><td>'.($workorder->getGstExempt() ? '<strong>YES</strong>' : 'No').'</td>';
          echo '</tr>';
        }

        if ($workorder->isEstimate())
        {
          echo '<tr>'.$created.$haulout.'</tr>';
        }
        else if ($workorder->isInProgress()) 
        { 
         echo '<tr>'.$created.$started.'</tr>';
         if ($workorder->getHauloutDate())
         {
            echo '<tr>'.$haulout.$haulin.'</tr>';
            echo '<tr>'.$status.$blank.'</tr>';
         }
         else
         {
           echo '<tr>'.$status.$blank.'</tr>';
         }
        }
        else 
        {
          echo '<tr>'.$created.$started.'</tr><tr>'.$completed.$blank.'</tr>';
          if ($workorder->getHauloutDate()) echo '<tr>'.$haulout.$haulin.'</tr>';
          echo '<tr>'.$status.$blank.'</tr>';
        }
      ?>
    </table>

    <div id="workorder_tabs"></div>

  </div>
</div>

<script type="text/javascript">

var this_workorder_id = <?php echo $workorder->getId(); ?>;
var this_workorder_root = <?php echo $workorder->getRootItem()->getId(); ?>;
var partInst = 0;

var color_code_array = [
      <?php $colors = WorkorderPeer::getColorCodesArray(); ?>
      <?php $first = current($colors); ?>
      <?php foreach ($colors AS $colorcode => $colorname): ?><?php if ($first != $colorname) echo ','; ?>
        { value: '<?php echo $colorcode; ?>', text: '<span style="display: inline-block; height: 15px; width: 16px; margin-left: 1px; border: 1px solid #333; background-color: #<?php echo $colorcode; ?>;' }
      <?php endforeach; ?>
];
 
var tree_root_node = { 
  text: '-- Top Level Item--',
  id: '<?php echo $workorder->getRootItem()->getId(); ?>',
  expanded: true
};

var boattypesTpl = new Ext.XTemplate(
  '<tpl for="."><div class="x-boundlist-item">{make}',
    '<tpl if="model == \'\'"> <span style="font-size: 10px; color: #999;"> (all models)</span></tpl>',
    '<tpl if="model != \'\'"> {model}</tpl>',
  '</div></tpl>'
);

var boatTpl = new Ext.XTemplate(
  '<tpl for="."><div class="x-boundlist-item">{name}',
    '<tpl if="make != \'\'"> <span style="font-size: 10px; color: #999;">({make}',
      '<tpl if="model != \'\'"> {model}</tpl>',
    ')</span></tpl>',
  '</div></tpl>'
);

function reload_tree(){
  workorder_tree.saveState();
  tree_root_node.expanded = false;
  node = workorder_tree.setRootNode(tree_root_node);
  node.expand(false, function(){ workorder_tree.applyState(); });

  tree_root_node.expanded = true;
  foldersStore.setRootNode(tree_root_node);
}

var workordersStore = new Ext.data.JsonStore({
  fields: ['id', 'customer', 'boat', 'boattype', 'date', 'status','haulout','haulin','color','for_rigging','category_name', 'progress', 'pst_exempt', 'gst_exempt','tax_exempt','text'],
  //sorters: [{ property: 'id', direction: 'DESC' }],
  remoteSort: true,
  pageSize: 1000,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/datagrid'); ?>',
    extraParams: { status: 'In Progress', sort: 'id', dir: 'DESC' },
    //simpleSortMode: true,
    reader: { 
      root: 'workorders',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var workorderItemsStore = new Ext.data.JsonStore({
  fields: ['id','workorder_id','label','text'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('/work_order/workorderItems'); ?>',
    reader: {
      root: 'items',
      idProperty: 'id'
    }
  }
});

var itemsStore = new Ext.data.TreeStore({
  fields: ['id', 'text','estimate','actual','info','custom'],
  proxy: {
    type: 'ajax',
    url: '/work_order/detailstree'
  },
  root: <?php echo json_encode($workorder->baseDetailsTree()); ?>
});

foldersStore = new Ext.data.TreeStore({
  root: tree_root_node,
  proxy: {
    type: 'ajax',
    url: '/work_order/folderstree'
  }
});

var customerStore = new Ext.data.JsonStore({
  fields: ['id','name','country'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/datagrid'); ?>',
    extraParams: {firstlast: '0', withcountry: '1'},
    reader: {
      root: 'customers'
    }
  }
});

var categoriesStore = new Ext.data.TreeStore({
  root: {
    text: 'All Categories',
    expanded: true
  },
  proxy: {
    type: 'ajax',
    url: '/part/categoriestree',
    reader: {
      root: 'categories'
    }
  }
});

wocatsStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  autoLoad: true,
  pageSize: 1000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '/work_order/categoryDatagrid?uncat=1',
    simpleSortMode: true,
    reader: { 
      root: 'categories',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var timelogsStore = new Ext.data.JsonStore({
  fields: ['id', 'employee_id', 'employee', 'date', 'billable', 'type', 
           'rate', 'cost', 'payroll_hours', 'billable_hours', 'start_time', 'end_time', 
           'workorder', 'item', 'boat', 'status', 
           'employee_notes', 'admin_notes'],
  remoteSort: true,
  pageSize: 100,
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '/timelogs/datagrid?workorder_id=' + this_workorder_id,
    simpleSortMode: true,
    reader: { 
      root: 'timelogs',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var partslistStore = new Ext.data.JsonStore({
  fields: ['id', 'date', 'description', 'quantity', 'status', 'task', 'name', 'part_id', 'sku', 'base_status', 'custom'],
  remoteSort: true,
  pageSize: 100,
  sorters: [{ property: 'id', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '/part/partinstanceDatagrid?workorder_id=' + this_workorder_id,
    simpleSortMode: true,
    reader: {
      root: 'instances',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var billingStore = new Ext.data.JsonStore({
  fields: ['payment_id', 'invoice_id', 'date', 'description', 'amount'],
  proxy: {
    type: 'ajax',
    url: '/work_order/billingDatagrid?id=' + this_workorder_id,
    reader: { 
      root: 'items'
    }
  },
  listeners: {
    'load': function(store){
      Ext.getCmp('billing_totals').setVisible(false);
      billtotals_template.overwrite(Ext.getCmp('billing_totals').body, store.proxy.reader.jsonData);
      Ext.getCmp('billing_totals').setVisible(true);
    }
  }
});

var partsfindStore = new Ext.data.JsonStore({
  fields: ['part_id', 'part_variant_id', 'name', 'sku', 'units', 'available', 'regular_price', 'unit_cost',
           'track_inventory', 'has_serial_number', 'category_path', 'location', 'enviro_levy', 'battery_levy',
           'min_quantity', 'max_quantity','manufacturer_sku'],
  remoteSort: true,
  pagesize: 50,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '/part/datagrid?show_pricing=1',    
    simpleSortMode: true,
    reader: {
      root: 'parts',
      idProperty: 'id',
      totalProperty: 'totalCount'
    }
  }
});





/*********************************************/
/*      PARTS STUFF                          */
/*********************************************/

function showPartAddWin(){
  PartAddWin.show();
  Ext.getCmp('partadd_namesearch').focus(true, 200);
}

function showPartMoveWin(partInstanceId){

  var config = {
    workorder_id: <?php echo $workorder->getId(); ?>,
    workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
    pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
    gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
    pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
    gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
  };

  partInst = partInstanceId;
  workordersStore.load();
  PartMoveWin.show();
}//showPartMoveWin()-----------------------------------------------------------


function filterPartGrid(field){
  grid = Ext.getCmp('parts_grid');
    if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  newval = field.getValue();
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    Ext.getCmp('parts_pager').moveFirst();
  }
}

var showPartCustomEditWin = function(inst_id){
  var config = {
    workorder_id: <?php echo $workorder->getId(); ?>,
    workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
    pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
    gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
    pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
    gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
  };

  if (inst_id){
    config.title = 'Edit One-Off Part';
    config.formConfig = { 
      autoLoadUrl: '<?php echo url_for('work_order/partcustomLoad?id='.$workorder->getId()); ?>?instance_id=' + inst_id,
      params: { instance_id: inst_id }
    };
  } else {
    config.title = 'Add One-Off Part';
  }
  
  new Ext.ux.PartCustomEditWin(config);
};

var showPartEditWin = function(inst_id, data){
  var workorder_id = <?php echo $workorder->getId(); ?>;

  showPartEditWindow(inst_id, workorder_id, data);
};//showPartEditWin()----------------------------------------------------------

function showPartEditWindow(inst_id, wo, data){
  var config = {
    workorder_id: <?php echo $workorder->getId(); ?>,
    workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
    pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
    gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
    pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
    gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
  };

  if (inst_id){
    config.title = 'Edit Part';
    config.formConfig = { autoLoadUrl: '<?php echo url_for('work_order/partload?id='.$workorder->getId()); ?>?instance_id=' + inst_id }
  } else {
    config.title = 'Add Part';
    config.formConfig = { loadRawData: data }
  }
  
  new Ext.ux.PartEditWin(config);
};//showPartEditWindow()-------------------------------------------------------

var PartMoveWin = new Ext.Window({
  width: 550,
  height: 200,
  border: false,
  resizable: false,
  modal: true,
  id: 'partmovewo',
  closeAction: 'hide',
  title: 'Move Part to Other Work Order',
  layout: 'fit',
  items: new Ext.FormPanel({
    autoWidth: true,
    id: 'partmoveform',
    url: '<?php echo url_for('work_order/partmovewo'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left' },
    items: [{
      xtype: 'textfield',
      width: 350,
      fieldLabel: 'Move from Work Order',
      name: 'workorderNumber',
      value: this_workorder_id,
      disabled: true,    
    },{
          xtype: 'combo',
          width: 350,
          itemId: 'workorderField',
          id: 'workorderField',
          fieldLabel: 'Move to Workorder',
          name: 'workorder_id',
          forceSelection: true,
          allowBlank: false,
          valueField: 'id',
          displayField: 'text',
          emptyText: 'Select New Workorder...',
          minChars: 2,
          anyMatch: true,
          store: workordersStore,
          listConfig: { minWidth: 350 },
          queryMode: 'local',
          listeners: {
            'select': function(field,r){
              var itemField = field.up('form').down('#itemField');
              itemField.clearValue();
              itemField.setDisabled(false);
              itemField.getStore().proxy.setExtraParam('workorder_id', field.getValue());
              itemField.getStore().load({
                callback: function(){
                  var itemField = field.up('form').down('#itemField');
                  if (itemField.getStore().getCount() === 0){
                    Ext.Msg.alert(
                      'No Tasks Available', 
                      'This workorder ('+field.getValue()+') does not have any tasks');
                  }
                  else if (itemField.getStore().getCount() == 1){
                    itemField.setValue( itemField.getStore().getAt(0).data.id);
                  } else {
                    itemField.onTriggerClick();
                  }
                }
              });
              //r = field.findRecordByValue(field.getValue());
            },
            'blur': function(field){
              if (field.getValue() == '')
              {
                var itemField = field.up('form').down('#itemField');
                itemField.clearValue();
                itemField.setDisabled(true);
                itemField.getStore().proxy.setExtraParam('workorder_id', null);
                field.up('form').down('#itemField').setDisabled(true);
              }
            }
          }
        },{
          xtype: 'combo',
          width: 350,
          itemId: 'itemField',
          id: 'itemField',
          fieldLabel: 'Move to Task',
          name: 'wo_item_id',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          valueField: 'id',
          displayField: 'text',
          disabled: true,
          triggerAction: 'all',
          emptyText: 'Select New Task...',
          minChars: 1,
          store: workorderItemsStore,
          listConfig: { minWidth: 350 },
          queryMode: 'local'
        }
  ],

    buttons: [{
      text: 'OK',
      formBind: true,
      handler: function(btn){
        var newWorkorderItem = Ext.getCmp('itemField').getValue();
        var newWorkorder = Ext.getCmp('workorderField').getValue();
                
        Ext.Msg.alert('Moving Part', 'Moving Part to Workorder: ' + newWorkorder);
        PartMoveWin.hide();

       Ext.Ajax.request({
            url: '<?php echo url_for('work_order/partmove'); ?>',
            method: 'POST',
            params: { 
              id: partInst, 
              target: newWorkorderItem
            },
            success: function(){
              Ext.Msg.hide();
              reload_tree();
              partslistStore.load();
            },
            failure: function(){
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not move part! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });

          Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
          Ext.getCmp('workorder_tree').getSelectionModel().deselectAll();
      }
    },{
      text: 'Cancel',
      handler:function(){
        PartMoveWin.hide();
        Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
      }
    }
  ]

  })
});//PartMoveWin()-------------------------------------------------------------

var PartAddWin = new Ext.Window({
  width: 650,
  height: 600,
  modal: true,
  id: 'partadd',
  closeAction: 'hide',
  title: 'Add Part to Work Order',
  layout: 'fit',
  items: [
    new Ext.grid.GridPanel({
      id: 'parts_grid',
      enableColumnMove: false,
      border: false,
      emptyText: 'No matching parts found',
      store: partsfindStore,
      viewConfig: { stripeRows: true, loadMask: true },
      columns:[{
        header: "Part Name",
        dataIndex: 'name',
        hideable: false,
        sortable: true,
        flex: 1
      },{
        header: "SKU",
        dataIndex: 'sku',
        sortable: true,
        width: 80
      },{
        header: "M SKU",
        dataIndex: 'manufacturer_sku',
        sortable: true,
        width: 80
      },{
        header: "Category",
        dataIndex: 'category_path',
        hideable: true,
        sortable: true,
        width: 140
      },{
        header: "Location",
        dataIndex: 'location',
        hideable: true,
        sortable: true,
        width: 140
      },{
        header: "Qty Avail",
        dataIndex: 'available',
        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
            if (value < record.get('min_quantity')){
              return '<span style="color:red;">' + value + '<\/span>';
            }else{
               return value;
            }
          },
        sortable: true,
        align: 'center',
        width: 60
      }],

      selModel: new Ext.selection.RowModel({
        listeners: {
          selectionchange: function(sm){
            Ext.getCmp('partadd_nextbutton').setDisabled(sm.getCount() != 1);
          }
        }
      }),

      tbar: new Ext.Toolbar({
        height: 27,
        items: [' ',{
          xtype: 'textfield',
          enableKeyEvents: true,
          name: 'namesearch',
          id: 'partadd_namesearch',
          paramField: 'name',
          width: 120,
          emptyText: 'Search By Name...',
          listeners: { keyup: filterPartGrid, blur: filterPartGrid }
        },'-',{
          xtype: 'textfield',
          enableKeyEvents: true,
          name: 'skusearch',
          id: 'partadd_skusearch',
          paramField: 'sku',
          width: 120,
          emptyText: 'Search By SKU...',
          listeners: { keyup: filterPartGrid, blur: filterPartGrid }
        },'-',{
          xtype: 'treecombo',
          valueField: 'id',
          paramField: 'category_id',
          displayField: 'text',
          rootVisible: true,
          treeHeight: 400,
          width: 135,
          emptyText: 'Select Category...',
          store: categoriesStore,
          listeners: { 
            change: filterPartGrid, 
            blur: filterPartGrid
          }
        }]
      }),

      bbar: new Ext.PagingToolbar({
        id: 'parts_pager',
        store: partsfindStore,
        displayInfo: true,
        displayMsg: 'Dispaying Parts {0} - {1} of {2}',
        emptyMsg: 'No Matching Parts Found'
      }),

      listeners: { 
        afterrender: function(){ 
          partsfindStore.load({params: {start: 0, limit: 50}}); 
        },
        itemdblclick: function(grid,idx){
          PartAddWin.hide();
          showPartEditWin(null, grid.getSelectionModel().getSelection()[0].data);
        }
      }
    })
  ],

  buttonAlign: 'left',
  buttons: [{
    text: 'Add One-Off Part',
    disabled: false,
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_edit')): ?>
        Ext.getCmp('partadd').hide();
        showPartCustomEditWin();
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit parts');
      <?php endif; ?>
    }
  },{    
    text: 'Quick Add New Part to Database',
    disabled: false,
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_edit')): ?>
        Ext.getCmp('partadd').hide();
        new Ext.ux.PartQuickaddWin();
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit parts');
      <?php endif; ?>
    }
  },'->',{
    text: 'Add Selected &gt;',
    id: 'partadd_nextbutton',
    disabled: true,
    handler: function(){
      PartAddWin.hide();
      showPartEditWin(null, Ext.getCmp('parts_grid').getSelectionModel().getSelection()[0].data, true);
    }
  },{
    text: 'Cancel',
    handler:function(){
      PartAddWin.hide();
      Ext.getCmp('parts_grid').getSelectionModel().deselectAll();
    }
  }]
});//PartAddWin()--------------------------------------------------------------

var parts_list = new Ext.grid.GridPanel({
  title: 'Parts List',
  border: false,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns: [{
    header: 'SKU',
    hideable: false,
    dataIndex: 'sku',
    sortable: true,
    width: 80
  },{
    header: 'Name',
    hideable: false,
    dataIndex: 'name',
    sortable: true,
    flex: 1,
    renderer: function(val,meta,r){
      var namedisplay = '';
      if (r.data.custom){
        namedisplay = val;
      }
      else{
        namedisplay = '<a href="<?php echo url_for('part/view?id='); ?>'+r.data.part_id+'">'+val+'</a>';
      }

      return namedisplay;
    }
  },{
    header: 'Date',
    dataIndex: 'date',
    width: 80,
    sortable: true
  },{
    header: 'Task',
    dataIndex: 'task',
    sortable: true,
    flex: 1
  },{
    header: 'Qty',
    align: 'center',
    dataIndex: 'quantity',
    hideable: false,
    sortable: true,
    width: 30
  },{
    header: 'Status',
    dataIndex: 'status',
    sortable: true,
    flex: 1
  }],

  store: partslistStore,

  tbar: new Ext.Toolbar({
  <?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
    items: [{
      text: 'Add Part',
      iconCls: 'partadd',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_add')): ?>
          showPartAddWin();
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit work orders');
        <?php endif; ?>
      }
    },'-',{
      text: 'Edit Selected Part',
      id: 'parts_list_editbutton',
      iconCls: 'partadd',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_edit')): ?>
          var record = parts_list.getSelectionModel().getSelection()[0].data;
          if (record.custom){
            showPartCustomEditWin(record.id);
          } else {
            showPartEditWin(record.id);
          }
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit workorders');
        <?php endif; ?>
      }
    },'-',
    {
      text: 'Move Selected Part',
      id: 'parts_list_movebutton',
      iconCls: 'partmove',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_edit')): ?>
          var record = parts_list.getSelectionModel().getSelection()[0].data;
          partInst = record.id;

          showPartMoveWin(record.id);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit workorders');
        <?php endif; ?>
      }
    },'->',{
      text: 'Print List',
      iconCls: 'print',
      handler: function(){
        new Ext.ux.PartListPrintWin({ formConfig: { params: { id: <?php echo $workorder->getId(); ?> }}});
      }
    }]
    <?php endif; ?>
  }),
  
  bbar: new Ext.PagingToolbar({
    store: partslistStore,
    emptyMsg: 'No Parts for this Workorder',
    displayInfo: true
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        partslistStore.load();
      }
    },
    itemdblclick: function(grid, record){
      <?php if ($sf_user->hasCredential('workorder_edit')): ?>
        if (record.data.custom){
          showPartCustomEditWin(record.data.id);
        } else {
          showPartEditWin(record.data.id);
        }
      <?php endif; ?>
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      selectionchange: function(sm){
        Ext.getCmp('parts_list_editbutton').setDisabled(!sm.hasSelection());
        Ext.getCmp('parts_list_movebutton').setDisabled(!sm.hasSelection());
      }
    }
  }) 
});//parts_list()--------------------------------------------------------------

/*********************************************/
/*      LABOUR STUFF                         */
/*********************************************/

//centralized action code
function doTimelogAction(button){
  sm = timelogs_list.getSelectionModel();
  if (button.doAction == 'delete' || sm.getCount() > 1){
    <?php if ($sf_user->hasCredential('timelogs_edit')): ?>
      if (button.doAction == 'delete'){
        msg = 'Are you sure you want to delete the selected Timelog(s)?<br /><br />This cannot be undone!';
      }else{
        msg = 'Are you sure you want to ' + button.doAction + ' the selected timelogs?';
      }
      Ext.Msg.show({
        icon: Ext.MessageBox.QUESTION,
        buttons: Ext.MessageBox.OKCANCEL,
        msg: msg,
        modal: true,
        height: 150,
        title: 'Confirm '+ button.doAction,
        fn: function(butid){
          if (butid == 'ok'){
            executeTimelogAction(button.doAction);
          }
        }
      });
    <?php else: ?>
      Ext.Msg.alert('Permission Denied','You do not have permission to edit or delete timelogs');
    <?php endif; ?>
  } else {
    <?php if ($sf_user->hasCredential('timelogs_approve')): ?>
      executeTimelogAction(button.doAction);
    <?php else: ?>
      Ext.Msg.alert('Permission Denied','You do not have permission to modify timelog status');
    <?php endif; ?>
  }
}

function executeTimelogAction(name){
  var selectedIds = new Array;
  sm = timelogs_list.getSelectionModel();
  Ext.each(sm.getSelection(),function(record){ selectedIds.push(record.data.id); });
  Ext.Msg.show({
    msg: 'Updating timelog(s), please wait',
    width: 300,
    wait: true
  });
  Ext.Ajax.request({
    url: '<?php echo url_for('timelogs/changeStatus?dowhat='); ?>' + name,
    method: 'POST',
    params: {ids: selectedIds.join()},
    success: function(){
      Ext.Msg.hide();
      timelogs_list.store.load();
      reload_tree();
    },
    failure: function(){
      Ext.Msg.hide();
      Ext.Msg.show({
        icon: Ext.MessageBox.ERROR,
        buttons: Ext.MessageBox.OK,
        msg: 'Could not edit timelog(s)! Reload page and try again.',
        modal: true,
        title: 'Error'
      });
    }
  });
}

var timelogs_list = new Ext.grid.GridPanel({
  title: 'Timelogs List',
  border: false,
  enableColumnMove: false,
  emptyText: 'No matching Timelogs found',
  store: timelogsStore,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[
  {
    header: "Date",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 80
  },{
    header: "Employee",
    dataIndex: 'employee',
    sortable: true,
    width: 140
  },{
    header: "Payroll",
    dataIndex: 'payroll_hours',
    sortable: false,
    align: 'center',
    width: 55
  },{
    header: "Billed",
    dataIndex: 'billable_hours',
    sortable: false,
    align: 'center',
    width: 50,
    renderer: function(value,metaData,record){
      if (value != record.data.payroll_hours) {
        return '<span style="color: red">'+value+'</span>';
      } else {
        return value;
      }
    }
  },{
    header: "Task",
    dataIndex: 'item',
    sortable: false,
    flex: 1
  },{
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    width: 60,
    renderer: function(value,metaData,record){
      if (value == 'Approved'){
        img = 'tick';
      }
      else if (value == 'Flagged'){
        img = 'flag_red';
      }
      else{
        img = 'error'
      }
      output = '<img src="/images/silkicon/'+img+'.png" title="'+value+'" alt="'+value+'" />';
      if (record.data['employee_notes']){
        output += '<img src="/images/silkicon/information.png" title="Has Employee Notes" alt="Has Employee Notes" />';
      } else {
        output += '<img src="/images/x.gif" width="16" height="16" />';
      }
      if (record.data['admin_notes']){
        output += '<img src="/images/silkicon/note.png" title="Has Admin Notes" alt="Has Admin Notes" />';
      }
      return output;
    }
  }],

  selModel: new Ext.selection.RowModel({
    listeners: { 
      selectionchange: function(sm, record){
        if (sm.hasSelection()){
          record = sm.getSelection()[0];
          Ext.getCmp('timelogs_list_editbutton').setDisabled(false);
          Ext.getCmp('singledelete').setDisabled(false);
          Ext.getCmp('singleflag').setVisible(record.data.status != 'Flagged');
          Ext.getCmp('singleflag').setDisabled(false);
          Ext.getCmp('singleunflag').setVisible(record.data.status == 'Flagged');
          Ext.getCmp('singleapprove').setVisible(record.data.status != 'Approved');
          Ext.getCmp('singleapprove').setDisabled(false);
          Ext.getCmp('singleunapprove').setVisible(record.data.status == 'Approved');
        } else {
          Ext.getCmp('timelogs_list_editbutton').setDisabled(true);
          Ext.getCmp('singledelete').setDisabled(true);
          Ext.getCmp('singleflag').setVisible(true);
          Ext.getCmp('singleflag').setDisabled(true);
          Ext.getCmp('singleunflag').setVisible(false);
          Ext.getCmp('singleapprove').setVisible(true);
          Ext.getCmp('singleapprove').setDisabled(true);         
          Ext.getCmp('singleunapprove').setVisible(false);
        }
      }
    }
  }),

  tbar: new Ext.Toolbar({
    items: [
    <?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
    {
      text:'Add Timelog',
      iconCls: 'timeadd',
      handler: function(){
        <?php if ($sf_user->hasCredential(array('timelogs_add_self','timelogs_add_other'), false)): ?>
          new Ext.ux.TimelogEditWin({
            title: 'Add Timelog',
            workorder_id: <?php echo $workorder->getId(); ?>,
            is_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
            formConfig: {
              params: { id: 'new' }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to add timelogs');
        <?php endif; ?>
      }
    },
  <?php endif; ?>
    '->',{
      text: 'Print List',
      iconCls: 'print',
      handler: function(){ 
        new Ext.ux.TimelogsPrintWin({ formConfig: { params: { id: <?php echo $workorder->getId(); ?>}}});
      }
    }] 
  }),

  bbar: new Ext.PagingToolbar({
    id: 'timelogs_pager',
    store: timelogsStore,
    items: ['->',{
      id: 'timelogs_list_editbutton',
      text: 'Edit',
      disabled: true,
      iconCls: 'timeedit',
      handler: function(){
        <?php if ($sf_user->hasCredential('timelogs_edit')): ?>
          var sel_id = timelogs_list.getSelectionModel().getSelection()[0].data.id;
          new Ext.ux.TimelogEditWin({
            title: 'Edit Timelog',
            workorder_id: <?php echo $workorder->getId(); ?>,
            formConfig: {
              params: { id: sel_id },
              autoLoadUrl: '<?php echo url_for('timelogs/load'); ?>?id=' + sel_id
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit timelogs');
        <?php endif; ?>
      }
    },'-',{
      id: 'singleapprove',
      doAction: 'approve',
      text: 'Approve',
      iconCls: 'approve',
      handler: doTimelogAction,
      disabled: true
    },{
      id: 'singleunapprove',
      doAction: 'unapprove',
      text: 'Unapprove',
      iconCls: 'reject',
      handler: doTimelogAction,
      hidden: true
    },'-',{
      id: 'singleunflag',
      doAction: 'unflag',
      text: 'Un-Flag',
      iconCls: 'flag',
      handler: doTimelogAction,
      hidden: true
    },{
      id: 'singleflag',
      doAction: 'flag',
      text: 'Flag',
      iconCls: 'flag',
      handler: doTimelogAction,
      disabled: true
    },'-',{
      id: 'singledelete',
      doAction: 'delete',
      text: 'Delete',
      iconCls: 'delete',
      handler: doTimelogAction,
      disabled: true
    }]
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        timelogsStore.load({params:{start:0, limit:100}});
      }
    },
    itemdblclick: function(grid, record){
      <?php if ($sf_user->hasCredential('timelogs_edit')): ?>
        new Ext.ux.TimelogEditWin({
          title: 'Edit Timelog',
          workorder_id: <?php echo $workorder->getId(); ?>,
          formConfig: {
            params: { id: record.data.id },
            autoLoadUrl: '<?php echo url_for('timelogs/load'); ?>?id=' + record.data.id
          }
        });    
      <?php endif; ?>
    }    
  },

});


/*********************************************/
/*      GENERAL STUFF                        */
/*********************************************/

var customerBoatStore = new Ext.data.JsonStore({
  fields: ['id','name', 'make', 'model'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boatsdatagrid',
    reader: {
      root: 'boats'
    }
  }
});

Ext.define('Ext.ux.WorkorderEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Edit Workorder',
  width: 500,
  autoShow: true,

  defaultFormConfig: {
    url: '/work_order/edit',
    params: { 
      id: this_workorder_id 
    },

    formSuccess: function(form,action,obj){
      if (obj.suppliererror){
        Ext.Msg.show({
          closable:false, 
          fn: function(){
            var myMask = new Ext.LoadMask(Ext.getBody(), { msg: "Workorder Updated. Refreshing Page..."});
            myMask.show();
            location.href = '<?php echo url_for('work_order/view?id='.$workorder->getId()); ?>';
          },
          modal: true,
          title: 'Oops',
          icon: Ext.MessageBox.ERROR,
          buttons: Ext.MessageBox.OK,
          width: 400,
          msg: 'There was a problem creating one or more special items because a part had either no or multiple suppliers, so the system wasn\'t able to determine which Supplier to use. These parts will show up as still being an Estimate. You will have to manually re-add the parts to the workorder in order to create a special order. Sorry.'
        });
      } else {
        var myMask = new Ext.LoadMask(Ext.getBody(), { msg: "Workorder Updated. Refreshing Page..."});
        myMask.show();
        location.href = '<?php echo url_for('work_order/view?id='.$workorder->getId()); ?>';
      }      
    },

    fieldDefaults: { labelWidth: 140 },

    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.7,
        layout: 'anchor',
        items: [{
          xtype: 'combo',
          itemId: 'customer',
          fieldLabel: 'Customer',
          name: 'customer_id',
          forceSelection: true,
          allowBlank: false,
          valueField: 'id',
          displayField: 'name',
          hideTrigger: true,
          matchFieldWidth: false,
          minChars: 2,
          store: customerStore,
          anchor: '-25',
          queryMode: 'remote',
          listeners: {
            'render': function(field){
              var newmodel = field.getStore().add({id: <?php echo $workorder->getCustomerId(); ?>, name: <?php echo json_encode($workorder->getCustomer()->generateName(false, false, false)); ?>});
              field.setValue(newmodel);
            },
            'select': function(field,r){
              var boatfield = field.up('form').down('#boatfield');
              boatfield.clearValue();
              boatfield.setDisabled(false);
              boatfield.getStore().proxy.setExtraParam('customer_id', field.getValue());
              boatfield.getStore().load({
                callback: function(){
                  var boatfield = field.up('form').down('#boatfield');
                  if (boatfield.getStore().getCount() === 0){
                    Ext.Msg.confirm(
                      'No Boats Available', 
                      'This customer doesn\'t have a boat set up.<br /><br />Would you like to add one now?', 
                      function (btn){
                        if (btn == 'yes'){
                          new Ext.ux.BoatEditWin({
                            customer_id: field.getValue(),
                            loadIntoSelect: field.up('form').down('#boatfield')
                          });
                        }
                      }
                    );
                  }
                  else if (boatfield.getStore().getCount() == 1){
                    boatfield.setValue(boatfield.getStore().getAt(0).data.id);
                  } else {
                    boatfield.onTriggerClick();
                  }
                }
              });
            },
            'blur': function(field){
              if (field.getValue() == '')
              {
                boatfield = Ext.getCmp('workorderedit_boatfield');
                boatfield.clearValue();
                boatfield.setDisabled(true);
                boatfield.getStore().proxy.setExtraParam('customer_id', null);
                Ext.getCmp('workorderedit_boatbutton').setDisabled(true);
              }
            }
          }
        }]
      },{
        border: false,
        columnWidth: 0.3,
        items: new Ext.Button({
          text: 'Add Customer',
          iconCls: 'add',
          width: 125,
          handler: function(btn){
            new Ext.ux.CustomerEditWin({
              loadIntoSelect: btn.up('form').down('#customer')
            });
          }
        })
      }]
    },{
      layout: 'column',
      border: false,
      padding: '0 0 5 0',
      items: [{
        border: false,
        columnWidth: 0.7,
        layout: 'anchor',
        items: [{
          xtype: 'combo',
          itemId: 'boatfield',
          fieldLabel: 'Boat',
          name: 'customer_boat_id',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          valueField: 'id',
          displayField: 'name',
          triggerAction: 'all',
          minChars: 1,
          store: customerBoatStore,
          tpl: boatTpl,
          anchor: '-25',
          listConfig: { minWidth: 250 },
          queryMode: 'local',
          listeners: {
            'render': function(field){
              field.getStore().proxy.setExtraParam('customer_id', <?php echo $workorder->getCustomerId(); ?>);
              field.getStore().load({ scope: field, callback: function() {
                this.setValue(<?php echo $workorder->getCustomerBoatId(); ?>);
              }})
            }
          }
        }]
      },{
        border: false,
        columnWidth: 0.3,
        items: new Ext.Button({
          text: 'Add Boat',
          id: 'workorderedit_boatbutton',
          iconCls: 'add',
          width: 125,
          handler: function(btn){
            new Ext.ux.BoatEditWin({
              customer_id: btn.up('form').down('#customer').getValue(),
              loadIntoSelect: btn.up('form').down('#boatfield')
            });
          }
        })
      }]
    },{
      itemId: 'oldstatus',
      xtype: 'displayfield',
      fieldLabel: 'Current Status',
      margin: '15 0 5 0',
      value: '<?php echo $workorder->getStatus(); ?>',
      name: 'oldstatus'
    },{
      itemId: 'newstatus',
      xtype: 'acbuttongroup',
      name: 'status',
      value: '<?php echo $workorder->getStatus(); ?>',
      fieldLabel: 'New Status',
      items: [ 'Estimate', 'In Progress', 'Completed', 'Cancelled' ],
      listeners: { 
        change: function(field){
          var value = field.getValue();
          var form = field.up('form');
          var starteddate = form.down('#starteddate');
          var completeddate = form.down('#completeddate');
          
          if (value == 'In Progress' && starteddate.getValue() == '') {
            starteddate.setValue(new Date());
          } else if ((value == 'Completed' || value == 'Cancelled') && (completeddate.getValue() == '')) {
            completeddate.setValue(new Date());
          }

          var newval = value;
          var oldval = form.down('#oldstatus').getValue();

          form.down('#holdaction').setVisible(newval != oldval);
          form.down('#orderaction').setVisible(newval != oldval);
          if (newval != oldval){
            form.down('#holdaction_unhold').setVisible(newval != 'In Progress');
            form.down('#holdaction_hold').setVisible(newval == 'In Progress');
            form.down('#orderaction_split').setVisible(newval == 'In Progress');
            form.down('#orderaction_all').setVisible(newval == 'In Progress');
            form.down('#orderaction_remove').setVisible(newval != 'In Progress');
          }
        }
      }
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Color Code',
      name: 'color_code',
      value: '<?php echo $workorder->getSummaryColor(); ?>',
      width: 350,
      items: color_code_array
    },
    /*
    Removed for_rigging option
    {
      xtype: 'acbuttongroup',
      fieldLabel: 'Company',
      width: 350,
      name: 'for_rigging',
      value: '<?php echo ($workorder->getForRigging() ? '1' : '0'); ?>',
      items: [
        { value: '0', text: 'Delta Services' },
        { value: '1', text: 'Delta Rigging' }
      ]
    },
    */
    {
      xtype: 'combo',
      fieldLabel: 'Category',
      name: 'workorder_category_id',
      width: 350,
      editable: false,
      forceSelection: true,
      queryMode: 'local',
      displayField: 'name',
      valueField: 'id',
      triggerAction: 'all',
      store: wocatsStore,
      value: '<?php echo ($workorder->getWorkorderCategoryId() ? $workorder->getWorkorderCategoryId() : '-1'); ?>',
      listConfig: { minWidth: 200 }      
    <?php if ($sf_user->hasCredential('workorder_payment')): ?>
    },{
      xtype: 'acbuttongroup',
      itemId: 'pstfield',
      fieldLabel: 'PST',
      width: 350,
      margin: '15 0 5 0',
      name: 'pst_exempt',
      value:  '<?php echo ($workorder->getPstExempt() ? '1' : '0'); ?>',
      items: [
        { value: '0', text: 'Charge <?php echo sfConfig::get('app_pst_rate'); ?>% PST', flex: 5 },
        { value: '1', text: 'PST Exempt', flex: 3 }
      ]
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'GST',
      itemId: 'gstfield',
      width: 350,
      name: 'gst_exempt',
      value: '<?php echo ($workorder->getGstExempt() ? '1' : '0'); ?>',
      items: [
        { value: '0', text: 'Charge <?php echo sfConfig::get('app_gst_rate'); ?>% GST', flex: 5 },
        { value: '1', text: 'GST Exempt', flex: 3 }
      ]
    },{
      xtype: 'numberfield',
      fieldLabel: 'Shop Supplies %',
      name: 'shop_supplies_surcharge',
      minValue: 0,
      maxValue: 100,
      anchor: '-225',
      value: <?php echo $workorder->getShopSuppliesSurcharge(); ?>
    },{
      xtype: 'numberfield',
      fieldLabel: 'Power/Moorage $',
      name: 'moorage_surcharge_amt',
      forcePrecision: true,
      minValue: 0,
      anchor: '-225',
      value: <?php echo $workorder->getMoorageSurchargeAmt(); ?>
    <?php endif; ?>
    },{
      xtype: 'datefield',      
      fieldLabel: 'Created Date',
      anchor: '-165',
      margin: '15 0 5 0',
      format: 'M j, Y',
      name: 'created_on',
      value: <?php echo ($workorder->getCreatedOn() ? '\''.$workorder->getCreatedOn('M j, Y').'\'' : 'null'); ?>
    },{
      itemId: 'starteddate',
      xtype: 'datefield',
      fieldLabel: 'Start Date',
      anchor: '-165',
      format: 'M j, Y',
      name: 'started_on',
      value: <?php echo ($workorder->getStartedOn() ? '\''.$workorder->getStartedOn('M j, Y').'\'' : 'null'); ?>,
      visibleIf: {
        itemId: 'newstatus',
        compareType: '!=',
        compareValue: 'Estimate',
        onlyDisable: true
      }
    },{
      xtype: 'datefield',
      itemId: 'completeddate',
      fieldLabel: 'Completed Date',
      anchor: '-165',
      format: 'M j, Y',
      name: 'completed_on',
      value: <?php echo ($workorder->getCompletedOn() ? '\''.$workorder->getCompletedOn('M j, Y').'\'' : 'null'); ?>,
      visibleIf: {
        itemId: 'newstatus',
        compareValue: 'Completed',
        onlyDisable: true
      }
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Haulout Date & Time',
      layout: 'hbox',
      items: [{
        xtype: 'datefield',
        flex: 1,
        format: 'M j, Y',
        name: 'haulout',
        value: <?php echo ($workorder->getHauloutDate() ? '\''.$workorder->getHauloutDate('M j, Y').'\'' : 'null'); ?>
      },{
       xtype: 'splitter'
      },{
        xtype: 'timefield',
        flex: 1,
        name: 'haulout_time',
        minValue: '6:00 AM',
        maxValue: '6:00 PM',
        increment: 10,
        value: <?php echo ($workorder->getHauloutDate('G') > 0 ? '\''.$workorder->getHauloutDate('g:i A').'\'' : 'null'); ?>
      }]
    },{
      xtype: 'fieldcontainer',
      fieldLabel: 'Relaunch Date & Time',
      layout: 'hbox',
      items: [{
        xtype: 'datefield',
        flex: 1,
        format: 'M j, Y',
        name: 'haulin',
        value: <?php echo ($workorder->getHaulinDate() ? '\''.$workorder->getHaulinDate('M j, Y').'\'' : 'null'); ?>
      },{
       xtype: 'splitter'
      },{
        xtype: 'timefield',
        flex: 1,
        name: 'haulin_time',
        minValue: '6:00 AM',
        maxValue: '6:00 PM',
        increment: 10,
        value: <?php echo ($workorder->getHaulinDate('G') > 0 ? '\''.$workorder->getHaulinDate('g:i A').'\'' : 'null'); ?>
      }]
    },{
      itemId: 'holdaction',
      xtype: 'fieldset',
      layout: 'anchor',
      title: 'Put Parts on Hold',
      hidden: true,
      items: [{
        xtype: 'radiogroup',
        columns: 1,
        hideLabel: true,
        items: [
          {name: 'holdaction', itemId: 'holdaction_leave',  boxLabel: 'Leave Parts Status As-Is', inputValue: 'leave', checked: true},
          {name: 'holdaction', itemId: 'holdaction_hold',  boxLabel: 'Put All Parts On Hold', inputValue: 'hold'},
          {name: 'holdaction', itemId: 'holdaction_unhold',  boxLabel: 'Remove Hold on any parts', inputValue: 'unhold'}
        ]
      }]
    },{
      itemId: 'orderaction',
      xtype: 'fieldset',
      layout: 'anchor',
      title: 'Place Special Orders (if needed)',
      hidden: true,
      items: [{
        xtype: 'radiogroup',
        columns: 1,
        hideLabel: true,
        items: [
          {name: 'orderaction', itemId: 'orderaction_leave',  boxLabel: 'Don\'t create Special Orders for me', inputValue: 'leave', checked: true },
          {name: 'orderaction', itemId: 'orderaction_split',  boxLabel: 'Create special order only for extra quantity needed of a part', inputValue: 'split'},
          {name: 'orderaction', itemId: 'orderaction_all',  boxLabel: 'Create special order for entire quantity of an understocked part', inputValue: 'all'},
          {name: 'orderaction', itemId: 'orderaction_remove',  boxLabel: 'Remove any unsent special orders', inputValue: 'remove'}
        ]
      }]
    }]

  }
});

var notes_panel = new Ext.Panel({
  id: 'notes_panel',
  title: 'Notes<?php if ($workorder->getCustomerNotes() || $workorder->getInternalNotes()) echo ' (*)'; ?>',
  border: false,
  autoScroll: true,
  bodyStyle: 'font-size: 12px; padding: 20px; font-weight: normal;',
  items: [{
    xtype: 'fieldset',
    layout: 'anchor',
    title: 'Notes for Customer',
    items: [{
      id: 'notes_panel_customer',
      border: false,
      padding: 10,
      html: <?php echo (trim($workorder->getCustomerNotes()) ? json_encode(nl2br($workorder->getCustomerNotes())) : "'<span class=\"inactive_text\">No Customer Notes Specified.</span>'"); ?>
    }]
  },{
    xtype: 'fieldset',
    layout: 'anchor',
    title: 'Notes for Internal Use',
    items: [{
      id: 'notes_panel_internal',
      border: false,
      padding: 10,
      html: <?php echo (trim($workorder->getInternalNotes()) ? json_encode(nl2br($workorder->getInternalNotes())) : "'<span class=\"inactive_text\">No Internal Notes Specified.</span>'"); ?>
    }]
  }],
  tbar: new Ext.Toolbar({
    items: [{
      id: 'notesedit',
      text: 'Edit Notes',
      iconCls: 'info',
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_edit')): ?>
          new Ext.ux.NotesEditWin({
            formConfig: {
              params: { id: <?php echo $workorder->getId(); ?>},
              autoLoadUrl: '<?php echo url_for('work_order/notesload?id='.$workorder->getId()); ?>',
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit work orders');
        <?php endif; ?>
      }
    }]
  })
});


var workorder_bbar = new Ext.Toolbar({
  height: 27,
  items: [{
    id: 'woi_copybutton',
    text: 'Copy Task',
    iconCls: 'info',
    disabled: true,
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_edit')): ?>
        var sel = workorder_tree.getSelectionModel().getSelection()[0].data;
        if (/^[0-9]+$/.test(sel.id)){
          new Ext.ux.ItemCopyWin({
            itemtext: sel.text,
            item_id: sel.id,
            workorder_id: <?php echo $workorder->getId(); ?>
          });
        }
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to edit workorder items');
      <?php endif; ?>
    }
  },'->',{
<?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
    id: 'woi_editbutton',
    text: 'Edit',
    iconCls: 'infoedit',
    disabled: true,
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_edit')): ?>
        var sel = workorder_tree.getSelectionModel().getSelection()[0];
        var sel_id = sel.data.id;
        if (/^[0-9]+$/.test(sel_id)){
          var win = new Ext.ux.ItemEditWin({
            title: 'Edit Task',
            workorder_id: this_workorder_id,
            color_codes: color_code_array,
            formConfig: {
              params: { item_id: sel_id },
              autoLoadUrl: '<?php echo url_for('work_order/itemload?id='.$workorder->getId()); ?>?item_id=' + sel_id
            }
          });
        } else if (/^part-[0-9]+-[0-9]+$/.test(sel_id)) {
          var record = sel.data;
          if (record.custom){
            showPartCustomEditWin(sel_id.replace(/^part-[0-9]+-([0-9]+)$/, '$1'));
          } else {
            showPartEditWin(sel_id.replace(/^part-[0-9]+-([0-9]+)$/, '$1'));
          }          
        } else if (/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-[0-9]+$/.test(sel_id)){
          var sel_id = sel_id.replace(/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-([0-9]+)$/, '$1');
          new Ext.ux.TimelogEditWin({
            title: 'Edit Timelog',
            workorder_id: <?php echo $workorder->getId(); ?>,
            formConfig: {
              params: { id: sel_id },
              autoLoadUrl: '<?php echo url_for('timelogs/load'); ?>?id=' + sel_id
            }
          });
          //end edit timelog
        } else if (/^expense-[0-9]+-[0-9]+$/.test(sel_id)) {
          new Ext.ux.ExpenseEditWin({
            title: 'Edit Expense',
            workorder_id: <?php echo $workorder->getId(); ?>,
            workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
            pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
            gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
            pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
            gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
            formConfig: { 
              params: { expense_id: sel_id.replace(/^expense-[0-9]+-([0-9]+)$/, '$1') },
              autoLoadUrl: '<?php echo url_for('work_order/expenseload?id='.$workorder->getId()); ?>?expense_id=' + sel_id.replace(/^expense-[0-9]+-([0-9]+)$/, '$1')
            }
          });
          //end edit expense
        }
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to edit workorder items');
      <?php endif; ?>
    }
  },'-',{
<?php endif; ?>
<?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
    id: 'woi_deletebutton',
    text: 'Delete',
    iconCls: 'infodelete',
    disabled: true,
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_edit')): ?>
        var sel_id = workorder_tree.getSelectionModel().getSelection()[0].data.id;
        if (/^[0-9]+$/.test(sel_id))
        {
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete the selected Task?<br />All items (parts, labour, expenses) in this task will be deleted as well! This cannot be undone!!',
            modal: true,
            title: 'Confirm Delete',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({
                  msg: 'Deleting Item, please wait',
                  width: 300,
                  wait: true
                });
                Ext.Ajax.request({
                  url: '<?php echo url_for('work_order/itemdelete?id='.$workorder->getId()); ?>',
                  method: 'POST',
                  params: { item_id: sel_id },
                  callback : function (opt,success,response){
                    Ext.Msg.hide();
                    var data = Ext.decode(response.responseText);
                
                    if (success && data && data.success == true){
                      reload_tree();
                      partslistStore.load();
                      timelogsStore.load();
                    } else {
                      if (data && data.errors && data.errors.reason){
                        var myMsg = data.errors.reason;
                      } else {
                        var myMsg = 'Could not delete task. Try again later!';  
                      }

                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: myMsg,
                        modal: true,
                        title: 'Error'
                      });
                    }
                  }
                });
              }
            }
          });
        } else if (/^part-[0-9]+-[0-9]+$/.test(sel_id)) {
          //delete part
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this part?<br /><br />If a special order was created for this item it will also be removed.',
            modal: true,
            title: 'Delete Work Order Part',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({title:'Please Wait',msg:'Removing Part, please wait...', closable: false});
                Ext.Ajax.request({
                  url: '<?php echo url_for('work_order/partdelete?id='.$workorder->getId()); ?>',
                  method: 'POST',
                  params: { instance_id: sel_id.replace(/^part-[0-9]+-([0-9]+)$/, '$1') },
                  success: function(){
                    Ext.Msg.hide();
                    reload_tree();
                    partslistStore.load();
                  },
                  failure: function(){
                    Ext.Msg.hide();
                    Ext.Msg.show({
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: 'Could not delete part!',
                      modal: true,
                      title: 'Error'
                    });
                  }
                });
              }
            }
          });
          //end delete part
        } else if (/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-[0-9]+$/.test(sel_id)){
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this timelog?',
            modal: true,
            title: 'Delete Work Order Part',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({title:'Please Wait',msg:'Removing Timelog, please wait...', closable: false});
                Ext.Ajax.request({
                  url: '<?php echo url_for('timelogs/changeStatus?dowhat=delete'); ?>',
                  method: 'POST',
                  params: {ids: sel_id.replace(/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-([0-9]+)$/, '$1') },
                  success: function(){
                    Ext.Msg.hide();
                    reload_tree();
                  },
                  failure: function(){
                    Ext.Msg.hide();
                    Ext.Msg.show({
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: 'Could not delete timelog!',
                      modal: true,
                      title: 'Error'
                    });
                  }
                });
              }
            }
          });
          //end delete labour
        } else if (/^expense-[0-9]+-[0-9]+$/.test(sel_id)) {
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this expense?',
            modal: true,
            title: 'Delete Work Order Expense',
            fn: function(butid){
              if (butid == 'ok'){
                Ext.Msg.show({title:'Please Wait',msg:'Removing Expense, please wait...', closable: false});
                Ext.Ajax.request({
                  url: '<?php echo url_for('work_order/expensedelete?id='.$workorder->getId()); ?>',
                  method: 'POST',
                  params: {expense_id: sel_id.replace(/^expense-[0-9]+-([0-9]+)$/, '$1') },
                  success: function(){
                    Ext.Msg.hide();
                    reload_tree();
                  },
                  failure: function(){
                    Ext.Msg.hide();
                    Ext.Msg.show({
                      icon: Ext.MessageBox.ERROR,
                      buttons: Ext.MessageBox.OK,
                      msg: 'Could not delete expense!',
                      modal: true,
                      title: 'Error'
                    });
                  }
                });
              }
            }
          });
          //end delete expense
        }
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to edit or delete workorder items');
      <?php endif; ?>
    }
<?php endif; ?>
  }]
});

var workorder_tbar = new Ext.Toolbar({
  items: [{
    text: 'Add a Task',
    iconCls: 'folderadd',
    handler: function(){    
      <?php if ($sf_user->hasCredential('workorder_add')): ?>
        new Ext.ux.ItemEditWin({ 
          workorder_id: this_workorder_id,
          color_codes: color_code_array
        });
      <?php else: ?>    
        Ext.Msg.alert('Permission Denied','You do not have permission to edit work orders');
      <?php endif; ?>
    }
<?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
  },'-',{
    text: 'Add Part',
    iconCls: 'partadd',
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_add')): ?>
        showPartAddWin();
      <?php else: ?>    
        Ext.Msg.alert('Permission Denied','You do not have permission to edit worklogs');
      <?php endif; ?>
    }
<?php endif; ?>
<?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
  },'-',{
    text: 'Add Timelog',
    iconCls: 'timeadd',
    handler: function(){
      <?php if ($sf_user->hasCredential(array('timelogs_add_other','timelogs_add_self'), false)): ?>
        new Ext.ux.TimelogEditWin({
          title: 'Add Timelog',
          workorder_id: <?php echo $workorder->getId(); ?>,
          is_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
          formConfig: {
            params: { id: 'new' }
          }
        });
      <?php else: ?>    
        Ext.Msg.alert('Permission Denied','You do not have permission to add timelogs to a work order');
      <?php endif; ?>
    }
<?php endif; ?>
<?php if ($workorder->isEstimate() || $workorder->isInProgress()): ?>
  },'-',{
    text: 'Add Expense',
    iconCls: 'moneyadd',
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_add')): ?>
        var win = new Ext.ux.ExpenseEditWin({
          title: 'Add Expense',
          workorder_id: <?php echo $workorder->getId(); ?>,
          workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
          pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
          gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
          pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
          gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>
        });
      <?php else: ?>    
        Ext.Msg.alert('Permission Denied','You do not have permission to edit workorders');
      <?php endif; ?>
    }
<?php endif; ?>
  },'-',{
      text: 'Move Selected Part',
      id: 'wo_tasks_movebutton',
      iconCls: 'partmove',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('workorder_edit')): ?>
          var record = workorder_tree.getSelectionModel().getSelection()[0].data;
          partInst = record.id.substring(record.id.lastIndexOf("-")+1);

          Ext.getCmp('wo_tasks_movebutton').setDisabled(true);

          showPartMoveWin(partInst);

        <?php else: ?>
          Ext.Msg.alert('Permission Denied','You do not have permission to edit workorders');
        <?php endif; ?>
      }
    },'->',{
    text: 'Print Estimate',
    iconCls: 'print',
    handler: function(){
        new Ext.ux.EstimatePrintWin({
          workorder_id: <?php echo $workorder->getId(); ?>,
          pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
          gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
          pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
          gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
          shop_supplies_pct: <?php echo $workorder->getShopSuppliesSurcharge(); ?>,
          moorage_amt: '<?php echo $workorder->getMoorageSurchargeAmt(); ?>'
        });
    }
  },'-',{
    text: 'Print Summary',
    iconCls: 'print',
    handler: function(){
      new Ext.ux.IFrame({
        hidden: true,
        frameName: 'iframe-summaryprint',
        renderTo: Ext.getBody(),
        src: '<?php echo url_for('work_order/printsummary?id='.$workorder->getId()); ?>'
      });
    }
  }]
});


Ext.state.Manager.setProvider(new Ext.state.LocalStorageProvider());

var workorder_tree = new Ext.tree.TreePanel({
  id: 'workorder_tree_<?php echo $workorder->getId(); ?>',
  itemId: 'workorder_tree',
  rootVisible:false,
  border: false,
  flex: 1,
  stateful: true,
  stateId: 'woi-<?php echo $workorder->getId(); ?>',
  stateEvents: ['afteritemcollapse','afteritemexpand'],
  store: itemsStore,
  saveState: function () {
    var me = this,
      id = me.stateful && me.getStateId(),
      state;

    if (id) {
      state = me.getState() || [];    //pass along for custom interactions
      Ext.state.Manager.set(id, state);
    }
  },
  getState: function () {

    var ids = [];

    // Warning! Use private API: tree.flatten()
    var expanded = Ext.Array.filter(this.getStore().tree.flatten(), function (node) {
      return node.get('expanded') == true;
    });

    Ext.each(expanded, function (node) {
      if (node.getId() == 'root') return;
      ids.push(node.getId());
    });

    if (ids.length == 0) {
      ids = null;
    }

    return ids;
  },
  applyState: function () {
    var me = this,
      id = me.stateful && me.getStateId(),
      state,
      store = me.getStore(),
      node;

    if (id) {

      state = Ext.state.Manager.get(id);

      if (state) {
        state = Ext.apply([], state);

        Ext.each(state, function (id) {
          node = store.getNodeById(id);
          if (node) {
            node.bubble(function (node) {
              node.expand()
            });
          }
        });
      }
    }
  },

  viewConfig: {
    plugins: {
        ptype: 'treeviewdragdrop'
    },
    listeners: {
      beforeitemdblclick: function(v, record){
        var sel_id = record.data.id + '';
        var isleaf = record.data.leaf;

        if (/^expense-[0-9]+-[0-9]+$/.test(sel_id)) {
          sel_id = sel_id.replace(/^.+-([0-9]+)$/, '$1');
          new Ext.ux.ExpenseEditWin({
            title: 'Edit Expense',
            workorder_id: <?php echo $workorder->getId(); ?>,
            workorder_estimate: <?php echo ($workorder->isEstimate() ? 'true' : 'false'); ?>,
            pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
            gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
            pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
            gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
            formConfig: { 
              params: { expense_id: sel_id },
              autoLoadUrl: '<?php echo url_for('work_order/expenseload?id='.$workorder->getId()); ?>?expense_id=' + sel_id
            }
          });
        } else if (/^part-[0-9]+-[0-9]+$/.test(sel_id)) {
          sel_id = sel_id.replace(/^.+-([0-9]+)$/, '$1');
          if (record.data.custom){
            showPartCustomEditWin(sel_id);
          } else {
            showPartEditWin(sel_id);
          }
        } else if (/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-[0-9]+$/.test(sel_id)) {
          sel_id = sel_id.replace(/^.+-([0-9]+)$/, '$1');
          new Ext.ux.TimelogEditWin({
            title: 'Edit Timelog',
            workorder_id: <?php echo $workorder->getId(); ?>,
            formConfig: {
              params: { id: sel_id },
              autoLoadUrl: '<?php echo url_for('timelogs/load'); ?>?id=' + sel_id
            }
          });
        } else if (/^[0-9]+$/.test(sel_id) && record.isExpanded()) {
          win = new Ext.ux.ItemEditWin({
            title: 'Edit Task',
            workorder_id: this_workorder_id,
            color_codes: color_code_array,
            formConfig: {
              params: { item_id: sel_id },
              autoLoadUrl: '<?php echo url_for('work_order/itemload?id='.$workorder->getId()); ?>?item_id=' + sel_id
            }
          });
        } else if (/^(?:expense|part|labour)-[0-9]+$/.test(sel_id) && isleaf) {
          var focustype = sel_id.replace(/^(expense|part|labour)-[0-9]+$/, '$1');
          sel_id = sel_id.replace(/^.+-([0-9]+)$/, '$1');
          var win = new Ext.ux.ItemEditWin({
            title: 'Edit Task',
            workorder_id: this_workorder_id,
            color_codes: color_code_array,
            formConfig: {
              params: { item_id: sel_id },
              autoLoadUrl: '<?php echo url_for('work_order/itemload?id='.$workorder->getId()); ?>?item_id=' + sel_id
            }
          });          
          win.down('#' + focustype + 'estimate').focus(true, 200);
        } else {
          //let the node expand
          return true;
        }

        return (!isleaf && (!record.childNodes.length || !record.isExpanded()));
      },
      nodedragover: function(target, position, dragdata, e){
        //only allow labour, expenses, and parts to be dragged into folders that aren't their current parent
        var source = dragdata.records[0];
        var dest = target;


        if (!(/^[0-9]+$/.test(source.data.id))){
          //only allow folders to be positioned relative to other folders
          if (position != 'append') return false;

          npar = source.parentNode;
          while (npar){
            if (npar.data.id == dest.data.id) return false;
            npar = npar.parentNode;
          }
        } else {
          return false;
        }

        //prevent node expansion
        dragdata.view.plugins[0].dropZone.cancelExpand();

      },
      beforedrop: function(target, dragdata, targetModel, position, dropHandlers, e){
        var source = dragdata.records[0];
        var dest = targetModel;

        dropHandlers.wait = true;

        if (/^expense-[0-9]+-[0-9]+$/.test(source.data.id)) {
          //change position of expense item
          Ext.Msg.show({
            msg: 'Updating Expense\'s Task, Please Wait...',
            width: 300,
            wait: true
          });
          Ext.Ajax.request({
            url: '<?php echo url_for('work_order/expensemove'); ?>',
            method: 'POST',
            params: { 
              id: source.data.id.replace(/^expense-[0-9]+-([0-9]+)$/, '$1'), 
              target: dest.data.id 
            },
            success: function(){
              Ext.Msg.hide();
              reload_tree();
            },
            failure: function(){
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not move expense! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });
        } else if (/^part-[0-9]+-[0-9]+$/.test(source.data.id)) {
          //change position of part
          Ext.Msg.show({
            msg: 'Updating Part\'s Task, Please Wait...',
            width: 300,
            wait: true
          });
          Ext.Ajax.request({
            url: '<?php echo url_for('work_order/partmove'); ?>',
            method: 'POST',
            params: { 
              id: source.data.id.replace(/^part-[0-9]+-([0-9]+)$/, '$1'), 
              target: dest.data.id 
            },
            success: function(){
              Ext.Msg.hide();
              reload_tree();
            },
            failure: function(){
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not move part! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });
        } else if (/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-[0-9]+$/.test(source.data.id)) {
          //change position of timelog
          Ext.Msg.show({
            msg: 'Updating Timelog\'s Task, Please Wait...',
            width: 300,
            wait: true
          });
          Ext.Ajax.request({
            url: '<?php echo url_for('work_order/timelogmove'); ?>',
            method: 'POST',
            params: { 
              id: timelogid = source.data.id.replace(/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-([0-9]+)$/, '$1'),
              target: dest.data.id 
            },
            success: function(){
              Ext.Msg.hide();
              reload_tree();
            },
            failure: function(){
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not move timelog! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });
        } else if (/^[0-9]+$/.test(source.data.id)) {
          //change position of folder
          return false;
          /*
          Ext.Msg.show({
            msg: 'Updating Task Order, Please Wait...',
            width: 300,
            wait: true
          });
          Ext.Ajax.request({
            url: '<?php echo url_for('work_order/itemmove?id='.$workorder->getId()); ?>',
            method: 'POST',
            params: { 
              item_id: source.data.id, 
              point: position, 
              target: dest.data.id 
            },
            success: function(){
              Ext.Msg.hide();
              reload_tree();
            },
            failure: function(){
              Ext.Msg.hide();
              Ext.Msg.show({
                icon: Ext.MessageBox.ERROR,
                buttons: Ext.MessageBox.OK,
                msg: 'Could not reorder tasks! Reload page and try again.',
                modal: true,
                title: 'Error'
              });
              reload_tree();
            }
          });
          */
        }      
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      selectionchange: function(sm,node){
        node = sm.getSelection()[0];
        if (node){
          Ext.getCmp('wo_tasks_movebutton').setDisabled(true);

          if (/^[0-9]+$/.test(node.data.id)){
            Ext.getCmp('woi_copybutton').setDisabled(false);
            <?php if ($workorder->isInProgress() || $workorder->isEstimate()): ?>
              Ext.getCmp('woi_editbutton').setDisabled(false);
              Ext.getCmp('woi_deletebutton').setDisabled(false);
            <?php endif; ?>
          } else if (/^labour-[0-9]+-(?:estimate|(?:[0-9]+))-([0-9]+)$/.test(node.data.id)){
            <?php if ($workorder->isInProgress()): ?>
              Ext.getCmp('woi_editbutton').setDisabled(false);
              Ext.getCmp('woi_deletebutton').setDisabled(false);
            <?php endif; ?>
          } else if (/^part-[0-9]+-[0-9]+$/.test(node.data.id)) {
            <?php if ($workorder->isInProgress() || $workorder->isEstimate()): ?>
              Ext.getCmp('woi_editbutton').setDisabled(false);
              Ext.getCmp('woi_deletebutton').setDisabled(false);
              Ext.getCmp('wo_tasks_movebutton').setDisabled(false);
            <?php endif; ?>
          } else if (/^expense-[0-9]+-[0-9]+$/.test(node.data.id)) {
            <?php if ($workorder->isInProgress() || $workorder->isEstimate()): ?>
              Ext.getCmp('woi_editbutton').setDisabled(false);
              Ext.getCmp('woi_deletebutton').setDisabled(false);
            <?php endif; ?>
          } else {
            sm.deselectAll();
          }
        } else {
          <?php if ($workorder->isInProgress() || $workorder->isEstimate()): ?>
            Ext.getCmp('woi_editbutton').setDisabled(true);
            Ext.getCmp('woi_deletebutton').setDisabled(true);
          <?php endif; ?>
        }
      }
    } 
  }),

  columns: {
    defaults: { 
      sortable: false,
      menuDisabled: true
    },
    items: [{
      xtype: 'treecolumn',
      header:'Name',
      dataIndex:'text',
      width: 310
    },{
      header:'Estimate',
      width:80,
      dataIndex:'estimate',
      align: 'right',
      renderer: function(val){
        if (val > 0){
          <?php if ($workorder->getStatus() != 'Estimate'): ?>
            return '<span style="color: #888; font-style: italic;">'+Ext.util.Format.usMoney(val)+'</span>';
          <?php else: ?>
            return Ext.util.Format.usMoney(val);
          <?php endif; ?>
        } else {
          return '';
        }
      }
    },{
      header:'Actual',
      width:80,
      dataIndex:'actual',
      align: 'right',
      renderer: function(val){
        if (val > 0){
          return Ext.util.Format.usMoney(val);
        } else {
          return '';
        }
      }
    },{
      header:'Info',
      dataIndex:'info',
      width: 170
    }]
  }

});







var billtotals_template = new Ext.XTemplate(
  '<table class="totalstable">',
  '<tr><td class="label">Total Charges to Customer:</td><td class="subtotal">{custcharges}</td></tr>',
  '<tr><td class="label">Total Payments from Customer:</td><td class="fee">{payments}</td></tr>',
  '<tpl if="parseFloat(owing) == 0"><tr><td class="label">Amount Owing:</td><td class="total">{owing}</td></tr></tpl>',
  '<tpl if="parseFloat(owing) &gt; 0"><tr><td class="label">Amount Owing:</td><td class="total" style="color: #900">{owing}</td></tr></tpl>',
  '<tpl if="parseFloat(owing) &lt; 0"><tr><td class="label">Refund Owing:</td><td class="total" style="color: orange">{owing}</td></tr></tpl>',
  '</table>'
);


var billing_buttons = new Ext.Toolbar({

});

var billing_panel = new Ext.Panel({ 
  title: 'Invoices & Billing', 
  layout: {type: 'anchor'}, 
  autoScroll: true,
  border: false,
  bodyStyle: 'padding: 20px;',
  items: [{
    border: false,
    html: '<h2 style="margin-top: 0;">Total Charges & Payments</h2><p>Note: Only timelogs set as Approved will show up in Invoices!</p>'
  },{
    xtype: 'gridpanel',
    id: 'billing_grid',
    enableHdMenu: false,
    enableColumnMove: false,
    loadMask: true,
    emptyText: 'No Items!',
    store: billingStore,
    listeners: {
      'afterrender': function() {
        billingStore.load();
      }
    },
    columns: [{
      header: "Date",
      dataIndex: 'date',
      width: 130
    },{
      header: "Description",
      dataIndex: 'description',
      flex: 1
    },{
      header: 'Amount',
      dataIndex: 'amount',
      align: 'right',
      width: 80,
      renderer: function(val,meta,r){
        if (!r.data.payment_id && parseFloat(val) < 0){
          return '<span style="color: red">'+val+'</span>';
        } else {
          return val;
        }
      }
    }],
    selModel: new Ext.selection.RowModel({
      listeners: {
        selectionchange: function (sm, r){
          Ext.getCmp('billing_removebutton').setDisabled(sm.getCount() == 0 || (!sm.getSelection()[0].data.invoice_id && !sm.getSelection()[0].data.payment_id));
        }
      }
    })
  },{
    xtype: 'panel',
    id: 'billing_totals',
    layout: 'card',
    border: false
  }],

  tbar: {
    height: 27,
    items: [{
      text: 'Add Payment',
      iconCls: 'moneyadd',
      handler: function(){    
        <?php if ($sf_user->hasCredential('workorder_payment')): ?>
          new Ext.ux.WorkorderAddPaymentWin({ workorder_id: <?php echo $workorder->getId(); ?> });
        <?php else: ?>    
          Ext.Msg.alert('Permission Denied','You do not have permission to edit work orders');
        <?php endif; ?>
      }
    },'-',{
      text: 'Add Progress Billing',
      iconCls: 'moneyadd',
      handler: function(){    
        <?php if ($sf_user->hasCredential('workorder_payment')): ?>
          new Ext.ux.WorkorderAddInvoiceWin({ workorder_id: <?php echo $workorder->getId(); ?> });
        <?php else: ?>    
          Ext.Msg.alert('Permission Denied','You do not have permission to edit work orders');
        <?php endif; ?>
      }
    },'-',{      
      text: 'Delete Selected',
      id: 'billing_removebutton',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        selected = Ext.getCmp('billing_grid').getSelectionModel().getSelection()[0];
        if (!selected.data.payment_id && !selected.data.invoice_id){
          Ext.Msg.alert('Cannot Delete', 'You cannot delete the work order cost items.');
        } else {
          <?php if ($sf_user->hasCredential('workorder_payment')): ?>
          if (selected.data.payment_id){
            Ext.Msg.show( {
              icon: Ext.MessageBox.QUESTION,
              buttons: Ext.MessageBox.OKCANCEL,
              msg: 'Are you sure you want to delete this payment?',
              modal: true,
              title: 'Delete Payment',
              fn: function(butid){
                if (butid == 'ok'){
                  Ext.Msg.show({title:'Please Wait',msg:'Deleting Payment, please wait...', closable: false});
                  Ext.Ajax.request({
                    url: '<?php echo url_for('work_order/deletepayment?id='.$workorder->getId()); ?>',
                    params: {payment_id: selected.data.payment_id},
                    method: 'POST',
                    success: function(){
                      Ext.Msg.hide();
                      Ext.getCmp('billing_grid').getStore().load();
                    },
                    failure: function(){
                      Ext.Msg.hide();
                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: 'Could not delete payment!',
                        modal: true,
                        title: 'Error'
                      });
                    }
                  });
                }
              }
            });
          } else if (selected.data.invoice_id) {
            Ext.Msg.show( {
              icon: Ext.MessageBox.QUESTION,
              buttons: Ext.MessageBox.OKCANCEL,
              msg: 'Are you sure you want to delete this progress billing?',
              modal: true,
              title: 'Delete Progress Billing',
              fn: function(butid){
                if (butid == 'ok'){
                  Ext.Msg.show({title:'Please Wait',msg:'Deleting Progress Billing, please wait...', closable: false});
                  Ext.Ajax.request({
                    url: '<?php echo url_for('work_order/deleteinvoice?id='.$workorder->getId()); ?>',
                    params: {invoice_id: selected.data.invoice_id},
                    method: 'POST',
                    success: function(){
                      Ext.Msg.hide();
                      Ext.getCmp('billing_grid').getStore().load();
                    },
                    failure: function(){
                      Ext.Msg.hide();
                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: 'Could not delete progress billing!',
                        modal: true,
                        title: 'Error'
                      });
                    }
                  });
                }
              }
            });
          }
          <?php else: ?>
            Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete payments.');
          <?php endif; ?>
        } 
      }
    },'->',{
      <?php if ($workorder->isEstimate()): ?>
        text: 'Generate Printable Estimate',
        iconCls: 'print',
        handler: function(){
          new Ext.ux.EstimatePrintWin({
            workorder_id: <?php echo $workorder->getId(); ?>,
            pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
            gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
            pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
            gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
            shop_supplies_pct: <?php echo $workorder->getShopSuppliesSurcharge(); ?>,
            moorage_amt: '<?php echo $workorder->getMoorageSurchargeAmt(); ?>'
          });
        }
      <?php else: ?>
        text: 'Generate Printable Invoice', 
        iconCls: 'print',
        handler: function(){
          var win = new Ext.ux.WorkorderPrintWin({
            workorder_id: <?php echo $workorder->getId(); ?>,
            pst_rate: <?php echo sfConfig::get('app_pst_rate'); ?>,
            gst_rate: <?php echo sfConfig::get('app_gst_rate'); ?>,
            pst_exempt: <?php echo ($workorder->getPstExempt() ? 'true' : 'false'); ?>,
            gst_exempt: <?php echo ($workorder->getGstExempt() ? 'true' : 'false'); ?>,
            shop_supplies_pct: <?php echo $workorder->getShopSuppliesSurcharge(); ?>,
            moorage_amt: '<?php echo $workorder->getMoorageSurchargeAmt(); ?>'
          });
        }    
      <?php endif; ?>
    }]
  }
});

var reports_profit_store = new Ext.data.JsonStore({
  fields: ['name', 'angle', 'amt']
});

var reports_values_store = new Ext.data.JsonStore({
  fields: ['name', 'angle']
});

var reports_tasks_store = new Ext.data.JsonStore({
  fields: ['name', 'amount']
});

var reports_panel = new Ext.panel.Panel({
  disabled: <?php echo ($sf_user->hasCredential('reports_view') ? 'false' : 'true'); ?>,
  title: 'Reports',
  layout: 'fit',
  border: false,
  listeners: { 
    render: function(){ 
      this.add(new Ext.ux.WorkorderReportPanel({
        workorder_id: this_workorder_id
      }))
    }
  }
 });

var workorder_tabs = new Ext.TabPanel({
  activeTab: <?php echo (($workorder->getStatus() == 'Completed' || $workorder->getStatus() == 'Cancelled') && ($workorder->getCustomerNotes() || $workorder->getInternalNotes()) ? 3 : 0); ?>,
  height: 500,
  plain: true,
  padding: '15 0 0 0',
  items:[
    { id: 'tabgridbar', title: 'Workorder Tasks', layout: {type: 'vbox', align: 'stretch'}, items: [workorder_tbar, workorder_tree, workorder_bbar], border: false},
    parts_list,
    timelogs_list,
    notes_panel,
    billing_panel,
    reports_panel
  ],
  listeners: {
    tabchange: function(panel,newitem,olditem){
      if (newitem && newitem.id == 'tabgridbar'){
        Ext.get('treetips').show();
      } else {
        Ext.get('treetips').hide();
      }

    }
  }

});

var workorder_toolbar = new Ext.Toolbar({
  height: 27,
  items: [{
    text: 'Edit Workorder Status & Info',
    iconCls: 'personedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_approve')): ?>
        new Ext.ux.WorkorderEditWin();
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to edit work order status');
      <?php endif; ?>
    }
  },'-',{
    text: 'Delete Workorder',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('workorder_approve')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this workorder? You might want to instead consider changing the status to "Cancelled".<br /><br />You cannot delete a workorder if it has any Timelogs or Utilized Parts or Expenses added to it. Deleting is only intended for removing a workorder that was created in error, or for an Estimate that is no longer needed.',
          modal: true,
          title: 'Delete Workorder',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('work_order/delete?id='.$workorder->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg: "Deleted Workorder. Redirecting..."});
                  myMask.show();
                  location.href = '<?php echo url_for('work_order/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    width: 350,
                    msg: 'Can not delete workorder!<br /><br />Workorder has delivered/utilized parts, expenses, timelogs, or payments and therefore cannot be deleted.',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied','You do not have permission to delete workorders');
      <?php endif; ?>
    }
  },'->',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }]
});

//this only fires if sale is inactive
var partadd_selected_data = false;
var partaddlistener = function(code,symbid){
  Ext.Msg.show({
    closable: false,
    msg: 'Looking Up Scanned Barcode...',
    title: 'Please Wait'
  });
  //query for the part
  Ext.Ajax.request({
    url: '/part/datagrid',
    params: { code: code, symbid: symbid, show_pricing: 1 },
    callback : function (opt,success,response){
      Ext.Msg.hide(); 
      if (success){
        data = Ext.decode(response.responseText);
        if (data && data.parts.length > 0){
          if (data.parts && data.parts.length == 1){
            partadd_selected_data = data.parts[0];

              //if window is already open
              var win = null;
              if (PartAddWin.isVisible()) {
                PartAddWin.hide();
                showPartEditWin(null, partadd_selected_data);
              } else if (win = (Ext.ComponentQuery.query('parteditwin[isVisible]')[0])) {
                if (win.form.params.part_variant_id == partadd_selected_data.part_variant_id) {
                  //INCREASE QUANTITY
                  win.form.down('#quantity').setValue(parseFloat(win.form.down('#quantity').getValue()) + 1);
                  win.form.down('#quantity').labelEl.highlight("99ee99", { duration: 1500 });
                  //FOCUS NEXT BLANK SERIAL FIELD
                  if (win.form.down('#serial').isVisible()){
                    win.form.down('#serial').focus(true, 200);
                  }
                } else {
                  Ext.Msg.confirm('Cancel Existing Part Add?', 
                    'Add Part window is already open, adding a different part. Close window to add scanned part?',
                    function (btn){
                      if (btn == 'yes'){
                        win.close();
                        showPartEditWin(null, partadd_selected_data);
                      }
                    }
                  );
                }
              } else {
                //OPEN WINDOW
                showPartEditWin(null, partadd_selected_data);
              }
          } else {
            Ext.Msg.alert('Multiple Parts Found', 'Error: could not select part; matched multiple parts!');
          }      
        } else if (barcodeListener.misshandleroverride) {
          barcodeListener.misshandleroverride(data, code, symbid);
        }
      }
    }
  });
};

Ext.onReady(function(){
  <?php if ($workorder->isInProgress() || $workorder->isEstimate()): ?>
    barcodeListener.handleroverride = partaddlistener;
  <?php endif; ?>

  workorder_tabs.render('workorder_tabs');
  workorder_toolbar.render('view-toolbar');
});

</script>
