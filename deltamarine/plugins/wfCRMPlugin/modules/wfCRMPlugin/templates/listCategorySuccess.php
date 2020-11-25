<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>Category list</h1>
<table width="900px">
<thead>
<tr>
	<th>Name</th>
	<th width="100px">Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($cat_tree as $i=>$cat){?>
<tr class="<?php echo $i%2?'odd':'even'?>">
	<td><?php echo $cat->getNameWithLevel()?></td>
	<td><a href="<?php echo url_for('wfCRMPlugin/editCategory?id='.$cat->getId())?>">Edit</a> | <?php echo link_to('Delete', 'wfCRMPlugin/deleteCategory?id='.$cat->getId(), array('class'=>'red','method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
</tr>
<?php }?>
</tbody>
</table>

<a href="<?php echo url_for('wfCRMPlugin/newCategory')?>">Add new category</a>