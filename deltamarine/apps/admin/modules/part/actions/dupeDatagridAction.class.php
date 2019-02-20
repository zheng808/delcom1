<?php

class dupeDatagridAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START dupeDatagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());

    //GET THE LIST OF DUPLICATE SKUs
    $sql = 'SELECT internal_sku FROM ('.
              ' SELECT '.PartVariantPeer::INTERNAL_SKU.', COUNT('.PartVariantPeer::INTERNAL_SKU.') AS cnt'.
              ' FROM '.PartPeer::TABLE_NAME.', '.PartVariantPeer::TABLE_NAME.
              ' WHERE '.PartPeer::ID.' = '.PartVariantPeer::PART_ID.
              ' AND '.PartPeer::ACTIVE.' = 1 AND '.PartVariantPeer::INTERNAL_SKU." <> ''".
              ' GROUP BY '.PartVariantPeer::INTERNAL_SKU.' ORDER BY '.PartVariantPeer::INTERNAL_SKU.' ASC'.
            ') AS src_query WHERE cnt > 1';

    //paging
    if ($request->getParameter('limit'))
    {
      $sql .= ' LIMIT '. ((int) $request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $sql .= ' OFFSET '. ((int) $request->getParameter('start'));
    }

    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $skus = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $skus[] = $row[0];
    }

    //RETRIEVE ALL AFFECTED PARTS
    $c = new Criteria();
    $c->add(PartVariantPeer::INTERNAL_SKU, $skus, Criteria::IN);
    $parts = PartPeer::doSelectJoinPartVariants($c);

    //SORT INTO AN ARRAY INDEXED BY SKU
    $output_holder = array();
    foreach ($parts AS $part)
    {
      if ($v = $part->getDefaultVariant())
      {
        if (!isset($output_holder[$v->getInternalSku()]))
        {
          $output_holder[$v->getInternalSku()] = array();
        }
        $output_holder[$v->getInternalSku()][] = $part;
      }
    }

    //OUTPUT THE RESULT, COLLATED INTO A SINGLE LINE
    $output = array();
    foreach ($output_holder AS $skukey => $parts)
    {
      $thisoutput = array('sku' => $skukey);
      for ($i = 0; $i < 3; $i ++)
      {
        if (isset($parts[$i]))
        {
          $thisoutput['part'.($i + 1).'name'] = $parts[$i]->getName();
          $thisoutput['part'.($i + 1).'id'] = $parts[$i]->getId();
        }
        else {
          $thisoutput['part'.($i + 1).'name'] = '';
          $thisoutput['part'.($i + 1).'id'] = '';
        }
      }
      $output[] = $thisoutput;
    }
    unset($skus, $output_holder, $thisoutput);

    //count the totals and add stuff to the final array
    $dataarray = array('totalCount' => PartPeer::doCountDupeSkus(), 'parts' => $output);

    $this->renderText(json_encode($dataarray));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE dupeDatagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//dupeDatagridAction{}========================================================
