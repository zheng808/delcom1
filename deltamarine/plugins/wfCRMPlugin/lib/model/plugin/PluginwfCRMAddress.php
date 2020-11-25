<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMAddress extends BasewfCRMAddress
{
  const HOME = 'home';
  const WORK = 'work';
  
  public function __toString()
  {
    return $this->getType().": ".$this->getAddress();
  }

  /*
   * if $home_country is specified, the country will not be outputted if it matches
   *  (only print country if the address if in another country)
  */
  public function getAddress($line_separator = "\n", $home_country = null)
  {
    $output = array();
    if ($this->getLine1()) $output[] = $this->getLine1();
    if ($this->getLine2()) $output[] = $this->getLine2();

    $line = '';
    if ($this->getCity())
    {
      $line = $this->getCity();
      if ($this->getRegion()) $line .= ", ".$this->getRegion();
    }
    if ($this->getPostal()) $line .= '  '.$this->getPostal();
    if (!empty($line)) $output[] = $line;
    if ($this->getCountry())
    {
      if (!$home_country || ($home_country != $this->getCountry()))
      {
        try{
          $context = sfContext::getInstance();
          $context->getConfiguration()->loadHelpers(array('I18N')); 
          $output[] = format_country($this->getCountry());
        }catch(Exception $e){
          $output[] = $this->getCountry();
        }
      }
    }
    
    $output = implode($line_separator, $output);

    return $output;
  }
  
  static public function getTypes(){
    return array(self::HOME=>'home',self::WORK=>'work');
  }
  
}
