Ext.define('Delta.model.Timelog', {
  extend: 'Ext.data.Model',
  config: {
    fields: [
      { name: 'id',                 type: 'int'     },
      { name: 'end_date',           type: 'date',   dateFormat: 'timestamp' },
      { name: 'employee_id',        type: 'int'     },
      { name: 'billable',           type: 'int'     },
      { name: 'task_id',            type: 'int'     },
      { name: 'task_name',          type: 'string'  },
      { name: 'task_hierarchy',     type: 'string'  },
      { name: 'workorder_id',       type: 'int'     },
      { name: 'boat_name',          type: 'string'  },
      { name: 'boat_type',          type: 'string'  },
      { name: 'labour_type_id',     type: 'int'     },
      { name: 'labour_type_name',   type: 'string'  },
      { name: 'nonbill_type_id',    type: 'int'     },
      { name: 'nonbill_type_name',  type: 'string'  },
      { name: 'payroll_hours',      type: 'float'   },
      { name: 'payroll_hourmins',   type: 'string'  },
      { name: 'billable_hours',     type: 'float'   },
      { name: 'notes',              type: 'string'  },
      { name: 'approved',           type: 'boolean' }
    ],
    
    proxy: {
      type: 'rest',
      url: '/touch.php/rest/timelog',
      reader: {
        type: 'json',
        rootProperty: 'timelogs'
      }
    }
  }
});

