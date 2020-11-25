<?php

/**
 * CustomerOrder form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CustomerOrderForm extends BaseCustomerOrderForm
{
  public function configure($std=false)
  {
        unset($this['guard_user_id'], $this['hidden']);
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'customer_id'          => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'finalized'            => new sfWidgetFormInputHidden(),//new sfWidgetFormInputCheckbox(),//new sfWidgetFormInputHidden(),//
      'approved'             => new sfWidgetFormInputHidden(),//new sfWidgetFormInputCheckbox(),//new sfWidgetFormInputHidden(),//
      'sent_some'            => new sfWidgetFormInputHidden(),//new sfWidgetFormInputCheckbox(),
      'sent_all'             => new sfWidgetFormInputHidden(),//new sfWidgetFormInputCheckbox(),
      'invoice_per_shipment' => new sfWidgetFormInputHidden(),//new sfWidgetFormInputCheckbox(),
      'invoice_id'           => new sfWidgetFormInputHidden(),//new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'date_ordered'         => new sfWidgetFormInputHidden(),//=> new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'customer_id'          => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => true)),
      'finalized'            => new sfValidatorBoolean(array('required' => false)),
      'approved'             => new sfValidatorBoolean(array('required' => false)),
      'sent_some'            => new sfValidatorBoolean(array('required' => false)),
      'sent_all'             => new sfValidatorBoolean(array('required' => false)),
      'invoice_per_shipment' => new sfValidatorBoolean(array('required' => false)),
      'invoice_id'           => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'date_ordered'         => new sfValidatorDateTime(array('required' => false)),
    ));
    $this->bind(array("date_ordered"=>date('Y-m-d H:i:s')));
    //$this->bind(array("date_ordered"=>array(date('Y'),date('m'),date('d'), date('H'), date('i'), date('s'))));
    $this->widgetSchema->setNameFormat('customer_order[%s]');
    if($std)
    {
	$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	return;
    }
    /*
    $qPart = PartVariantPeer::doSelect(new Criteria());
    $partIds = array();
    $partNames = array();
    $prices = array();
    $markup = array();
    $quantity = array();
    $availquantity = array();
    $skus = array();
	for($i=0;$i<sizeof($qPart);$i++)
	{
		$c1=new Criteria();
		$qpvid = $qPart[$i]->getId();
		$qpid = $qPart[$i]->getPartId();
		$availquantity[sizeof($availquantity)] = $qPart[$i]->getQuantity('available');
		$c1->add(PartPeer::ID,$qpid);
		$rPart = PartPeer::doSelectOne($c1);
		$partIds[sizeof($partIds)] = $qpvid;
                $d = "";
                $sku = "";
                $s1 = $qPart[$i]->getInternalSku();
                $s2 = $qPart[$i]->getManufacturerSku();
                $c_sup = new Criteria();
                $c_sup->add(PartSupplierPeer::PART_VARIANT_ID, $qpvid);
                $s3a = PartSupplierPeer::doSelectOne($c_sup);
                $s3 = '';
                if($s3a)
                {
                  $s3 = $s3a->getSupplierSku();
                }
                $sku = ((empty($s1)) ? ((empty($s2)) ? ($s3) : ($s2)) : ($s1));
                    $part = $rPart;
                    $variantpart = $qPart[$i];
                    if ($part->getIsMultisku())
                    {
                        $d = ''.$variantpart->outputOptionValuesList().'';
                    }
                    if(!empty($d))
                    {
		        $partNames[sizeof($partNames)] = $rPart->getName()."(".$d.")";
		    }
		    else
		    {
		        $partNames[sizeof($partNames)] = $rPart->getName();
		    }
		    $tempPrice = $qPart[$i]->outputUnitPrice();
		    if(substr($tempPrice,0,1)== '$')
		    {
			$tempPrice=substr($tempPrice,1);
		    }
		    $prices[sizeof($prices)] = $tempPrice;
		    $quantity[sizeof($quantity)] = 0;
		    $skus[sizeof($skus)] = $sku;
		    $markup[sizeof($markup)] = $qPart[$i]->getMarkupPercent();
	}
    $parts = array();
    $j = array();
    $this->prices = $prices;
    $this->markup = $markup;
    $this->ids = $partIds;
    $this->quantity = $quantity;
    $this->availquantity = $availquantity;
    $this->skus = $skus;
    for($i=0;$i<sizeof($partIds);$i++)
    {
	$parts[$partIds[$i]] = $partNames[$i];//array($partNames[$i],$prices[$i]);
    }
    $this->parts = $parts;
    //sfWidgetFormPropelChoice(array('choices'=> $parts)),
    //'choices'  =>  array(0 => 'Change the price', 1 => 'Change discount', 2 => 'Change markup'),
    //$w = new sfWidgetFormChoice(array(
    //    'choices'        => $parts,
    //	'renderer_class' => 'sfWidgetFormSelectDoubleList',
    //	));
    //$w = new sfWidgetFormInputHidden();
    //$w = new sfWidgetFormPropelChoice(array('model' => '','choices'=> $parts));
    //$this->setWidget('parts',$w);
    for($i=0;$i<sizeof($partIds);$i++)
    {
	$newW = new sfWidgetFormInputHidden();
	$this->setWidget('price_'.$partIds[$i],$newW);
	$this->bind(array('price_'.$partIds[$i] => $prices[$i]));
	$newW1 = new sfWidgetFormInputHidden();
	$this->setWidget('markup_'.$partIds[$i],$newW1);
	$this->bind(array('markup_'.$partIds[$i] => $markup[$i]));
	$newW2 = new sfWidgetFormInputHidden();
	$this->setWidget('discount_'.$partIds[$i],$newW2);
	$this->bind(array('discount_'.$partIds[$i] => "0"));
	$newW3 = new sfWidgetFormInputHidden();
	$this->setWidget('todowithprice__'.$partIds[$i],$newW3);
	$this->bind(array('todowithprice__'.$partIds[$i] => "0"));
	$newW4 = new sfWidgetFormInputHidden();
	$this->setWidget('quantity_'.$partIds[$i],$newW4);
	$this->bind(array('quantity_'.$partIds[$i] => "0"));
    }
    $w11 = new sfWidgetFormInputHidden();
    $w11->setLabel('Set discount (%)');
    $w12 = new sfWidgetFormInputHidden();
    $w12->setLabel('Change markup (%)');
    $this->setWidget('discount',$w11);
    $this->setWidget('markup',$w12);
    $w13 = new sfWidgetFormInputHidden();
    $w13->setLabel('Change quantity');
    $this->setWidget('quantity',$w13);
    //$w2 = new sfWidgetFormChoice(array(
    //	'choices'  =>  array(0 => 'Change the price', 1 => 'Change discount', 2 => 'Change markup'),
    //	'expanded' => true,
    //));
    $w2 = new sfWidgetFormInputHidden();
    $w2->setLabel('To do with price');
    $this->setWidget('todowithprice',$w2);
    $this->bind(array('discount' => "0",'markup' => "0", 'todowithprice' => "0"));
    $flVal1 = new sfValidatorNumber(array('required' => false));
    $flVal2 = new sfValidatorNumber(array('required' => false));
    $flVal3 = new sfValidatorNumber(array('required' => false));
    $flVal4 = new sfValidatorNumber(array('required' => false));
    $this->setValidator('discount', $flVal1);
    $this->setValidator('discount', $flVal2);
    $this->setValidator('markup', $flVal3);
    $this->setValidator('quantity', $flVal4);
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    ;*/
  }

  public function processValues($values)
  {
    // see if the user has overridden some column setter
    $valuesToProcess = $values;
    foreach ($valuesToProcess as $field => $value)
    {
      try
      {
        $method = sprintf('update%sColumn', call_user_func(array(constant(get_class($this->object).'::PEER'), 'translateFieldName'), $field, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_PHPNAME));
      }
      catch (Exception $e)
      {
        // not a "real" column of this object
        if (!method_exists($this, $method = sprintf('update%sColumn', self::camelize($field))))
        {
          continue;
        }
      }

      if (method_exists($this, $method))
      {
        if (false === $ret = $this->$method($value))
        {
          unset($values[$field]);
        }
        else
        {
          $values[$field] = $ret;
        }
      }
      else
      {
        // save files
        if ($this->validatorSchema[$field] instanceof sfValidatorFile)
        {
          $values[$field] = $this->processUploadedFile($field, null, $valuesToProcess);
        }
      }
    }

    return $values;
  }

  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);

    $this->object->fromArray($values, BasePeer::TYPE_FIELDNAME);

    // embedded forms
    $this->updateObjectEmbeddedForms($values);

    return $this->object;
  }

  protected function doSave($con = null)
  {
    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $this->updateObject();

    $this->object->save($con);

    // embedded forms
    $this->saveEmbeddedForms($con);
  }

  public function save($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    try
    {
      $con->beginTransaction();

      $this->doSave($con);

      $con->commit();
    }
    catch (Exception $e)
    {
      $con->rollBack();

      throw $e;
    }

    return $this->object;
  }

}
