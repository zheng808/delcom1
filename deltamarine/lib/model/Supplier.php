<?php

class Supplier extends BaseSupplier
{
  public function __toString()
  {
    return $this->getName();
  }

  //proxy/convenience function. blindly selects first address for now.
  public function getAddress($line_break = "\n", $home_country = null)
  {
    $addresses = $this->getCRM()->getwfCRMAddresss();

    return ($addresses ? $addresses[0]->getAddress($linebreak, $home_country) : null);
  }

  public function getLatestOpenOrder()
  {
    $c = new Criteria();
    $c->add(SupplierOrderPeer::SUPPLIER_ID, $this->getId());
    $c->add(SupplierOrderPeer::FINALIZED, false);
    $c->addDescendingOrderByColumn(SupplierOrderPeer::ID);
    return SupplierOrderPeer::doSelectOne($c);
  }
}

sfPropelBehavior::add('Supplier', array('wfCRMBehavior'));
