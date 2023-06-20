<?php

use Facebook\WebDriver\WebDriverKeys;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client;

class GoogleSearchChromeTest extends PantherTestCase
{

    public function testGoogleSearch()
    {
        $client = Client::createFirefoxClient();
        $crawler = $client->request('GET', 'https://www.google.com/');
        $client->waitFor('.gLFyf'); // Wait for up to 10 seconds
        $searchInput = $crawler->filter('.gLFyf');
        $searchInput->sendKeys('test query');
        $searchInput->sendKeys(WebDriverKeys::ENTER);
        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
        $this->assertEquals('test query - Google Search', $client->getTitle());
        $this->assertCount(1, $crawler->filter('.g'));
        $client->quit();


    }

}