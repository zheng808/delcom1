<table class="invoiceheader">
  <tr>
    <td style="width: 120px"><?php echo image_tag('invoice_header', array('width' => 110, 'height' => 110)); ?></td>
    <td>
      <h2>Delta Marine</h2>
      <p>2075 Tryon Road, Sidney B.C. V8L 3X9</p>
      <p>
        Tel: (250) 656-2639<br />
        Fax: (250) 656-2619
      </p>
    </td>
    <td style="width: 75px;">&nbsp;</td>
    <td style="width: 350px;">
      <h1>Supplier Order</h1>
    </td>
  </tr>
  <tr><td colspan="4">&nbsp;</td></tr>
  <tr>
    <th colspan="2">Ordering From</th>
    <td>&nbsp;</td>
    <th>Order Information</th>
  </tr>
  <tr>
    <td colspan="2" class="boxed">
      <?php echo $order->getSupplier()->getName(); ?>  (Supplier ID: <?php echo $order->getSupplierId(); ?>)<br />
      <?php 
        if ($addr = $order->getSupplier()->getAddress('<br />'))
        {
          echo $addr.'<br />';
        }
        if ($phone = $order->getSupplier()->getWfCRM()->getWorkPhone())
        {
          echo "Phone: ".$phone.'<br />';
        }
        if ($phone = $order->getSupplier()->getWfCRM()->getMobilePhone())
        {
          echo "Mobile: ".$phone.'<br />';
        }
        if ($email = $order->getSupplier()->getWfCRM()->getEmail())
        {
          echo $email.'<br />';
        }
        if ($homepage = $order->getSupplier()->getWfCRM()->getHomepage())
        {
          echo $homepage.'<br />';
        }
      ?>
    </td>
    <td>&nbsp;</td>
    <td class="boxed">
      Ordering Date: <strong><?php echo ($order->getDateOrdered() ? $order->getDateOrdered('M j, Y h:iA') : date('M j, Y h:iA')); ?></strong><br />
      Order ID: <strong><?php echo $order->getId(); ?></strong><br />
      Supplier Account #: <strong><?php echo $order->getSupplier()->getAccountNumber(); ?></strong>
    </td>
  </tr>
</table>

<table class="invoiceitems">
  <tr>
    <th>Part Information</th>
    <th>Delta SKU</th>
    <th>Supplier SKU</th>
    <th style="width: 70px;">Quantity</th>
  </tr>
  <?php $alt = true; ?>
  <?php foreach ($items AS $item): ?>
    <?php $variant = $item->getPartVariant(); ?>
    <?php $partsupp = $variant->getPartSupplierById($order->getSupplierId()); ?>
    <tr class="itemtop<?php if ($alt = !$alt) echo ' alt'; ?>">
      <td><?php echo $variant->getPart()->getName(); ?></td>
      <td class="center"><?php echo $variant->getInternalSku(); ?></td>
      <td class="center"><?php echo $partsupp->getSupplierSku(); ?></td>
      <td class="linetotal" style="text-align: center;"><?php echo $item->outputQuantityRequested();?></td>
    </tr>
  <?php endforeach; ?>
</table>

