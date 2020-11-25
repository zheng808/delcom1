var employeeDepartmentsStore = new Ext.data.JsonStore({
  fields: ['value','label'],
  pageSize: false,
  proxy: {
    type: 'ajax',
    url: '/wfCRMPlugin/ajaxDepartmentsList',
    reader: { root: 'departments' }
  }
});

Ext.define('Ext.ux.EmployeeEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Employee',
  showPayrate: false,
  width: 650,

  initComponent: function(cfg){
    var me = this;

    me.callParent();

    me.on('show', function(w){
      w.down('#payrate').setVisible(me.showPayrate);
      w.down('#payrate').setDisabled(!me.showPayrate);
      w.down('#deptlist').getStore().load();
    });
  },

  defaultFormConfig: {
    submitButtonText: 'Create Employee',
    waitMsg: 'Adding Employee...',
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [
          new Ext.ux.acToggleButtons({
            fieldLabel: 'Employee Type',
            anchor: '-25',
            name: 'emptype',
            value: 'Employee',
            items: [
                { value: 'Employee', text: 'Employee' },
                { value: 'Contractor', text: 'Contractor' }
            ],
            listeners: {
              'change': function(f,newval){
                f.up('form').query('[employeeOnly]').forEach(function(f){
                  f.setVisible(newval == 'Employee');
                  f.setDisabled(newval != 'Employee');
                  (newval == 'Employee') ? f.validate() : f.clearInvalid();

                });
                f.up('form').query('[companyOnly]').forEach(function(f){
                  f.setVisible(newval == 'Contractor');
                  f.setDisabled(newval != 'Contractor');
                  (newval == 'Contractor') ? f.validate() : f.clearInvalid();
                });
              }
            }
          }),
        {
          xtype: 'textfield',
          fieldLabel: 'First Name',
          allowBlank: false,
          initialFocus: true,
          name: 'first_name',
          employeeOnly: true,
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Last Name',
          allowBlank: false,
          name: 'last_name',
          employeeOnly: true,
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Job Title',
          name: 'job_title',
          employeeOnly: true,
          anchor: '-25'
        },{
          xtype: 'combo',
          itemId: 'deptlist',                          
          fieldLabel: 'Department',
          name: 'parent_node',
          editable: false,
          forceSelection: true,
          employeeOnly: true,
          anchor: '-25',
          queryMode: 'local',
          store: employeeDepartmentsStore,
          valueField: 'value',
          displayField: 'label',
          triggerAction: 'all'
        },{
          xtype: 'textfield',
          fieldLabel: 'Company Name',
          allowBlank: false,
          disabled: true,
          hidden: true,
          name: 'company_name',
          companyOnly: true,
          anchor: '-25'
        },{
          itemId: 'payrate',
          xtype: 'numberfield',
          fieldLabel: 'Hourly Pay ($)',
          name: 'payrate',
          anchor: '-25',
          forcePrecision: true,
          hideTrigger: true,
          allowBlank: false,
          hidden: true,
          minValue: 0
        }]
      },{
        border: false,
        columnWidth: 0.5,
        layout: 'anchor',
        items: [
          new Ext.ux.acToggleButtons({
            fieldLabel: 'Employee Status',
            anchor: '-25',
            name: 'status',
            value: 'Active',
            items: [
                { value: 'Active', text: 'Active' },
                { value: 'Inactive', text: 'Inactive' }
            ]
          }),
        {
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
          employeeOnly: true,
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Fax',
          name: 'fax',
          anchor: '-25'
        },{
          xtype: 'textfield',
          fieldLabel: 'Email',
          vtype: 'email',
          name: 'email',
          anchor: '-25'
        }]
      }],
    },{
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
          labelWidth: 60,
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
          labelWidth: 60,
          anchor: '-25'
        }]
      }]          
    },{
      fieldLabel: 'Employee Notes',
      xtype: 'textarea',
      name: 'private_notes',
      anchor: '-25',
      height: 85
    }]
  }
});