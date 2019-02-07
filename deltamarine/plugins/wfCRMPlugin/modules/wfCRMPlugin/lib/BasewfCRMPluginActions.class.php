<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class BasewfCRMPluginActions extends sfActions
{

  /**
   * preExecute
   *
   */
  public function preExecute()
  {
    if (sfConfig::get('app_wf_crm_plugin_use_bundled_layout', true))
    {
      $this->setLayout(sfConfig::get('sf_plugins_dir') . '/wfCRMPlugin/templates/layout');
    }
    if (sfConfig::get('app_wf_crm_plugin_use_bundled_stylesheet', true))
    {
      $this->getResponse()->addStylesheet('/wfCRMPlugin/css/wfCRMstyle.css', 'last');
    }
  }

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('wfCRMPlugin', 'list');
  }

  /**
   * Executes list action
   *
   * @param sfRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $c = new Criteria();
    
    $this->contacts_pager = array();
    $this->companies = array();
    
    $this->cat_id = 0;
    $this->crm_id = 0;
    $this->selected_crm = null;
    $this->departments = array();
    
    if ($request->getParameter('crm'))
    {
      $this->selected_crm = wfCRMPeer::retrieveByPK($request->getParameter('crm'));
      $this->forward404Unless($this->selected_crm);
      $this->crm_id = $this->selected_crm->getId();
      
      $descedants = $this->selected_crm->getDescendants();
      foreach ( $descedants as $crm )
      {
        if ($crm->getIsCompany())
          $this->departments[] = $crm;
      }

      $branch = $this->selected_crm->getDescendants();
      $ids = array($this->selected_crm->getId());
      foreach ( $branch as $branch_el )
      {
        $ids[] = $branch_el->getId();
      }
      $c->add(wfCRMPeer::ID, $ids, Criteria::IN);
    } else {
      $this->companies = wfCRMPeer::getFirstLevel();
    }
    
    if ($request->getParameter('cat'))
    {
      $this->selected_cat = wfCRMCategoryPeer::retrieveByPK($request->getParameter('cat'));
      $this->forward404Unless($this->selected_cat);
      $this->cat_id = $this->selected_cat->getId();
      $branch = $this->selected_cat->getDescendants();
      $ids = array($this->selected_cat->getId());
      foreach ( $branch as $branch_el )
      {
        $ids[] = $branch_el->getId();
      }
      $c->addJoin(wfCRMPeer::ID,wfCRMCategoryRefPeer::CRM_ID,Criteria::LEFT_JOIN);
      $c->add(wfCRMCategoryRefPeer::CATEGORY_ID, $ids, Criteria::IN);
    }
    $c->add(wfCRMPeer::IS_COMPANY, 0);
    $c->add(wfCRMPeer::IS_IN_ADDRESSBOOK, 1);
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    
    $pager = new sfPropelPager('wfCRM', 10);
    $pager->setCriteria($c);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    $this->contacts_pager = $pager;
  }

  
  /**
   * Executes new contact action
   *
   * @param sfRequest $request A request object
   */
  public function executeNewContact(sfWebRequest $request)
  {
    $this->form = new wfCRMContactForm();
    $this->form->setDefault('parent_node',$request->getParameter('parent_id',''));
  }
  /**
   * Executes new department action
   *
   * @param sfRequest $request A request object
   */
  public function executeNewDepartment(sfWebRequest $request)
  {
    $this->form = new wfCRMDepartmentForm();
    $this->form->setDefault('parent_node',$request->getParameter('parent_id',''));
  }
  /**
   * Executes new action
   *
   * @param sfRequest $request A request object
   */
//  public function executeNew(sfWebRequest $request)
//  {
//    $this->form = new wfCRMForm();
//  }

  /**
   * Executes create action
   *
   * @param sfRequest $request A request object
   */
//  public function executeCreate(sfWebRequest $request)
//  {
//    $this->forward404Unless($request->isMethod('post'));
//    
//    $this->form = new wfCRMForm();
//    
//    $this->processForm($request, $this->form);
//    
//    $this->setTemplate('new');
//  }
  
  public function executeCreateDepartment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->form = new wfCRMDepartmentForm();
    
    $this->processDepartmentForm($request, $this->form);
    
    $this->setTemplate('newDepartment');
  }
  
  public function executeCreateContact(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->form = new wfCRMContactForm();
    
    $this->processContactForm($request, $this->form);
    
    $this->setTemplate('newContact');
  }

  /**
   * Executes edit action
   *
   * @param sfRequest $request A request object
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    if ($wf_crm->getIsCompany())
    {
      $this->forward($this->moduleName, 'editDepartment');
    }
    else
    {
      $this->forward($this->moduleName, 'editContact');
    }
  }
  
  public function executeEditDepartment(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMDepartmentForm($wf_crm);
    $this->crm_id = $request->getParameter('id');
    $this->addresses = $wf_crm->getwfCRMAddresss();
  }
  
  public function executeEditContact(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMContactForm($wf_crm);
    $this->crm_id = $request->getParameter('id');
    $this->addresses = $wf_crm->getwfCRMAddresss();
  }

  /**
   * Executes update action
   *
   * @param sfRequest $request A request object
   */
//  public function executeUpdate(sfWebRequest $request)
//  {
//    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
//    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
//    $this->form = new wfCRMForm($wf_crm);
//    
//    $this->processForm($request, $this->form);
//    
//    $this->setTemplate('edit');
//  }
  
  public function executeUpdateContact(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMContactForm($wf_crm);
    
    $this->processContactForm($request, $this->form);
    
    $this->crm_id = $request->getParameter('id');
    $this->addresses = $wf_crm->getwfCRMAddresss();
    $this->setTemplate('editContact');
  }
  
  public function executeUpdateDepartment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMDepartmentForm($wf_crm);
    
    $this->processDepartmentForm($request, $this->form);
    
    $this->crm_id = $request->getParameter('id');
    $this->addresses = $wf_crm->getwfCRMAddresss();
    $this->setTemplate('editDepartment');
  }

  /**
   * Executes delete action
   *
   * @param sfRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $wf_crm->delete();
    
    $this->redirect('wfCRMPlugin/index');
  }

  /**
   * Process form
   *
   * @param sfRequest $request A request object
   */
  protected function processContactForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $wf_crm = $form->save();
      $this->redirect('wfCRMPlugin/list?crm=' . $wf_crm->getParentNodeId());
    }
  }

  protected function processDepartmentForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $wf_crm = $form->save();
      $this->redirect('wfCRMPlugin/edit?id=' . $wf_crm->getId());
    }
  }

  /**
   * Executes show action
   *
   * @param sfRequest $request A request object
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('id')));
    $this->contact = $wf_crm;
  }

  /**
   * Executes category_list action
   *
   * @param sfRequest $request A request object
   */
  public function executeListCategory(sfWebRequest $request)
  {
    $this->cat_tree = wfCRMCategoryPeer::retrieveAllTree(new Criteria());
  }

  /**
   * Executes new category action
   *
   * @param sfRequest $request A request object
   */
  public function executeNewCategory(sfWebRequest $request)
  {
    $this->form = new wfCRMCategoryForm();
  }

  /**
   * Executes create category action
   *
   * @param sfRequest $request A request object
   */
  public function executeCreateCategory(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->form = new wfCRMCategoryForm();
    
    $this->processCategoryForm($request, $this->form);
    
    $this->setTemplate('newCategory');
  }

  /**
   * Executes edit category action
   *
   * @param sfRequest $request A request object
   */
  public function executeEditCategory(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm_category = wfCRMCategoryPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_category does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMCategoryForm($wf_crm_category);
  }

  /**
   * Executes update category action
   *
   * @param sfRequest $request A request object
   */
  public function executeUpdateCategory(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($wf_crm_category = wfCRMCategoryPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_category does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMCategoryForm($wf_crm_category);
    $this->processCategoryForm($request, $this->form);
    $this->setTemplate('editCategory');
  }

  /**
   * Executes delete category action
   *
   * @param sfRequest $request A request object
   */
  public function executeDeleteCategory(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($wf_crm_category = wfCRMCategoryPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_category does not exist (%s).', $request->getParameter('id')));
    $wf_crm_category->delete();
    $this->redirect('wfCRMPlugin/listCategory');
  }

  /**
   * Process form
   *
   * @param sfRequest $request A request object
   */
  protected function processCategoryForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $wf_crm_category = $form->save();
      $this->redirect('wfCRMPlugin/editCategory?id=' . $wf_crm_category->getId());
    }
  }
  
  /**
   * Executes new category action
   *
   * @param sfRequest $request A request object
   */
  public function executeNewAddress(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('crm_id')), sprintf('Object wf_crm does not exist (%s).', $request->getParameter('crm_id')));
    $this->form = new wfCRMAddressForm();
    $this->form->setDefault('crm_id',$request->getParameter('crm_id'));
    $this->crm_id = $request->getParameter('crm_id');
  }

  /**
   * Executes create category action
   *
   * @param sfRequest $request A request object
   */
  public function executeCreateAddress(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->form = new wfCRMAddressForm();
    
    $this->processAddressForm($request, $this->form);
    
    $this->setTemplate('newAddress');
  }

  /**
   * Executes edit category action
   *
   * @param sfRequest $request A request object
   */
  public function executeEditAddress(sfWebRequest $request)
  {
    $this->forward404Unless($wf_crm_address = wfCRMAddressPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_address does not exist (%s).', $request->getParameter('id')));    
    $this->form = new wfCRMAddressForm($wf_crm_address);
  }

  /**
   * Executes update category action
   *
   * @param sfRequest $request A request object
   */
  public function executeUpdateAddress(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($wf_crm_address = wfCRMAddressPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_category does not exist (%s).', $request->getParameter('id')));
    $this->form = new wfCRMAddressForm($wf_crm_address);
    $this->processAddressForm($request, $this->form);
    $this->setTemplate('editAddress');
  }

  /**
   * Executes delete category action
   *
   * @param sfRequest $request A request object
   */
  public function executeDeleteAddress(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($wf_crm_address = wfCRMAddressPeer::retrieveByPk($request->getParameter('id')), sprintf('Object wf_crm_category does not exist (%s).', $request->getParameter('id')));
    $crm_id = $wf_crm_address->getCrmId();
    $wf_crm_address->delete();
    $this->redirect('wfCRMPlugin/edit?id='.$crm_id);
  }

  /**
   * Process form
   *
   * @param sfRequest $request A request object
   */
  protected function processAddressForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $wf_crm_address = $form->save();
      //$this->redirect('wfCRMPlugin/editAddress?id=' . $wf_crm_address->getId());
      $this->redirect('wfCRMPlugin/edit?id=' . $wf_crm_address->getCrmId());
    }
  }

  public function executeAjaxCompanyTree($request)
  {
    $wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($wf_crm && $wf_crm->getIsCompany());
    //$this->forward404Unless($request->isXmlHttpRequest());

    $node = $request->getParameter('node');
    if (is_numeric($node))
    {
      $node = wfCRMPeer::retrieveByPk($node);
    } 
    else{
      $node = $wf_crm;
    }
    
    $children = $node->getChildren();
    $output = array();
    foreach ($children AS $child)
    {
        $output[] = array('id' => $child->getId(),
                          'text' => $child->getName(),
                          'sorttext' => ($child->getDepartmentName() ? '1_' : '2_').$child->getName(),
                          'work_phone' => $child->getWorkPhone(),
                          'email' => $child->getEmail(),
                          'uiProvider' => 'col', 
                          'leaf' => $child->isLeaf(),
                          'iconCls' => ($child->getDepartmentName() ? 'department' : 'person'));
    }

    $this->renderText(json_encode($output));
    return sfView::NONE;
  }

  public function executeAjaxDepartmentsList($request)
  {
    if ($request->getParameter('id'))
    {
      $wf_crm = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    }
    else
    {
      $wf_crm = wfCRMPeer::getSiteOwnerCompany();
    }
    $this->forward404Unless($wf_crm && $wf_crm->getIsCompany());
    //$this->forward404Unless($request->isXmlHttpRequest());

    $depts = $wf_crm->getDepartmentsList(' ', 6);
    $list = array(array('value' => $wf_crm->getId(), 'label' => 'None'));
    foreach ($depts AS $key => $dept)
    {
      $list[] = array('value' => $key, 'label' => $dept);
    }

    $this->renderText(json_encode(array('departments' => $list)));
    return sfView::NONE;
  }

  public function executeAjaxAddDepartment($request)
  {
    $crm = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($crm && $crm->getIsCompany());
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();
    if (!$parent = wfCRMPeer::retrieveByPk($request->getParameter('parent_node')))
    {
      if ($request->getParameter('parent_node') > 0)
      {
        $result = false;
        $errors['parent_node'] = 'Invalid Parent Department selected!';
      }
      else
      {
        $request->getParameterHolder()->set('parent_node', $crm->getId());
      }
    }

    //create object
    if ($result)
    {
      $newcrm = new wfCRM();
      $newcrm->setIsCompany(false);
      $newcrm->setDepartmentName($request->getParameter('department_name'));
      $newcrm->setParentNodeId($request->getParameter('parent_node'));
      $newcrm->setWorkPhone($request->getParameter('work_phone'));
      $newcrm->setFax($request->getParameter('fax'));
      $newcrm->setEmail($request->getParameter('email'));
      $newcrm->setPrivateNotes($request->getParameter('private_notes'));
      $newcrm->save();
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  public function executeAjaxAddContact($request)
  {
    $crm = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($crm && $crm->getIsCompany());
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();
    if (!$parent = wfCRMPeer::retrieveByPk($request->getParameter('parent_node')))
    {
      if ($request->getParameter('parent_node') > 0)
      {
        $result = false;
        $errors['parent_node'] = 'Invalid Department selected!';
      }
      else
      {
        $request->getParameterHolder()->set('parent_node', $crm->getId());
      }
    }

    //create object
    if ($result)
    {
      $newcrm = new wfCRM();
      $newcrm->setIsCompany(false);
      $newcrm->setParentNodeId($request->getParameter('parent_node'));
      $newcrm->setSalutation($request->getParameter('salutation'));
      $newcrm->setFirstName($request->getParameter('first_name'));
      $newcrm->setLastName($request->getParameter('last_name'));
      $newcrm->setJobTitle($request->getParameter('job_title'));
      $newcrm->setWorkPhone($request->getParameter('work_phone'));
      $newcrm->setHomePhone($request->getParameter('home_phone'));
      $newcrm->setMobilePhone($request->getParameter('mobile_phone'));
      $newcrm->setFax($request->getParameter('fax'));
      $newcrm->setEmail($request->getParameter('email'));
      $newcrm->setPrivateNotes($request->getParameter('private_notes'));
      $newcrm->save();
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

}
