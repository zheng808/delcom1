<?php
/**
 * wfCRMSubscribe actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class BasewfCRMSubscribeActions extends sfActions
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
    $this->forward('wfCRMSubscribe', 'new');
  }
  
  /**
   * Executes new contact action
   *
   * @param sfRequest $request A request object
   */
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new wfCRMSubscribeForm();
  }
  
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->form = new wfCRMSubscribeForm();
    
    $this->processForm($request, $this->form);
    
    $this->setTemplate('new');
  }

  /**
   * Process form
   *
   * @param sfRequest $request A request object
   */
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->save();
      $this->redirect('wfCRMSubscribe/thanks');
    }
  }
  
  public function executeThanks(sfWebRequest $request)
  {
    
  }
}
