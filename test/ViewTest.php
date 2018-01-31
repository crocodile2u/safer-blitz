<?php

namespace SaferBlitz\Test;

use PHPUnit\Framework\TestCase;
use SaferBlitz\View;

class ViewTest extends TestCase
{
    function testExtend()
    {
        $child = new View();

        $childBody = <<<BLITZ
{{must_be_escaped_once}}
BLITZ;
        $parentBody = <<<BLITZ
header
{{raw(content)}}
footer
BLITZ;
        $unescaped = "&";
        $child->load($childBody);
        $child->set(["must_be_escaped_once" => $unescaped]);

        $parent = new View();
        $parent->load($parentBody);

        $child->extend($parent);

        $result = $child->parse();
        $this->assertEquals("header\n&amp;\nfooter", $result);
    }
    function testAutoescaping()
    {
        $view = new View();

        $body = <<<BLITZ
{{\$must_be_escaped}}
BLITZ;
        $unescaped = "&";
        $view->load($body);
        $view->set(["must_be_escaped" => $unescaped]);
        $result = $view->parse();
        $this->assertEquals("&amp;", $result);
    }

    function testRaw()
    {
        $view = new View();

        $body = <<<BLITZ
{{ raw(\$must_not_be_escaped) }}
BLITZ;
        $unescaped = "&";
        $view->load($body);
        $view->set(["must_not_be_escaped" => $unescaped]);
        $result = $view->parse();
        $this->assertEquals($unescaped, $result);
    }

    function testIncludeFromTemplate()
    {
        $view = new View();

        $body = <<<BLITZ
{{ include("inc.tpl") }}
BLITZ;
        $unescaped = "&";
        $view->load($body);
        $view->set(["must_be_escaped" => $unescaped]);
        $result = $view->parse();
        $this->assertEquals("&amp;", $result);
    }

    function testIncludeFromCode()
    {
        $view = new View();
        $unescaped = "&";
        $result = $view->include("inc.tpl", ["must_be_escaped" => $unescaped]);
        $this->assertEquals("&amp;", $result);
    }
}