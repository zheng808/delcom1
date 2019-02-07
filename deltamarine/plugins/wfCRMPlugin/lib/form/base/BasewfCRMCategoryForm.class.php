<?php

/**
 * wfCRMCategory form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'tree_left'                => new sfWidgetFormInput(),
      'tree_right'               => new sfWidgetFormInput(),
      'tree_id'                  => new sfWidgetFormInput(),
      'parent_node_id'           => new sfWidgetFormPropelChoice(array('model' => 'wfCRMCategory', 'add_empty' => true)),
      'private_name'             => new sfWidgetFormInput(),
      'public_name'              => new sfWidgetFormInput(),
      'is_subscribable'          => new sfWidgetFormInputCheckbox(),
      'wf_crm_category_ref_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'wfCRM')),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorPropelChoice(array('model' => 'wfCRMCategory', 'column' => 'id', 'required' => false)),
      'tree_left'                => new sfValidatorInteger(array('required' => false)),
      'tree_right'               => new sfValidatorInteger(array('required' => false)),
      'tree_id'                  => new sfValidatorInteger(array('required' => false)),
      'parent_node_id'           => new sfValidatorPropelChoice(array('model' => 'wfCRMCategory', 'column' => 'id', 'required' => false)),
      'private_name'             => new sfValidatorString(array('max_length' => 255)),
      'public_name'              => new sfValidatorString(array('max_length' => 255)),
      'is_subscribable'          => new sfValidatorBoolean(),
      'wf_crm_category_ref_list' => new sfValidatorPropelChoiceMany(array('model' => 'wfCRM', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMCategory';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['wf_crm_category_ref_list']))
    {
      $values = array();
      foreach ($this->object->getwfCRMCategoryRefs() as $obj)
      {
        $values[] = $obj->getCrmId();
      }

      $this->setDefault('wf_crm_category_ref_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->savewfCRMCategoryRefList($con);
  }

  public function savewfCRMCategoryRefList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['wf_crm_category_ref_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(wfCRMCategoryRefPeer::CATEGORY_ID, $this->object->getPrimaryKey());
    wfCRMCategoryRefPeer::doDelete($c, $con);

    $values = $this->getValue('wf_crm_category_ref_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new wfCRMCategoryRef();
        $obj->setCategoryId($this->object->getPrimaryKey());
        $obj->setCrmId($value);
        $obj->save();
      }
    }
  }

}
