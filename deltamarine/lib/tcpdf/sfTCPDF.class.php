<?php

class sfTCPDF extends TCPDF
{
  public function __construct($orientation = 'P', $unit = 'mm', $format = 'Letter', $unicode = true, $encoding = "UTF-8")
  {
    parent::__construct($orientation, $unit, $format, $unicode, $encoding);
  }

  public function __destruct()
  {
  }

}
