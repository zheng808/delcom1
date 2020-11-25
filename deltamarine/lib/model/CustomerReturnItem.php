<?php

class CustomerReturnItem extends BaseCustomerReturnItem
{
  public function outputQuantityCompleted($with_units = true)
  {
    $amount = $this->getQuantityCompleted();

    return round($amount,3).(($with_units && $this->getPartInstance()->getPartVariant()->getUnits()) ? ' '.$this->getPartInstance()->getPartVariant()->getUnits() : '');
  }
  
  /*
   * called ONCE, when creating. this restores returned inventory to stock.
   */
  public function putBackInInventory()
  {
    if ($this->getPartInstance()->getPartVariant()->getTrackInventory())
    { 
      $lot = true;

      $quantity_needing_return = (-1 * $this->getPartInstance()->getQuantity());

      while ($quantity_needing_return > 0 && $lot)
      {
        $lot = false;
        
        //find the first non-full lot
        $c = new Criteria();
        $c->add(PartLotPeer::PART_VARIANT_ID, $this->getPartInstance()->getPartVariantId());
        $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
        $c->add(PartLotPeer::QUANTITY_RECEIVED, PartLotPeer::QUANTITY_RECEIVED.'>'.PartLotPeer::QUANTITY_REMAINING, Criteria::CUSTOM);
        if ($this->getPartInstance()->getPartVariant()->getCostCalculationMethod() == 'lifo')
        {
          $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
        }
        else
        {
          $c->addDecendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
        }
        $lot = PartLotPeer::doSelectOne($c);

        //next, try an empty lot
        if (!$lot)
        {
          $c = new Criteria();
          $c->add(PartLotPeer::PART_VARIANT_ID, $this->getPartInstance()->getPartVariantId());
          $c->add(PartLotPeer::QUANTITY_REMAINING, 0);
          if ($this->getPartInstance()->getPartVariant()->getCostCalculationMethod() == 'lifo')
          {
            $c->addDescendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
          }
          else
          {
            $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
          }
          $lot = PartLotPeer::doSelectOne($c);
        }

        //there is no reason why lot should not be set by now, if inventory is tracked.
        if (!$lot)
        {
          break;  
        }

        $amount_left = ($lot->getQuantityReceived() - $lot->getQuantityRemaining());
        if (($lot->getQuantityReceived() - $lot->getQuantityRemaining()) < $quantity_needing_return)
        {
          $quantity_needing_return -= ($lot->getQuantityReceived() - $lot->getQuantityRemaining());
          $lot->setQuantityRemaining($lot->getQuantityReceived());
        }
        else
        {
          $lot->setQuantityRemaining($lot->getQuantityRemaining() + $quantity_needing_return);
          $quantity_needing_return = 0;
        }
        $lot->save();
      }

      $this->getPartInstance()->getPartVariant()->calculateCurrentOnHand();
      $this->getPartInstance()->getPartVariant()->calculateCurrentOnHold();
    }
  }


  public function delete (PropelPDO $con = null)
  {
    $inst = $this->getPartInstance();
    $quantity_to_redeliver = $inst->getQuantity();
    $this->getCustomerOrderItem()->getPartInstance()->deliver($quantity_to_redeliver);

    parent::delete($con);

    $inst->delete();
  }
}
