Ext.define('Delta.model.PartCategory', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id', type: 'int' },
      { name: 'name', type: 'string' }
    ],
  
    proxy: {
      type: 'rest',
      url: '/touch.php/rest/partcategory',
      reader: {
        type: 'json',
        rootProperty: 'categories'
      }
    }
  }
});
