<?php

class categorieslistAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START categorieslistAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());

    $tree = PartCategoryPeer::retrieveAllTree();
    $output = array();
    foreach ($tree AS $child)
    {
      $output[] = array('id' => $child->getId(), 'text' => $child->getNameWithLevel(' ', 6));
    }

    $this->renderText('{success:true,categories:'.json_encode($output).'}');

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE categorieslistAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//categorieslistAction{}======================================================
