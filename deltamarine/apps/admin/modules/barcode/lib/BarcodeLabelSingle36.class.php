<?php

class BarcodeLabelSingle36 extends BarcodeLabel
{
    protected $cols         = 1;
    protected $rows         = 1;

    protected $paper_size   = array('89','36');
    protected $orientation  = 'L';
    protected $fontsize     = 7;

    //metrics of the labels, in millimetres
    protected $margin_top   = 4;
    protected $margin_left  = 8;
    protected $col_spacing  = 0;
    protected $row_spacing  = 0;
    protected $width        = 89;
    protected $height       = 36;


    public function drawSingle($x, $y, $code, $codetype, $line1, $line2, $price)
    {

      $x += 1; //padding
      $y += 1;

      //draw barcode
      $settings = array('position' => 'C');
      $this->write1DBarcode($code, $codetype, $x, $y, 70, 14, 0.6, $settings, 'N');

      //draw price
      $current_y = $this->getY();
      $this->setY($current_y + 4);
      $textwidth = 5;
      if ($price)
      {
        $this->setFontSize(16);
        $this->SetX($x);
        $this->Cell(35, 0, $price, 0, 0, 'L', 0, '', 1);
      }

      //draw descriptive info   
      if ($line1 || $line2)
      {
        $current_y = $this->getY();
        $this->setY($current_y + 2);
        $this->setFontSize(8);
        if ($line1)
        {
          $this->setX($x + 35);
          $this->Cell(40, 0, $line1, 0, 1, 'R', 0, '', 1);
        }
        if ($line2)
        {
          $this->setFont('','B');
          $this->setX($x + 35);
          $this->Cell(40, 0, $line2, 0, 0, 'R', 0, '', 1);
          $this->setFont('');
        }
      }

    }

}

