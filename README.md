flavaTwitterFeedPlugin
======================

A very quick and dirty way to pull in a twitter feed into your Symfony
project.


Usage
-----

In your actions:


    $this->tweets = $this->getFeed('username', array('count' => 4));


In your view (note the use of the output partial; you'll need something like this to escape Symfony's output escaper):


    <ul id="tweets">
      <?php foreach ($tweets as $tweet): ?>
        <li>
          <?php include_partial('flavaTwitterFeed/output', array('text' => $tweet->text)) ?>
        </li>
      <?php endforeach ?>
    </ul>

Enable the flavaTwitterFeed module in settings.yml:

    enabled_modules:
    ...
      - flavaTwitterFeed    
    ...


It is highly recommended that you cache which view you're using to output your
twitter feed, as twitter imposes an houry limit on API calls.
