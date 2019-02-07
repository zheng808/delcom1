<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMContactUsForm extends BasewfCRMForm
{

  private static $flds = array(
      'first_name', 
      'middle_name', 
      'last_name', 
      'salutation', 
      'titles', 
      'job_title', 
      'alpha_name', 
      'email', 
      'work_phone', 
      'mobile_phone', 
      'home_phone', 
      'fax', 
      'homepage', 
      'public_notes', 
      'wf_crm_category_ref_list');

  public function configure()
  {
    parent::configure();
    unset($this['private_notes'], $this['is_company'], $this['tree_left'], $this['tree_id'], $this['tree_right'], $this['created_at'], $this['updated_at'], $this['parent_node_id'], $this['department_name'], $this['is_in_addressbook']);
    
    $this->widgetSchema['wf_crm_category_ref_list']->setOption('method', 'getPublicNameWithLevel');
    $this->widgetSchema['wf_crm_category_ref_list']->setOption('peer_method', 'doSelectSubscribeble');
    $this->validatorSchema['email'] = new sfValidatorEmail();
    
    $conf = sfConfig::get('app_wf_crm_plugin_contactus_form', null);
    if ($conf)
    { 
      foreach ( self::$flds as $field )
      {
        if (isset($conf['display']) && is_array($conf['display']) && in_array($field, $conf['display']))
        {
          if (isset($conf['fields']) && isset($conf['fields'][$field]))
          {
            $opts = $conf['fields'][$field];
          }
          else
          {
          	$opts = array();
          }
          
          $required = isset($opts['required']) ? $opts['required'] : false;
          $this->validatorSchema[$field]->setOption('required', $required);
          if (isset($opts['required_msg']))
          {
            $this->validatorSchema[$field]->setMessage('required', $opts['required_msg']);
          }
          if (isset($opts['label']))
          {
            $this->widgetSchema[$field]->setLabel($opts['label']);
          } 
        }
        else
        {
          unset($this[$field]);
        }
      }
    }
  }

  protected function doSave($con = null)
  {
    if ($this->getObject()->isNew())
    {
      $this->getObject()->setScopeIdValue(time());
      $this->getObject()->makeRoot();
      
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('Date'));
      $this->getObject()->setPrivateNotes('added via contact page on ' . format_date(time(), 'g'));
    }
    parent::doSave($con);
  }
}
      