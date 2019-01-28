<?php

namespace App\Tests;

use App\Tests\Controller\Routes\RouteErrors;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ImageSaverTest : Test App routing.
 * @package App\Tests
 */
class RouteTest extends WebTestCase
{
    private $routeErrors;
    private $routeCollection;

    protected function setUp()
    {
        parent::setUp();
        // return a two dimensional array, ex $errors[300] = [
        $this->routeErrors = (new RouteErrors())->initialize();
    }

    private function outputMessage($statuscode, $path, $message)
    {
        return <<<EOT
.
.
Error $statuscode on route $path
$message
.
EOT;
    }

    /**
     * Is there any errors to check.
     *
     *
     */
    public function testIsThereAnyError()
    {
        $this->assertNotEquals(0, $this->routeErrors->count(), "Congrats, no error in routes !");
    }

    /**
     * Show all Errors.
     *
     * @dataProvider routeProvider
     */
    public function testWhatKindOfErrors($statuscode, $path, $message)
    {
        $this->assertTrue(200 == $statuscode,
            $this->outputMessage($statuscode, $path, $message));
    }

    /**
     * Route to provide for testing.
     *
     * @return \Generator
     */
    public function routeProvider()
    {
        // Provider is called before setup
        $this->collection = (new RouteErrors())->initialize();
        $errors = $this->routeErrors = $this->collection->getAll();

        return $errors;

/*        foreach ($this->routeErrors as $name => $error)
        {
            yield [$error];
        }*/
    }
}
