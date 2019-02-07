<?php

/**
 * PartCategory form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartCategoryForm extends BasePartCategoryForm
{
  public function configure()
  {

    unset($this['lft'],
          $this['rgt'],
          $this['scope']);

    $this->widgetSchema['parent_node'] = new sfWidgetFormPropelNestedSetChoice(array(
        'model' => 'PartCategory',
        'peer_method' => 'retrieveAllTree',
        'method' => 'getNameWithLevel',
      ));

    $this->validatorSchema['parent_node'] = new sfValidatorPropelNestedSetChoice(
        array('model' => 'PartCategory'),
        array('invalid' => 'Invalid Parent Node')
      );

    if (!$this->getObject()->isNew())
    {
      $parent = $this->getObject()->retrieveParent();
      $this->defaults['parent_node'] = $parent->getId();
      $this->widgetSchema['parent_node']->setOption('exclude_id', $this->getObject()->getId());
      $this->validatorSchema['parent_node']->setOption('exclude_id', $this->getObject()->getId());
    }

    $this->widgetSchema['parent_node']->setLabel('Parent Category');
  }

  public function updateParentNodeColumn($value)
  {
    $category = $this->getObject();

    if ($parent = PartCategoryPeer::retrieveByPK($value))
    {
      $category->isNew() ? $category->insertAsLastChildOf($parent) : $category->moveToLastChildOf($parent);
    }

    return false;
  }


}
