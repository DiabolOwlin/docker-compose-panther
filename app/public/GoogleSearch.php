<?php
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;

class GoogleSearch
{
    private $client;

    public function __construct()
    {
        $this->client = Client::createChromeClient();
    }



    public function search($query)
    {
        $crawler = $this->client->request('GET', 'https://www.google.com/');

        $searchInput = $crawler->filter('input[name="q"]')->first();
        $searchInput->sendKeys($query);

        $searchInput->sendKeys(WebDriverKeys::ENTER);

        $this->client->waitFor('.g');

        $results = $crawler->filter('div.g');

        $results->each(function (Crawler $node) {
            $title = $node->filter('h3')->text();
            $url = $node->filter('a')->attr('href');

            echo $title . ": " . $url . "\n";
        });
    }


    public function quit()
    {
        $this->client->quit();
    }
}
