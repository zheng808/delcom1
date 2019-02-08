<?php

class workorderItemsAction extends sfAction
{
  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $workorder_id = $request->getParameter('workorder_id');

    $message = $workorder_id;
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info($message);
    }


    $items = null;
		$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		$criteria->add(WorkorderItemPeer::WORKORDER_ID, $workorder_id);
		$items = WorkorderItemPeer::doSelect($criteria, $con);

    $count_all = count($items);
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info($count_all);
    }

    $woItems = WorkorderItemPeer::getItemsByWorkordeId($workorder_id);
    $message = count($woItems);
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info($message);
    }  
    
    //generate JSON output
    $itemsarray = array();
    $i = 0;
    foreach ($items AS $item)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = $item->getLabel();
        sfContext::getInstance()->getLogger()->info($message);
      }

      $text = '';
      if ($item->getLabel() && $item->getLabel() !== null)
      {
        $i = $i + 1;
        $text = '[Task '.$i.'] '.$item->getLabel();
      }
      $itemsarray[] = array('id'    => $item->getId(),
                           'workorder_id'  => $item->getWorkorderId(), 
                           'label'  => $item->getLabel(),
                           'text'  => $text
                          );
      }

    $itemsarray = array('totalCount' => $count_all, 'items' => $itemsarray);

    $this->renderText(json_encode($itemsarray));


    return sfView::NONE;
  }


  }//workorderItemsAction{}=======================================================
