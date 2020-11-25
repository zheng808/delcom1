<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * 
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterList.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterDivs extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<div class=\"formrow%haserror%\">\n  %label%\n  <div class=\"formrow-fields\">%help%%required%%field%%error%\n%hidden_fields%</div></div>\n",
    $errorRowFormat  = "<div class=\"formrow-error\">\n%errors%</div>\n",
    $helpFormat      = '<a class="formrow-help" href="#" onclick="alert(\'%help%\'); return false;">?</a>',
    $decoratorFormat = "%content%",
    $errorListFormatInARow     = "  <ul class=\"formrow-errors\">\n%errors%  </ul>\n",
    $errorRowFormatInARow      = "    <li>%error%</li>\n",
    $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";

    public function formatRow($label, $field, $errors = array(), $help ='', $hiddenFields = null)
    {
        return strtr($this->getRowFormat(), array(
          '%required%'      => strstr($field, 'required') ? '<span title="Required Field" class="requiredmark">*</span>' : '',
          '%haserror%'      => (is_null($errors) || !$errors) ? '' : ' haserror',
          '%label%'         => $label,
          '%field%'         => $field,
          '%error%'         => $this->formatErrorsForRow($errors),
          '%help%'          => $this->formatHelp($help),
          '%hidden_fields%' => is_null($hiddenFields) ? '%hidden_fields%' : $hiddenFields,
        ));
    } 

  public function formatHelp($help)
  {
    if (!$help)
    {
      return '';
    }

    return strtr($this->getHelpFormat(), array('%help%' => htmlspecialchars($this->translate($help), ENT_QUOTES)));
  }

  public function generateLabelName($name)
  {
    $label = $this->widgetSchema->getLabel($name);

    if (!$label && false !== $label)
    {
      $label = ucwords(str_replace('_', ' ', $name));
    }
    $label = $label.':';

    return $this->translate($label);
  }
}
