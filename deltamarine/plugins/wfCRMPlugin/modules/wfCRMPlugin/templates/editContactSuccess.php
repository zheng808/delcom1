<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>Edit Сontact</h1>
<form action="<?php echo url_for('wfCRMPlugin/'.($form->getObject()->isNew() ? 'createContact' : 'updateContact').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php include_partial('form', array('form' => $form)) ?>
</form>
<h1>Address list</h1>
<table width="900px">
<?php if(count($addresses)){?>
<thead>
<tr>
	<th>Address</th>
	<th width="100px">Actions</th>
</tr>
</thead>
<tbody>

<?php foreach ($addresses as $i=>$address){?>
<tr class="<?php echo $i%2?'odd':'even'?>">
	<td><?php echo $address?></td>
	<td><a href="<?php echo url_for('wfCRMPlugin/editAddress?id='.$address->getId())?>">Edit</a> | <?php echo link_to('Delete', 'wfCRMPlugin/deleteAddress?id='.$address->getId(), array('class'=>'red','method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
</tr>
<?php }?>
</tbody>
<?php }else{?>
<thead>
<tr><th colspan="2">No addresses found</th></tr>
</thead>
<?php }?>

</table>

<a href="<?php echo url_for('wfCRMPlugin/newAddress?crm_id='.$crm_id)?>">Add new address</a>
