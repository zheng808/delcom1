Ext.define('Delta.model.PartInstance', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id',                 type: 'int'     },
      { name: 'part_id',            type: 'int'     },
      { name: 'part_name',          type: 'string'  },
      { name: 'category_name',      type: 'string'  },
      { name: 'category_hierarchy', type: 'string'  },
      { name: 'sku',                type: 'string'  },
      { name: 'part_variant_id',    type: 'string'  },
      { name: 'quantity',           type: 'float'   },
      { name: 'employee_id',        type: 'int'     },
      { name: 'employee_name',      type: 'string'  },
      { name: 'serial_number',      type: 'string'  },
      { name: 'date_used',          type: 'date',   dateFormat: 'timestamp' },
      { name: 'workorder_id',       type: 'int'     },
      { name: 'customer_name',      type: 'string'  },
      { name: 'boat_type',          type: 'string'  },
      { name: 'boat_name',          type: 'string'  },
      { name: 'task_id',            type: 'int'     },
      { name: 'task_name',          type: 'string'  },
      { name: 'task_hierarchy',     type: 'string'  }
    ],
    proxy: {
      type: 'rest',
      url: '/touch.php/rest/partinstance',
      reader: {
        type: 'json',
        rootProperty: 'instances'
      }
    }
  }
});
