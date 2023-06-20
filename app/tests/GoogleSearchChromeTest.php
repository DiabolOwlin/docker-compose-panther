<?php

use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverKeys;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;


class GoogleSearchChromeTest extends PantherTestCase
{

    public function testGoogleSearch()
    {
        $client = Client::createFirefoxClient();
        $size = new WebDriverDimension(1400, 1400);
        $client->manage()->window()->maximize()->setSize($size);
        $crawler = $client->request('GET', 'https://www.google.com/');
        $button = $crawler->filter('#W0wltc');
        $button->click();
        $client->waitFor('.gLFyf');
        $searchInput = $crawler->filter('.gLFyf');
        $searchInput->sendKeys('marcus rashford');
        $searchInput->sendKeys(WebDriverKeys::ENTER);
        $crawler = $client->waitFor('.g');
        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
        $results = $crawler->filter('div.g');

        $results->each(function (Crawler $node) {
            $title = $node->filter('h3')->text();
            $url = $node->filter('a')->attr('href');

            echo $title . ": " . $url . "\n";
        });
        $this->assertEquals('marcus rashford - Szukaj w Google', $client->getTitle());
        $client->quit();


    }

}