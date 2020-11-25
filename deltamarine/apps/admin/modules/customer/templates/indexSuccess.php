<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-person">View Customers</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">
var customersStore = new Ext.data.JsonStore({
  fields: ['id','name','phone','email'],
  remoteSort: true,
  pageSize: 25,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('customer/datagrid'); ?>',
    simpleSortMode: true,
    reader: {
      root: 'customers',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var grid = new Ext.grid.GridPanel({
  bodyCls: 'indexgrid',
  minHeight: 500,
  store: customersStore,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[
  {
    header: "Customer Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    sortType: 'asUCText',
    flex: 1
  },{
    header: "Phone Number",
    dataIndex: 'phone',
    sortable: true,
    width: 120
  },{
    header: "Email",
    dataIndex: 'email',
    sortable: false,
    width: 150
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add A New Customer',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('customer_edit')): ?>
          new Ext.ux.CustomerEditWin({
            formConfig: { 
              formSuccess: function(){
                var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
                myMask.show();
                location.href = '<?php echo url_for('customer/index'); ?>';
              }
            }
          });
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user does not have permission to add a customer');
        <?php endif; ?>
      }
    }]
  }),

  bbar: new Ext.PagingToolbar({
    id: 'index_pager',
    store: customersStore,
    displayInfo: true,
    displayMsg: 'Displaying Customers {0} - {1} of {2}',
    emptyMsg:   'No Customers Available'
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Customer Information..."});
        myMask.show();
        location.href= '<?php echo url_for('customer/view?id='); ?>' + record.data.id ;
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('limit', 25);
        $inst->getRequest()->setParameter('sort', 'name');
        $inst->getRequest()->setParameter('dir', 'ASC');
        $inst->getController()->getPresentationFor('customer','datagrid');
     ?>);
    }
  }

});

var updateFilterVal = function(field){
  if (grid.store.proxy.extraParams[field.paramField]){
    oldval = grid.store.proxy.extraParams[field.paramField];
  } else {
    oldval = '';
  }
  newval = field.getValue();
  if (oldval != newval)
  {
    grid.store.proxy.setExtraParam(field.paramField, newval);
    Ext.getCmp('index_pager').moveFirst();
  }
};

var filter = new Ext.Panel({
  width: 225,
  title: 'Filter Customers',
  items: [{
    xtype: 'panel',
    layout: 'anchor',
    id: 'filter_form',
    border: false,
    bodyStyle: 'padding: 10px;',
    fieldDefaults: { labelWidth: 60 },
    items: [{
      id: 'filter_name',
      xtype: 'textfield',
      fieldLabel: 'Name',
      paramField: 'query',
      anchor: '-1',
      enableKeyEvents: true,
      listeners: { 'keyup': updateFilterVal, 'blur': updateFilterVal }
    }]
  }],

  bbar: new Ext.Toolbar({
    items: ['->',{
      text:'Reset',
      iconCls: 'undo',
      handler: function(){
        Ext.getCmp('filter_name').reset();
        grid.store.proxy.setExtraParam('query', null);
        Ext.getCmp('index_pager').moveFirst();
      }
    }]
  })
});

Ext.onReady(function(){

  filter.render('index-filter');
  grid.render('index-grid');

});

</script>
