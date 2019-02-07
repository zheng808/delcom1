<?php

/**
 * CustomerReturn form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CustomerReturnForm extends BaseCustomerReturnForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'customer_order_id' => new sfWidgetFormInputHidden(),//new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'date_returned'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'CustomerReturn', 'column' => 'id', 'required' => false)),
      'customer_order_id' => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'date_returned'     => new sfValidatorDateTime(array('required' => false)),
    ));
    $this->widgetSchema->setNameFormat('customer_return[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
