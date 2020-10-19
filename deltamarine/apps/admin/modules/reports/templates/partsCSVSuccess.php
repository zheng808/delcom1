<div class="leftside" style="padding-top: 36px;">
  <div id="index-goto"></div>
  <div id="index-PartExcel"></div>
  <?php
    echo link_to('Print Parts CSV Data', 'reports/generatePartsExcel',
      array('class' => 'button tabbutton printPartsData', 'style' => 'margin: 20px auto;'));
  ?>
</div>
<div class="rightside rightside-narrow">
  <h1 class="headicon headicon-person">Parts CSV Data</h1>
  <div style="margin-top: 25px;" id="parts-tabs"></div>
  <div id="index-grid"></div>
</div>

<script type="text/javascript">

var partItemsStore = new Ext.data.JsonStore({
storeId:'employeeStore',
autoLoad: true,
fields: ['taskname','tasknumber', 'partname','quantity','unitprice', 'origin', 'total'],
pageSize: 20,
proxy: {
    type: 'ajax',
    url: '<?php echo url_for('/reports/retreiveParts'); ?>'
  }
});
var workNumberField = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Download Part Data',
  items: [
  new Ext.FormPanel({
    autoWidth: true,
    standardSubmit: true,
    id: 'downloadExcel',
    bodyStyle: 'padding: 10px',
    labelWidth: 70,
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.8,
        layout: 'anchor',
        items: [{      
          itemId: 'woid',
          id:'workIdData',
          name: 'workExcel',
          xtype: 'textfield',
          fieldLabel: 'Enter Workorder',
          anchor: '-1',
        }]
    }]
  }]
  })]
});
  

 //php echo url_for('reports/partsCSV'); ?>,
var goto_panel = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Generate Part Data',
  items: [
  new Ext.FormPanel({
    autoWidth: true,
    standardSubmit: true,
    id: 'gotoform',
    bodyStyle: 'padding: 10px',
    labelWidth: 70,
    items: [{
      layout: 'column',
      border: false,
      items: [{
        border: false,
        columnWidth: 0.8,
        layout: 'anchor',
        items: [{      
          itemId: 'woid',
          id:'workId',
          name: 'workId',
          xtype: 'textfield',
          fieldLabel: 'Workorder #',
          anchor: '-1',
          listeners: {
              specialkey: function(field, e){
                  if (e.getKey() == e.ENTER) {
                    field.up('form').submit();
                  }
              }
          }
        }]
      },{
        border: false,
        columnWidth: 0.2,
        items: new Ext.Button({
          text: 'Go',
          handler: function(btn){
            var workid = btn.up('form').getForm().getValues();
            Ext.Ajax.request({
              url: '<?php echo url_for('/reports/retreiveParts'); ?>',
              method: 'POST',
              params: {id: workid},
              success: function(data){
                var result = Ext.decode(data.responseText);
                partItemsStore.loadRawData(result);
              },
              failure: function(){
                Ext.Msg.hide();
                Ext.Msg.show({
                  icon: Ext.MessageBox.ERROR,
                  buttons: Ext.MessageBox.OK,
                  msg: 'Could not edit timelog(s)! Reload page and try again.',
                  modal: true,
                  title: 'Error'
                });
              }
            });
          }
        })
      }]
    }]
  })]
});



var grid = new Ext.grid.GridPanel({
  minHeight: 500,
  bodyCls: 'indexgrid',
  enableColumnMove: false,
  emptyText: 'No matching Work Orders found',
  viewConfig: { stripeRows: true, loadMask: true, enableTextSelection: true },
  url: '<?php echo url_for('reports/retreiveParts'); ?>',
  store: Ext.data.StoreManager.lookup('employeeStore'),
  
  columns:[{
    header: "Task",
    dataIndex: 'taskname',
    sortable: true,
    format: 0,
    width: 180
  },{
    header: "Task Number",
    dataIndex: 'tasknumber',
    sortable: true,
    format: 0,
    width: 90
  },
  {
    header: "Part Name",
    dataIndex: 'partname',
    hideable: false,
    sortable: true,
    width: 200
  },{
    header: "Quantity",
    dataIndex: 'quantity',
    sortable: true,
    width : 90
    //flex: 1
  },{
    header: "Unit Price",
    dataIndex: 'unitprice',
    sortable: false,
    width : 90
    //flex: 1
  },{
    header: "Total Amount",
    dataIndex: 'total',
    width : 90
    //flex: 1
  },
  {
    header: "Origin",
    dataIndex: 'origin',
    sortable: true,
    width: 75
  }]
});

$(".printPartsData").click(function(){
      var work_id = $("#workIdData-inputEl").val();
      var href = $('.printPartsData').attr('href');
      var queryString = '?id=' +  encodeURIComponent(work_id);
      $('.printPartsData').attr('href', href + queryString );
});


Ext.onReady(function(){
    grid.render("index-grid");
    goto_panel.render("index-goto");
    workNumberField.render("index-PartExcel");
});
</script>