<?php

namespace SaferBlitz;

class View extends \Blitz
{
    /**
     * @var \Blitz|View
     */
    private $parent;
    /**
     * @var string
     */
    private $placeholder;

    const DEFAULT_PLACEHOLDER = "content";
    /**
     * Allow for extending another template.
     * If this template has a parent,
     * then, during the parse process, any global variables will be passed through to the parent.
     * Also, the top level placeholder named $placeholder will be substituted with the child template parse result,
     * the the parent parse result is actually returned.
     * It is the responsibility of developer to apply raw() to the child content if necessary.
     *
     * Placeholder name defaults to "content".
     *
     * Example.
     *
     * article.tpl:
     * ----------------------------------------
     * <article>text</article>
     * ----------------------------------------
     *
     * layout.tpl:
     * ----------------------------------------
     * <header/>
     * {{ raw(content) }}
     * <footer/>
     * ----------------------------------------
     *
     * PHP code:
     * ----------------------------------------
     * $view = new View("article.tpl");
     * $view->extend("layout.tpl");
     * echo $view->parse();
     * ----------------------------------------
     *
     * The output:
     * ----------------------------------------
     * <header/>
     * <article>text</article>
     * <footer/>
     * ----------------------------------------
     *
     * @param string|\Blitz $template template filename or a Blitz instance
     */
    public function extend($template, $placeholder = self::DEFAULT_PLACEHOLDER) {
        if ($template instanceof \Blitz) {
            $this->parent = $template;
        } else {
            $this->parent = new View($template);
        }
        $this->placeholder = $placeholder;
    }

    /**
     * @return \Blitz|View|null
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * @param array|null $iterations
     * @return mixed
     */
    function parse($iterations = null) {
        $output = parent::parse($this->wrap($iterations));
        if ($this->parent) {
            $this->parent->setGlobals($this->getGlobals());
            return $this->parent->parse([
                $this->placeholder => $output
            ]);
        } else {
            return $output;
        }
    }

    /**
     * @param array|null $iterations
     * @return bool
     */
    function display($iterations = null) {
        echo $this->parse($iterations);
        return true;
    }

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
        if (empty($parameters)) {
            return [];
        }
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