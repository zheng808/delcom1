Ext.define('Delta.store.Employee', {
  extend: 'Ext.data.Store',

  config: {
    autoLoad: true,
    model: 'Delta.model.Employee',

    sorters: [{ property: 'shortname', direction: 'ASC' }],
    filters: [{ property: 'contractor', value: false}]
  }

});
