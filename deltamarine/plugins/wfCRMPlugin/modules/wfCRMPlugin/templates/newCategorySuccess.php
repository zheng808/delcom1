<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>New Category</h1>

<?php include_partial('categoryForm', array('form' => $form)) ?>
