<?php

class categoriestreeAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START categoriestreeAction.execute====================';
      //sfContext::getInstance()->getLogger()->info($message);
    }
    //$this->forward404Unless($request->isXmlHttpRequest());

    //must check for root nodes
    $node = $request->getParameter('node');
    if (!is_numeric($node))
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'node is not numeric';
        //sfContext::getInstance()->getLogger()->info($message);
        //sfContext::getInstance()->getLogger()->info('node: '.$node);
      }

      $this->forward404Unless($node = PartCategoryPeer::retrieveRoot());

      //figure out where (if anywhere) to recurse
      if ($selected = $request->getParameter('selected_node'))
      {
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'selected_node: ';
          //sfContext::getInstance()->getLogger()->info($message.$selected);
        }
        
        $this->forward404Unless($selected = PartCategoryPeer::retrieveByPk($selected));
        $selected_tree = array();
        do{
          array_unshift($selected_tree, $selected->getId());
        } while ($selected = $selected->retrieveParent());
      }
      else
      {
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'else selected_node...';
          //sfContext::getInstance()->getLogger()->info($message);
        }
        $selected_tree = array($node->getId());
      }
    }
    else
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'node is numeric';
        //sfContext::getInstance()->getLogger()->info($message);
        //sfContext::getInstance()->getLogger()->info('node: '.$node);
      }

      $this->forward404Unless($node = PartCategoryPeer::retrieveByPk($node));
      $selected_tree = array($node->getId());
    }

    $output = $this->addChildNodes($node, $selected_tree, true);
    $this->renderText(json_encode($output));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE categoriestreeAction.execute====================';
      //sfContext::getInstance()->getLogger()->info($message);
    }
    
    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

  private function addChildNodes($node, $selected_tree, $is_root)
  {
    $output = array();
    if (!$is_root)
    {
      $output = array(
        'id' => $node->getId(),
        'leaf' => true,
        'text' => $node->getName(),
        'iconCls' => 'folder'
      );
    }
    if (count($selected_tree) > 0 && $node->getId() == $selected_tree[0])
    {
      //recurse down into the tree
      array_shift($selected_tree);
      $children = $node->getChildren();
      $children_arr = array();
      foreach ($children AS $child)
      {
        $children_arr[strtoupper($child->getName())] = $this->addChildNodes($child, $selected_tree, false);
      }
      ksort($children_arr);
      $children_arr = array_values($children_arr);
      if ($is_root)
      {
        $output = $children_arr;
      }
      else
      {
        $output['leaf'] = false;
        $output['expanded'] = true;
        $output['categories'] = $children_arr;
      }
    }
    else if ($node->getChildren())
    {
      $output['leaf'] = false;
    }

    return $output;
  }//addChildNodes()-----------------------------------------------------------
}//categoriestreeAction.class{}================================================
