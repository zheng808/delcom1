Ext.onReady(function(){



var boatStore = new Ext.data.JsonStore({
  fields: ['id','name','make','model'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boatsdatagrid',
    reader: {
      root: 'boats'
    }
  }
});

var boattypeStore = new Ext.data.JsonStore({
  fields: ['id','make','model','desc'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boattypes',
    reader: {
      root: 'types'
    }
  }
});

var boatmodelsStore = new Ext.data.JsonStore({
  fields: ['info'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boattypes?modelonly=1',
    reader: { 
      root: 'types',
      totalProperty: 'totalCount'
    }
  }
});

var boatmakesStore = new Ext.data.JsonStore({
  fields: ['info'],
  remoteSort: true,
  proxy: {
    type: 'ajax',
    url: '/customer/boattypes?makeonly=1',
    reader: {
      root: 'types',
      totalProperty: 'totalCount'
    }
  }
});



Ext.define('Ext.ux.CustomerEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add Customer',
  width: 800,
  autoShow: true,

  loadIntoSelect: null,

  defaultFormConfig: {
    url: '/customer/edit',
    params: {
      id: 'new'
    },

    formSuccess: function(form,action,obj){
      var me = this;

      if (obj.newid && me.parentWin.loadIntoSelect){
        var f = me.parentWin.loadIntoSelect;
        f.getStore().add({id: obj.newid, name: obj.newname});
        f.setValue(obj.newid);
        f.fireEvent('select', f);
        f.labelEl.highlight("99ee99", { duration: 1500 });
      }
    },

    fieldDefaults: { labelAlign: 'top' },

    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.6,
        layout: 'anchor',
        items: [{
          layout: 'column',
          border: false,
          items: [{
            border: false,
            columnWidth: 0.5,
            layout: 'anchor',
            items: [{
              itemId: 'custtype',
              xtype: 'acbuttongroup',
              fieldLabel: 'Customer Type',
              anchor: '-25',
              name: 'custtype',
              value: 'Individual',
              items: [ 'Individual', 'Company'],
              listeners: { change: {delay: 200, fn: function(a){ a.up('form').setInitialFocus(); } } },
            },{
              xtype: 'container',
              border: false,
              hidden: false,
              layout: 'anchor',
              visibleIf: {
                itemId: 'custtype',
                compareValue: 'Individual'
              },
              items: [{
                xtype: 'textfield',
                fieldLabel: 'First Name',
                allowBlank: false,
                initialFocus: true,
                name: 'first_name',
                anchor: '-25'
              },{
                xtype: 'textfield',
                fieldLabel: 'Last Name',
                allowBlank: false,
                name: 'last_name',
                anchor: '-25'
              }]
            },{
              xtype: 'container',
              border: false,
              hidden: true,
              layout: 'anchor',
              visibleIf: {
                itemId: 'custtype',
                compareValue: 'Company'
              },
              items: [{
                xtype: 'textfield',
                fieldLabel: 'Company Name',
                allowBlank: false,
                initialFocus: true,
                name: 'company_name',
                anchor: '-25'
              }]
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
              fieldLabel: 'Work Phone',
              name: 'work_phone',
              anchor: '-25'
            },{
              xtype: 'textfield',
              fieldLabel: 'Mobile Phone',
              name: 'mobile_phone',
              anchor: '-25'
            },{
              xtype: 'container',
              border: false,
              hidden: false,
              layout: 'anchor',
              visibleIf: {
                itemId: 'custtype',
                compareValue: 'Individual'
              },          
              items: [{
                xtype: 'textfield',
                fieldLabel: 'Home Phone',
                name: 'home_phone',
                anchor: '-25'
              }]
            },{
              xtype: 'textfield',
              fieldLabel: 'Fax',
              name: 'fax',
              anchor: '-25'
            }]
          }],
        },{
          fieldLabel: 'Customer Notes',
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
        },{
          fieldLabel: 'PST Number',
          xtype: 'textfield',
          name: 'pst_number',
          anchor: '-25'
        }]
      }]
    }]

  }
});


Ext.define('Ext.ux.BoatEditWin', {
  extend: 'Ext.ux.acFormWindow',

  title: 'Add a Boat',
  width: 450,
  autoShow: true,

  customer_id: null,
  loadIntoSelect: null,

  doneSetup: function(){
    var me = this;

    if (me.customer_id) me.form.params.customer_id = me.customer_id;
  },

  defaultFormConfig: {
    url: '/customer/boatEdit',
    params: {
      customer_id: null,
      id: 'new'
    },

    formSuccess: function(form,action,obj){
      var me = this;

      if (obj.newid && me.parentWin.loadIntoSelect){
        var f = me.parentWin.loadIntoSelect;
        f.getStore().add({id: obj.newid, name: obj.newname});
        f.setValue(obj.newid);
        f.labelEl.highlight("99ee99", { duration: 1500 });
        f.fireEvent('select', f);
      }
    },

    fieldDefaults: { labelWidth: 160 },

    items: [{
      xtype: 'textfield',
      fieldLabel: 'Boat Name',
      initialFocus: true,
      allowBlank: false,
      name: 'name',
    },{
      xtype: 'combo',
      fieldLabel: 'Make or Manufacturer',
      name: 'make',
      queryMode: 'remote',
      minChars: 2,
      displayField: 'info',
      hideTrigger: true,
      store: boatmakesStore,
      listeners: {
        'select': function(field,r){
          field.up('form').down('#boatmodel').store.proxy.setExtraParam('make', field.getValue());
        },
        'blur': function(field){
          field.up('form').down('#boatmodel').store.proxy.setExtraParam('make', field.getValue());
        }
      }
    },{
      xtype: 'combo',
      itemId: 'boatmodel',
      fieldLabel: 'Boat Model/Length',
      name: 'model',
      queryMode: 'remote',
      minChars: 2,
      displayField: 'info',
      hideTrigger: true,
      store: boatmodelsStore
    },{
      xtype: 'textfield',
      fieldLabel: 'Boat/Hull Serial Number',
      name: 'serial_number'
    },{
      xtype: 'textfield',
      fieldLabel: 'Registration #',
      name: 'registration'
    },{
      xtype: 'textarea',
      name: 'notes',
      labelAlign: 'top',
      fieldLabel: 'Boat Notes',
      height: 85
    }]

  }
});



});