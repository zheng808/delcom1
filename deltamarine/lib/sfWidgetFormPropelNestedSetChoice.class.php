<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormPropelNestedSetChoice represents a choice widget for a model.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormPropelChoice.class.php 12803 2008-11-09 07:26:18Z fabien $
 */
class sfWidgetFormPropelNestedSetChoice extends sfWidgetFormPropelChoice
{

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * exclude_id:  If specified, the node with this id and any descendants will be excluded
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('exclude_id', null);

    parent::configure($options, $attributes);
  }

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->getOption('add_empty');
    }

    $class = constant($this->getOption('model').'::PEER');

    $criteria = is_null($this->getOption('criteria')) ? new Criteria() : clone $this->getOption('criteria');
    if ($order = $this->getOption('order_by'))
    {
      $method = sprintf('add%sOrderByColumn', 0 === strpos(strtoupper($order[1]), 'ASC') ? 'Ascending' : 'Descending');
      $criteria->$method(call_user_func(array($class, 'translateFieldName'), $order[0], BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME));
    }

    $objects = call_user_func(array($class, $this->getOption('peer_method')), $criteria, $this->getOption('connection'));

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model'), $methodKey))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodKey, __CLASS__));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
    }

    $exclude = false;
    if (false !== $this->getOption('exclude_id'))
    {
      $exclude = call_user_func(array(constant($this->getOption('model').'::PEER'), 'getNode'), $this->getOption('exclude_id'), $this->getOption('connection'));
    }

    foreach ($objects as $object)
    {
      if (!$exclude || ($exclude->getPrimaryKey() != $object->getPrimaryKey() && !call_user_func(array(constant($this->getOption('model').'::PEER'), 'isChildOf'), $object, $exclude)))
      {
        $choices[$object->$methodKey()] = $object->$methodValue();
      }
    }

    return $choices;
  }
}
