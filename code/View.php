<?php
/**
 * This file is part of "Modernizing Legacy Applications in PHP".
 *
 * @copyright 2014 Paul M. Jones <pmjones88@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Mlaphp;

/**
 * Renders a view in its own scope.
 *
 * @see http://martinfowler.com/eaaCatalog/templateView.html
 * @see http://martinfowler.com/eaaCatalog/transformView.html
 */
class View
{
    /**
     * A file to require in its own scope for rendering.
     *
     * @var string
     */
    protected $file;

    /**
     * Variables to extract into the view scope.
     *
     * @var array
     */
    protected $vars = array();

    /**
     * Renders the view in its own scope and returns the buffered output.
     *
     * @return string
     */
    public function __toString()
    {
        extract($this->vars);
        ob_start();
        require $this->file;
        return ob_get_clean();
    }

    /**
     * Sets the path to the file to be rendered.
     *
     * @param string $file The path to the file to be rendered.
     * @return null
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Returns the path to the file to be rendered.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
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
     * Returns the variables to be extracted into the view scope.
     *
     * @return array
     */
    public function getVars(array $vars)
    {
        return $this->vars;
    }
}
