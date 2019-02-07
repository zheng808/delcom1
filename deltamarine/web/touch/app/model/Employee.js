Ext.define('Delta.model.Employee', {
  extend: 'Ext.data.Model',
  config: {
    pageSize: 50,
    fields: [
      { name: 'id', type: 'int' },
      { name: 'firstname', type: 'string' },
      { name: 'lastname', type: 'string' },
      { name: 'fullname', type: 'string' },
      { name: 'shortname', type: 'string' },
      { name: 'contractor', type: 'boolean' }
    ],

    proxy: {
      type: 'rest',
      url: '/touch.php/rest/employee',
      reader: {
        type: 'json',
        rootProperty: 'employees'
      } 
    }
  }
});
