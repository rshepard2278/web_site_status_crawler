<?php
  // include Spider class file
  require_once('spider.class.php');

  // create new Spider object
  $spider = new Spider('http://www.ringenie.com');

  // allow files with extension *.txt being spidered
  $spider->allowType('txt');

  // and disable files with that extension
  $spider->restrictType('txt');

  // set it to true if you want to see what is happening on the screen
  $spider->setVerbose(true);

  // start spidering website
  $spider->startSpider();

  // all found and fetched links are in that variable
  $links = $spider->all_links;

  // print it out
  sprintf($links);
?>