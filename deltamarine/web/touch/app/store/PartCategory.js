Ext.define('Delta.store.PartCategory', {
  extend: 'Ext.data.TreeStore',
  
  config: {
    model: 'Delta.model.PartCategory',
    defaultRootProperty: 'categories'
  
  }
});
