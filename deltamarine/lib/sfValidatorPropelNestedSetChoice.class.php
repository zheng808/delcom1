<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorPropelChoice validates that the value is one of the rows of a table.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorPropelChoice.class.php 11669 2008-09-19 14:03:40Z fabien $
 */
class sfValidatorPropelNestedSetChoice extends sfValidatorPropelChoice
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * exclude_id: prevent selecting a node that is a child of the node with given id
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('exclude_id', false);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $criteria = is_null($this->getOption('criteria')) ? new Criteria() : clone $this->getOption('criteria');

    if ($this->getOption('multiple'))
    {
      if (!is_array($value))
      {
        $value = array($value);
      }

      $criteria->add($this->getColumn(), $value, Criteria::IN);

      $objects = call_user_func(array(constant($this->getOption('model').'::PEER'), 'doSelect'), $criteria, $this->getOption('connection'));

      if (count($objects) != count($value))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }

      else if ($this->getOption('exclude_id'))
      {
        if ($exclude = call_user_func(array(constant($this->getOption('model').'::PEER'), 'getNode'), $this->getOption('exclude_id'), $this->getOption('connection')))
        {
          foreach ($objects AS $object)
          {
            if (call_user_func(array(constant($this->getOption('model').'::PEER'), 'isChildOf'), $object, $exclude))
            {
              throw new sfValidatorError($this, 'invalid item selected', array('value' => $value));
            }
            if ($object->getPrimaryKey() == $exclude->getPrimaryKey())
            {
              throw new sfValidatorError($this, 'invalid item selected', array('value' => $value));
            }
          }
        }
      }
    }
    else
    {
      $criteria->add($this->getColumn(), $value);

      $object = call_user_func(array(constant($this->getOption('model').'::PEER'), 'doSelectOne'), $criteria, $this->getOption('connection'));

      if (is_null($object))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
      else if ($this->getOption('exclude_id'))
      {
        if ($exclude = call_user_func(array(constant($this->getOption('model').'::PEER'), 'getNode'), $this->getOption('exclude_id'), $this->getOption('connection')))
        {
          if (call_user_func(array(constant($this->getOption('model').'::PEER'), 'isChildOf'), $object, $exclude))
          {
            throw new sfValidatorError($this, 'invalid item selected', array('value' => $value));
          }
          if ($object->getPrimaryKey() == $exclude->getPrimaryKey())
          {
            throw new sfValidatorError($this, 'invalid item selected', array('value' => $value));
          }
        }
      }
    }

    return $value;
  }

}
