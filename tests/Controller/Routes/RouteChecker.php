<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 26/01/2019
 * Time: 18:47
 */

namespace App\Tests\Controller\Routes;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class RouteChecker extends KernelTestCase
{
    private $routeErrors = [];
    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     *
     * @return $client A Client instance
     */
    protected static function createClient(array $options = [], array $server = [])
    {
        $kernel = static::bootKernel($options);
        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException $e) {
            throw new \LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".');
        }
        $client->setServerParameters($server);
        return $client;
    }

    private function replace(string $path): string
    {
        $res = str_replace("{id}", "2", $path);
        return str_replace("{slug}", "slug", $res);
    }

    private function filterErrors(int $statuscode): array
    {
        return array_filter($this->routeErrors, function($error) use ($statuscode){
            return $error['statuscode'] == $statuscode;
        });
    }

    private function outputMessage($error)
    {
        $statuscode = $error['statuscode'];
        $path = $error['path'];
        $message = $error['message'];

        return <<<EOT
.
    erreur $statuscode on $path :
    $message
.
EOT;
    }

    private function isConnexionRoute (string $path): bool
    {
        return strpos("$path", 'sign');
    }

    public function initialize(): self
    {
        // Get the test kernel
        $client = $this->createClient();
        // Get the route collection
        $collection = self::$container->get('router')->getRouteCollection()->all();

        foreach ($collection as $name => $route)
        {
            $path = $this->replace($route->getPath());
            $routes[] = $path;
        }

        // get all errors in one array
        //$client = static::createClient();
        $err = [];

        array_walk($routes, function ($route) use ($client, &$err){
            $crawler = $client->request('GET', "$route");
            $statuscode = $client->getResponse()->getStatusCode();
            $success = $client->getResponse()->isSuccessful();

            if (!$success)
            {
                $message = $crawler->filter('title')->html();
                $err[] = ["statuscode" => $statuscode, "path" => $route, "message" => $message];
            }
        });

        $this->routeErrors = $err;
        return $this;
    }

    public function getAll(): array
    {
        return $this->routeErrors;
    }

    public function get300(): array
    {
        return $this->filterErrors(300);
    }

    public function get400(): array
    {
        return $this->filterErrors(400);
    }

    public function get500(): array
    {
        return $this->filterErrors(500);
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->routeErrors as $e)
        {
            $count += count($e);
        }
        return $count;
    }

    public function printErrors()
    {

        echo "Error found in routes :";
        echo " ";
        echo "* Error 300 : ";
        foreach ($this->get300() as $error)
        {
            $this->outputMessage($error);
        }

    }
}

$check = new RouteChecker();
$check->initialize();
$check->printErrors();