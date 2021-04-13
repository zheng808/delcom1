<div class="leftside" style="padding-top: 36px;">
  <div id="index-PartCost"></div>
  <?php
    echo link_to('Print Parts Unit Cost CSV Data', 'reports/generateUnitCostPartsExcel',
      array('class' => 'button tabbutton printPartsCostData', 'style' => 'margin: 20px auto;')); 
  ?> 

  <div id="index-ExpenseCost"></div>
  <?php
    echo link_to('Print Expense Unit Cost CSV Data', 'reports/generateUnitCostExpenseExcel',
      array('class' => 'button tabbutton printExpenseCostData', 'style' => 'margin: 20px auto;'));
  ?>
  <div id="index-LabourCost"></div>
  <?php
    echo link_to('Print Labour Unit Cost CSV Data', 'reports/generateUnitCostLabourExcel',
      array('class' => 'button tabbutton printLabourCostData', 'style' => 'margin: 20px auto;'));
  ?>
</div>
<div class="rightside rightside-narrow">
</div>

<script type="text/javascript">

var partItemsStore = new Ext.data.JsonStore({
storeId:'employeeStore',
autoLoad: true,
fields: ['taskname','tasknumber', 'partname','quantity','unitprice', 'origin', 'total', 'ExpectedDate'],
pageSize: 20,
proxy: {
    type: 'ajax',
    url: '<?php echo url_for('/reports/retreiveParts'); ?>'
  }
});

//part unit cost
var partUnitCostField = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Download Part Unit Cost CSV',
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
          id:'workIdDataPartUnitCost',
          name: 'workExcel',
          xtype: 'textfield',
          fieldLabel: 'Enter Workorder',
          anchor: '-1',
        }]
    }]
  }]
  })]
});

//expense
var expensesUnitCostField = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Download Expense Unit Cost CSV',
  items: [
  new Ext.FormPanel({
    autoWidth: true,
    standardSubmit: true,
    id: 'downloadExpenseExcel',
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
          id:'workIdDataExpenseUnitCost',
          name: 'workExcel',
          xtype: 'textfield',
          fieldLabel: 'Enter Workorder',
          anchor: '-1',
        }]
    }]
  }]
  })]
});

//labour unit cost
var laboursUnitCostField = new Ext.Panel({
  width: 225,
  margin: '0 0 25px 0',
  title: 'Download Labour Unit Cost CSV',
  items: [
  new Ext.FormPanel({
    autoWidth: true,
    standardSubmit: true,
    id: 'downloadLabourExcel',
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
          id:'workIdDataLabourUnitCost',
          name: 'workExcel',
          xtype: 'textfield',
          fieldLabel: 'Enter Workorder',
          anchor: '-1',
        }]
    }]
  }]
  })]
});


$(".printPartsCostData").click(()=>{
      var work_id = $("#workIdDataPartUnitCost-inputEl").val();
      var href = $('.printPartsCostData').attr('href');
      var queryString = '?id=' +  encodeURIComponent(work_id);
      $('.printPartsCostData').attr('href', href + queryString ); 
      //$('.printPartsCostData').removeAttr('href');
      // fetch('', {
      //   method: 'post',
      //   mode: "same-origin",
      //   credentials: "same-origin",
      //   headers: {
      //   "Content-Type": "application/json"
      //   },
      //   body: JSON.stringify({id: work_id})
      // }).then(function(response) {
        
      // });
});

$(".printExpenseCostData").click(()=>{
      var work_id = $("#workIdDataExpenseUnitCost-inputEl").val();
      var href = $('.printExpenseCostData').attr('href');
      var queryString = '?id=' +  encodeURIComponent(work_id);
      $('.printExpenseCostData').attr('href', href + queryString );
      location.reload();
});


$(".printLabourCostData").click(()=>{
      var work_id = $("#workIdDataLabourUnitCost-inputEl").val();
      var href = $('.printLabourCostData').attr('href');
      var queryString = '?id=' +  encodeURIComponent(work_id);
      $('.printLabourCostData').attr('href', href + queryString );
      location.reload();
});


Ext.onReady(function(){
    partUnitCostField.render("index-PartCost");
    expensesUnitCostField.render("index-ExpenseCost");
    laboursUnitCostField.render("index-LabourCost");
});
</script>