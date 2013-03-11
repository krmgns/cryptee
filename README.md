**USAGE**

```php
error_reporting(E_ALL & ~E_NOTICE);

require 'Cryptee.php';

// $str = file_get_contents('Cryptee.php');
$str = 'Lorem ipsum dolor.';

$c = new Cryptee();
// Or hex way
// $c = new Cryptee(null, Cryptee::HEX);
$crypted = $c->crypt($str);
$encoded = $c->encode($str);
$decoded = $c->decode($encoded);
printf('<b>Crypted String</b>: %s<br>', $crypted);
printf('<b>Encoded String</b>: %s<br>', $encoded);
printf('<b>Decoded String</b>: %s', htmlspecialchars($decoded, ENT_NOQUOTES, 'UTF-8'));
// Crypted String: W˜kžß¨ÍJKV]%'„Ë
// Encoded String: V5hrnt-ozUoDS1ZdJScEhMsH
// Decoded String: Lorem ipsum dolor.
```

Use different pass keys for different purposes.

```php
// Keep in safe these chicks!!!
define('COOKIE_PASS', 'z:W;[*l>Eq.h"t)cs#XhU\+!=S]#q)\yG-"?c"F;zVZFq./i_W"}"6^/=x$q)$');
define('FOOBAR_PASS', 'SNz6@b*/k(iw!plOVeTBWxpL[1$;la|kb2}KHsx7TO/Z28NTxr:QqTCNV$*v1S');

$c = new Cryptee(COOKIE_PASS);
$encodedCookie = $c->encode('username=qeremy&userpass=gotcha..' /* Really! */);
$decodedCookie = $c->decode($encodedCookie);
printf('<b>Encoded</b>: %s, <b>Decoded</b>: %s<br>', $encodedCookie, $decodedCookie);

$c = new Cryptee(FOOBAR_PASS);
$encodedFoobar = $c->encode('Foobar stuff...');
$decodedFoobar = $c->decode($encodedFoobar);
printf('<b>Encoded</b>: %s, <b>Decoded</b>: %s<br>', $encodedFoobar, $decodedFoobar);
```