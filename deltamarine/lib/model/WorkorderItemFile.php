<?php

class WorkorderItemFile extends BaseWorkorderItemFile
{
  public function delete (PropelPDO $con = null)
  {
    if ($file = $this->getFile())
    {
      $file->delete();
    }

    parent::delete($con);
  }

}
