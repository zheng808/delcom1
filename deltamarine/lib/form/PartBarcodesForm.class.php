<?php

class PartBarcodesForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'format'    => new sfWidgetFormChoice(array('choices' => array('30up' => '30 Per Page (Avery 5160 Compatible)',
                                                                      '80up' => '80 Per Page (Avery 5167 Compatible)'),
                                                  'multiple' => false, 'expanded' => true)),
      'price'     => new sfWidgetFormSelectCheckbox(array('choices' => array('1' => 'Yes, show price'),
                                                    'class' => 'radio_list')),
      'name'      => new sfWidgetFormSelectCheckbox(array('choices' => array('1' => 'Yes, show part names'),
                                                    'class' => 'radio_list')),
      'offset'    => new sfWidgetFormInput(array(), array('size' => 3))
    ));

    $this->setDefaults(array('format' => '30up', 'price' => 1, 'name' => 1, 'offset' => 0));
    $this->widgetSchema->setHelps(array('offset' => 'You can re-use a partially used sheet of labels by specifying here how many labels '.
                                                    'to skip over. Labels are printed from left to right across the sheet, then down. Count '.
                                                    'the number of already-used labels and enter it here.',
                                        'format' => 'Note that if selecting the "80 per page" option, you should use the high-quality setting '.
                                                    'for your inkjet printer, or use a laser printer-- to ensure the barcode can be scanned '.
                                                    'reliably.'));

    $this->setValidators(array(
      'format'   => new sfValidatorChoice(array('choices' => array('30up', '80up'))),
      'price'    => new sfValidatorPass(),
      'name'     => new sfValidatorPass(),
      'offset'   => new sfValidatorNumber(array('min' => 0, 'max' => 79)),
    ));

  }
}
