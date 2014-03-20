<?php
namespace Mlaphp;

require __DIR__ . '/Router.php';

class RouterTest extends \PHPUnit_Framework_TestCase
{
    protected $router;

    protected $pages_dir;

    public function setUp()
    {
        $this->pages_dir = __DIR__ . '/pages';
        $this->router = new Router($this->pages_dir);
        $this->router->setFront('front-controller.php');
        $this->router->setHome('hello.php');
        $this->router->setNotFound('http-404.php');
    }

    public function testMatchWithoutFront()
    {
        $expect = "{$this->pages_dir}/hello.php";
        
        $actual = $this->router->match('/');
        $this->assertSame($expect, $actual);
        
        $actual = $this->router->match('/hello.php');
        $this->assertSame($expect, $actual);
    }

    public function testMatchStripsFront()
    {
        $expect = "{$this->pages_dir}/hello.php";

        $actual = $this->router->match('/front-controller.php');
        $this->assertSame($expect, $actual);

        $actual = $this->router->match('/front-controller.php/');
        $this->assertSame($expect, $actual);
    }

    public function testMatchNotFound()
    {
        $expect = "{$this->pages_dir}/http-404.php";
        $actual = $this->router->match('/no-such-file');
        $this->assertSame($expect, $actual);
    }

    public function testMatchMappedClass()
    {
        $this->router->setRoutes(array(
            '/controller-name' => 'ControllerClass',
        ));

        $expect = 'ControllerClass';
        $actual = $this->router->match('/controller-name');
        $this->assertSame($expect, $actual);
    }

    public function testMatchMappedFile()
    {
        $this->router->setRoutes(array(
            '/hello.php' => '/other.php',
        ));

        $expect = "{$this->pages_dir}/other.php";
        $actual = $this->router->match('/hello.php');
        $this->assertSame($expect, $actual);
    }

    public function testNoPagesDir()
    {
        $router = new Router;
        $this->setExpectedException('RuntimeException');
        $router->match('/hello.php');
    }
}
