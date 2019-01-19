<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RoutingTest : Test App routing.
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
     * Test route with error.
     *
     * @dataProvider routeProvider
     *
     */
    public function testRouteWithError($path)
    {
        $client = $this->client;
        $crawler = $client->request('GET', "$path");
        $statuscode = $client->getResponse()->getStatusCode();

        if ($statuscode < 400)
        {
            $this->assertTrue(true);;
            return;
        }

        $message = $crawler->filter('title')->html();
        $this->assertEquals("200","$statuscode", "$message");
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
}
