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
class sfWidgetFormSchemaFormatterCells extends sfWidgetFormSchemaFormatterDivs
{
  protected
    $rowFormat       = "<td class=\"subheader\"%rows%>%label%</td>\n<td%cols%%rows%%haserror%>%help%%required%%field%%error%\n%hidden_fields%</td>\n",
    $errorRowFormat  = "<div class=\"formrow-error\">\n%errors%</div>\n",
    $helpFormat      = '<a class="formrow-help" href="#" onclick="alert(\'%help%\'); return false;">?</a>',
    $decoratorFormat = "%content%",
    $errorListFormatInARow     = "  <ul class=\"formrow-errors\">\n%errors%  </ul>\n",
    $errorRowFormatInARow      = "    <li>%error%</li>\n",
    $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";

    public function formatRow($label, $field, $errors = array(), $help ='', $hiddenFields = null)
    {
      $cols = (preg_match('/cols([0-9])/', $field, $matches) ? ' colspan="'.$matches[1].'"' : '');
      $rows = (preg_match('/rows([0-9])/', $field, $matches) ? ' rowspan="'.$matches[1].'"' : '');
      return strtr($this->getRowFormat(), array(
          '%rows%'          => $rows,
          '%cols%'          => $cols,
          '%required%'      => strstr($field, 'required') ? '<span title="Required Field" class="requiredmark">*</span>' : '',
          '%haserror%'      => (is_null($errors) || !$errors) ? '' : ' class="haserror"',
          '%label%'         => $label,
          '%field%'         => $field,
          '%error%'         => $this->formatErrorsForRow($errors),
          '%help%'          => $this->formatHelp($help),
          '%hidden_fields%' => is_null($hiddenFields) ? '%hidden_fields%' : $hiddenFields,
        ));
    } 
}
