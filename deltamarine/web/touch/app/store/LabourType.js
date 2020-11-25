Ext.define('Delta.store.LabourType', {
  extend: 'Ext.data.Store',

  config: {
    autoLoad: true,
    model: 'Delta.model.LabourType',

    sorters: [
      { property: 'name', direction: 'ASC' }
    ]
  }

});
