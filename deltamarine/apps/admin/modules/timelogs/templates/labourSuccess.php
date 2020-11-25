<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Timelogs Admin', 'timelogs/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-group">View Labour Types</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

laboursStore = new Ext.data.JsonStore({
  fields: ['id','name','desc','rate','active'],
  pageSize: 1000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('timelogs/labourDatagrid'); ?>',
    extraParams: { showall: 1},
    simpleSortMode: true,
    reader: { 
      root: 'labourtypes',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var LabourEditWin = new Ext.Window({
  title: 'Labour Type',
  closable: false,
  width: 450,
  height: 175,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'laboureditform',
    url: '<?php echo url_for('timelogs/labourEdit'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 120 },
    items: [{
      xtype: 'textfield',
      id: 'editform_labourfield',
      fieldLabel: 'Labour Type Name',
      allowBlank: false,
      name: 'name',
      anchor: '-25'
    },{
      xtype: 'numberfield',
      name: 'rate',
      fieldLabel: 'Billed Rate ($/hr)',
      allowBlank: false,
      minValue: 0,
      maxValue: 1000,
      forcePrecision: true,
      anchor: '-175'
    },{
      xtype: 'acbuttongroup',
      fieldLabel: 'Active',
      name: 'active',
      anchor: '-175',
      value: '1',
      items: [
        { value: '1', text: 'Yes' },
        { value: '0', text: 'No' }
      ]
    },{
      xtype: 'hidden',
      name: 'id'
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        LabourEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Labour Type...',
          success:function(form,action){
            laboursStore.load();
            form.reset();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save labour type. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ LabourEditWin.show(); },
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
  minHeight: 550,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  store: laboursStore,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[
  {
    header: "Labour Type Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    flex: 3,
    renderer: function(val, obj, r)
    {
      if (r.data.active) return val;
      else return '<span style="color: #c00;">' + val + '</span>';
    }
  },{
    header: "Active",
    dataIndex: 'active',
    hideable: false,
    align: 'center',
    flex: 1,
    renderer: function(val){
      return (val ? 'Yes' : '<span style="color: #c00;">No</span>');
    }
  },{
    header: "Rate",
    dataIndex: 'rate',
    align: 'center',
    sortable: true,
    renderer: function(val){
      return '$'+val+' /hr';
    },
    flex: 1
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Labour Type',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        LabourEditWin.show();
        Ext.getCmp('laboureditform').form.loadRecord(grid.getSelectionModel().getSelection()[0]);
        Ext.getCmp('editform_labourfield').focus(true, 200);
      }
    },'-',{
      text: 'Delete Labour Type',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this labour type?<br /><br />Any timelogs using these labour types will remain unchanged, but will be marked as having an "Unknown" labour type from now on. The cost of the timelogs as billed will remain as-is.',
          modal: true,
          title: 'Delete Labour Type',
          fn: function(butid){
            if (butid == 'ok'){
              selected = grid.getSelectionModel().getSelection()[0].data.id;
              Ext.Ajax.request({
                url: '<?php echo url_for('timelogs/labourDelete?id='); ?>' + selected,
                method: 'POST',
                success: function(){
                  laboursStore.load();
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete labour type!',
                    modal: true,
                    title: 'Error'
                  });
                }
              });
            }
          }
        });
      }
    },'->',{
      text: 'Add Labour Type',
      iconCls: 'add',
      handler: function(){
        LabourEditWin.show();
        Ext.getCmp('editform_labourfield').focus(true, 200);
      }
    }]
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        Ext.getCmp('delete_button').setDisabled(sm.getCount() != 1);
        Ext.getCmp('edit_button').setDisabled(sm.getCount() != 1);
      }
    }
  }),

  listeners: {
    'beforerender': function(grid){
      grid.getStore().loadRawData(<?php
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('sort', 'name');
        $inst->getRequest()->setParameter('dir', 'ASC');
        $inst->getRequest()->setParameter('showall', '1');
        $inst->getController()->getPresentationFor('timelogs','labourDatagrid');
     ?>);
    },
    'itemdblclick': function(grid, record){
        LabourEditWin.show();
        Ext.getCmp('laboureditform').form.loadRecord(record);
        Ext.getCmp('editform_labourfield').focus(true, 200);
      }
  }

});


Ext.onReady(function(){

  grid.render('index-grid');

});

</script>
