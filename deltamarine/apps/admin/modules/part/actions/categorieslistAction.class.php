<?php

class categorieslistAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $tree = PartCategoryPeer::retrieveAllTree();
    $output = array();
    foreach ($tree AS $child)
    {
      $output[] = array('id' => $child->getId(), 'text' => $child->getNameWithLevel(' ', 6));
    }

    $this->renderText('{success:true,categories:'.json_encode($output).'}');

    return sfView::NONE;
  }

}
