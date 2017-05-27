<?php

namespace LostMovie;

use Symfony\Component\DomCrawler\Crawler;

/*
    SensCritique search engine
*/
class SensCritique extends AbstractEngine {
    
    /*
        Return the site URL for searching

        Return
            string
    */
    protected function _getSiteUrl() {
        return 'senscritique.com';
    }
    
    /*
        Return the pattern to use to find the right movie URL

        Return
            string
    */
    protected function _getUrlPattern() {
        return '/https:\/\/www.senscritique.com\/film\/\w+\/\d+/';
    }

    /*
        Extract movie data
        
        Parameters
            Symfony\Component\DomCrawler\Crawler $crawler
        
        Return
            array
    */
    protected function _extract(Crawler $crawler) {
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
