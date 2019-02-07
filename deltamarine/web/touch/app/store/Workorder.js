Ext.define('Delta.store.Workorder', {
  extend: 'Ext.data.Store',

  config: {
    model: 'Delta.model.Workorder',
    pageSize: 1000,

    sorters: [
      { property: 'lastnamefirst', direction: 'ASC' }
    ]
  }

});
