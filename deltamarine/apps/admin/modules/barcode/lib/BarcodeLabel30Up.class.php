<?php

class BarcodeLabel30Up extends BarcodeLabel
{
    protected $cols         = 3;
    protected $rows         = 10;

    protected $paper_size   = 'letter';
    protected $orientation  = 'P';
    protected $fontsize     = 7;

    //metrics of the labels, in millimetres
    protected $margin_top   = 12.5;
    protected $margin_left  = 3;
    protected $col_spacing  = 3.3;
    protected $row_spacing  = 0;
    protected $width        = 66.675;
    protected $height       = 25.4;


    public function drawSingle($x, $y, $code, $codetype, $line1, $line2, $price)
    {

      $x += 4; //padding
      $y += 4;

      //draw barcode
      $settings = array('cellfitalign' => 'C'); 
      $this->write1DBarcode($code, $codetype, $x, $y, 60, 10, 0.5, $settings, 'N');

      //draw price
      $textwidth = 5;
      if ($price)
      {
        $this->setFontSize(14);
        $this->SetX($x);
        $this->Cell(25, 0, $price, 0, 0, 'L', 0, '', 1);
      }

      //draw descriptive info   
      if ($line1 || $line2)
      {
        $this->setFontSize(7);
        if ($line1)
        {
          $this->setX($x + 25);
          $this->Cell(35, 0, $line1, 0, 1, 'R', 0, '', 1);
        }
        if ($line2)
        {
          $this->setFont('','B');
          $this->setX($x + 25);
          $this->Cell(35, 0, $line2, 0, 0, 'R', 0, '', 1);
          $this->setFont('');
        }
      }

    }

}

