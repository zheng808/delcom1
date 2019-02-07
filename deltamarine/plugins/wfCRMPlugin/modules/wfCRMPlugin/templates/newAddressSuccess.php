<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>New Address</h1>

<?php include_partial('addressForm', array('form' => $form)) ?>
