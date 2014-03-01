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
     * A reference to $_COOKIE.
     * 
     * @var array
     */
    protected $cookie = array();

    /**
     * A reference to $_ENV.
     * 
     * @var array
     */
    protected $env = array();

    /**
     * A reference to $_FILES.
     * 
     * @var array
     */
    protected $files = array();

    /**
     * A reference to $_GET.
     * 
     * @var array
     */
    protected $get = array();

    /**
     * A reference to $_POST.
     * 
     * @var array
     */
    protected $post = array();

    /**
     * A reference to $_REQUEST.
     * 
     * @var array
     */
    protected $request = array();

    /**
     * A reference to $_SERVER.
     * 
     * @var array
     */
    protected $server = array();

    /**
     * Constructor.
     * 
     * @param array $globals A reference to $GLOBALS.
     */
    public function __construct(&$globals)
    {
        $super_property = array(
            '_COOKIE'  => 'cookie',
            '_ENV'     => 'env',
            '_FILES'   => 'files',
            '_GET'     => 'get',
            '_POST'    => 'post',
            '_REQUEST' => 'request',
            '_SERVER'  => 'server',
        );

        foreach ($super_property as $super => $property) {
            if (isset($globals[$super])) {
                $this->$property =& $globals[$super];
            }
        }
    }

    /**
     * Returns a reference to the property.
     *
     * @param string $property The property name.
     * @return array A reference to the property.
     */
    public function &__get($property)
    {
        return $this->$property;
    }
}
