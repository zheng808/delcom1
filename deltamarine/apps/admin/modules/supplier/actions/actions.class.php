<?php

/**
 * supplier actions.
 *
 * @package    deltamarine
 * @subpackage supplier
 * @author     Dave Achtemichuk
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class supplierActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'parts');
  }

 /*
  * Displays the datagrid of suppliers
  */ 
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * Views a single supplier record including related data
   */
  public function executeView(sfWebRequest $request)
  {
    $this->supplier = $this->loadSupplier($request);
    
    return sfView::SUCCESS; 
  }


  /*
   * Edits contact information of supplier
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $supplier = $this->loadSupplier($request);
    $contact = $supplier->getCRM();

    //PERFORM VALIDATION
    $result = true;
    $errors = array();

    //check for valid net_days
    if (!is_numeric($request->getParameter('net_days', 0)))
    {
      $result = false;
      $errors['net_days'] = 'Invalid payment terms selected';
    }
    //check for valid credit_limit
    if ($limit = $request->getParameter('credit_limit'))
    {
      if (!is_numeric($limit))
      {
        $result = false;
        $errors['net_days'] = 'Invalid credit limit. Must be positive number';
      }
    }

    //SAVE CHANGES
    if ($result)
    {
      $supplier->setAccountNumber($request->getParameter('account_number'));
      $supplier->setNetDays($request->getParameter('net_days'));
      $supplier->setCreditLimit($request->getParameter('credit_limit'));
      $supplier->save();
      $contact->setDepartmentName($request->getParameter('department_name'));
      $contact->setWorkPhone($request->getParameter('work_phone'));
      $contact->setFax($request->getParameter('fax'));
      $contact->setEmail($request->getParameter('email'));
      $contact->setHomepage($request->getParameter('homepage'));
      $contact->setPrivateNotes($request->getParameter('private_notes'));
      $contact->save();

      //update the address record
      $address = $contact->getWfCRMAddresss();
      $address = ($address ? $address[0] : new wfCRMAddress());
      if ($request->getParameter('address_line1') || $request->getParameter('address_line2') || $request->getParameter('address_city') || $request->getParameter('address_region'))
      {
        $address->setLine1($request->getParameter('address_line1'));
        $address->setLine2($request->getParameter('address_line2'));
        $address->setCity($request->getParameter('address_city'));
        $address->setRegion($request->getParameter('address_region'));
        $address->setPostal($request->getParameter('address_postal'));
        $address->setCountry($request->getParameter('address_country'));
        $address->setWfCRM($contact);
        $address->save();
      }
      else
      {
        $address->delete();
      }

      $this->renderText(json_encode(array('success' => true)));
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check errors and try again';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   * Add a new supplier to the system
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //PERFORM VALIDATION
    $result = true;
    $errors = array();

    //check for duplicate name
    $c = new Criteria();
    $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
    $c->add(wfCRMPeer::DEPARTMENT_NAME, $request->getParameter('department_name'));
    if (SupplierPeer::doSelectOne($c))
    {
      $result = false;
      $errors['department_name'] = 'Supplier with this name already exists!';
    }
    //check for valid net_days
    if (!is_numeric($request->getParameter('net_days', 0)))
    {
      $result = false;
      $errors['net_days'] = 'Invalid payment terms selected';
    }
    //check for valid credit_limit
    if ($limit = $request->getParameter('credit_limit'))
    {
      if (!is_numeric($limit))
      {
        $result = false;
        $errors['net_days'] = 'Invalid credit limit. Must be positive number';
      }
    }

    //SAVE CHANGES
    if ($result)
    {
      $contact = new wfCRM();
      $contact->setIsCompany(true);
      $contact->setParentNodeId(null); //set as root node in tree
      $contact->setDepartmentName($request->getParameter('department_name'));
      $contact->setWorkPhone($request->getParameter('work_phone'));
      $contact->setFax($request->getParameter('fax'));
      $contact->setEmail($request->getParameter('email'));
      $contact->setHomepage($request->getParameter('homepage'));
      $contact->setPrivateNotes($request->getParameter('private_notes'));
      $contact->save();

      if ($request->getParameter('address_line1') || $request->getParameter('address_line2') || $request->getParameter('address_city') || $request->getParameter('address_region'))
      {
        $address = new wfCRMAddress();
        $address->setLine1($request->getParameter('address_line1'));
        $address->setLine2($request->getParameter('address_line2'));
        $address->setCity($request->getParameter('address_city'));
        $address->setRegion($request->getParameter('address_region'));
        $address->setPostal($request->getParameter('address_postal'));
        $address->setCountry($request->getParameter('address_country'));
        $address->setWfCRM($contact);
        $address->save();
      }

      $supplier = new Supplier();
      $supplier->setAccountNumber($request->getParameter('account_number'));
      $supplier->setNetDays($request->getParameter('net_days'));
      $supplier->setCreditLimit($request->getParameter('credit_limit'));
      $supplier->setWfCRM($contact);
      $supplier->save();

      $this->renderText(json_encode(array('success' => true)));
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check errors and try again';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   * Deletes an supplier and any associated information
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $supplier = $this->loadSupplier($request);
    $this->renderText("{success: ".(($supplier && $supplier->delete()) ? 'true' : 'false')."}");

    return sfView::NONE;
  }

  public function executeLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $supplier = $this->loadSupplier($request);
    $contact = $supplier->getCRM();

    if ($supplier && $contact)
    {
      $address = $contact->getwfCRMAddresss();
      $address = ($address ? $address[0] : new wfCRMAddress());
      $data = array('department_name' => $contact->getDepartmentName(),
                    'account_number' => $supplier->getAccountNumber(),
                    'credit_limit' => $supplier->getCreditLimit(),
                    'net_days' => $supplier->getNetDays(),
                    'work_phone' => $contact->getWorkPhone(),
                    'fax' => $contact->getFax(),
                    'email' => $contact->getEmail(),
                    'homepage' => $contact->getHomepage(),
                    'private_notes' => $contact->getPrivateNotes(),
                    'address_line1'   => $address->getLine1(),
                    'address_line2'   => $address->getLine2(),
                    'address_city'    => $address->getCity(),
                    'address_postal'  => $address->getPostal(),
                    'address_region'  => $address->getRegion(),
                    'address_country' => $address->getCountry()
                  );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;
  }

  public function executeListJson(sfWebRequest $request)
  {
    $c = new Criteria();
    $suppliers = SupplierPeer::doSelectForListing($c);
    $suppliers_array = array();
    foreach ( $suppliers as $supplier )
      $suppliers_array[] = "{ id : {$supplier->getId()}, name: ".json_encode($supplier->getwfCRM()->getDepartmentName())."}";
    return $this->renderText(
        "{".
          "results: [".implode(",", $suppliers_array)."]".
         "}");
  }

  protected function loadSupplier(sfWebRequest $request)
  {
    $supplier = SupplierPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($supplier, sprintf('Object supplier does not exist (%s).', $request->getParameter('id')));
    return $supplier;
  }

}
