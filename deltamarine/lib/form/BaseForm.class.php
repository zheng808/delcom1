<?php

/**
 * Project form base class.
 *
 * @package    form
 * @version    SVN: $Id: sfPropelFormBaseTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class BaseForm extends sfForm
{

    const REQUIRED_CLASS_NAME = 'required';

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
    {
        parent::__construct($defaults, $options, $CSRFSecret);

        $this->postSetup();
    }

    final private function postSetup()
    {
        $this->handleRequiredFields();
    }

    protected function handleRequiredFields()
    {
        if (!$this->validatorSchema)
        {
            return;
        }

        foreach ($this->validatorSchema->getFields() as $fieldName => $validator)
        {
            /* @var $validator sfValidatorBase */
            if (true === $validator->getOption('required'))
            {
                if (!array_key_exists($fieldName, $this->widgetSchema->getFields()))
                {
                    continue;
                }

                /* @var $widget sfWidget */
                $widget = $this->widgetSchema[$fieldName];

                $class = trim($widget->getAttribute('class'));

                if (!$class)
                {
                    $class = self::REQUIRED_CLASS_NAME;
                }
                else if (!preg_match(sprintf('/%s/i', self::REQUIRED_CLASS_NAME), $class))
                {
                    $class = sprintf('%s%s', $class, self::REQUIRED_CLASS_NAME);
                }

                $widget->setAttribute('class', $class);
            }
        }
    }

    public function setup()
    {
      sfWidgetFormSchema::setDefaultFormFormatterName('divs');
    }

    protected function bindEmbeddedForms(array $taintedValues, array $taintedFiles)
    {
      if ($this->embeddedForms != null)
      {
        foreach ($this->embeddedForms AS $name => $form)
        {
          $values = (isset($taintedValues[$name]) ? $taintedValues[$name] : array());
          $files = (isset($taintedFiles[$name]) ? $taintedFiles[$name] : array());
          $form->bind($values, $files);
        }
      }
    }

}
