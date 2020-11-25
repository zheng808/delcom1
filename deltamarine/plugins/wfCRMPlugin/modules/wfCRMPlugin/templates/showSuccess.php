<?php
slot('menu');
?>
<?php
include_component('wfCRMPlugin', 'navigation');
?>
<?php
end_slot();
?>
<?php include_component('wfCRMPlugin','breadcrumb',array('id'=>$contact->getId()));?>
<?php include_partial('wfCRMPlugin/crm_show',array('contact'=>$contact));?>
<div><a href="<?php echo url_for('wfCRMPlugin/edit?id='.$contact->getId())?>">Edit</a> | <?php echo link_to('Delete', 'wfCRMPlugin/delete?id='.$contact->getId(), array('class'=>'red','method' => 'delete', 'confirm' => 'Are you sure?')) ?></div>