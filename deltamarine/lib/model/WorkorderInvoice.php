<?php

class WorkorderInvoice extends BaseWorkorderInvoice
{
    public function calculateTotal()
    {
        if (sfConfig::get('sf_logging_enabled'))
        {
        $message = 'START WorkorderInvoice.calculateTotal======================';
        sfContext::getInstance()->getLogger()->info($message);
        }
        $whom_id = $this->getWhomId();
        if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('whom_id: '.$whom_id); }

        $workorder = $this->getWorkorder();
        if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('workorder: '.$workorder->getId()); }

        $totals = $workorder->getTotalsByPayer($this->getId());

        if (isset($totals[$whom_id]))
        {
            $payer_total = $totals[$whom_id];
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('payer_total: '.$payer_total); }

            $total = $payer_total['amount'];
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('total: '.$total); }

            $fees = $payer_total['fees'];
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('fees: '.$fees); }

            $supplies = round($total * ($workorder->getShopSuppliesSurcharge()/100), 2);
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('supplies: '.$supplies); }

            $total_amt = $total + $fees + $supplies;
            $total_with_tax = $total_amt;
            $total_with_tax += ($total_amt * ($workorder->getHstExempt() ? 0 : (sfConfig::get('app_hst_rate') / 100)));
            $total_with_tax += ($total_amt * ($workorder->getPstExempt() ? 0 : (sfConfig::get('app_pst_rate') / 100)));
            $total_with_tax += ($total_amt * ($workorder->getGstExempt() ? 0 : (sfConfig::get('app_gst_rate') / 100)));
            $total_amt = round($total_with_tax, 2);
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('total_amt: '.$total_amt); }

            $this->getInvoice()->setTotal($total_amt);  
            $this->getInvoice()->save();
            if (sfConfig::get('sf_logging_enabled')) {sfContext::getInstance()->getLogger()->info('invoice saved'); }

        }//if (isset($totals[$whom_id]))

        if (sfConfig::get('sf_logging_enabled'))
        {
        $message = 'DONE WorkorderInvoice.calculateTotal======================';
        sfContext::getInstance()->getLogger()->info($message);
        }
    }//calculateTotal()--------------------------------------------------------

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
    }//getWhomId()-------------------------------------------------------------
}//WorkorderInvoice{}==========================================================
