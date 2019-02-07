<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMForm extends BasewfCRMForm
{

  public function configure()
  {
    unset($this['tree_left']);
    unset($this['tree_id']);
    unset($this['tree_right']);
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['parent_node_id']);
		unset($this['alpha_name']);
    unset($this['is_in_addressbook']);
    
    $this->validatorSchema->setPreValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'preValidationHandler')),array('invalid'=>'Invalid parent node (self)')));
    
    $this->widgetSchema['wf_crm_category_ref_list'] = new sfWidgetFormPropelChoiceMany(array(
        'model' => 'wfCRMCategory', 
        'method' => 'getNameWithLevel'));
    $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => false));
    
    $this->getWidgetSchema()->setLabels(array('wf_crm_category_ref_list' => 'Categories'));
    
    $this->parentNodeWidget();
  }

  private function parentNodeWidget()
  {
    if (!$this->getObject()->isNew())
    {
      $parent = $this->getObject()->retrieveParent();
      if ($parent)
      {
        $c = new Criteria();
        $c->add(wfCRMPeer::TREE_ID, $this->getObject()->getTreeId());
        $this->widgetSchema['parent_node'] = new sfWidgetFormPropelChoice(array(
            'criteria' => $c, 
            'model' => 'wfCRM', 
            'multiple' => false, 
            'peer_method' => 'retrieveAllCompaniesTree', 
            'method' => 'getNameWithLevel'));
        $this->validatorSchema['parent_node'] = new sfValidatorPropelChoice(array(
            'model' => 'wfCRM'), array(
            'invalid' => 'Invalid parent node'));
        $this->defaults['parent_node'] = $parent->getId();
      }
    }
    else
    {
      $this->widgetSchema['parent_node'] = new sfWidgetFormPropelChoice(array(
          'add_empty' => true, 
          'model' => 'wfCRM', 
          'multiple' => false, 
          'peer_method' => 'retrieveAllCompaniesTree', 
          'method' => 'getNameWithLevel'));
      $this->validatorSchema['parent_node'] = new sfValidatorPropelChoice(array(
          'model' => 'wfCRM', 
          'required' => false), array('invalid' => 'Invalid parent node'));
    }
    if (isset($this->widgetSchema['parent_node']))
    {
      $this->getWidgetSchema()->setLabels(array('parent_node' => 'Parent'));
      $this->getWidgetSchema()->moveField('parent_node', sfWidgetFormSchema::FIRST);
    }
  }

  public function preValidationHandler($validator, $value, $arguments)
  {
    if (isset($value['is_company']) && $value['is_company'])
    {
      $this->validatorSchema['department_name']->addOption('required', true);
    }
    else
    {
      $this->validatorSchema['first_name']->addOption('required', true);
    }
    
    if (isset($value['parent_node']) && !empty($value['parent_node']) && $value['parent_node'] == $value['id'])
    {throw new sfValidatorError($validator, 'invalid', array('value' => $value));}
  }

  public function updateParentNodeColumn($value)
  {
    $this->getObject()->setParentNodeId($value);
    return false;
  }
}
