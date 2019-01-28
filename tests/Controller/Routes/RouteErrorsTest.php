<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ImageSaverTest : Test App routing.
 * @package App\Tests
 */
class RouteErrorsTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    private function replace(string $path): string
    {
        $res = str_replace("{id}", "2", $path);
        return str_replace("{slug}", "slug", $res);
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

    private function outputMessage($error, $path, $message)
    {
        return <<<EOT
.
    erreur $error on $path :
    $message
.
EOT;
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
        $message = $this->outputMessage($statuscode, $path, $crawler->filter('title')->html());

        $this->assertTrue($success, "$message");
    }
}
