# safer-blitz
A small extension to [Blitz template engine](https://github.com/alexeyrybak/blitz), 
adding template inheritance and auto-escaping.

## Template inheritance

**article.tpl**:

```html
<article>text</article>
```

**layout.tpl**:

```html
<header/>
{{ raw(content) }}
<footer/>
```

**PHP code**:

```php
$view = new View("article.tpl");
$view->extend("layout.tpl");
echo $view->parse();
```

**The output**:

```html
<header/>
<article>text</article>
<footer/>
```

## Auto-escaping

Initialize view:

```php
$view = new \SaferBlitz\View;
```

In template:

```
{{ $some_variable }}
```

In controller:

```php
$view->set(["some_variable" => "some nasty XSS attempt: \"><script>alert(\"XSS\");</script>"]);
$view->display();
```

Result:

```
some nasty XSS attempt: &quot;&gt;&lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;
```

To output variable unescaped, use _raw($var)_ template API:

```
{{ raw($trusted_variable) }}
```

If anyone appears to be interested in this project, I will probably add proper escape methods to escape attributes, CSS, JS. For now, this is out of my personal scope of use though.