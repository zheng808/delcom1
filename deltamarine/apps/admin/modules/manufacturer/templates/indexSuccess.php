<div class="leftside" style="padding-top: 36px;">
  <div id="index-filter"></div>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-company">View Manufacturers</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var manufacturersStore = new Ext.data.JsonStore({
  fields: ['id','name','phone','count'],
  remoteSort: true,
  pageSize: 25,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('manufacturer/datagrid'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'manufacturers',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var ManufacturerAddWin = new Ext.Window({
  title: 'Add Manufacturer',
  closable: false,
  width: 700,
  height: 350,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'manufactureraddform',
    url: '<?php echo url_for('manufacturer/add'); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: {labelAlign: 'top'},
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.6,
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
          id: 'manuadd_namefield',
          fieldLabel: 'Manufacturer Name',
          allowBlank: false,
          name: 'department_name',
          anchor: '-25'
        },{
          layout: 'column',
          border: false,
          items: [{
            border: false,
            columnWidth: 0.5,
            layout: 'anchor',
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Work Phone',
              name: 'work_phone',
              anchor: '-25'
            },{
              xtype: 'textfield',
              fieldLabel: 'Email',
              vtype: 'email',
              name: 'email',
              anchor: '-25'
            }]
          },{
            border: false,
            columnWidth: 0.5,
            layout: 'anchor',
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Fax',
              name: 'fax',
              anchor: '-25'
            },{
              xtype: 'textfield',
              vtype: 'url',
              fieldLabel: 'Website',
              name: 'homepage',
              anchor: '-25'
            }]
          }]
        },{
          fieldLabel: 'Manufacturer Notes',
          xtype: 'textarea',
          name: 'private_notes',
          anchor: '-25',
          height: 85
        }]
      },{
        border: false,
        columnWidth: 0.4,
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
          fieldLabel: 'Address Line 1',
          name: 'address_line1',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Address Line 2',
          name: 'address_line2',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Town/City',
          name: 'address_city',
          anchor: '-25'
        },{
          layout: 'column',
          border: false,
          items: [{
            layout: 'anchor',
            columnWidth: 0.3,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Prov.',
              maxLength: 3,
              name: 'address_region',
              anchor: '-25'
            }]
          },{
            layout: 'anchor',
            columnWidth: 0.4,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Postal',
              name: 'address_postal',
              maxLength: 16,
              anchor: '-25'
            }]
          },{
            layout: 'anchor',
            columnWidth: 0.3,
            border: false,
            items: [{
              xtype: 'textfield',
              fieldLabel: 'Country',
              name: 'address_country',
              value: 'CA',
              maxLength: 2,
              anchor: '-25'
            }]
          }]
        }]
      }]
    }],    

    buttons:[{
      text: 'Add',
      formBind: true,
      handler:function(){
        ManufacturerAddWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Manufacturer...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {
              msg: "Reloading Page"});
            myMask.show();
            location.href = '<?php echo url_for('manufacturer/index'); ?>';
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not add manufacturer. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ ManufacturerAddWin.show(); },
              modal: true,
              title: 'Oops',
              icon: Ext.MessageBox.ERROR,
              buttons: Ext.MessageBox.OK,
              msg: myMsg
            });
          }
        });
      }
    },{
      text: 'Cancel',
      handler:function(){
        this.findParentByType('window').hide();
        this.findParentByType('form').getForm().reset();
      }
    }]
  })
});

var grid = new Ext.grid.GridPanel({
  bodyCls: 'indexgrid',
  minHeight: 500,
  store: manufacturersStore,
  enableColumnMove: false,
  viewConfig: { stripeRows: true, loadMask: true },

  columns:[
  {
    header: "Manufacturer Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    sortType: 'asUCText',
    flex: 2
  },{
    header: "Phone Number",
    dataIndex: 'phone',
    sortable: false,
    flex: 1
  },{
    header: "# Parts",
    dataIndex: 'count',
    sortable: false,
    width: 60,
    xtype: 'numbercolumn',
    format: 0,
    align: 'center'
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: ['->',{
      text: 'Add A New Manufacturer',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_manufacturer_edit')): ?>
          ManufacturerAddWin.show();
          Ext.getCmp('manuadd_namefield').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to add a manufacturer');
        <?php endif; ?>
      }
    }]
  }),

  bbar: new Ext.PagingToolbar({
    id: 'index_pager',
    store: manufacturersStore,
    displayInfo: true,
    displayMsg: 'Displaying Manufacturers {0} - {1} of {2}',
    emptyMsg:   'No Manufacturers Available'
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Manufacturer Information..."});
        myMask.show();
        location.href= '<?php echo url_for('manufacturer/view?id='); ?>' + record.data.id ;
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
        $inst->getController()->getPresentationFor('manufacturer','datagrid');
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
  title: 'Filter Manufacturers',
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
