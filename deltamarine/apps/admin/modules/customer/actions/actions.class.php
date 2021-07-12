<?php

/**
 * customer actions.
 *
 * @package    deltamarine
 * @subpackage customer
 * @author     Dave Achtemichuk
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class customerActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'customers');
  }

 /*
  * Displays the datagrid of customers
  */ 
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * Views a single customer record including related data
   */
  public function executeView(sfWebRequest $request)
  {
    $this->customer = $this->loadCustomer($request);

    return sfView::SUCCESS;
  }

  /*
   * Loads information about a customer for use via JSON
   */
  public function executeLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $customer = $this->loadCustomer($request);
    $contact = $customer->getCRM();

    if ($customer && $contact)
    {
      $address = $contact->getwfCRMAddresss();
      $address = ($address ? $address[0] : new wfCRMAddress());
      $data = array(
                    'salutation'      => $contact->getSalutation(),
                    'custtype'        => ($contact->getIsCompany() ? 'Company' : 'Individual'),
                    'first_name'      => $contact->getFirstName(),
                    'last_name'       => $contact->getLastName(),
                    'company_name'    => $contact->getDepartmentName(),
                    'email'           => $contact->getEmail(),
                    'work_phone'      => $contact->getWorkPhone(),
                    'mobile_phone'    => $contact->getMobilePhone(),
                    'home_phone'      => $contact->getHomePhone(),
                    'fax'             => $contact->getFax(),
                    'private_notes'   => $contact->getPrivateNotes(),
                    'address_line1'   => $address->getLine1(),
                    'address_line2'   => $address->getLine2(),
                    'address_city'    => $address->getCity(),
                    'address_postal'  => $address->getPostal(),
                    'address_region'  => $address->getRegion(),
                    'address_country' => $address->getCountry(),
                    'pst_number'      => $customer->getPstNumber()
                  );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;

  }

  /*
   * Edits contact information of customer
   */
  public function executeEdit(sfWebRequest $request)
  {
    $is_new = false;

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    if ($request->getParameter('id') == 'new')
    {
      $contact = new wfCRM();
      $contact->setParentNodeId(null);
      $customer = new Customer();
      $customer->setWfCRM($contact);
      $is_new = true;
    }
    else
    {
      $customer = $this->loadCustomer($request);
      $contact = $customer->getCRM();
    }

    //validate
    $result = true;
    $errors = array();

    if (strtolower($request->getParameter('custtype')) == 'individual')
    {
      if (trim($request->getParameter('first_name')) == '')
      {
        $result = false;
        $errors['first_name'] = 'First Name is Required';
      }
      if (trim($request->getParameter('last_name')) == '')
      {
        $result = false;
        $errors['last_name'] = 'Last Name is Required';
      }
    }
    else if ((strtolower($request->getParameter('custtype')) == 'company') && trim($request->getParameter('company_name')) == '')
    {
      $result = false;
      $errors['company_name'] = 'Company Name is required';
    }

    //create object
    if ($result)
    {
      if (strtolower($request->getParameter('custtype')) == 'individual')
      {
        $contact->setSalutation($request->getParameter('salutation'));
        $contact->setFirstName($request->getParameter('first_name'));
        $contact->setLastName($request->getParameter('last_name'));
        $contact->setHomePhone($request->getParameter('home_phone'));
        $contact->setDepartmentName(null);
        $contact->setIsCompany(false);
      }
      else
      {
        $contact->setDepartmentName($request->getParameter('company_name'));
        $contact->setFirstName(null);
        $contact->setLastName(null);
        $contact->setHomePhone(null);
        $contact->setIsCompany(true);
      }
      $contact->setWorkPhone($request->getParameter('work_phone'));
      $contact->setMobilePhone($request->getParameter('mobile_phone'));
      $contact->setFax($request->getParameter('fax'));
      $contact->setEmail($request->getParameter('email'));
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

      $customer->setPstNumber($request->getParameter('pst_number'));
      $customer->save();

      //output result as JSON
      if ($is_new)
      {
        $this->renderText("{success:true,newid:".$customer->getId().",newname:".json_encode($customer->getName())."}");
      }
      else
      {
        $this->renderText("{success:true}");
      }
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * Deletes a customer and any associated information
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $customer = $this->loadCustomer($request);
    if (!($customer && $customer->delete()))
    {
      $this->forward404('Could Not Delete Customer');
    }

    return sfView::NONE;
  }



  /* ****************************** */
  /* BOAT STUFF FOLLOWS.            */
  /* This should probably be in a   */
  /* separate module, but oh well.  */
  /* ****************************** */


  /*
   * Views a customer's boat record including related data
   */
  public function executeBoat(sfWebRequest $request)
  {
    $this->boat = $this->loadBoat($request);
    $this->customer = $this->boat->getCustomer();

    return sfView::SUCCESS;
  }

  /*
   * Loads information about a boat for use via JSON
   */
  public function executeBoatLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $boat = $this->loadBoat($request);
    $customer = $boat->getCustomer();

    if ($boat && $customer)
    {
      $data = array(
        'id' => $boat->getId(),
        'name' => $boat->getName(),
        'make' => $boat->getMake(),
        'model' => $boat->getModel(),
        'serial_number' => $boat->getSerialNumber(),
        'registration' => $boat->getRegistration(),
        'notes' => $boat->getNotes(),
        'fire_date' =>$boat->getFire_Date()
      );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;

  }

  /*
   * Add a new boat for a customer via ajax
   */
  public function executeBoatEdit(sfWebRequest $request)
  {
    $is_new = false;

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    if ($request->getParameter('id') == 'new')
    {
      $boat = new CustomerBoat();
      $this->forward404Unless($customer = CustomerPeer::retrieveByPk($request->getParameter('customer_id')));
      $boat->setCustomerId($customer->getId());
      $is_new = true;
    }
    else
    {
      $this->forward404Unless($boat = CustomerBoatPeer::retrieveByPk($request->getParameter('id')));
    }

    //validate
    $result = true;
    $errors = array();

    //create object
    if ($result)
    {
      $boat->setName($request->getParameter('name'));
      $boat->setMake($request->getParameter('make'));
      $boat->setModel($request->getParameter('model'));
      $boat->setSerialNumber($request->getParameter('serial_number'));
      $boat->setRegistration($request->getParameter('registration'));
      $boat->setNotes($request->getParameter('notes'));
      $boat->setFireDate($request->getParameter('fire_date'));
      $boat->save();

      //output result as JSON
      if ($is_new)
      {
        $this->renderText("{success:true,newid:".$boat->getId().",newname:".json_encode($boat->getName())."}");
      }
      else
      {
        $this->renderText("{success:true}");
      }
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE; 
  }

  /*
   * Deletes a boat
   */
  public function executeBoatDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $boat = $this->loadBoat($request);
    if (!($boat && $boat->delete()))
    {
      $this->forward404('Could Not Delete Boat');
    }

    return sfView::NONE;
  }

  protected function loadCustomer(sfWebRequest $request)
  {
    $customer = CustomerPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($customer, sprintf('Object customer does not exist (%s).', $request->getParameter('id')));

    return $customer;
  }

  protected function loadBoat(sfWebRequest $request)
  {
    $boat = CustomerBoatPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($boat, sprintf('Boat does not exist (id = %s).', $request->getParameter('id')));

    return $boat;
  }

}
