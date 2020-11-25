Ext.define('Delta.store.NonbillType', {
  extend: 'Ext.data.Store',

  config: {
    autoLoad: true,
    model: 'Delta.model.NonbillType',

    sorters: [
      { property: 'name', direction: 'ASC' }
    ]
  }

});
