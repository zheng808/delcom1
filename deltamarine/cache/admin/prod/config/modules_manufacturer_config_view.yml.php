<?php
// auto-generated by sfViewConfigHandler
// date: 2020/10/18 22:37:03
$response = $this->context->getResponse();


  $templateName = sfConfig::get('symfony.view.'.$this->moduleName.'_'.$this->actionName.'_template', $this->actionName);
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());



  if (!is_null($layout = sfConfig::get('symfony.view.'.$this->moduleName.'_'.$this->actionName.'_layout')))
  {
    $this->setDecoratorTemplate(false === $layout ? false : $layout.$this->getExtension());
  }
  else if (is_null($this->getDecoratorTemplate()) && !$this->context->getRequest()->isXmlHttpRequest())
  {
    $this->setDecoratorTemplate('' == 'layout' ? false : 'layout'.$this->getExtension());
  }
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'Delta - Manufacturer Management', false, false);

  $response->addStylesheet('base', '', array ());
  $response->addStylesheet('main', '', array ());
  $response->addStylesheet('/js/ext-4.2.1/resources/css/ext-all', '', array ());
  $response->addStylesheet('admin', '', array ());
  $response->addJavascript('ext-4.2.1/ext-all-debug', '', array ());
  $response->addJavascript('ext-4.2.1/examples/ux/IFrame.js', '', array ());
  $response->addJavascript('barcodes_base', '', array ());
  $response->addJavascript('barcodes_admin', '', array ());
  $response->addJavascript('waitfix', '', array ());
  $response->addJavascript('precisionfix', '', array ());
  $response->addJavascript('extObjs-161010', '', array ());


