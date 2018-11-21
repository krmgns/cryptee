<?php
/**
 * Copyright (c) 2008 Kerem Güneş
 *
 * MIT License <https://opensource.org/licenses/mit>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Cryptee;

/**
 * @package Cryptee
 * @object  Cryptee\Cryptee
 * @author  Kerem Güneş <k-gun@mail.com>
 */
class Cryptee
{
    /**
     * Output types.
     * @const int
     */
    const B64 = 1, // Base64
          HEX = 2; // Hex

    /**
     * Key length.
     * @const int
     */
    const KEY_LENGTH = 128;

    /**
     * Key.
     * @var string
     */
    private $key;

    /**
     * Output type (base64 or hex, for readable strings)
     * @var int
     */
    private $type = self::B64;

    /**
     * Constructor.
     * @param string $key
     * @param int    $type
     */
    public function __construct($key = null, $type = null)
    {
        // check key
        if (!$key || strlen($key) < 6 || !(
            preg_match('~[a-z0-9]+~i', $key) && preg_match('~[_=&"\.\+\-\*\?\']+~', $key)
        )) {
            throw new CrypteeException(sprintf("
                Key length must be at least 6 chars and contain alp-num & printable chars!\n
                Pick up random key below generated for once.\n
                Key: %s", self::generateKey()
            ));
        }
        $this->key = strval($key);

        if ($type) {
            $this->type = $type;
        }
    }

    /**
     * Crypt.
     * @param  string $input
     * @return binary
     */
    public function crypt($input)
    {
        $ret = b'';
        $key = $cnt = array();
        for ($i = 0, $length = strlen($this->key); $i < 255; $i++) {
            $key[$i] = ord(substr($this->key, ($i % $length) + 1, 1));
            $cnt[$i] = $i;
        }

        for ($i = 0, $x = 0; $i < 255; $i++) {
            $x = ($x + $cnt[$i] + $key[$i]) % 256;
            $s = $cnt[$i];
            $cnt[$i] = $cnt[$x];
            $cnt[$x] = $s;
        }

        for ($i = 0, $x = -1, $y = -1, $length = strlen($input); $i < $length; $i++) {
            $x = ($x + 1) % 256;
            $y = ($y + $cnt[$x]) % 256;
            $z = $cnt[$x];
            $cnt[$x] = isset($cnt[$y]) ? $cnt[$y] : 1;
            $cnt[$y] = $z;
            $ord  = ord(substr($input, $i, 1)) ^ $cnt[($cnt[$x] + $cnt[$y]) % 256];
            $ret .= chr($ord);
        }

        return $ret;
    }

    /**
     * Encode.
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function encode($input, $translate = false)
    {
        $input = $this->crypt($input);
        if ($this->type == self::B64) {
            $input = base64_encode($input);
            if ($translate) {
                $input = rtrim(strtr($input, '+/', '-_'), '=');
            }
        } elseif ($this->type == self::HEX) {
            $input = bin2hex($input);
        }

        return $input;
    }

    /**
     * Decode.
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function decode($input, $translate = false)
    {
        if ($this->type == self::B64) {
            if ($translate) {
                $input = strtr($input, '-_', '+/');
            }
            $input = base64_decode($input);
            $input = $this->crypt($input);
        } elseif ($this->type == self::HEX) {
            $input = $this->crypt($this->hexToBin($input));
        }

        return $input;
    }

    /**
     * Hex to bin.
     * @param  string $hex
     * @return binary
     */
    public function hexToBin($hex)
    {
        $bin = b'';
        for ($i = 0, $length = strlen($hex); $i < $length; $i += 2) {
            $bin .= chr(hexdec(substr($hex, $i, 2)));
        }

        return $bin;
    }

    /**
     * Generate key.
     * @param  int $length
     * @return string
     */
    public static function generateKey($length = self::KEY_LENGTH)
    {
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= chr(rand(33, 126));
        }

        return $key;
    }
}
