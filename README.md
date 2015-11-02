##Usage##

```php
// composer
{"require": {"qeremy/cryptee": "dev-master"}}

// manual inc
require('path/to/Cryptee/Cryptee.php');

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

Use different pass keys for different purposes.

```php
// keep in safe these chicks!!!
define('FOO_KEY', 'z:W;[*l>Eq.h"t)cs#XhU\+!=S]#q)\yG-"?c"F;zVZFq./i_W"}"6^/=x$q)$');
define('BAR_KEY', 'SNz6@b*/k(iw!plOVeTBWxpL[1$;la|kb2}KHsx7TO/Z28NTxr:QqTCNV$*v1S');

$cFoo = new Cryptee\Cryptee(FOO_KEY);
$cBar = new Cryptee\Cryptee(BAR_KEY);
```

##License##

<pre>
Copyright (c) 2008-2015 Kerem Güneş
   &lt;http://qeremy.com>

GNU General Public License v3.0
   &lt;http://www.gnu.org/licenses/gpl-3.0.txt>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see &lt;http://www.gnu.org/licenses/>.
</pre>
