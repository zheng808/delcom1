<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Employee List', 'employee/index',
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-person"><?php echo ($employee->getCRM()->getIsCompany() ? 'Contractor' : ($employee->getHidden() ? 'Inactive Employee' : 'Employee')); ?>: <?php echo $employee; ?></h1>
  <div id="view-toolbar"></div>
  <div class="pagebox">
    <?php
      include_partial('wfCRMPlugin/crm_show', array('contact' => $employee->getCRM(),
        'include_title' => false));
    ?>

    <div id="view-tabs"></div>

  </div>
</div>


<script type="text/javascript">
      
Ext.apply(Ext.form.VTypes, {
  password : function(val, field) {
    if (field.initialPassField) {
      var pwd = Ext.getCmp(field.initialPassField);
      return (val == pwd.getValue());
    }
    return true;
  },

  passwordText : 'Passwords do not match!'
});

var timelogsStore = new Ext.data.JsonStore({
  fields: ['id', 'employee', 'date', 'billable', 'type', 
           'rate', 'cost', 'billable_hours', 'payroll_hours', 'start_time', 'end_time', 
           'workorder', 'item', 'boat', 'status', 
           'employee_notes', 'admin_notes'],
  pagesize: 25,
  remoteSort: true,
  <?php if ($sf_user->hasCredential('timelogs_view')): ?>
    autoLoad: {params:{start:0,limit:25}},
  <?php endif; ?>
  sorters: [{ property: 'date', direction: 'DESC' }],
  proxy: {
    type: 'ajax',
    url: '<?php echo url_for('timelogs/datagrid'); ?>',
    simpleSortMode: true,
    extraParams: {employee_id: <?php echo $employee->getId(); ?>},
    reader: { 
      root: 'timelogs',
      totalProperty: 'totalCount',
      idProperty: 'id'
    }
  }
});


var EmployeeTimelogs = new Ext.grid.GridPanel({
  width: '100%',
  height: 270,
  enableColumnMove: false,
  border: false,
  store: timelogsStore,
  viewConfig: { stripeRows: true, loadMask: true },
  columns:[
  {
    header: "Date",
    dataIndex: 'date',
    hideable: false,
    sortable: true,
    width: 120
  },{
    header: "Type",
    dataIndex: 'type',
    sortable: true,
    renderer: function(value,metaData,record){
      if (record.data['billable'] == true){
        output = '<img src="/images/silkicon/money_dollar.png" title="Billable" alt="Billable" />';
      } else {
        output = '<img src="/images/silkicon/money_dollar_hollow.png" title="Non-Billable" alt="Non-Billable" />';
      }
      output += ' '+value;
      return output;
    },
    flex: 1
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
    header: "Workorder",
    dataIndex: 'workorder',
    sortable: false,
    renderer: function(value,metaData,record){
      if (record.data['workorder'] != ''){
        return '#' + record.data['workorder'] + ' - ' + record.data['boat'];
      } else {
        return 'None';
      }
    },
    flex: 1
  },{
    header: "Status",
    dataIndex: 'status',
    sortable: true,
    width: 50,
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
        if (sm.getCount() == 0){
          Ext.getCmp('details').getLayout().setActiveItem(0);
        } else if (sm.getCount() == 1){
          Ext.getCmp('details').getLayout().setActiveItem(1);
          thisdata = sm.getSelection()[0].data;
          detailsTpl.overwrite(Ext.getCmp('details').items.get(1).body, thisdata);
        }
      }
    }
  }),

  bbar: new Ext.PagingToolbar({
    store: timelogsStore
  })
});

//panel view Template
var detailsTpl = new Ext.XTemplate(
  '<table class="infotable" style="padding-bottom: 8px;"><tr>',
  '<td class="label">Employee:</td><td>{employee}</td>',
  '<td class="label">Billable:</td><td><tpl if="billable">Yes</tpl><tpl if="!billable">No</tpl></td></tr>',
  '<tr><td class="label">Labor Type:</td><td>{type}</td>',
  '<td class="label">Rate:</td><td><tpl if="rate &gt; 0">{rate} /hr</tpl></td></tr>',
  '<tr><td class="label">Date:</td><td>{date}</td>',
  '<td class="label">Hours:</td><td>{payroll_hours}<tpl if="billable_hours != \'\' && billable_hours != payroll_hours"> (billed {billable_hours})</tpl></td></tr>',
  '<tpl if="start_time != \'\'"><tr><td class="label">Start Time:</td><td>{start_time}</td>',
  '  <td class="label">End Time:</td><td>{end_time}</td></tr></tpl>',
  '<tr><td class="label">Workorder:</td><td><tpl if="workorder == \'\'">None</tpl>',
  '  <tpl if="workorder != \'\'"><a href="<?php echo url_for('work_order/view?id='); ?>{workorder}">#{workorder} - {boat}</a></tpl>',
  '</td>',
  '<tpl if="item != \'\'"><td class="label">Workorder Item:</td><td>{item}</td></tpl></tr>',
  '<tr><td class="label">Status:</td><td>{status}</td>',
  '<tpl if="cost &gt; 0"><td class="label">Amount:</td><td>{cost}</td></tpl></tr>',
  '<tpl if="employee_notes != \'\'"><tr><td class="label">Employee Notes:</td><td colspan="3">{employee_notes}</td></tr></tpl>',
  '<tpl if="admin_notes != \'\'"><tr><td class="label">Admin notes:</td><td colspan="3">{admin_notes}</td></tr></tpl>',
  '</table>'
);

var details = new Ext.Panel({
  id: 'details',
  width: '100%',
  height: 195,
  layout: 'card',
  activeItem: 0,
  border: false,
  items: [{
    <?php if ($sf_user->hasCredential('timelogs_view')): ?>
      html: 'Click a timelog entry to view details<br /><br />To edit timelogs use the Timelogs item in the main menu.',
    <?php else: ?>
      html: 'Your user does not have permission to view time logs for employees.',
    <?php endif; ?>
    bodyStyle: 'text-align: center; padding-top: 60px; background-color: #eee; border: none;'
  },{
    bodyStyle: 'font-size: 12px; padding: 10px 10px 0 10px; font-weight: normal; border: none;',
    autoScroll: true
  }]
});

var EmployeeLoginWin = new Ext.ux.acFormWindow({
  title: 'Edit User/Password',
  width: 300,
  height: 200,

  formConfig: {
      trackResetOnLoad: true, //resets to loaded values, not blank
      url: '<?php echo url_for('employee/userEdit'); ?>',
      params: { id: <?php echo $employee->getId(); ?> },
      fieldDefaults: { labelAlign: 'right', labelWidth: 125 },
      autoLoadUrl: '<?php echo url_for('employee/userLoad?id='.$employee->getId()); ?>',
      waitMsg: 'Saving Changes...',
      items: [
        new Ext.ux.acToggleButtons({
          fieldLabel: 'Allow Login',
          anchor: '-25',
          name: 'enabled',
          items: [
              { value: 1, text: 'Yes' },
              { value: 0, text: 'No' }
          ],
          listeners: {
            'change': function(f,newval){
              f.up('form').down('#userinputspanel').setVisible(newval == 1);
            }
          }
        }),
      {
        xtype: 'panel',
        layout: 'anchor',
        itemId: 'userinputspanel',
        border: false,
        items: [{
          xtype: 'textfield',
          fieldLabel: 'Username',
          name: 'username',
          minLength: 3,
          anchor: '-25'
        },{
          xtype: 'textfield',
          inputType: 'password',
          fieldLabel: 'Password',
          name: 'password1',
          id: 'pass1',
          minLength: 6,
          anchor: '-25'
        },{
          xtype: 'textfield',
          inputType: 'password',
          fieldLabel: 'Confirm Password',
          name: 'password2',
          vtype: 'password',
          initialPassField: 'pass1',
          anchor: '-25'
        }]
      }]
  }
});

var EmployeePermsWin = new Ext.ux.acFormWindow({
  title: 'Employee Permissions',
  width: 375,
  height: 600,
  resizable: true,

  formConfig: {
      url: '<?php echo url_for('employee/PermsEdit?id='.$employee->getId()); ?>',
      bodyCls: 'permslist',
      autoScroll: true,
      items: [],
      waitMsg: 'Saving Changes...',
      formSuccess:function(form,action,response){
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
        myMask.show();
        location.href = '<?php echo url_for('employee/view?id='.$employee->getId()); ?>';
      }
  }

});

var tb = new Ext.Toolbar({
    height: 27,
    items: [
      {
        text: 'Edit Employee',
        iconCls: 'personedit',
        handler: function(){
          <?php if ($sf_user->hasCredential('employee_edit')): ?>
          new Ext.ux.EmployeeEditWin({
            autoShow: true,
            title: 'Edit Employee',
            showPayrate: <?php echo ($sf_user->hasCredential('employee_payrate') ? 'true' : 'false'); ?>,
            formConfig: {
              url: '<?php echo url_for('employee/edit'); ?>',
              autoLoadUrl: '<?php echo url_for('employee/load?id='.$employee->getId()); ?>',
              params: { id: <?php echo $employee->getId(); ?>},
              submitButtonText: 'Save Changes',
              waitMsg: 'Saving Changes',
              formSuccess: function(form,action,response){
                var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Reloading Page"});
                myMask.show();
                location.href = '<?php echo url_for('employee/view?id='.$employee->getId()); ?>';
              }
            }
          });
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','Your user does not have permission to add an employee');
          <?php endif; ?>
        }
      },'-',{
        text: 'Edit Login/Password',
        iconCls: 'lock',
        handler: function(){
          <?php if ($sf_user->hasCredential('employee_login')): ?>
            EmployeeLoginWin.show();
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','Your user does not have permission to edit employee user names and passwords');
          <?php endif; ?>
        }
      },'-',{
        text: 'Edit Permissions',
        iconCls: 'lockedit',
        handler: function(){
          <?php if ($sf_user->hasCredential('employee_permissions') && ($sf_user->getGuardUser()->getId() != $employee->getGuardUserId())): ?>
            var permform = EmployeePermsWin.getForm();
            if (permform.items.getCount() == 0)
            {
              permform.setDisabled(true);
              Ext.Ajax.request({
                url: '<?php echo url_for('employee/permsLoad?id='.$employee->getId()); ?>',
                success: function(response){
                  data = Ext.decode(response.responseText);

                  if (data.length == 0) {
                    Ext.Msg.alert("Error", "No Permissions available to load!");
                    permform.setDisabled(false);
                  } else {
                    permform.add(Ext.decode(response.responseText));
                    permform.doLayout();
                    permform.setDisabled(false);
                    EmployeePermsWin.show();
                  }
                },
                failure: function (){
                  Ext.Msg.alert("Load Failed", "Could not edit permissions. Specify a Username first!");
                  permform.setDisabled(false);
                }
              });
            }
            else
            {
              EmployeePermsWin.show();
            }
          <?php elseif ($sf_user->hasCredential('employee_permissions')): ?>
            Ext.Msg.alert('Permission Denied','Although you have permission to edit employee permissions, you cannot edit the permissions of your own user, as a precaution. Get someone else to do this for you, or ask the administrator.');
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','Your user does not have permission to edit employee permissions');
          <?php endif; ?>
        }
      },'-',{
        text: 'Delete Employee',
        iconCls: 'delete',
        handler: function(){
          <?php if ($sf_user->hasCredential('employee_edit')): ?>
            Ext.Msg.show({
              icon: Ext.MessageBox.QUESTION,
              buttons: Ext.MessageBox.OKCANCEL,
              msg: 'Are you sure you want to delete this employee?<br /><br />Employees with any timelogs won\'t be able to be deleted.',
              modal: true,
              title: 'Delete Employee',
              fn: function(butid){
                if (butid == 'ok'){
                  Ext.Ajax.request({
                    url: '<?php echo url_for('employee/delete?id='.$employee->getId()); ?>',
                    method: 'POST',
                    success: function(){
                      var myMask = new Ext.LoadMask(Ext.getBody(), {
                        msg: "Deleting Employee..."});
                      myMask.show();
                      location.href = '<?php echo url_for('employee/index'); ?>';
                    },
                    failure: function(){
                      Ext.Msg.show({
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.MessageBox.OK,
                        msg: 'Could not delete employee!',
                        modal: true,
                        title: 'Error'
                      });
                    }
                  });
                }
              }
            });
          <?php else: ?>
            Ext.Msg.alert('Permission Denied','Your user does not have permission to delete an employee');
          <?php endif; ?>
        }
      },'->',{
        text: 'View Change History',
        disabled: true,
        iconCls: 'history'
      }
    ]
});

var tabs = new Ext.TabPanel({
  activeTab: 0,
  height: 480,
  plain: true,
  padding: '15 0 0 0',
  items:[{
    title: 'Recent Timelogs',
    layout: 'vbox',
    align: 'stretch',
    pack: 'start',
    items: [EmployeeTimelogs, details]
  },{
    title: 'Reports',
    disabled: true
  }]
});

Ext.onReady(function(){

    tb.render('view-toolbar');
    tabs.render('view-tabs');

});
</script>
