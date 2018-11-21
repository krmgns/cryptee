## Usage

```php
// composer
{"require": {"k-gun/cryptee": "dev-master"}}

// manual inc
require('path/to/cryptee/src/Cryptee.php');

$str = 'Lorem ipsum dolor.';

$c = new Cryptee\Cryptee();
// or hex way
// $c = new Cryptee\Cryptee(null, Cryptee::HEX);
$crypted = $c->crypt($str);
$encoded = $c->encode($str);
$decoded = $c->decode($encoded);
printf('Crypted String: %s', $crypted);
printf('Encoded String: %s', $encoded);
printf('Decoded String: %s', htmlspecialchars($decoded, ENT_NOQUOTES, 'UTF-8'));
// Crypted String: W˜kžß¨ÍJKV]%'„Ë
// Encoded String: V5hrnt-ozUoDS1ZdJScEhMsH
// Decoded String: Lorem ipsum dolor.
```

Use different keys for different purposes.

```php
// keep in safe these chicks!!!
define('FOO_KEY', 'z:W;[*l>Eq.h"t)cs#XhU\+!=S]#q)\yG-"?c"F;zVZFq./i_W"}"6^/=x$q)$');
define('BAR_KEY', 'SNz6@b*/k(iw!plOVeTBWxpL[1$;la|kb2}KHsx7TO/Z28NTxr:QqTCNV$*v1S');

$cFoo = new Cryptee\Cryptee(FOO_KEY);
$cBar = new Cryptee\Cryptee(BAR_KEY);
```

Tip: You can create new keys calling `Cryptee::generateKey()` method.
