<?php

namespace SaferBlitz;

class View extends \Blitz
{
    /**
     * @param array $parameters
     * @return bool
     */
    public function set($parameters)
    {
        return parent::set($this->wrap($parameters));
    }

    /**
     * @param array $parameters
     * @return bool
     */
    public function setGlobal($parameters)
    {
        return parent::setGlobal($this->wrap($parameters));
    }

    /**
     * @param array $parameters
     * @return bool
     */
    public function setGlobals($parameters)
    {
        return $this->setGlobal($parameters);
    }

    /**
     * @param string $p1
     * @param null $p2
     * @param null $nonexistent
     * @return bool
     */
    public function block($p1, $p2 = null, $nonexistent = null)
    {
        return parent::block($p1, $p2 ? $this->wrap($p2) : null, $nonexistent);
    }

    /**
     * @param $filename
     * @param array $vars
     * @return mixed
     */
    public function include($filename, $vars = [])
    {
        return parent::include($filename, $this->wrap($vars));
    }

    /**
     * @param $path
     * @param array $vars
     * @return mixed
     */
    public function fetch($path, $vars = [])
    {
        return parent::fetch($path, $this->wrap($vars));
    }

    /**
     * @param array $vars
     * @return mixed
     */
    public function display($vars = [])
    {
        return parent::display($this->wrap($vars));
    }

    /**
     * @param array $vars
     * @return string
     */
    public function parse($vars = [])
    {
        return parent::parse($this->wrap($vars));
    }

    /**
     * To use in template: {{ raw($var) }}
     *
     * @param string | StringContainer $arg
     * @return string
     */
    public function raw($arg)
    {
        if ($arg instanceof StringContainer) {
            return $arg->getRaw();
        } else {
            return $arg;
        }
    }

    /**
     * Wrap string variables from $parameters in StringContainer, so that the output is escaped.
     * @param array $parameters
     * @return array
     */
    private function wrap($parameters)
    {
        $wrapped = [];
        foreach ($parameters as $key => $parameter) {
            if (is_string($parameter)) {
                $wrapped[$key] = new StringContainer($parameter);
            } elseif (is_array($parameter)) {
                $wrapped[$key] = $this->wrap($parameter);
            } else {
                $wrapped[$key] = $parameter;
            }
        }
        return $wrapped;
    }
}