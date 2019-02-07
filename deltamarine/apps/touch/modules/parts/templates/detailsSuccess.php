<?php use_helper('Form', 'Javascript'); ?>
<div id="filter">
  <h2>Add Part to Workorder #<?php echo $workorder->getId(); ?> Task: "<?php echo $item->getLabel(); ?>"</h2>
</div>

<?php echo form_tag('parts/details?id='.$workorder->getId().'&item='.$item->getId().'&part='.$part->getId()); ?>
  <input type="hidden" id="addpart" name="addpart" value="1" />

  <div class="partadd-area rounded10" style="margin-top: 70px;">
    <h2 class="rounded10-top">Selected Part Information</h2>
    <div class="qtylabel">Part Name:</div><div class="qtyval"><?php echo $part->getName(); ?></div>
    <div class="qtylabel">Part SKU:</div><div class="qtyval"><?php echo $part->getDefaultVariant()->getInternalSku(); ?></div>
    <div class="qtylabel">Unit Price:</div><div class="qtyval"><?php echo $part->getDefaultVariant()->outputUnitPrice(); ?></div>
  </div>

  <div class="partadd-area rounded10">
    <h2 class="rounded10-top">Enter Quantity</h2>
    <p style="text-align: center; ">Please note that you won't be able to enter a quantity greater than the amount available!</p>
    <div class="qtylabel">Quantity On Hand:</div><div class="qtyval"><?php echo $part->getDefaultVariant()->getQuantity('onhand'); ?></div>
    <div class="qtylabel">Quantity Available:</div><div class="qtyval"><?php echo $part->getDefaultVariant()->getQuantity('available'); ?></div>
    <div class="qtylabel" style="margin-top: 17px;">Quantity to Use:</div>
    <div class="qtyval"><input type="text" id="quantity_field" name="quantity" value="<?php echo $sf_request->getParameter('quantity', 1); ?>" style="font-size: 18px; height: 30px; width: 50px;" /></div>
  </div>

  <div class="error-area" style="margin-left: 180px;">
    <?php if ($sf_request->hasParameter('qtyerr')): ?>
      <div class="smallwarning error"><?php echo $sf_request->getParameter('qtyerr'); ?></div>
    <?php endif; ?>
  </div>

  <div class="submit-area" style="margin-left: 200px;">
    <button type="submit">Add Part</button>
  </div>
</form>

<?php echo javascript_tag("
if (window.addEventListener) {
    window.addEventListener('load', function() { f=document.getElementById('quantity_field'); f.focus(); f.select();}, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', function() { f=document.getElementById('quantity_field'); f.focus(); f.select();});
}
"); ?>
