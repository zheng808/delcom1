<?php
class wfCRM extends PluginwfCRM
{
	public function save(PropelPDO $con = null)
	{
		$this->updateAlphaName();
		parent::save($con);
	}
	
	public function updateAlphaName()
	{
	  if ($this->getDepartmentName())
	  {
	    $this->setAlphaName($this->GetDepartmentName());
	  }
	  else
	  {
	    $this->setAlphaName($this->getLastName() ? $this->getLastName().' '.$this->getFirstName() : $this->getFirstName());
	  }
	}
}