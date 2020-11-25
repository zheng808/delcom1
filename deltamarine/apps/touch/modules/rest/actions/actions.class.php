<?php

/**
 * rest actions.
 *
 * @package    deltamarine
 * @subpackage rest
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class restActions extends sfActions
{
  public function executeIndex()
  {
    $this->forward404();
  }
}
