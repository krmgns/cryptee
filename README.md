## Usage

```bash
composer require k-gun/cryptee
```

```php
use Cryptee\Cryptee;

$key = '4]%gmHo"e:]*hR(NQ?B...';
$str = 'Lorem ipsum dolor.';

$c = new Cryptee($key);
// Or hex way.
// $c = new Cryptee($key, Cryptee::HEX);

$crypted = $c->crypt($str);
$encoded = $c->encode($str);
$decoded = $c->decode($encoded);

printf("Crypted String: %s\n", $crypted);
printf("Encoded String: %s\n", $encoded);
printf("Decoded String: %s\n", $decoded);

// Crypted String: X�/����;���-6�[��
// Encoded String: WJAv2/6x5Du/5IXjLTakW+jr
// Decoded String: Lorem ipsum dolor.
```

Use different keys for different fields.

```php
// Keep these chicks in save!
const FOO_KEY = 'z:W;[*l>Eq.h"t)cs#XhU\+!=S]#q)\yG-...';
const BAR_KEY = 'SNz6@b*/k(iw!plOVeTBWxpL[1$;la|kb2...';

$cFoo = new Cryptee(FOO_KEY);
$cBar = new Cryptee(BAR_KEY);
```

Tip: You can create new keys calling `Cryptee::generateKey()` method.
