<?php

abstract class BarcodeLabel extends sfTCPDF
{
  protected $cols;            //number of label cols per sheet
  protected $rows;            //number of label rows per sheet
  protected $num_per_page;    //number of labels per sheet (calculated)
  protected $margin_top;      //right-side page margin
  protected $margin_left;     //left-side page margin
  protected $col_spacing;     //gutter width betwen cols
  protected $row_spacing;     //gutter width between rows
  protected $paper_size;      //letter, legal, A4, etc.
  protected $orientation;     //P or L
  protected $width;           //individual label width
  protected $height;          //individual label height
  protected $fontsize;        //default font size
  protected $offset;          //starting offset

  public function __construct($offset = 0)
  {
    parent::__construct($this->orientation, 'mm', $this->paper_size, false);

    require_once(sfConfig::get('sf_root_dir'). '/lib/tcpdf/config/lang/eng.php');
    $this->setLanguageArray($l);

    $this->setMargins($this->margin_left, $this->margin_top);
    $this->setAutoPageBreak(false);
    $this->setFont('Arial');
    $this->setFontSize($this->fontsize);
    $this->setPrintHeader(false);
    $this->setPrintFooter(false);
    $this->AliasNbPages();
    $this->AddPage();

    $this->offset = $offset + 1;
    $this->num_per_page = $this->cols * $this->rows;
  }

  public function generateSingle($code, $codetype, $line1 = null, $line2 = null, $price = null, $quantity = 1)
  {
    for ($i = 1; $i <= $quantity; $i++)
    {
        $col = 1 + (($this->offset - 1) % $this->cols);
        $row = 1 + floor((($this->offset  - 1) % $this->num_per_page) / $this->cols);
        $x = $this->margin_left + (($col - 1) * ($this->width  + $this->col_spacing));
        $y = $this->margin_top  + (($row - 1) * ($this->height + $this->row_spacing));

        if ($this->offset != 1 && $col == 1 && $row == 1)
        {
            $this->AddPage();
        }

        $this->drawSingle($x, $y, $code, $codetype, $line1, $line2, $price);
        $this->offset++;
    }
  }

  abstract function drawSingle($x, $y, $code, $codetype, $line1, $line2, $price);

}
