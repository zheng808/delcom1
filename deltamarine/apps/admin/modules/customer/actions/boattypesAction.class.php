<?php

class boattypesAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $two_dim = false;

    if ($request->getParameter('makeonly'))
    {
      $types = CustomerBoatPeer::getBoatMakes($request->getParameter('query'));
    }
    else if ($request->getParameter('modelonly'))
    {
      $types = CustomerBoatPeer::getBoatModels($request->getParameter('make'), $request->getParameter('query'));
    }
    else
    {
      $types = CustomerBoatPeer::getBoatTypes($request->getParameter('query'));
      $two_dim = true;
    }

    if ($two_dim)
    {
      //generate JSON output
      $typesarray = array();
      foreach ($types AS $make => $models)
      {
        $typesarray[] = array('id' => $make.'::',
                             'make' => $make,
                             'model' => '',
                             'desc' => $make . ' (All Models)'
                            );
        foreach ($models AS $model)
        {
          $typesarray[] = array('id'    => $make.'::'.$model,
                               'make'  => $make,
                               'model' => $model,
                               'desc'  => $make .' '. $model
                              );
        }
      }
    }
    else
    {
      $typesarray = array();
      foreach ($types AS $type)
      {
        $typesarray[] = array('info' => $type);
      }
    }

    $totalcount = count($typesarray);
    $dataarray = array('totalCount' => $totalcount, 'types' => $typesarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
