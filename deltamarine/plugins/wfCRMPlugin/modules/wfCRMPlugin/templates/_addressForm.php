<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('wfCRMPlugin/'.($form->getObject()->isNew() ? 'createAddress' : 'updateAddress').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<a href="<?php echo url_for('wfCRMPlugin/edit?id='.$form->getObject()->getCrmId()) ?>">Cancel</a>
            &nbsp;<?php echo link_to('Delete', 'wfCRMPlugin/deleteAddress?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php else:?>
            &nbsp;<a href="<?php echo url_for('wfCRMPlugin/edit?id='.$form->getDefault('crm_id'))?>">Cancel</a>
          <?php endif; ?>
          
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
		<?php echo $form?>
    </tbody>
  </table>
</form>
