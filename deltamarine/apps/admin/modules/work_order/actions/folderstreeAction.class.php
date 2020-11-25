<?php

class folderstreeAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    //must check for root nodes
    $nodeid = $request->getParameter('node');
    if (strpos($nodeid, 'root-') === 0){
      $nodeid = substr($nodeid, 5);
      $node = WorkorderItemPeer::retrieveRoot($nodeid);
      $output_root = true;
      $is_root = true;
    }
    else
    {
      $node = WorkorderItemPeer::retrieveByPk($nodeid);
      $output_root = false;
      $is_root = true;
    }

    if ($node) 
    {
      $output = $this->addChildNodes($node, $output_root, $is_root, false);
    }
    else
    {
      $output = array();
    }
    $this->renderText(json_encode($output));

    return sfView::NONE;
  }



  private function addChildNodes($node, $output_root, $is_root, $prefix = false, $number = 1)
  {
    $output = array();
    if (!$prefix) 
    { 
      $prefix = array();
    }
    if (!$is_root)
    {
      $numbering = $prefix;
      $numbering[] = $number;
      $numbering = implode('.', $numbering);      
      $prefix[] = $number;
    }
    if ($output_root)
    {
      $output = array(
        'id' => $node->getId(),
        'leaf' => true,
        'text' => '[Task '.$numbering.'] '.$node->getLabel(),
        'iconCls' => 'folder'
      );
    }

    //recurse down into the tree
    $children = $node->getChildren();
    $children_arr = array();
    foreach ($children AS $idx => $child)
    {
      $number = $idx + 1;
      $children_arr[] = $this->addChildNodes($child, true, false, $prefix, $number);
    }
    if ($is_root)
    {
      $output = $children_arr;
    }
    else
    {
      $output['leaf'] = false;
      $output['expanded'] = true;
      $output['children'] = $children_arr;
    }

    return $output;
  }


}
