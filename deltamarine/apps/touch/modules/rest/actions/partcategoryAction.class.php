<?php

class partcategoryAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {

    //send back just one if it is requested
    if ($request->getParameter('id')){
      $cat = PartCategoryPeer::retrieveByPk($request->getParameter('id'));

      return array('totalCount' => 1, 'categories' => array($cat));
    }

    //build the query of categories
    $root = PartCategoryPeer::retrieveRoot();
    $cats = $this->addChildNodes($root, true);
    
    return array('success' => true, 'categories' => $cats);
  }

  private function addChildNodes($node, $is_root)
  {
    $output = array();
    if (!$is_root)
    {
      $output = array(
        'id' => $node->getId(),
        'leaf' => true,
        'name' => $node->getName()
      );
    }

    //recurse down into the tree
    $children = $node->getChildren();
    $children_arr = array();
    if ($is_root)
    {
      $children_arr['000'] = array('id' => 0, 'leaf' => true, 'name' => '-- ALL --');
    }
    foreach ($children AS $child)
    {
      $children_arr[strtolower($child->getName())] = $this->addChildNodes($child, false);
    }
    ksort($children_arr);
    $children_arr = array_values($children_arr);
    if ($is_root)
    {
      $output = $children_arr;
    }
    else if (count($children_arr))
    {
      $output['leaf'] = false;
      $output['categories'] = $children_arr;
    }

    return $output;
  }

}
