<?php use_helper('Javascript'); ?>
<div id="menu">
  <h2>Search by company:</h2>
  <p>
    <ul>
      <?php foreach ($crm_tree as $el){?>
        <li><?php echo str_repeat('&nbsp;', $el->getLevel())?><a href="<?php echo url_for('wfCRMPlugin/list?crm='.$el->getId().'&cat='.$cat_id)?>" class="<?php echo ($el->getId()==$crm_id?'active':'')?>"><?php echo $el->getName()?></a></li>
      <?php }?>
    </ul>
    <a href="<?php echo url_for('wfCRMPlugin/newDepartment')?>">Add new company</a>
  </p>
  <h2>Search by category:</h2>
  <p>
    <ul>
      <?php foreach ($crm_cat_tree as $el){?>
        <li><?php echo str_repeat('&nbsp;', $el->getLevel())?><a href="<?php echo url_for('wfCRMPlugin/list?cat='.$el->getId().'&crm='.$crm_id)?>" class="<?php echo ($el->getId()==$cat_id?'active':'')?>"><?php echo $el->getPrivateName()?></a></li>
      <?php }?>
    </ul>
    <a href="<?php echo url_for('wfCRMPlugin/listCategory')?>">Edit categories</a>
  </p>
  
  <h2>Search:</h2>
  <form action="<?php echo url_for('wfCRMPlugin/search')?>" method="post">
  <?php echo input_auto_complete_tag('query', $sf_params->get('query') ,'wfCRMPlugin/autosearch',
        array('autocomplete' => 'on'),
        array('after_update_element'  => 'function (inputField, selectedItem) { location.href = "' . url_for('wfCRMPlugin/show') . '?id=" + selectedItem.id; }',
           'indicator' => 'wf_crm_search_indicator',
         )
      );
  ?>
  <input type="submit" value="Search" />
  <div id='wf_crm_search_indicator' style='display: none;'>Loading...</div>
  </form> 
</div>                  