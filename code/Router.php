<?php
/**
 * This file is part of "Modernizing Legacy Applications in PHP".
 *
 * @copyright 2014 Paul M. Jones <pmjones88@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Mlaphp;

use RuntimeException;

/**
 * A basic router implementation that converts URL paths to file paths or class
 * names.
 */
class Router
{
    /**
     * The path to the front controller in the document root.
     *
     * @var string
     */
    protected $front = '/front.php';

    /**
     * The path to the "home" page script in the pages directory.
     *
     * @var string
     */
    protected $home = '/index.php';

    /**
     * The path to the "page not found" page script in the pages directory.
     *
     * @var string
     */
    protected $not_found = '/not-found.php';

    /**
     * The path to the pages directory.
     *
     * @var string
     */
    protected $pages_dir;

    /**
     * The map of URL paths (keys) to file paths or class names (values).
     *
     * @var array
     */
    protected $routes = array();

    /**
     * Constructor.
     *
     * @param string $pages_dir The path to the pages directory.
     */
    public function __construct($pages_dir = null)
    {
        if ($pages_dir) {
            $this->pages_dir = rtrim($pages_dir, '/');
        }
    }

    /**
     * Sets the path to the front controller in the document root.
     *
     * @param string $front The path to the front controller in the document
     * root.
     * @return null
     */
    public function setFront($front)
    {
        $this->front = '/' . ltrim($front, '/');
    }

    /**
     * Sets the path to the "home" page script in the pages directory.
     *
     * @param string $home The path to the "home" page script in the pages
     * directory.
     * @return null
     */
    public function setHome($home)
    {
        $this->home = '/' . ltrim($home, '/');
    }

    /**
     * Sets the path to the "page not found" page script in the pages directory.
     *
     * @param string $not_found The path to the "page not found" page script in
     * the pages directory.
     * @return null
     */
    public function setNotFound($not_found)
    {
        $this->not_found = '/' . ltrim($not_found, '/');
    }

    /**
     * Sets the map of URL paths (keys) to file paths or class names (values).
     *
     * @param array $routes The map of URL paths (keys) to file paths or class
     * names (values).
     * @return null
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Given a URL path, returns a matching route value (either a file name or
     * a class name).
     *
     * @param string $path The URL path to be routed.
     * @return string A file path or a class name.
     */
    public function match($path)
    {
        $path = $this->fixPath($path);

        if (isset($this->routes[$path])) {
            $route = $this->routes[$path];
        } else {
            $route = $path;
        }

        return $this->fixRoute($route);
    }

    /**
     * Fixes the incoming URL path to strip the front controller script
     * name, and replace empty or root URL paths with the home page.
     *
     * @param string $path The incoming URL path.
     * @return string The normalized path.
     */
    protected function fixPath($path)
    {
        $len = strlen($this->front);

        if (substr($path, 0, $len) == $this->front) {
            $path = substr($path, $len);
        }

        if ($path == '' || $path == '/') {
            $path = $this->home;
        }

        return $path;
    }

    /**
     * Fixes a route specification: if it is a file name, finds the real path
     * and checks to see if it actually exists; otherwise, leaves it alone.
     *
     * @param string $route The matched route.
     * @return string The "fixed" route.
     * @throws RuntimeException when the route is a file but no pages directory
     * is specified.
     */
    protected function fixRoute($route)
    {
        if (! $this->isFile($route)) {
            return $route;
        }

        if (! $this->pages_dir) {
            throw new RuntimeException('No pages directory specified.');
        }

        $page = realpath($this->pages_dir . $route);
        if ($this->pageExists($page)) {
            return $page;
        } else {
            return $this->pages_dir . $this->not_found;
        }
    }

    /**
     * Is the matched route a file name?
     *
     * @param string $route The matched route.
     * @return bool
     */
    protected function isFile($route)
    {
        return substr($route, 0, 1) == '/';
    }

    /**
     * Does the pages directory have a matching readable file?
     *
     * @param string $file The file to check.
     * @return bool
     */
    protected function pageExists($file)
    {
        return $file != ''
            && substr($file, 0, strlen($this->pages_dir)) == $this->pages_dir
            && file_exists($file)
            && is_readable($file);
    }
}
