<?php
/**
 * This view is part of "Modernizing Legacy Applications in PHP".
 *
 * @copyright 2014 Paul M. Jones <pmjones88@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Mlaphp;

/**
 * Encapsulates a plain old PHP response.
 */
class Response
{
    /**
     * A buffer for HTTP-related output (headers and cookies).
     *
     * @var array
     */
    protected $http = array();

    /**
     * Variables to extract into the view scope.
     *
     * @var array
     */
    protected $vars = array();

    /**
     * A view file to require in its own scope for rendering.
     *
     * @var string
     */
    protected $view;

    /**
     * Allows read-only access to protected properties.
     *
     * @param string $property The property name.
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Renders the view in its own scope and returns the buffered output.
     *
     * @return string
     */
    public function __invoke()
    {
        extract($this->vars);
        ob_start();
        require $this->view;
        return ob_get_clean();
    }

    /**
     * Sets the path to the view file to be rendered.
     *
     * @param string $view The path to the view to be rendered.
     * @return null
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Sets the variables to be extracted into the view scope.
     *
     * @param array $vars The variables to be extracted into the view scope.
     * @return null
     */
    public function setVars(array $vars)
    {
        unset($vars['this']);
        $this->vars = $vars;
    }

    /**
     * Buffers a call to `header()`.
     *
     * @return null
     */
    public function header()
    {
        $args = func_get_args();
        array_unshift('header', $args);
        $this->http[] = $args;
    }

    /**
     * Buffers a call to `setcookie()`.
     *
     * @return bool
     */
    public function setcookie()
    {
        $args = func_get_args();
        array_unshift('setcookie', $args);
        $this->http[] = $args;
        return true;
    }

    /**
     * Buffers a call to `setrawcookie()`.
     *
     * @return bool
     */
    public function setrawcookie()
    {
        $args = func_get_args();
        array_unshift('setrawcookie', $args);
        $this->http[] = $args;
        return true;
    }

    /**
     * Outputs the headers and content of the rendered view.
     *
     * @return null
     */
    public function send()
    {
        // render first, in case view script calls $this->header() etc
        $content = $this->__invoke();

        // output headers
        foreach ($this->http as $args) {
            $func = array_shift($args);
            call_user_func_array($func, $args);
        }

        // output content
        return $content;
    }
}
