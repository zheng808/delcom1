Ext.define('Delta.model.Workorder', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id', type: 'int' },
      { name: 'active', type: 'boolean' },
      { name: 'customer_name', type: 'string' },
      { name: 'lastnamefirst', type: 'string' },
      { name: 'boat_name', type: 'string' },
      { name: 'boat_type', type: 'string' },
      { name: 'completion', type: 'string' },
      { name: 'started_on', type: 'date' },
      { name: 'customer_notes', type: 'string' },
      { name: 'internal_notes', type: 'string' },
      { name: 'category_id', type: 'string' },
      { name: 'category_name', type: 'string' },
      { name: 'rigging', type: 'int' }
    ],
    proxy: {
      type: 'rest',
      simpleSortMode: true,
      url: '/touch.php/rest/workorder',
      reader: {
        type: 'json',
        rootProperty: 'workorders'
      }
    }
  }
});

