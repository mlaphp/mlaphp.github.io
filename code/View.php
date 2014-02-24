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
     * Variables to extract into the rendering scope.
     *
     * @var array
     */
    protected $vars = array();

    /**
     * Sets the view file to be rendered in its own scope.
     *
     * @param string $file The file to be rendered in its own scope.
     * @return null
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Sets the variables to use when rendering the file.
     *
     * @param array $vars Variables to extract into the rendering scope.
     * @return null
     */
    public function setVars(array $vars)
    {
        unset($vars['this']);
        $this->vars = $vars;
    }

    /**
     * Renders the view in its own scope and returns the buffered output.
     *
     * @return string
     */
    public function render()
    {
        extract($this->vars);
        ob_start();
        require $this->file;
        return ob_get_clean();
    }
}
