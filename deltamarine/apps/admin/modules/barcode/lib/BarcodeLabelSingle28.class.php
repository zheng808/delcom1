<?php

class BarcodeLabelSingle28 extends BarcodeLabel
{
    protected $cols         = 1;
    protected $rows         = 1;

    protected $paper_size   = array('65','28');
    protected $orientation  = 'L';
    protected $fontsize     = 7;

    //metrics of the labels, in millimetres
    protected $margin_top   = 2;
    protected $margin_left  = 2;
    protected $col_spacing  = 0;
    protected $row_spacing  = 0;
    protected $width        = 65;
    protected $height       = 28;


    public function drawSingle($x, $y, $code, $codetype, $line1, $line2, $price)
    {

      $x += 1; //padding
      $y += 1;

      //draw barcode
      $settings = array('position' => 'C');
      $this->write1DBarcode($code, $codetype, $x, $y, 60, 10, 0.5, $settings, 'N');

      //draw price
      $current_y = $this->getY();
      $this->setY($current_y + 3);

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
        $current_y = $this->getY();
        $this->setY($current_y + 2);

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

