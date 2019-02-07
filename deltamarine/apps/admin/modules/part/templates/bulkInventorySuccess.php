<script type="text/javascript">
</script>

<div class="leftside" style="padding-top: 27px;">
  <?php
    echo link_to('Return to Parts List', 'part/index', 
      array('class' => 'button tabbutton'));
  ?>
</div>

<div class="rightside rightside-narrow">

  <h1 class="headicon headicon-part">Update Part Inventory: Part 1 (Scan)</h1>
  <div class="pagebox">
    <p>To update the inventory for multiple items at a time, you must put the barcode scanner
       into inventory mode.
    </p>
    <p>For instructions on doing this, 
       <a href="/inventory_instructions.pdf">download this PDF file</a>
       which can be printed out and contains needed barcodes and instructions to complete
       the inventorying.
    </p>
    <div id="invbox"></div>
  </div>
</div>


<script type="text/javascript">
var inventory_input = new Ext.FormPanel({
  labelAlign: 'top',
  border: false,
  width: 600,
  url: '<?php echo url_for('part/bulkInventoryReview'); ?>',
  standardSubmit: true,
  items: [{
    id: 'inventory_box',
    xtype: 'textarea',
    name: 'inventory',
    required: true,
    fieldLabel: 'Scanned Barcode Data',
    height: 200,
    width: 600
  }],

  buttons: [{
    text: 'Submit Changes to Inventory',
    formBind: true,
    handler: function(){
      this.findParentByType('form').getForm().submit({
        waitTitle: 'Please Wait',
        waitMsg: 'Checking scanned/inputted barcodes...'
      });
    }
  },{
    text: 'Cancel',
    handler: function(){
      location.href = '<?php echo url_for('part/index'); ?>';
    }
  }]
});




Ext.onReady(function(){
  inventory_input.render('invbox');
  Ext.getCmp('inventory_box').focus(200);
});


</script>
