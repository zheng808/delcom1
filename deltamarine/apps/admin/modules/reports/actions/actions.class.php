<?php

/**
 * reports actions.
 *
 * @package    deltamarine
 * @subpackage reports
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class reportsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //$this->forward('default', 'module');
  }

  public function executeTimelogs(sfWebRequest $request)
  {
  }

  public function executePartsCSV(sfWebRequest $request)
  {
  }



}
