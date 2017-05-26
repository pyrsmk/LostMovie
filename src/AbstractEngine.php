<?php

namespace LostMovie;

use GuzzleHttp;
use Goutte;

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
        Search for a movie
        
        Parameters
            string $title
        
        Return
            array
    */
    public function search($title) {
        // Prepare
        $fields = ['title', 'year', 'duration', 'genres', 'rating', 'poster', 'synopsis'];
        // Get data
        $data = $this->_search($title);
        // Verify
        foreach($fields as $field) {
            if(!array_key_exists($field, $data)) {
                throw new Exception("'$field' field not found");
            }
        }
        foreach($data as $field => $value) {
            if(!in_array($field, $fields)) {
                throw new Exception("'$field' field not supported");
            }
        }
        return $data;
    }
    
    /*
        Search for a movie
        
        Parameters
            string $title
        
        Return
            array
    */
    abstract protected function _search($title);
    
}
