<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Parts List', 'part/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-group">Edit Part Categories</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var categoriesStore = new Ext.data.TreeStore({
  root: { 
    text: 'Top-Level',
    id: 1,
    expanded: true,
    categories: <?php     
        //load the initial data
        $inst = sfContext::getInstance();
        $inst->getRequest()->setParameter('node', 1);
        $inst->getController()->getPresentationFor('part','categoriestree');
    ?>
  },
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('part/categoriestree'); ?>',
    reader: {
      root: 'categories'
    }
  }
});


var CategoryEditWin = new Ext.Window({
  title: 'Category',
  closable: false,
  width: 450,
  height: 150,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    id: 'categoryeditform',
    url: '<?php echo url_for('part/categoryEdit'); ?>',
    bodyStyle: 'padding: 15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'left', labelWidth: 120 },
    items: [{
      xtype: 'textfield',
      id: 'categoryedit_namefield',
      fieldLabel: 'Category Name',
      allowBlank: false,
      name: 'category_name',
      anchor: '-25'
    },{
      xtype: 'treecombo',
      id: 'categoryedit_parentfield',
      panelWidth: 300,
      panelMaxHeight: 300,
      fieldLabel: 'Parent Category',
      name: 'parent_id',
      allowBlank: false,
      selectChildren: false,
      canSelectFolders: true,
      anchor: '-25',
      rootVisible: true,
      store: categoriesStore
    },{
      xtype: 'hidden',
      name: 'category_id'
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
            categoriesStore.load();
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

var tree = new Ext.tree.TreePanel({
  minHeight: 500,
  bodyCssClass: 'indexgrid',
  lines: true,
  rootVisible: false,
  store: categoriesStore,
  viewConfig: { loadMask: true, stripeRows: true},

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Category',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_category_edit')): ?>
          CategoryEditWin.show();
          selected = tree.getSelectionModel().getSelection()[0].data.id;
          form = Ext.getCmp('categoryeditform');
          form.setDisabled(true);
          form.load({
            url: '<?php echo url_for('part/categoryLoad?category_id='); ?>' + selected,
            failure: function (form, action){
              Ext.Msg.alert("Load Failed", "Could not load category info for editing");
              Ext.getCmp('categoryeditform').setDisabled(false);
              CategoryEditWin.hide();
            },
            success: function (){
              Ext.getCmp('categoryeditform').setDisabled(false);
            }
          });
          form.getForm().findField('category_id').setRawValue(selected);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to edit part categories.');
        <?php endif; ?>
      }
    },'-',{
      text: 'Delete Category',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
      <?php if ($sf_user->hasCredential('parts_category_edit')): ?>
          Ext.Msg.show({
            icon: Ext.MessageBox.QUESTION,
            buttons: Ext.MessageBox.OKCANCEL,
            msg: 'Are you sure you want to delete this category?<br /><br />Any sub-categories and parts in this will be moved to the parent of the deleted category.',
            modal: true,
            title: 'Delete Category',
            fn: function(butid){
              if (butid == 'ok'){
                selected = tree.getSelectionModel().getSelection()[0].data.id;
                Ext.Ajax.request({
                  url: '<?php echo url_for('part/categoryDelete?id='); ?>' + selected,
                  method: 'POST',
                  success: function(){
                    categoriesStore.load();
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
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to edit part categories.');
        <?php endif; ?>
      }
    },'->',{
      text: 'Add Category',
      iconCls: 'add',
      handler: function(){
        <?php if ($sf_user->hasCredential('parts_category_edit')): ?>
          CategoryEditWin.show();
          Ext.getCmp('categoryedit_namefield').focus(true,200);
          if (tree.getSelectionModel().getSelection().length > 0)
          {
            selected = tree.getSelectionModel().getSelection()[0].data.id;
            Ext.getCmp('categoryedit_parentfield').setValue(selected);
          }
        <?php else: ?>
          Ext.Msg.alert('Permission Denied','Your user not have permission to edit part categories.');
        <?php endif; ?>
      }
    }]
  }),

  selModel: new Ext.selection.RowModel({
    listeners: {
      select: function(sm, record){
        Ext.getCmp('delete_button').setDisabled(!record);
        Ext.getCmp('edit_button').setDisabled(!record);
      }
    }
  })

});


Ext.onReady(function(){

  tree.render('index-grid');

});

</script>
