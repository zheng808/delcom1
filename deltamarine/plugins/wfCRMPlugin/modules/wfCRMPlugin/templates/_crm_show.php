<?php $company = $contact->getIsCompany(); ?>
<?php if (!isset($include_title)) $include_title = false; ?>
<?php if (!isset($include_name))  $include_name = false; ?>

<?php if ($include_title): ?>
 <h2><?php echo $contact->getName(true, true)?></h2>
<?php endif; ?>

<table class="infotable">

  <?php if (isset($prepend_to_table)) echo $prepend_to_table; ?>

  <?php if ($include_name): ?>
    <tr>
      <td class="label"><?php if ($company) echo "Company "; ?>Name:</td>
      <td colspan="3"><?php echo $contact->getName(); ?></td>
    </tr>
  <?php endif; ?>

  <?php if (!$company): ?>
    <?php if ($contact->getJobTitle() || $contact->hasParent()): ?>
      <tr>
        <td class="label">Job Title:</td>
        <?php if ($contact->hasParent()): ?>
          <td><?php echo $contact->getJobTitle(); ?></td>
          <td class="label">Department:</td>
          <td><?php echo $contact->getDepartmentHierarchy(); ?></td>
        <?php else: ?>
          <td colspan="3"><?php echo $contact->getJobTitle(); ?></td>
        <?php endif; ?>
      </tr>
    <?php endif; ?>

    <tr>
      <td class="label">Home Phone:</td>
      <td><?php echo $contact->getHomePhone(); ?></td>
      <td class="label">Mobile Phone:</td>
      <td><?php echo $contact->getMobilePhone(); ?></td>
    </tr>
    <tr>
      <td class="label">Work Phone:</td>
      <td><?php echo $contact->getWorkPhone(); ?></td>
      <td class="label">Fax:</td>
      <td><?php echo $contact->getFax(); ?></td>
    </tr>
  <?php else: ?>

    <tr>
      <td class="label">Work Phone:</td>
      <td><?php echo $contact->getWorkPhone(); ?></td>
      <td class="label">Mobile Phone:</td>
      <td><?php echo $contact->getMobilePhone(); ?></td>
    </tr>

    <?php if ($contact->getFax()): ?>
      <tr>
        <td class="label">Fax:</td>
        <td><?php echo $contact->getFax(); ?></td>
      </tr>
    <?php endif; ?>

  <?php endif; ?>

  <?php if ($contact->getEmail() || $contact->getHomepage()): ?>
    <tr>
      <td class="label">Email:</td>
      <td><?php echo $contact->getEmail(); ?></td>
      <td class="label">Website:</td>
      <td>
        <?php if ($contact->getHomepage()): ?>
          <?php echo link_to($contact->getHomepage(), $contact->getHomepage(), 
                             array('target' => '_blank')); ?>
        <?php endif; ?>
      </td>
    </tr>
  <?php endif; ?>

  <?php if ($addresses = $contact->getwfCRMAddresss()): ?>
    <?php foreach ($addresses AS $address): ?>
      <tr>
        <td class="label"><?php echo $address->getType(); ?> Address:</td>
        <td colspan="3"><?php echo $address->getAddress(', '); ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($notes = $contact->getPrivateNotes()): ?>
    <tr>
      <td class="label">Notes:</td>
      <td colspan="3"><?php echo nl2br($notes); ?></td>
    </tr>
  <?php endif; ?>

  <?php if (isset($append_to_table)) echo $append_to_table; ?>
</table>
