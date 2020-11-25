Ext.define('Delta.model.NonbillType', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id', type: 'int' },
      { name: 'name', type: 'string' }
    ],

    proxy: {
      type: 'rest',
      url: '/touch.php/rest/nonbilltype',
      reader: {
        type: 'json',
        rootProperty: 'types'
      }
    }
  }
});
