<?php

class labourtypeAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //generate JSON output
    $types = array();
    foreach (LabourTypePeer::loadTypesArray(true) AS $id => $type)
    {
      $types[$id] = array('id' => $id, 'name' => $type['name'], 'rate' => $type['rate']);
    }

    if ($id = $request->getParameter('id'))
    {
      return array('success' => true, 'types' => array((isset($types[$id]) ? $types[$id] : null)));
    } 
    else
    {
      return array('success' => true, 'types' => array_values($types));
    }
  }
}
