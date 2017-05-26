<?php

namespace LostMovie;

/*
    SensCritique search engine
*/
class SensCritique extends AbstractEngine {
    
    /*
        Search for a movie
        
        Parameters
            string $title
        
        Return
            array
    */
    protected function _search($title) {
        // Search for the movie
        $crawler = $this->goutte->request('GET', 'https://www.senscritique.com/recherche?filter=movies&query='.$title);
        $uri = $crawler->filter('.esco-item')->first()->filter('.esco-cover')->attr('href');
        $crawler = $this->goutte->request('GET', 'https://www.senscritique.com'.$uri);
        // Extract data
        return [
            'title' => $crawler->filter('.pvi-product-originaltitle')->text(),
            'year' => trim($crawler->filter('.pvi-product-year')->text(), '()'),
            'duration' => trim($crawler->filter('.pvi-productDetails-item')->eq(2)->text()),
            'genres' => $crawler->filter('.pvi-productDetails-item')->eq(1)->filter('span')->each(function($node) {
                return strtolower($node->text());
            }),
            'rating' => $crawler->filter('.pvi-scrating-value')->text(),
            'poster' => $crawler->filter('.pvi-hero-poster')->attr('src'),
            'synopsis' => $crawler->filter('.pvi-productDetails-resume')->text()
        ];
    }
    
}
