<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Workorders List', 'work_order/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-group">View Workorder Categories</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

catsStore = new Ext.data.JsonStore({
  fields: ['id','name'],
  pageSize: 1000,
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('work_order/categoryDatagrid'); ?>',
    simpleSortMode: true,
    reader: { 
      root: 'categories',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});

var CategoryEditWin = new Ext.Window({
  title: 'Edit Workorder Category',
  closable: false,
  width: 500,
  height: 125,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'categoryeditform',
    url: '<?php echo url_for('work_order/categoryEdit'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 160 },
    items: [{
      xtype: 'textfield',
      id: 'editform_categoryfield',
      fieldLabel: 'Workorder Category Name',
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
        CategoryEditWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Saving Category...',
          success:function(form,action){
            catsStore.load();
            form.reset();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save category. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ CategoryEditWin.show(); },
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
  store: catsStore,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[
  {
    header: "Workorder Category Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    flex: 3
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Workorder Category',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        CategoryEditWin.show();
        Ext.getCmp('categoryeditform').form.loadRecord(grid.getSelectionModel().getSelection()[0]);
        Ext.getCmp('editform_categoryfield').focus(true, 200);
      }
    },'-',{
      text: 'Delete Workorder Category',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this workorder category?<br /><br />Any workorders using this category will remain unchanged, but will be marked as being "Uncategorized".',
          modal: true,
          title: 'Delete Workorder Category',
          fn: function(butid){
            if (butid == 'ok'){
              selected = grid.getSelectionModel().getSelection()[0].data.id;
              Ext.Ajax.request({
                url: '<?php echo url_for('work_order/categoryDelete?id='); ?>' + selected,
                method: 'POST',
                success: function(){
                  catsStore.load();
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete category!',
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
      text: 'Add Workorder Category',
      iconCls: 'add',
      handler: function(){
        CategoryEditWin.show();
        Ext.getCmp('editform_categoryfield').focus(true, 200);
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
        $inst->getController()->getPresentationFor('work_order','categoryDatagrid');
     ?>);
    }
  }

});


Ext.onReady(function(){

  grid.render('index-grid');

});

</script>