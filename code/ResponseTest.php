<?php
namespace Mlaphp;

require __DIR__ . '/Response.php';
require __DIR__ . '/FakeResponse.php';

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $func_result = 'func not called';

    protected $view_file;

    public function setUp()
    {
        $this->view_file = __DIR__ . DIRECTORY_SEPARATOR . 'response_view.php';
        $this->response = new FakeResponse;
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

    public function testSetAndGetFunc()
    {
        $func = array($this, 'responseFunc');
        $this->response->setFunc($func);
        $this->assertSame($func, $this->response->getFunc());
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

    public function testRequireView()
    {
        $this->response->setVars(array('noun' => 'World'));
        $this->response->setView($this->view_file);
        $output = $this->response->requireView();
        $this->assertSame('Hello World!', $output);
    }

    public function testNoViewToRequire()
    {
        $output = $this->response->requireView();
        $this->assertSame('', $output);
    }

    public function testCallFunc()
    {
        $this->response->setFunc(array($this, 'responseFunc'));
        $this->response->callFunc();
        $this->assertSame('called func', $this->func_result);
    }

    public function testNoFuncToCall()
    {
        $this->response->callFunc();
        $this->assertSame('func not called', $this->func_result);
    }

    public function responseFunc()
    {
        $this->func_result = 'called func';
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
        $this->response->setView($this->view_file);
        $this->response->setVars(array('noun' => 'World'));
        $this->response->setFunc(array($this, 'responseFunc'));
        $this->response->fakeHeader('Foo: Bar');

        // send
        ob_start();
        $this->response->send();
        $output = ob_get_clean();

        // test
        $this->assertSame('Hello World!', $output);
        $this->assertSame('Foo: Bar', $this->response->fake_headers);
        $this->assertSame('called func', $this->func_result);
    }
}
