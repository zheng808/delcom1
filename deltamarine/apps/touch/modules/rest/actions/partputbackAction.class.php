<?php

class partputbackAction extends SfAction {

  public function execute ($request)
  {
    $this->forward404Unless($inst = PartInstancePeer::retrieveByPk($request->getParameter('id')));

    $result = true;
    $errors = array();
    $used_date = $inst->getDateUsed('Y-m-d');
  
    $orig_qty = (float) $inst->getQuantity();
    $putback_qty = (float) $request->getParameter('quantity');
    $new_qty = $orig_qty - $putback_qty;

    if ($new_qty < 0)
    {
      $result = false;
      $errors[] = 'You cannot put back more of an item than you took!';
    }
    if ($putback_qty == 0)
    {
      $result = false;
      $errors[] = 'You can\'t put back a zero quantity of the part';
    }

    if (!$result)
    {
      $this->getResponse()->setContentType('application/json');
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
      return sfView::NONE;
    }
    if ($new_qty == 0)
    {
      $inst->delete();
    }
    else if ($new_qty < $orig_qty)
    {
      if ($redeliver = $inst->getDelivered())
      {
        $inst->undeliver();
      }
      $inst->setQuantity($new_qty);
      $inst->save();
      if ($redeliver)
      {
        $inst->deliver();
      }
      $inst->getWorkorderItem()->calculateActualPart();
      $inst->getPartVariant()->calculateCurrentOnHand();
      $inst->getPartVariant()->calculateCurrentOnHold();
    }

    $this->getResponse()->setContentType('application/json');
    $this->renderText(json_encode(array('success' => true, 'date_string' => $used_date)));
    return sfView::NONE;
  }

}
