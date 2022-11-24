For Cryptee/2.0, PHP/7.4 or newer version is required, but older Cryptee versions (1.0, 1.1, 1.2) work with older PHP versions as well.

You can install it via [Composer](//getcomposer.org) using the command below on console:

`composer require okerem/cryptee`

### Usage

```php
use Cryptee\Cryptee;

// Keep this key in save!
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

### Using Different Keys

```php
// Keep these keys in save!
const FOO_KEY = 'z:W;[*l>Eq.h"t)cs#XhU\+!=S]#q)\yG-...';
const BAR_KEY = 'SNz6@b*/k(iw!plOVeTBWxpL[1$;la|kb2...';

$cFoo = new Cryptee(FOO_KEY);
$cBar = new Cryptee(BAR_KEY);
```

### Generating Keys
You can generate new keys calling `Cryptee::generateKey()` method with/without `$length` argument as key length. Default length is 128.
