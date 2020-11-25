<?php

/**
 * barcode actions.
 *
 * @package    deltamarine
 * @subpackage barcode
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class barcodeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executePart(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START barcodeActions.executePart------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $include_price = $request->getParameter('price', false);
    $include_name  = $request->getParameter('name', false);
    $variants      = $request->getParameter('variants', array());
    $format        = $request->getParameter('format', '30up');
    $codetype      = $request->getParameter('codetype', 'C39');
    $offset        = $request->getParameter('offset', 0);

    switch ($format)
    {
    case '30up':
      $pdf = new BarcodeLabel30Up($offset);
      break;
    case '80up':
      $pdf = new BarcodeLabel80Up($offset);
      break;
    case 'single25':
      $pdf = new BarcodeLabelSingle25();
      break;
    case 'single28':
      $pdf = new BarcodeLabelSingle28();
      break;
    case 'single36':
      $pdf = new BarcodeLabelSingle36();
      break;
    }

    $c = new Criteria();
    $c->add(PartVariantPeer::ID, array_keys($variants), Criteria::IN);
    $vars = PartVariantPeer::doSelectJoinPart($c);

    //make sure there's something to do before continuing
    if (count($vars) == 0)
    {
      die('Error: No barcodes to print! Click Back in your browser and try again.');
    }

    foreach ($vars AS $var)
    {
      sfContext::getInstance()->getLogger()->info($var->getPart()->getName());

      $code = '-'.sprintf('%05d', $var->getId());
      $pdf->generateSingle($code,
                           $codetype,
                           ($include_name ? $var->getPart()->getName() : null),
                           ($var->getInternalSku() ? $var->getInternalSku() : null),
                           ($include_price ? $var->outputUnitPrice() : null),
                           $variants[$var->getId()]); //quantity
    }


    $pdf->Output('barcodes.pdf', 'D');
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE barcodeActions.executePart------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }
    
    die();
  }
}
