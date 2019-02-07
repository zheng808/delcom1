<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<?php include_component('wfCRMPlugin','breadcrumb',array('id'=>$crm_id,'cat'=>$cat_id));?>
<?php if($selected_crm){
  include_partial('wfCRMPlugin/crm_short_show',array('contact'=>$selected_crm));
 }?>

 <?php if($crm_id){?>
<h2>Departments list</h2>
<table width="900">
<?php if(count($departments)){?>
<thead>
<tr><th>Name</th></tr>
</thead>
<tbody>
<?php foreach ($departments as $i=>$department){?>
<tr class="<?php echo $i%2?'odd':'even'?>"><td><a href="<?php echo url_for('wfCRMPlugin/list?cat='.$cat_id.'&crm='.$department->getId())?>"><?php echo $department->getName()?></a></td></tr>
<?php } ?>
</tbody>
<?php }else{ ?>
<thead>
<tr><th>No departments found</th></tr>
</thead>
<?php }?>
</table>
<a href="<?php echo url_for('wfCRMPlugin/newDepartment?parent_id='.$crm_id)?>">Add new department</a>
<?php } else { ?>
	<h2>Companies list</h2>
	<table width="900">
		<?php foreach ($companies as $i=>$company){?>
		<tr class="<?php echo $i%2?'odd':'even'?>"><td><a href="<?php echo url_for('wfCRMPlugin/list?cat=0&crm='.$company->getId())?>"><?php echo $company->getName()?></a></td></tr>
		<?php } ?>
	</table>
<?php } ?>
<h2>Contacts list</h2>
<table width="900">
<?php if($contacts_pager->getNbResults()){?>
<thead>
<tr>
	<th>Name</th>
	<th>email</th>
	<th>Phone/Fax</th>
	<th>Address</th>
	<th>In address book</th>
	<th>Homepage</th>
	<th width="100">Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($contacts_pager->getResults() as $i=>$contact){?>
<tr class="<?php echo $i%2?'odd':'even'?>">
	<td><a href="<?php echo url_for('wfCRMPlugin/show?id='.$contact->getId())?>"><?php echo $contact->getName()?></a></td>
	<td><a href="mailto:<?php echo $contact->getEmail()?>"><?php echo $contact->getEmail()?></a></td>
	<td>
	<?php if($contact->getWorkPhone()){
         echo $contact->getWorkPhone()?> (work)<br />
    <?php }?>
	<?php if($contact->getMobilePhone()){
         echo $contact->getMobilePhone()?> (mobile)<br />
    <?php }?>
	<?php if($contact->getHomePhone()){
         echo $contact->getHomePhone()?> (home)<br />
    <?php }?>
	<?php if($contact->getFax()){
         echo $contact->getFax()?> (home)<br />
    <?php }?>
	</td>
	<td>
	<?php echo join('<br />',$contact->getwfCRMAddresss())?>
	</td>
	<td><?php echo $contact->getIsInAddressbook()?'yes':'no';?></td>
	<td><a href="<?php echo $contact->getHomepage()?>" target="_blank"><?php echo $contact->getHomepage()?></a></td>
	<td><a href="<?php echo url_for('wfCRMPlugin/edit?id='.$contact->getId())?>">Edit</a> | <?php echo link_to('Delete', 'wfCRMPlugin/delete?id='.$contact->getId(), array('class'=>'red','method' => 'delete', 'confirm' => 'Are you sure?')) ?></td>
</tr>
<?php }?>
</tbody>
<tfoot>
<tr>
	<td colspan="7" align="center">
<?php if ($contacts_pager->haveToPaginate()): ?>
  <?php echo link_to('&laquo;', 'wfCRMPlugin/list?page='.$contacts_pager->getFirstPage()) ?> &nbsp; 
  <?php echo link_to('&lt;', 'wfCRMPlugin/list?page='.$contacts_pager->getPreviousPage()) ?> &nbsp; 
  <?php $links = $contacts_pager->getLinks(); foreach ($links as $page): ?>
    <?php echo ($page == $contacts_pager->getPage()) ? $page : link_to($page, 'wfCRMPlugin/list?page='.$page) ?>
     <?php if ($page != $contacts_pager->getCurrentMaxLink()): ?> &nbsp;   -  &nbsp;  <?php endif; ?>
  <?php endforeach; ?>
  &nbsp;  <?php echo link_to('&gt;', 'wfCRMPlugin/list?page='.$contacts_pager->getNextPage()) ?>
   &nbsp; <?php echo link_to('&raquo;', 'wfCRMPlugin/list?page='.$contacts_pager->getLastPage()) ?>
<?php endif; ?>
	</td>
</tr>
</tfoot>
<?php }else{?>
<thead>
<tr>
	<th>No contacts found</th>
</tr>
</thead>
<?php }?>
</table>

<a href="<?php echo url_for('wfCRMPlugin/newContact?parent_id='.$crm_id)?>">Add new contact</a>