<?php

abstract class restInterfaceAction extends sfAction
{
  public function execute($request)
  {
    $method = strtolower($request->getMethod());
    if (method_exists($this, $method))
    {
      if ($method != 'get')
      {
        $request = json_decode(file_get_contents('php://input'));
      }
      if (!$request)
      {
        $result = array('error' => 'Could not read "'.$method.'" method data');
      }
      else
      {
        //forward on to the appropriate method in the Action class
        $result = $this->{$method}($request); 
      }
    }
    else
    {
      $result = array('error' => 'Data Accessor Method invalid.');
    }

    $this->getResponse()->setContentType('application/json');
    $this->renderText(json_encode($result));
    return sfView::NONE;
  }
}
