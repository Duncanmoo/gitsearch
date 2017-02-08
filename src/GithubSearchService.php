<?php

namespace Drupal\gitsearch;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

/**
 * Class GithubSearchService.
 *
 * @package Drupal\gitsearch
 */
class GithubSearchService {
  
  protected $api_client;

  /**
   * Constructor.
   */
  public function __construct() {
    $config = [
      'base_uri' => 'https://api.github.com',
    ];
    $this->api_client = new Client($config);
    
  }
  /**
   * @param $keywords - a string of text
   */
  public function keywordSearch($keywords) {
    try {
      $response = $this->api_client->get('/search/repositories?q=' . $keywords);
    } catch (RequestException $e) {
      echo $e->getRequest() . "\n";
      if ($e->hasResponse()) {
        echo $e->getResponse() . "\n";
      }
    }
    if ($response->getBody()) {
      return json_decode( $response->getBody());
    }
    else {
      return false;
    }
  }

}
