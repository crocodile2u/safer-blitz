# safer-blitz
A small extension to [Blitz template engine](http://alexeyrybak.com/blitz/blitz_en.html), with auto-escaping support.

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