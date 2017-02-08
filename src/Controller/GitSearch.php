<?php

namespace Drupal\gitsearch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\gitsearch\GithubSearchService;

/**
 * Class GitSearch.
 *
 * @package Drupal\gitsearch\Controller
 */
class GitSearch extends ControllerBase {
  
  /**
   * Search.
   */
  public function searchHome() {
    $form = \Drupal::formBuilder()->getForm('Drupal\gitsearch\Form\SearchForm');
    
    $content = [
      '#theme' => 'gitsearch',
      '#attached' => array(
        'library' => array(
          'gitsearch/gitsearch',
        ),
      ),
      '#content' => [
        '#markup' => $this->t('<p>Use the form below to search for github repositories:</p>'),
      ],
      '#form' => $form,
      '#results' => [
        '#markup' => $this->t('<p>Use the form above to search Github repositories.</p>'),
      ],
    ];
    
    $tempstore = \Drupal::service('user.private_tempstore')->get('gitsearch');
    $keywords = $tempstore->get('keywords');
    
    if ($keywords !== '') {
      // Set the default value for the search field
      $form['keywords']['#default_value'] = $keywords;
      
      // Get a search result from github
      //$request_url = 'https://api.github.com/search/repositories?q=' . urlencode($keywords);
      $search = new GithubSearchService();
      $results = $search->keywordSearch(urlencode($keywords));
      if (!empty($results)) {
        /**
         *  This is not the way to do this, I want to see a twig template that
         *  handles the row output
         */
        
        $content['#results'] = [];
        $content['#results']['#markup'] = '';
        foreach ($results->items as $item) {
          $content['#results']['#markup'] .= '<h4><a href="' . $item->url . '">' . $item->full_name . '</a></h4>';
          $content['#results']['#markup'] .= '<p>' . $item->description . '</p>';
        }
      }
    }
    
    return $content;
  }

}
