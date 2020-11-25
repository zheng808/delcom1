<?php slot('menu');?>
<?php include_component('wfCRMPlugin','navigation');?>
<?php end_slot();?>
<h1>New Contact</h1>

<form action="<?php echo url_for('wfCRMPlugin/'.($form->getObject()->isNew() ? 'createContact' : 'updateContact').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php include_partial('form', array('form' => $form)) ?>
</form>