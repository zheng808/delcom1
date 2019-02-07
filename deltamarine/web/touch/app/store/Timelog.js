Ext.define('Delta.store.Timelog', {
  extend: 'Ext.data.Store',

  config: {
    model: 'Delta.model.Timelog',

    sorters: [
      { property: 'end_date', direction: 'DESC' }, 
      { property: 'id',   direction: 'DESC' }
    ]
  }

});
