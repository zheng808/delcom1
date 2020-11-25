Ext.define('Delta.view.part.Search', {
  extend: 'Ext.Panel',
  xtype: 'partsearch',
  
  targetField: null,

  config: {
    layout: { type: 'fit' },
    items: [{
      xtype: 'toolbar',
      ui: 'light',
      docked: 'bottom',
      layout: { type: 'vbox' },
      items: [
        {
          xtype: 'container',
          height: 55,
          layout: { type: 'hbox', pack: 'left', align: 'center' },
          items: [{ 
            xtype: 'searchfield',
            itemId: 'partname',
            label: 'Part Name',
            width: 250
          },{
            xtype: 'searchfield',
            itemId: 'partsku',
            label: 'SKU',
            width: 250
          },{
            xtype: 'treepickerfield',
            itemId: 'partcat',
            label: 'Category',
            title: 'Part Category',
            width: 250,
            value: '',
            store: 'PartCategory',
            displayField: 'name',
            valueField: 'id'
          }]
        }
      ]
    },{
      itemId: 'scrollmsg',
      xtype: 'container',
      docked: 'bottom',
      height: 25,
      padding: 5,
      style: 'background-color: #000; font-size: 0.8em; text-align: center; color: #aaa;',
      html: 'Swipe list up to view more...'
    },{
      xtype: 'dataview',
      emptyText: 'No parts found. Check the filter settings and try again.',
      useComponents: true,
      defaultType: 'partsearchitem',
      padding: '20 0 20 0',
      store: {
        model: 'Delta.model.Part',
        pageSize: 50,
        autoLoad: true,
        remoteFilter: true,
        proxy: {
          type: 'ajax',
          url: '/touch.php/rest/part',
          reader: {
            rootProperty: 'parts' 
          }
        }
      },
      style: 'text-align: center'
    }]
  }
});

Ext.define('Delta.view.part.SearchItem', {
  extend: 'Ext.dataview.component.DataItem',
  xtype: 'partsearchitem',

  config: {
    margin: '6 12 6 12',
    width: 400,
    style: 'display: inline-block; font-size: 0.8em;',

    detailRow: true,

    dataMap: {
      getDetailRow: {
        setDetails: 'id'
      }
    }
  },

  applyDetailRow: function(config) {
    return Ext.factory(config, 'Delta.view.part.SearchRenderer', this.getDetailRow());
  },

  updateDetailRow: function(newDetailRow, oldDetailRow) {
    if (oldDetailRow) { this.remove(oldDetailRow); }
    if (newDetailRow) { this.add(newDetailRow);    }
  }

});

Ext.define('Delta.view.part.SearchRenderer', {
  extend: 'Ext.SegmentedButton',
  config: {
    allowToggle: false,
    margin: '0 0 4 0',
    defaults: { ui: 'normal', padding: 3, style: 'text-align: left', height: 55 },
    items: [
      { width: 100, html: '...', padding: 5, style: 'font-weight: bold; line-height: 1.2em;' },
      { width: 200, html: '...', style: 'text-align: left' },
      { width: 100, html: '...'  }
    ]
  },

  setDetails: function(info){
    var me = this;
    var items = me.getInnerItems();
    var data = me.parent.getRecord().data;
    var oldval = parseInt(Ext.ComponentQuery.query('partfield')[0].getValue());

    var skutext = '<div>' + data.internal_sku + '</div><div style="color: #666; font-size: 0.7em; font-style: italic;">SKU</div>';
    var infotext = '<div style="white-space: normal; line-height: 1.2em; font-size: 1em;">' + data.name + '</div>';
    infotext = infotext + '<div style="color: #666; font-size: 0.7em; font-style: italic;">' + data.category_path + '</div>';
    var qtytext = '<div>' + data.price + '</div><div style="color: #666; font-size: 0.7em; font-style: italic;">' + (data.on_hand - data.on_hold) + ' Avail.</div>';

    items[0].setHtml(skutext); 
    items[1].setHtml(infotext);
    items[2].setHtml(qtytext);
    if (oldval == data.part_variant_id){
      items[0].setUi('confirm');
      items[1].setUi('confirm');
      items[2].setUi('confirm');
    }

    var selecthandler = function(but){
      //set the value and clear the popup
      var tofield = but.up('partsearch').config.targetField;
      tofield.setValueFromRecord(but.getParent().getParent().getRecord());
      var comp = but.up('partsearch').up('panel').hide();
      comp.destroy();

      return false; //prevent double-firing
    }
    items[0].on('tap', selecthandler);
    items[1].on('tap', selecthandler);
    items[2].on('tap', selecthandler);
  }

});

