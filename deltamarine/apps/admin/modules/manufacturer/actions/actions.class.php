<?php

/**
 * manufacturer actions.
 *
 * @package    deltamarine
 * @subpackage manufacturer
 * @author     Dave Achtemichuk
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class manufacturerActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'parts');
  }

 /*
  * Displays the datagrid of manufacturers
  */ 
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * Views a single manufacturer record including related data
   */
  public function executeView(sfWebRequest $request)
  {
    $this->manufacturer = $this->loadManufacturer($request);
    
    return sfView::SUCCESS; 
  }


  /*
   * Edits contact information of manufacturer
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $manufacturer = $this->loadManufacturer($request);
    $contact = $manufacturer->getCRM();

    //SAVE CHANGES
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

    return sfView::NONE;
  }

  /*
   * Add a new manufacturer to the system
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //PERFORM VALIDATION
    $result = true;
    $errors = array();

    //check to see if duplicate name
    $c = new Criteria();
    $c->addJoin(ManufacturerPeer::WF_CRM_ID, wfCRMPeer::ID);
    $c->add(wfCRMPeer::DEPARTMENT_NAME, $request->getParameter('department_name'));
    if (ManufacturerPeer::doSelectOne($c))
    {
      $result = false;
      $errors['department_name'] = 'Manufacturer by this name already exists!';
    }

    if ($result)
    {
      //CREATE MANUFACTURER
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

      $manufacturer = new Manufacturer();
      $manufacturer->setWfCRM($contact);
      $manufacturer->save();   

      $this->renderText(json_encode(array('success' => true, 'newid' => $manufacturer->getId(), 'newname' => $contact->getName())));
    }
    else
    {
      $errors['reason'] = 'Invalid input detected. Please check errors and try again';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   * Deletes a manufacturer and any associated information
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $manufacturer = $this->loadManufacturer($request);
    $this->renderText("{success: ".(($manufacturer && $manufacturer->delete()) ? 'true' : 'false')."}");

    return sfView::NONE;
  }

  public function executeLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $manufacturer = $this->loadManufacturer($request);
    $contact = $manufacturer->getCRM();

    if ($manufacturer && $contact)
    {
      $address = $contact->getwfCRMAddresss();
      $address = ($address ? $address[0] : new wfCRMAddress());
      $data = array('department_name' => $contact->getDepartmentName(),
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
                    'address_country' => $address->getCountry(),
                  );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;
  }
  protected function loadManufacturer(sfWebRequest $request)
  {
    $manufacturer = ManufacturerPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($manufacturer, sprintf('Object manufacturer does not exist (%s).', $request->getParameter('id')));

    return $manufacturer;
  }

}
