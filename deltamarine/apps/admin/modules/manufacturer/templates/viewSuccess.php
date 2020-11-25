<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Manufacturer List', 'manufacturer/index', 
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-company">Manufacturer: <?php echo $manufacturer; ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <?php 
      include_partial('wfCRMPlugin/crm_show', array('contact' => $manufacturer->getCRM(),
        'include_title' => false));
    ?>

    <div id="view-tabs"></div>

  </div>
</div>

<?php include_partial('wfCRMPlugin/crm_company_ext_tree', array('credential' => 'parts_manufacturer_edit', 'contact' => $manufacturer->getCRM())); ?>

<script type="text/javascript">
var partsStore = new Ext.data.JsonStore({
  fields: ['part_id', 'name', 'sku', 'quantity', 'min_quantity'],
  remoteSort: true,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/datagrid?manufacturer_id='.$manufacturer->getId()); ?>',
    simpleSortMode: true,
    reader: {
      root: 'parts',
      totalProperty: 'totalCount',
      idProperty: 'part_id'
    }
  }
});

var man_parts = new Ext.grid.GridPanel({
  title: 'Manufacturer Parts',
  store: partsStore,
  enableColumnMove: false,
  loadMask: true,

  columns:[{
    header: "Part Name",
    dataIndex: 'name',
    hideable: false,
    sortType: 'asUCText',
    sortable: true,
    flex: 1
  },{
    header: "SKU",
    dataIndex: 'sku',
    sortType: 'asUCText',
    sortable: true,
    width: 100,
  },{
    header: "Qty",
    dataIndex: 'quantity',
    renderer: function(value, metaData, record, rowIndex, colIndex, store) {
        if (value < record.data.min_quantity){
          return '<span style="color:red;">' + value + '<\/span>';
        }else{
           return value;
        }
      },
    sortable: true,
    width: 40
  },{
    header: "Min",
    dataIndex: 'min_quantity',
    xtype: 'numbercolumn',
    format: 0,
    sortable: true,
    width: 40
  }],

  viewConfig: { 
    stripeRows: true
  },

  bbar: new Ext.PagingToolbar({
    pagesize: 50,
    store: partsStore,
    displayInfo: true,
    displayMsg: 'Displaying Parts {0} - {1} of {2}',
    emptyMsg: 'No Parts for this Manufacturer'
  }),

  listeners: {
    afterrender: {
      scope: this, 
      single: true, 
      fn: function() {
        partsStore.load({params:{start:0, limit:50}});
      }
    }
  },

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        <?php if ($sf_user->hasCredential('parts_view')): ?>
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading Part Information..."});
          myMask.show();
          location.href= '<?php echo url_for('part/view?id='); ?>' + record.data.part_id ;
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to view parts');
        <?php endif; ?>
      }
    }
  }) 
});

var ManufacturerEditWin = new Ext.Window({
  title: 'Edit Manufacturer Info',
  closable: false,
  width: 700,
  height: 350,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'manufacturereditform',
    url: '<?php echo url_for('manufacturer/edit?id='.$manufacturer->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'top' },
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.6,
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
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
      text: 'Save',
      formBind: true,
      handler:function(){
        ManufacturerEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Changes...',
          success:function(form,action){
            var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
            myMask.show();
            location.href = '<?php echo url_for('manufacturer/view?id='.$manufacturer->getId()); ?>';
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save changes. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ ManufacturerEditWin.show(); },
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

var tb = new Ext.Toolbar({
  width: 'auto',
  height: 27,
  items: [{
    text: 'Update Manufacturer Information',
    iconCls: 'buildingedit',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_manufacturer_edit')): ?>
        ManufacturerEditWin.show();
        Ext.getCmp('manufacturereditform').setDisabled(true);
        Ext.getCmp('manufacturereditform').load({
          url: '<?php echo url_for('manufacturer/load?id='.$manufacturer->getId()); ?>',
          failure: function (form, action){
            Ext.Msg.alert("Load Failed", "Could not load manufacturer info for editing");
            Ext.getCmp('manufacturereditform').setDisabled(false);
            ManufacturerEditWin.hide();
          },
          success: function (){
            Ext.getCmp('manufacturereditform').setDisabled(false);
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to edit manufacturer information');
      <?php endif; ?>
    }
  },'-',{
    text: 'Delete Manufacturer',
    iconCls: 'delete',
    handler: function(){
      <?php if ($sf_user->hasCredential('parts_manufacturer_edit')): ?>
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this manufacturer?<br /><br />All parts from this manufacturer will still exist, however this manufacturer will be removed from them.',
          modal: true,
          title: 'Delete Manufacturer',
          fn: function(butid){
            if (butid == 'ok'){
              Ext.Ajax.request({
                url: '<?php echo url_for('manufacturer/delete?id='.$manufacturer->getId()); ?>',
                method: 'POST',
                success: function(){
                  var myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg: "Deleting Manufacturer..."});
                  myMask.show();
                  location.href = '<?php echo url_for('manufacturer/index'); ?>';
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete manufacturer!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      <?php else: ?>
        Ext.Msg.alert('Permission Denied', 'Your user does not have permission to delete a manufacturer');
      <?php endif; ?>
    }
  },'-',{
    text: 'View Change History',
    disabled: true,
    iconCls: 'history'
  }] 
});

var manufacturer_tabs = new Ext.TabPanel({
    activeTab: 1,
    height:300,
    padding: '15 0 0 0',
    plain:true,
    items:[
      { title: 'Company Contacts', layout: 'fit', items: crm_companytree, border: false, disabled: true},
      man_parts
    ]
});

Ext.onReady(function(){

  tb.render('view-toolbar');
  manufacturer_tabs.render('view-tabs');

});


</script>
