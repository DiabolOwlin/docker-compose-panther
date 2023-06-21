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
        //i think panther client is more flexible and customisable than specific firefox/chrome clients
        $client = self::createPantherClient(
            [],
            [],
            [
                'chromedriver_arguments' => [
                    '--log-path=myfile.log',
                    '--log-level=DEBUG'
                ],
            ]
        );
        // we can adjust browser display resolution to our needs,
        // be aware that dev tools window is covering part of the screen and will not be present on screenshot(so choose resolution considering dev tools)
        // also choose resolution before using client's request method
        $size = new WebDriverDimension(1920, 1080);
        $client->manage()->window()->maximize()->setSize($size);
        $crawler = $client->request('GET', 'https://www.google.com/');
        $button = $crawler->filter('#W0wltc');
        $button->click();
        $client->waitFor('.gLFyf');
        $searchInput = $crawler->filter('.gLFyf');
        $searchInput->sendKeys('marcus rashford');
        $searchInput->sendKeys(WebDriverKeys::ENTER);
        //refreshing crawler content after changing the page to a search result
        $crawler = $client->waitFor('.g');
        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
        $results = $crawler->filter('div.g');

        $results->each(function (Crawler $node) {
            $title = $node->filter('h3')->text();
            $url = $node->filter('a')->attr('href');

            echo $title . ": " . $url . "\n";
        });
        //sleep just to check browser, without it in case if all tests are successful it closes right after the last test performed
        //sleep(100);
        $this->assertEquals('marcus rashford - Szukaj w Google', $client->getTitle());
        $client->quit();


    }

}



