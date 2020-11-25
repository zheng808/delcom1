<?php
if($contact){
$breadcrumbs = array('<a href="'.url_for('wfCRMPlugin/list'.($cat_id?'?cat='.$cat_id:'')).'">CRM</a>');
foreach ( $contact->getPath() as $el )
{
  $url = url_for('wfCRMPlugin/list?crm=' . $el->getId().'&cat='.$cat_id);
  if($el->getIsCompany())
    $breadcrumbs[] = '<a href="' . $url . '">' . $el->getName() . '</a>';
  else
   $breadcrumbs[] = $el->getName();?>
<?php
}
?>
<h3><?php echo join(' &gt; ', $breadcrumbs)?></h3>
<?php }?>
<?php
if($category){
$breadcrumbs = array('<a href="'.url_for('wfCRMPlugin/list'.($crm_id?'?crm='.$crm_id:'')).'">All</a>');
foreach ( $category->getPath() as $el )
{
  $url = url_for('wfCRMPlugin/list?cat=' . $el->getId().'&crm='.$crm_id);
  $breadcrumbs[] = '<a href="' . $url . '">' . $el->getPrivateName() . '</a>';
}
?>
<h3><?php echo join(' &gt; ', $breadcrumbs)?></h3>
<?php }?>