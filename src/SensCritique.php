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
        $title = count($title) ? $title->text() : $crawler->filter('.pvi-product-title')->text();

        $year = $crawler->filter('.pvi-product-year');
        $year = count($year) ? trim($year->text(), '()') : '';

        $year = $crawler->filter('.pvi-product-year');
        $year = count($year) ? trim($year->text(), '()') : '';

        $duration = $crawler->filter('.pvi-productDetails-item')->eq(2);
        $duration = count($duration) ? $duration->text() : '';

        $genres = $crawler->filter('.pvi-productDetails-item')->eq(1);
        if(count($genres)) {
            $genres = $genres->filter('span')->each(function($node) {
                return trim(strtolower($node->text()));
            });
        }
        else {
            $genres = [];
        }

        $rating = $crawler->filter('.pvi-scrating-value');
        $rating = count($rating) ? $rating->text() : '';

        $poster = $crawler->filter('.pvi-hero-poster');
        $poster = count($poster) ? $poster->attr('src') : '';

        $synopsis = $crawler->filter('.pvi-productDetails-resume');
        $synopsis = count($synopsis) ? $synopsis->text() : '';

        return [
            'title' => $title,
            'year' => $year,
            'duration' => $duration,
            'genres' => $genres,
            'rating' => $rating,
            'poster' => $poster,
            'synopsis' => $synopsis
        ];
    }
    
}
