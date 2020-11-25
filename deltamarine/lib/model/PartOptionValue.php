<?php

class PartOptionValue extends BasePartOptionValue
{
  public function __toString()
  {
    return $this->getValue();
  }
}
