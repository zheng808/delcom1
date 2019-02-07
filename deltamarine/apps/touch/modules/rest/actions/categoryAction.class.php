<?php

class categoryAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //build the query of valid employees for touch app
    $c = new Criteria();

    //generate JSON output
    $cats = array();
    $cats[] = array('id' => 'all', 'name' => ' [All Categories]');
    $cats[] = array('id' => 'none', 'name' => ' [Uncategorized]');
    foreach (WorkorderCategoryPeer::loadCatsArray() AS $id => $cat)
    {
      $cats[] = array('id' => $id, 'name' => $cat['name']);
    }
    
    return array('success' => true, 'categories' => $cats);
  }
}
