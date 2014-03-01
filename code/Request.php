<?php
/**
 * This file is part of "Modernizing Legacy Applications in PHP".
 *
 * @copyright 2014 Paul M. Jones <pmjones88@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Mlaphp;

/**
 * A data structure object to encapsulate superglobal references. Changes to
 * the property will be reflected in the superglobal, and vice versa.
 */
class Request
{
    /**
     * A reference to $GLOBALS.
     *
     * @var array
     */
    protected $globals;

    /**
     * A map of magic properties to their superglobal reference.
     *
     * @var array
     */
    protected $properties = array(
        'cookie' => '_COOKIE',
        'env' => '_ENV',
        'files' => '_FILES',
        'get' => '_GET',
        'post' => '_POST',
        'request' => '_REQUEST',
        'server' => '_SERVER',
        'session' => '_SESSION',
    );

    /**
     * Constructor.
     * 
     * @param array $globals A reference to $GLOBALS.
     */
    public function __construct(&$globals)
    {
        $this->globals = &$globals;
    }

    /**
     * Returns a reference to the superglobal mapped by the magic property name.
     *
     * @param string $property The property name.
     * @return array A reference to the mapped superglobal.
     */
    public function &__get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->globals[$this->properties[$name]];
        }
    }
}
