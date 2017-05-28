<?php

namespace LostMovie;

use GuzzleHttp;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;

/*
    Abstract search engine
*/
abstract class AbstractEngine {
    
    /*
        GuzzleHttp\Client $guzzle
        Goutte\Client $goutte
    */
    protected $guzzle;
    protected $goutte;
    
    /*
        Constructor
    */
    public function __construct() {
        $this->guzzle = new GuzzleHttp\Client(['verify' => false]);
        $this->goutte = new Goutte\Client();
        $this->goutte->setClient($this->guzzle);
    }

    /*
        Get the Guzzle driver

        Return
            GuzzleHttp\Client
    */
    public function getGuzzle() {
        return $this->guzzle;
    }

    /*
        Get the Goutte driver

        Return
            Goutte\Client
    */
    public function getGoutte() {
        return $this->goutte;
    }
    
    /*
        Search for a movie
        
        Parameters
            string $title
        
        Return
            array, null
    */
    public function search($title) {
        // Prepare
        $fields = ['title', 'year', 'duration', 'genres', 'rating', 'poster', 'synopsis'];
        // Search in Google
        $crawler = $this->goutte->request(
            'GET',
            'https://www.google.com/search?q=site:'.$this->_getSiteUrl().' '.$title
        );
        // No results
        if(!count($crawler->filter('.r a'))) {
            return null;
        }
        // Extract all URLs
        $urls = $crawler->filter('.r a')->each(function($node) {
            parse_str(parse_url($node->extract('href')[0], PHP_URL_QUERY), $vars);
            return $vars['q'];
        });
        foreach($urls as $url) {
            // We found a result!
            if(preg_match($this->_getUrlPattern(), $url)) {
                // Extract data
                $data = $this->_extract($this->goutte->request('GET', $url));
                // Verify data scheme and format
                foreach($fields as $field) {
                    if(!array_key_exists($field, $data)) {
                        throw new Exception("'$field' field not found");
                    }
                }
                foreach($data as $field => &$value) {
                    if(!in_array($field, $fields)) {
                        throw new Exception("'$field' field not supported");
                    }
                    if(is_string($value)) {
                        $value = trim($value);
                    }
                }
                return $data;
            }
        }
        // Nothing has been found on the first page
        return null;
    }

    /*
        Return the site URL for searching

        Return
            string
    */
    abstract protected function _getSiteUrl();

    /*
        Return the pattern to use to find the right movie URL

        Return
            string
    */
    abstract protected function _getUrlPattern();

    /*
        Extract movie data
        
        Parameters
            Symfony\Component\DomCrawler\Crawler $crawler
        
        Return
            array
    */
    abstract protected function _extract(Crawler $crawler);
    
}
