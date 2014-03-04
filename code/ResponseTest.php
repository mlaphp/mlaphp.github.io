<?php
namespace Mlaphp;

require __DIR__ . '/Response.php';
require __DIR__ . '/FakeResponse.php';

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $func_result = 'func not called';

    protected $view_file;

    protected $view_base;

    public function setUp()
    {
        $this->view_file = 'response_view.php';
        $this->view_base = __DIR__;
        $this->response = new FakeResponse;
    }

    public function testSetAndGetViewBase()
    {
        $this->response->setViewBase($this->view_base);
        $this->assertSame($this->view_base, $this->response->getViewBase());
    }

    public function testSetAndGetView()
    {
        $this->response->setView($this->view_file);
        $this->assertSame($this->view_file, $this->response->getView());
    }

    public function testSetAndGetVars()
    {
        $vars = array('foo' => 'bar');
        $this->response->setVars($vars);
        $this->assertSame($vars, $this->response->getVars());
    }

    public function testSetAndGetLastCall()
    {
        $func = array($this, 'responseFunc');
        $this->response->setLastCall($func, 'made last call');
        $expect = array($func, 'made last call');
        $actual = $this->response->getLastCall();
        $this->assertSame($expect, $actual);
    }

    public function testEsc()
    {
        $expect = "&lt;tag&gt;&quot;quote\&#039;apos&amp;amp";
        $actual = $this->response->esc("<tag>\"quote\'apos&amp");
        $this->assertSame($expect, $actual);
    }

    public function testBufferedHeaders()
    {
        $this->response->header('Foo: Bar');
        $this->response->setCookie('cookie', 'value');
        $this->response->setRawCookie('rawcookie', 'rawvalue');
        $expect = array(
            array('header', 'Foo: Bar'),
            array('setcookie', 'cookie', 'value'),
            array('setrawcookie', 'rawcookie', 'rawvalue'),
        );
        $this->assertSame($expect, $this->response->getHeaders());
    }

    public function testGetViewPath()
    {
        $this->response->setViewBase($this->view_base);
        $this->response->setView($this->view_file);
        $expect = $this->view_base . DIRECTORY_SEPARATOR . $this->view_file;
        $this->assertSame($expect, $this->response->getViewPath());
    }

    public function testRequireView()
    {
        $this->response->setVars(array('noun' => 'World'));
        $this->response->setViewBase($this->view_base);
        $this->response->setView($this->view_file);
        $output = $this->response->requireView();
        $this->assertSame('Hello World!', $output);
    }

    public function testNoViewToRequire()
    {
        $output = $this->response->requireView();
        $this->assertSame('', $output);
    }

    public function testInvokeLastCall()
    {
        $this->response->setLastCall(array($this, 'responseFunc'), 'made last call');
        $this->response->invokeLastCall();
        $this->assertSame('made last call', $this->func_result);
    }

    public function testNoLastCall()
    {
        $this->response->invokeLastCall();
        $this->assertSame('func not called', $this->func_result);
    }

    public function responseFunc($string)
    {
        $this->func_result = $string;
    }

    public function testSendHeaders()
    {
        $this->response->fakeHeader('Foo: Bar');
        $this->response->sendHeaders();
        $this->assertSame('Foo: Bar', $this->response->fake_headers);
    }

    public function testSend()
    {
        // prep
        $this->response->setViewBase($this->view_base);
        $this->response->setView($this->view_file);
        $this->response->setVars(array('noun' => 'World'));
        $this->response->setLastCall(array($this, 'responseFunc'), 'made last call');
        $this->response->fakeHeader('Foo: Bar');

        // send
        ob_start();
        $this->response->send();
        $output = ob_get_clean();

        // test
        $this->assertSame('Hello World!', $output);
        $this->assertSame('Foo: Bar', $this->response->fake_headers);
        $this->assertSame('made last call', $this->func_result);
    }
}
