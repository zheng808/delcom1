<?php

class WorkorderItemPhoto extends BaseWorkorderItemPhoto
{
  public function delete (PropelPDO $con = null)
  {
    if ($photo = $this->getPhoto())
    {
      $photo->delete();
    }

    parent::delete($con);
  }

}
