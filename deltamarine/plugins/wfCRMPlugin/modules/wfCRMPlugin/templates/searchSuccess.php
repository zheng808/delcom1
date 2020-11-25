<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>Search results for "<?php echo $query?>"</h1>
<table>
<?php if(count($contacts)){?>
<tr>
	<th>Name</th>
	<th>Email</th>
	<th>Phone/Fax</th>
	<th>Address</th>
	<th>Homepage</th>
</tr>
<?php
foreach ($contacts as $k=>$contact){
 ?>
 <tr class="<?php echo $k%2?'even':'odd'?>">
 <td><a href="<?php echo url_for('wfCRMPlugin/show?id='.$contact->pk)?>"><?php echo $contact->name?></a></td>
 <td><?php echo $contact->email?></td>
 <td>
 <?php if($contact->work_phone){?>
 <?php echo $contact->work_phone?> (work)<br />
 <?php }?>
 <?php if($contact->mobile_phone){?>
 <?php echo $contact->mobile_phone?> (mobil)<br />
 <?php }?>
 <?php if($contact->home_phone){?>
 <?php echo $contact->home_phone?> (home)<br />
 <?php }?>
 <?php if($contact->fax){?>
 <?php echo $contact->fax?> (fax)
 <?php }?>
 </td>
 <td><?php echo $contact->address?></td>
 <td><a href="<?php echo $contact->homepage?>" target="_blank"><?php echo $contact->homepage?></a></td>
 </tr>
 <?php 
}
?>
<?php }else{?>
<tr><th>No results</th></tr>
<?php }?>
</table>