<?php

namespace App\Tests;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class IndexControllerTest extends WebTestCase
{

    private $router = null;
    private $loader = null;

/*    private $urlGen;
    private $routes;


    public function __construct( UrlGeneratorInterface $urlGen, RouterInterface $routes)
    {
        $this->urlGen = $urlGen;
        $this->routes = $routes;
    }
    */

    protected function setUp()
    {
        //$this->loader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')->getMock();
        //$this->router = new Router($this->loader, 'routing.yml');

        self::bootKernel();
    }

    /**
     *
     * @dataProvider additionProvider
     *
     */
    public function testRouteWith500Error($route)
    {
            $path = $this->replace($route->getPath());

            $client = self::createClient();
            $crawler = $client->request('GET', "$path");
            $response = $client->getResponse()->getStatusCode();
            $message = $crawler->filter('title')->html();
            $this->assertNotEquals("500","$response", "$path return an error 500 : $message ");
            //$this->assertContains('200', "$response", "$path return an error 500 : $message ");

            //$nodename = $crawler->filter('table')->eq(0)->html();
            //dd($client->getResponse()->getStatusCode());
            //$this->assertContains(200, $client->getResponse()->getStatusCode());
            //$this->assertNotNull($crawler->filter('table.trace-details'),'Page return an error.');
            //$this->assertNotNull($crawler->filterXPath('//table[@class="trace-details"]'),'Page return an error.');

            //print_r("$path\n");
            //$this->expectOutputString($path);
            //var_dump($route->getPath());
    }

    public function additionProvider()
    {
        $client = static::createClient();

        // returns the real and unchanged service container
        //$container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        //$container = self::$container;

        $routes = self::$container->get('router')->getRouteCollection()->all();

        foreach ($routes as $name => $route)
        {
            yield [$route];
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
