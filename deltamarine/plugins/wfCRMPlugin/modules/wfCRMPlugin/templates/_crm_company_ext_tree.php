<?php $permission = (isset($credential) ? $sf_user->hasCredential($credential) : true); ?>
<script type="text/javascript">
var crm_deptstore = new Ext.data.JsonStore({
  fields: ['value','label'],
  autoLoad: true,
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('wfCRMPlugin/ajaxDepartmentsList?id='.$contact->getId()); ?>',
    reader: {
      root: 'departments'
    }
  }
});

var crm_contactstore = new Ext.data.TreeStore({
  fields: ['id','text','sorttext','work_phone','email','leaf','expandable','iconCls'],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('wfCRMPlugin/ajaxCompanyTree?id='.$contact->getId()); ?>',
    reader: {
      root: 'children'
    }
  }
});

var crm_companytree = new Ext.tree.Panel({
  rootVisible:false,
  autoScroll:true,
  store: crm_contactstore,

  border: false,
  selModel: false,
  viewConfig: { lines: true },

  columns:[{
    xtype: 'treecolumn',
    header:'Name',
    dataIndex: 'text',
    width: 340
  },{
    header:'Work Phone',
    width:130,
    dataIndex:'work_phone'
  },{
    header:'Email',
    width:170,
    dataIndex:'email'
  }],

  bbar: new Ext.Toolbar({
    height: 27,
    items: [{
      text: 'Add a Department',
      iconCls: 'deptadd',
      handler: function(){
        <?php if ($permission): ?>
          CRMAddDepartmentWin.show(); 
          Ext.getCmp('deptadd_namefield').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does not have permission to add a department');
        <?php endif; ?>
      }
    },'-',{
      text: 'Add a Contact',
      iconCls: 'personadd',
      handler: function(){
        <?php if ($permission): ?>
          CRMAddContactWin.show();
          Ext.getCmp('contactadd_namefield').focus(true, 200);
        <?php else: ?>
          Ext.Msg.alert('Permission Denied', 'Your user does have permission to add a contact');
        <?php endif; ?>
      }
    },'-',{
      text: 'View Selected',
      iconCls: 'info',
      disabled: true
    },'-',{
      text: 'Edit Selected',
      iconCls: 'infoedit',
      disabled: true
    }]
  })
});

var CRMAddDepartmentWin = new Ext.Window({
  title: 'Add a Department to <?php echo $contact->getName(); ?>',
  closeable: true,
  width: 450,
  height: 350,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    url: '<?php echo url_for('wfCRMPlugin/ajaxAddDepartment?id='.$contact->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'top'},
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
          id: 'deptadd_namefield',
          fieldLabel: 'Department Name',
          allowBlank: false,
          name: 'department_name',
          anchor: '-25'
        },{
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
          xtype: 'combo',
          itemId: 'deptlist',                          
          fieldLabel: 'Parent Dept.',
          name: 'parent_node',
          editable: false,
          forceSelection: true,
          anchor: '-25',
          queryMode: 'local',
          store: crm_deptstore,
          valueField: 'value',
          displayField: 'label',
          triggerAction: 'all'
        },{
          xtype: 'textfield',
          fieldLabel: 'Fax',
          name: 'fax',
          anchor: '-25'
        }]
      }]
  },{
    fieldLabel: 'Department Notes',
    xtype: 'textarea',
    name: 'private_notes',
    anchor: '-25',
    height: 85
  }],

  buttons:[{
    text: 'Save',
    formBind: true,
    handler:function(){
        CRMAddDepartmentWin.hide();
        this.findParentByType('form').getForm().submit({
            waitTitle: 'Please Wait',
            waitMsg: 'Adding Department...',
            success:function(form,action){
              form.reset();
              crm_deptstore.load();
              crm_companytree.root.load();
            },
            failure:function(form,action){
                if(action.failureType == 'server'){
                  obj = Ext.util.JSON.decode(action.response.responseText);
                  myMsg = obj.errors.reason;
                }else{
                  myMsg = 'Could not save Department. Try again later!';
                }
                Ext.Msg.show({
                  closable:false, 
                  fn: function(){ CRMAddDepartmentWin.show(); },
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

var CRMAddContactWin = new Ext.Window({
  title: 'Add a Contact to <?php echo $contact->getName(); ?>',
  closeable: true,
  width: 450,
  height: 450,
  border: false,
  modal: true,
  resizable: false,
  closeAction: 'hide',
  layout: 'fit',

  items: new Ext.FormPanel({
    url: '<?php echo url_for('wfCRMPlugin/ajaxAddContact?id='.$contact->getId()); ?>',
    bodyStyle: 'padding:15px 10px 0 10px',
    fieldDefaults: { labelAlign: 'top'},
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [{
          xtype: 'combo',
          fieldLabel: 'Prefix',
          name: 'salutation',
          editable: false,
          forceSelection: true,
          anchor: '-25',
          queryMode: 'local',
          store: ['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Mr. & Mrs.'],
          triggerAction: 'all'
        },{
          xtype: 'textfield',
          id: 'contactadd_namefield',
          fieldLabel: 'First Name',
          allowBlank: false,
          name: 'first_name',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Last Name',
          name: 'last_name',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Job Title',
          name: 'job_title',
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
          xtype: 'combo',
          itemId: 'deptlist',                          
          fieldLabel: 'Department',
          name: 'parent_node',
          editable: false,
          forceSelection: true,
          anchor: '-25',
          queryMode: 'local',
          store: crm_deptstore,
          valueField: 'value',
          displayField: 'label',
          triggerAction: 'all'
        },{
          xtype: 'textfield',
          fieldLabel: 'Work Phone',
          name: 'work_phone',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Mobile Phone',
          name: 'mobile_phone',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Home Phone',
          name: 'home_phone',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Fax',
          name: 'fax',
          anchor: '-25'
        }]
      }]
    },{
      fieldLabel: 'Contact Notes',
      xtype: 'textarea',
      name: 'private_notes',
      anchor: '-25',
      height: 85
    }],

    buttons:[{
      text: 'Save',
      formBind: true,
      handler:function(){
        CRMAddContactWin.hide();
        this.findParentByType('form').getForm().submit({
          waitTitle: 'Please Wait',
          waitMsg: 'Adding Contact...',
          success:function(form,action){
            form.reset();
            crm_companytree.root.load();
          },
          failure:function(form,action){
            if(action.failureType == 'server'){
              obj = Ext.util.JSON.decode(action.response.responseText);
              myMsg = obj.errors.reason;
            }else{
              myMsg = 'Could not save Contact. Try again later!';
            }
            Ext.Msg.show({
              closable:false, 
              fn: function(){ CRMAddContactWin.show(); },
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

</script>
