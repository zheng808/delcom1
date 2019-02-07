Ext.define('Delta.model.Part', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'part_variant_id', type: 'int' },
      { name: 'name', type: 'string' },
      { name: 'internal_sku', type: 'string' },
      { name: 'category_path', type: 'string' },
      { name: 'price', type: 'string' },
      { name: 'has_serial_number', type: 'boolean' },
      { name: 'manufacturer', type: 'string' },
      { name: 'units', type: 'string' },
      { name: 'on_hand', type: 'float' },
      { name: 'on_hold', type: 'float' },
      { name: 'on_order', type: 'float' },
      { name: 'location', type: 'string' }
    ],
    
    proxy: {
      type: 'rest',
      url: '/touch.php/rest/part',
      reader: {
        type: 'json',
        rootProperty: 'parts'
      }
    }

  }
});
