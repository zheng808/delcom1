<?php

/**
 * Part form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartForm extends BasePartForm
{
  public function configure()
  {
    unset($this['is_multisku']);

    $this->widgetSchema['manufacturer_name'] = new sfWidgetFormInput(array(), array('size' => 20));
    $this->widgetSchema['manufacturer_name']->setLabel('Manufacturer');
    $this->validatorSchema['manufacturer_name'] = new sfValidatorString(array('required' => false));
    if (!$this->isNew())
    {
      $manufacturer = $this->getObject()->getManufacturer();
      $this->widgetSchema->setDefault('manufacturer_name', $manufacturer ? $manufacturer : null);
    }

    //set renderer
    $this->getWidgetSchema()->setFormFormatterName('cells');


    $this->widgetSchema['has_serial_number'] = new sfWidgetFormSelectRadio(array(
        'choices' => array('1' => 'Yes', '0' => 'No'),
        'formatter' => array($this, 'radioformatter'),
        'separator' => '&nbsp;&nbsp;'));
    $this->widgetSchema->setHelp('has_serial_number', 'If set to "yes", then whenever one of these parts is used, '.
                                                      'you can optionally enter (or scan) the serial number of the '.
                                                      'part to keep for future reference or waranty purposes.');
    $this->widgetSchema['has_serial_number']->setLabel('Track Serial Numbers');

    $this->widgetSchema['manufacturer_id']->setLabel('Manufacturer');
    $this->widgetSchema['manufacturer_id']->setAttribute('style', 'width: 200px;');
    $this->widgetSchema->moveField('manufacturer_id', sfWidgetFormSchema::AFTER, 'name');

    $this->widgetSchema->moveField('part_category_id', sfWidgetFormSchema::AFTER, 'description');
    $this->widgetSchema['part_category_id'] = new sfWidgetFormPropelChoice(array(
        'model' => 'PartCategory',
        'peer_method' => 'retrieveAllTree',
        'method' => 'getNameWithLevel',
      ), array('style' => 'width: 200px;'));

    $this->validatorSchema['part_category_id'] = new sfValidatorPropelChoice(
        array('model' => 'PartCategory'),
        array('invalid' => 'Invalid Part Category')
      );
    $this->widgetSchema['part_category_id']->setLabel('Part Category');

    $this->widgetSchema['description']->setAttribute('rows', '2');
    $this->widgetSchema['description']->setAttribute('cols', '57');

  }

  public function bind(array $taintedValues = array(), array $taintedFiles = array())
  {
    //add manufacturer (or remove input if not filled in)
    if (isset($taintedValues['manufacturer_name']) && strlen($taintedValues['manufacturer_name']) > 0)
    {
      $manu = ManufacturerPeer::retrieveOrCreateByName($taintedValues['manufacturer_name']);
      $taintedValues['manufacturer_id'] = $manu->getId();
    }
    else
    {
      $taintedValues['manufacturer_id'] = null;
    }

    parent::bind($taintedValues, $taintedFiles);
  }

  public function radioformatter($widget, $inputs)
  {
    $output = array();
    foreach ($inputs AS $input)
    {
      $output[] = $input['input'].$input['label'];
    }

    return (implode('&nbsp;&nbsp;&nbsp;', $output));
  }
}
