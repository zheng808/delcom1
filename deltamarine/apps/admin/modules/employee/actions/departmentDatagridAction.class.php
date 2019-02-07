<?php

class departmentDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    $root = wfCRMPeer::getSiteOwnerCompany();
    $departments = $root->getChildren();

    //generate JSON output
    $departmentarray = array();
    foreach ($departments AS $department)
    {
      if ($department->getDepartmentName() && !$department->getIsCompany())
      {
        //NOTE: this assumes there are no sub-departments!!
        $employees = $department->getNumberOfDescendants();

        $departmentarray[] = array('id'        => $department->getId(),
                                   'name'      => $department->getDepartmentName(),
                                   'employees' => $employees
                                  );
      }
    }
    $dataarray = array('totalCount' => count($departmentarray), 'departments' => $departmentarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
