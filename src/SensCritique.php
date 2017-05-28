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
        return '/^https?:\/\/www.senscritique.com\/film\/\w+\/\d+$/';
    }

    /*
        Extract movie data
        
        Parameters
            Symfony\Component\DomCrawler\Crawler $crawler
        
        Return
            array
    */
    protected function _extract(Crawler $crawler) {
        $title = $crawler->filter('.pvi-product-originaltitle');
        if(!count($title)) {
            $title = $crawler->filter('.pvi-product-title');
        }

        return [
            'title' => trim($title->text()),
            'year' => trim($crawler->filter('.pvi-product-year')->text(), '()'),
            'duration' => trim($crawler->filter('.pvi-productDetails-item')->eq(2)->text()),
            'genres' => $crawler->filter('.pvi-productDetails-item')->eq(1)->filter('span')->each(function($node) {
                return trim(strtolower($node->text()));
            }),
            'rating' => trim($crawler->filter('.pvi-scrating-value')->text()),
            'poster' => $crawler->filter('.pvi-hero-poster')->attr('src'),
            'synopsis' => trim($crawler->filter('.pvi-productDetails-resume')->text())
        ];
    }
    
}
