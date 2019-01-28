<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ImageSaverTest : Test App routing.
 * @package App\Tests
 */
class RoutingTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /**
     * Route to provide for testing.
     *
     * @return \Generator
     */
    public function routeProvider()
    {
        // Provider are called before setup
        self::bootKernel();
        $routes = self::$container->get('router')->getRouteCollection()->all();

        foreach ($routes as $name => $route)
        {
            $path = $this->replace($route->getPath());
            yield ["$path" => $path];
        }
    }

    private function replace(string $path): string
    {
        $res = str_replace("{id}", "2", $path);
        return str_replace("{slug}", "slug", $res);
    }

    private function isConnexionRoute (string $path): bool
    {
        return strpos("$path", 'sign');
    }

    private function outputMessage($error, $message)
    {
        return <<<EOT
.
.
erreur $error :
$message
.
EOT;
    }

    /**
     * Test route with error 500.
     *
     * @dataProvider routeProvider
     *
     */
    public function testShowErrors500($path)
    {
        $client = $this->client;
        $crawler = $client->request('GET', "$path");
        $statuscode = $client->getResponse()->getStatusCode();

        if ($statuscode < 500 || $statuscode >= 600)
        {
            $this->assertTrue(true);
            return;
        }
        $message = $this->outputMessage($statuscode, $crawler->filter('title')->html());

        $this->assertFalse(true, "$message");
    }

    /**
     * Show error 400.
     *
     * @dataProvider routeProvider
     *
     */
    public function testShowErrors400($path)
    {
        $client = $this->client;
        $crawler = $client->request('GET', "$path");
        $statuscode = $client->getResponse()->getStatusCode();

        if ($statuscode < 500 || $statuscode >= 400)
        {
            $this->assertTrue(true);
            return;
        }
        $message = $this->outputMessage($statuscode, $crawler->filter('title')->html());

        $this->assertFalse(true, "$message");
    }

    /**
     * Show all Errors.
     *
     * @dataProvider routeProvider
     *
     */
    public function testShowAllErrors($path)
    {
        $client = $this->client;
        $crawler = $client->request('GET', "$path");
        $statuscode = $client->getResponse()->getStatusCode();
        $success = $client->getResponse()->isSuccessful();

        if (!$success)
        {
            $message = $this->outputMessage($statuscode, $crawler->filter('title')->html());
        }

        $this->assertTrue($success, "$message");

    }
}
