<?php

/**
 * flavaTwitterFeedPluginConfiguration 
 * 
 * @uses sfPluginConfiguration
 * @package 
 * @version $id$
 * @author Joshua Morse <joshua.morse@iostudio.com> 
 */
class flavaTwitterFeedPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('component.method_not_found', array(new flavaTwitterFeedActions(), 'listenComponentMethodNotFound'));
  }
}
