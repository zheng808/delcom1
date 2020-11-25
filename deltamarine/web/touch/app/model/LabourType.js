Ext.define('Delta.model.LabourType', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id', type: 'int' },
      { name: 'name', type: 'string' },
      { name: 'rate', type: 'float' }
    ],

    proxy: {
      type: 'rest',
      url: '/touch.php/rest/labourtype',
      reader: {
        type: 'json',
        rootProperty: 'types'
      }
    }
  }
});
