Ext.define('Delta.model.Category', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id', type: 'int' },
      { name: 'name', type: 'string' }
    ],
  
    proxy: {
      type: 'rest',
      url: '/touch.php/rest/category',
      reader: {
        type: 'json',
        rootProperty: 'categories'
      }
    }
  }
});
