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
     * A file to require in its own separate scope.
     *
     * @var string
     */
    protected $file;

    /**
     * Variables to extract into the separate scope.
     *
     * @var array
     */
    protected $vars = array();

    /**
     * Constructor.
     *
     * @param string A file to require in its own separate scope.
     * @param array Variables to extract into the separate scope.
     */

    public function __construct($file, array $vars = array())
    {
        $this->file = $file;
        $this->vars = $vars;
        unset($this->vars['this']);
    }

    /**
     * Renders and returns the view.
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
