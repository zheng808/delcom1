<?php

class WorkorderInvoice extends BaseWorkorderInvoice
{
    public function calculateTotal()
    {
        $whom_id = $this->getWhomId();

        $workorder = $this->getWorkorder();
        $totals = $workorder->getTotalsByPayer($this->getId());
        if (isset($totals[$whom_id]))
        {
            $payer_total = $totals[$whom_id];

            $total = $payer_total['amount'];
            $fees = $payer_total['fees'];
            $supplies = round($total * ($workorder->getShopSuppliesSurcharge()/100), 2);

            $total_amt = $total + $fees + $supplies;
            $total_with_tax = $total_amt;
            $total_with_tax += ($total_amt * ($workorder->getHstExempt() ? 0 : (sfConfig::get('app_hst_rate') / 100)));
            $total_with_tax += ($total_amt * ($workorder->getPstExempt() ? 0 : (sfConfig::get('app_pst_rate') / 100)));
            $total_with_tax += ($total_amt * ($workorder->getGstExempt() ? 0 : (sfConfig::get('app_gst_rate') / 100)));
            $total_amt = round($total_with_tax, 2);

            $this->getInvoice()->setTotal($total_amt);  
            $this->getInvoice()->save();
        }
    }

    public function getWhomId()
    {
        $invoice = $this->getInvoice();
        if ($invoice->getManufacturerId())
        {
            return 'm_'.$invoice->getManufacturerId();
        }
        else if ($invoice->getSupplierId())
        {
            return 's_'.$invoice->getSupplierId();
        }
        else
        {
            return 'cust';
        }
    }
}
