<?php

class BarcodeLabel80Up extends BarcodeLabel
{
    protected $cols         = 4;
    protected $rows         = 20;

    protected $paper_size   = 'letter';
    protected $orientation  = 'P';
    protected $fontsize     = 5;

    //metrics of the labels, in millimetres
    protected $margin_top   = 14.5;
    protected $margin_left  = 8;
    protected $col_spacing  = 7.7;
    protected $row_spacing  = 0;
    protected $width        = 44.3;
    protected $height       = 12.7;


    public function drawSingle($x, $y, $code, $codetype, $line1, $line2, $price)
    {

      $x += 2; //padding
      $y += 2;

      //draw barcode
      $settings = array('cellfitalign' => 'C');
      $this->write1DBarcode($code, $codetype, $x, $y, 41.5, 4, 0.35, $settings, 'N');

      //draw price
      $textwidth = 5;
      if ($price)
      {
        $this->setFontSize(9);
        $this->SetX($x);
        $this->Cell(15, 0, $price, 0, 0, 'L', 0, '', 1);
      }

      //draw descriptive info   
      if ($line1 || $line2)
      {
        $this->setFontSize(5);
        if ($line1)
        {
          $this->setX($x + 15);
          $this->Cell(26, 0, $line1, 0, 1, 'R', 0, '', 1);
        }
        if ($line2)
        {
          $this->setFont('','B');
          $this->setX($x + 15);
          $this->Cell(26, 0, $line2, 0, 0, 'R', 0, '', 1);
          $this->setFont('');
        }
      }

    }

}

