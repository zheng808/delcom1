Ext.define('Delta.model.Task', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id',             type: 'int'    },
      { name: 'name',           type: 'string' },
      { name: 'workorder_id',   type: 'int'    },
      { name: 'completed',      type: 'boolean'},
      { name: 'completed_name', type: 'string' },
      { name: 'completed_date', type: 'date'   },
      { name: 'internal_notes', type: 'string' },
      { name: 'customer_notes', type: 'string' },
      { name: 'level',          type: 'int'    },
      { name: 'path',           type: 'string' },
      { name: 'numbering',      type: 'string' }   
    ],
    proxy: {
      type: 'rest',
      simpleSortMode: true,
      url: '/touch.php/rest/task',
      reader: {
        type: 'json',
        rootProperty: 'tasks'
      }
    }
  }
});

