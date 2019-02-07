<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Timelogs Admin', 'timelogs/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-group">View Non-Billable Labour Types</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

nonbillsStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  pageSize: 1000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('timelogs/nonbillDatagrid'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'nonbilltypes',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var NonbillEditWin = new Ext.Window({
  title: 'Edit Non-Billable Type',
  closable: false,
  width: 500,
  height: 125,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'nonbilleditform',
    url: '<?php echo url_for('timelogs/nonbillEdit'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 160 },
    items: [{
      xtype: 'textfield',
      id: 'editform_nonbillfield',
      fieldLabel: 'Non-Billable Type Name',
      allowBlank: false,
      name: 'name',
      anchor: '-25'
    },{
      xtype: 'hidden',
      name: 'id'
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        NonbillEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Non-Billable Type...',
          success:function(form,action){
            nonbillsStore.load();
            form.reset();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save non-billable type. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ NonbillEditWin.show(); },
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
  store: nonbillsStore,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[
  {
    header: "Non-Billable Type Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    flex: 3
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Non-Billable Type',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        NonbillEditWin.show();
        Ext.getCmp('nonbilleditform').form.loadRecord(grid.getSelectionModel().getSelection()[0]);
        Ext.getCmp('editform_nonbillfield').focus(true, 200);
      }
    },'-',{
      text: 'Delete Non-Billable Type',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this non-billable type?<br /><br />Any timelogs using these non-billable types will remain unchanged, but will be marked as having an "Unknown" labour type from now on. The cost of the timelogs as billed will remain as-is.',
          modal: true,
          title: 'Delete Non-Billable Type',
          fn: function(butid){
            if (butid == 'ok'){
              selected = grid.getSelectionModel().getSelection()[0].data.id;
              Ext.Ajax.request({
                url: '<?php echo url_for('timelogs/nonbillDelete?id='); ?>' + selected,
                method: 'POST',
                success: function(){
                  nonbillsStore.load();
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete non-billable type!',
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
      text: 'Add Non-Billable Type',
      iconCls: 'add',
      handler: function(){
        NonbillEditWin.show();
        Ext.getCmp('editform_nonbillfield').focus(true, 200);
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
        $inst->getRequest()->setParameter('sort', 'date');
        $inst->getRequest()->setParameter('dir', 'DESC');
        $inst->getController()->getPresentationFor('timelogs','nonbillDatagrid');
     ?>);
    }
  }

});


Ext.onReady(function(){

  grid.render('index-grid');

});

</script>
