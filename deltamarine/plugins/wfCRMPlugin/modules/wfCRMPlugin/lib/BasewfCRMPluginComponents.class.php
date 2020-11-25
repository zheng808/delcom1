<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class BasewfCRMPluginComponents extends sfComponents
{

  public function executeNavigation()
  {
    $this->crm_tree = wfCRMPeer::retrieveAllCompaniesTree(new Criteria());
    
    $this->crm_cat_tree = wfCRMCategoryPeer::retrieveAllTree(new Criteria());

    $this->crm_id = $this->getRequestParameter('crm',0);
    $this->cat_id = $this->getRequestParameter('cat',0);
  }
  
  public function executeBreadcrumb()
  {
    $this->crm_id = $this->getVar('id');
    $this->cat_id = $this->getVar('cat');
    
    $this->contact = wfCRMPeer::retrieveByPK($this->getVar('id'));
    $this->category = wfCRMCategoryPeer::retrieveByPK($this->getVar('cat'));
    
    if(!$this->contact && !$this->category)
      return sfView::NONE;
  }
}
