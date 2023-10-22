<?php declare(strict_types=1);

use Cryptee\{Cryptee, CrypteeException};

class CrypteeTest extends PHPUnit\Framework\TestCase
{
    const KEY = '4]%gmHo"e:]*hR(NQ?B...';

    function test_constructor() {
        new Cryptee(self::KEY); // OK.

        $this->expectException(CrypteeException::class);
        $this->expectExceptionMessageMatches('~Key length must be at least 6 chars and contain alp-num & printable chars~');
        new Cryptee('abc');
    }

    function test_getters() {
        $cryptee = new Cryptee(self::KEY);

        $this->assertSame(self::KEY, $cryptee->key());
        $this->assertSame(Cryptee::B64, $cryptee->type());
    }

    function test_encode() {
        $cryptee = new Cryptee(self::KEY);

        $this->assertSame('WJAv2/6x5Du/5IXjLTakW+jr', $cryptee->encode('Lorem ipsum dolor.'));
    }

    function test_decode() {
        $cryptee = new Cryptee(self::KEY);
        $content = 'Lorem ipsum dolor.';

        $this->assertSame('Lorem ipsum dolor.', $cryptee->decode('WJAv2/6x5Du/5IXjLTakW+jr'));
    }

    function test_generateKey() {
        $this->assertSame(Cryptee::KEY_LENGTH, strlen(Cryptee::generateKey()));
        $this->assertSame(64, strlen(Cryptee::generateKey(64)));
    }
}
