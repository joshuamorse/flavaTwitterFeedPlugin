<?php

class flavaTwitterFeedActions
{
  protected $_action;
  
  /**
   * Listens to the component.method_not_found event to effectively
   * extend the actions class
   */
  public function listenComponentMethodNotFound(sfEvent $event)
  {
    $this->_action = $event->getSubject();
    $method = $event['method'];
    $arguments = $event['arguments'];

    if (method_exists($this, $method))
    {
      $result = call_user_func_array(array($this, $method), $arguments);

      $event->setReturnValue($result);

      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * getFeedFor 
   * 
   * @param mixed $username 
   * @param array $options 
   * @access public
   * @return void
   */
  public function getFeed($username, array $options = null)
  {
    // Set the default count option.
    if (!isset($options['count']))
    {
      $options['count'] = sfConfig::get('flava_twitter_feed_count', 5);
    }

    // Grab the feed.
    $feed = 'http://twitter.com/statuses/user_timeline/' . $username . '.json';

    // Set the initial count option then unset it.
    $feed .= '?count=' . $options['count'];
    unset($options['count']);

    // Append the rest of the options.
    foreach ($options as $key => $val)
    {
      $feed .= '&' . $key . '=' . $val;
    }
    
    // Get the feed.
    $feed = file_get_contents($feed);
    $tweets = json_decode($feed);

    // Wrap links in proper HTML.
    foreach ($tweets as $tweet)
    {
      $tweet->text = $this->twitterify($tweet->text);
    }

    return $tweets;
  }

  /**
   * from: http://www.snipe.net/2009/09/php-twitter-clickable-links/
   * 
   * @param mixed $ret 
   * @access private
   * @return void
   */
  private function twitterify($ret)
  {
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
    $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);

    return $ret;
  }
}
