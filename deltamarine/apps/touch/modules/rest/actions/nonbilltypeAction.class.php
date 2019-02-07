<?php

class nonbilltypeAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //generate JSON output
    $types = array();
    foreach (NonbillTypePeer::loadTypesArray() AS $id => $type)
    {
      $types[] = array('id' => $id, 'name' => $type['name']);
    }

    if ($request->getParameter('id'))
    {
      return array('success' => true, 'types' => array((isset($types[$id]) ? $types[$id] : null)));
    }   
    else
    {
      return array('success' => true, 'types' => array_values($types));
    }
  }
}
