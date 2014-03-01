<?php
namespace Mlaphp;

require __DIR__ . '/Request.php';

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected $request;

    public function setUp()
    {
        $this->request = new Request($GLOBALS);
    }

    public function testCookie()
    {
        $_COOKIE['foo'] = 'bar';
        $this->assertSame('bar', $this->request->cookie['foo']);

        $this->request->cookie['baz'] = 'dib';
        $this->assertSame('dib', $_COOKIE['baz']);
    }

    public function testEnv()
    {
        $_ENV['foo'] = 'bar';
        $this->assertSame('bar', $this->request->env['foo']);

        $this->request->env['baz'] = 'dib';
        $this->assertSame('dib', $_ENV['baz']);
    }

    public function testFiles()
    {
        $_FILES['foo'] = 'bar';
        $this->assertSame('bar', $this->request->files['foo']);

        $this->request->files['baz'] = 'dib';
        $this->assertSame('dib', $_FILES['baz']);
    }

    public function testGet()
    {
        $_GET['foo'] = 'bar';
        $this->assertSame('bar', $this->request->get['foo']);

        $this->request->get['baz'] = 'dib';
        $this->assertSame('dib', $_GET['baz']);
    }

    public function testPost()
    {
        $_POST['foo'] = 'bar';
        $this->assertSame('bar', $this->request->post['foo']);

        $this->request->post['baz'] = 'dib';
        $this->assertSame('dib', $_POST['baz']);
    }

    public function testRequest()
    {
        $_REQUEST['foo'] = 'bar';
        $this->assertSame('bar', $this->request->request['foo']);

        $this->request->request['baz'] = 'dib';
        $this->assertSame('dib', $_REQUEST['baz']);
    }

    public function testServer()
    {
        $_SERVER['foo'] = 'bar';
        $this->assertSame('bar', $this->request->server['foo']);

        $this->request->server['baz'] = 'dib';
        $this->assertSame('dib', $_SERVER['baz']);
    }
}
