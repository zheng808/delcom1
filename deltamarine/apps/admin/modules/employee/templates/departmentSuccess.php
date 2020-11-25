<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Employee List', 'employee/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-group">View Departments</h1>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var departmentsStore = new Ext.data.JsonStore({
  fields: ['id','name','employees'],
  sorters: [{ property: 'name', direction: 'ASC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('employee/departmentDatagrid'); ?>',
    reader: {
      root: 'departments',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var DepartmentEditWin = new Ext.ux.acFormWindow({
  title: 'Department',
  width: 300,

  formConfig: {
    url: '<?php echo url_for('employee/departmentEdit'); ?>',
    items: [{
      xtype: 'hidden',
      name: 'id'
    },{
      xtype: 'textfield',
      fieldLabel: 'Department Name',
      allowBlank: false,
      initialFocus: true,
      name: 'name',
      anchor: '-25'
    }],
    formSuccess:function(form,action){
      departmentsStore.load();
      form.reset();
    }
  }
});

var grid = new Ext.grid.GridPanel({
  minHeight: 500,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  store: departmentsStore,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[{
    id: 'deptname',
    header: "Department Name",
    dataIndex: 'name',
    sortable: true,
    hideable: false,
    flex: 1
  },{
    header: "# Employees",
    dataIndex: 'employees',
    align: 'center',
    sortable: true,
    xtype: 'numbercolumn',
    format: 0,
    width: 100
  }],

  tbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Edit Department',
      id: 'edit_button',
      iconCls: 'dept',
      disabled: true,
      handler: function(){
        var sel = grid.getSelectionModel().getSelection()[0];
        DepartmentEditWin.show();
        DepartmentEditWin.getForm().form.loadRecord(sel);
      }
    },'-',{
      text: 'Delete Department',
      id: 'delete_button',
      iconCls: 'delete',
      disabled: true,
      handler: function(){
        Ext.Msg.show({
          icon: Ext.MessageBox.QUESTION,
          buttons: Ext.MessageBox.OKCANCEL,
          msg: 'Are you sure you want to delete this department?<br /><br />Any employees in this department will still exist, they\'ll just have their department set to "None".',
          modal: true,
          title: 'Delete Department',
          fn: function(butid){
            if (butid == 'ok'){
              selected = grid.getSelectionModel().getSelection()[0].data.id;
              Ext.Ajax.request({
                url: '<?php echo url_for('employee/departmentDelete?id='); ?>' + selected,
                method: 'POST',
                success: function(){
                  departmentsStore.load();
                },
                failure: function(){
                  Ext.Msg.show({
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.MessageBox.OK,
                    msg: 'Could not delete department!',
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
      text: 'Add Department',
      iconCls: 'add',
      handler: function(){
        DepartmentEditWin.reset(true);
        DepartmentEditWin.show();
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
        $inst->getController()->getPresentationFor('employee','departmentDatagrid');
     ?>);
    }
  }


});


Ext.onReady(function(){

  grid.render('index-grid');

});

</script>
