<?php

class LabourType extends BaseLabourType
{
public function __toString()
  {
    return $this->getName();
  }
}
