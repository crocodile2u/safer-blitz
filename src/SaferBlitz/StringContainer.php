<?php

namespace SaferBlitz;
/**
 * Class SafeString
 * @package blitz
 */
class StringContainer
{
    /**
     * @var string
     */
    private $raw;

    /**
     * StringContainer constructor.
     * @param string $raw
     */
    function __construct(string $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return string
     */
    function escape()
    {
        return htmlspecialchars($this->raw, ENT_QUOTES);
    }

    /**
     * @return string
     */
    function getRaw()
    {
        return $this->raw;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->escape();
    }
}