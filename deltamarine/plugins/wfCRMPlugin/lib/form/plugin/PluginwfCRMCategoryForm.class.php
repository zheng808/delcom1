<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMCategoryForm extends BasewfCRMCategoryForm
{

  public function configure()
  {
    unset($this['tree_left']);
    unset($this['tree_id']);
    unset($this['tree_right']);
    unset($this['parent_node_id']);
    unset($this['wf_crm_category_ref_list']);
    
    $this->parentNodeWidget();
    
    $this->validatorSchema->setPreValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'preValidationHandler')),array('invalid'=>'Invalid parent node (self)')));
  }
  
  public function preValidationHandler($validator, $value, $arguments)
  {
    if (isset($value['parent_node']) && !empty($value['parent_node']) && $value['parent_node'] == $value['id'])
    {throw new sfValidatorError($validator, 'invalid', array('value' => $value));}
  }
  
  private function parentNodeWidget()
  {
    if (!$this->getObject()->isNew())
    {
      $parent = $this->getObject()->retrieveParent();
      if ($parent)
      {
        $c = new Criteria();
        $c->add(wfCRMCategoryPeer::TREE_ID, $this->getObject()->getTreeId());
        $this->widgetSchema['parent_node'] = new sfWidgetFormPropelChoice(array(
            'criteria' => $c, 
            'model' => 'wfCRMCategory', 
            'multiple' => false, 
            'peer_method' => 'retrieveAllTree', 
            'method' => 'getNameWithLevel'));
        $this->validatorSchema['parent_node'] = new sfValidatorPropelChoice(array(
            'model' => 'wfCRMCategory'), array(
            'invalid' => 'Invalid parent node'));
        $this->defaults['parent_node'] = $parent->getId();
      }
    }
    else
    {
      $this->widgetSchema['parent_node'] = new sfWidgetFormPropelChoice(array(
          'add_empty' => true,
          'model' => 'wfCRMCategory', 
          'multiple' => false, 
          'peer_method' => 'retrieveAllTree', 
          'method' => 'getNameWithLevel'));
      $this->validatorSchema['parent_node'] = new sfValidatorPropelChoice(array(
          'model' => 'wfCRMCategory', 'required' => false), array('invalid' => 'Invalid parent node'));
    }
    
    $this->getWidgetSchema()->setLabels(array('parent_node' => 'Parent'));
  }

  public function updateParentNodeColumn($value)
  {
    $this->getObject()->setParentNodeId($value);
    return false;
  }
}
