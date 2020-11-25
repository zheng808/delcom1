<h1><?php echo $contact->getName()?></h1>
<table width="900px">
<thead>
<tr>
	<th>Name</th>
	<th>email</th>
	<th>Phone/Fax</th>
	<th>Address</th>
	<th>In address book</th>
	<th>Homepage</th>
</tr>
</thead>
<tr class="even">
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
</tr>
</table>
<br>
<div align="right" style="width: 900px;">
	<a href="<?php echo url_for('wfCRMPlugin/edit?id='.$contact->getId())?>">Edit</a> | <?php echo link_to('Delete', 'wfCRMPlugin/delete?id='.$contact->getId(), array('class'=>'red','method' => 'delete', 'confirm' => 'Are you sure?')) ?>
</div>
