<?php
/**
 * Copyright (c) 2008-2015 Kerem Güneş
 *    <http://qeremy.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Cryptee;

/**
 * @package Cryptee
 * @object  Cryptee\Cryptee
 * @version v1.0
 * @author  Kerem Güneş <qeremy@gmail>
 */
final class Cryptee
{
    /**
     * Output types.
     * @const int, int
     */
    const B64 = 1, // Base64
          HEX = 2; // Hex

    /**
     * Salt length.
     * @const int
     */
    const SALT_LENGTH = 128;

    /**
     * Cryptee pass key.
     * @note Set your pass key here or call first Cryptee::generatesalt() to get new one.
     * @var  string
     */
    private $pass = '>~i:kN}\w=uH`#6}Qh4/H)S<q+J6kH]>b+BERj=kiQcDDv:No"6{*V=.]QasJO,P';

    /**
     * Output type (base64 or hex, for readable strings)
     * @var int
     */
    private $type = self::B64;

    /**
     * Object constructor.
     *
     * @param string  $pass
     * @param int     $type
     */
    public function __construct($pass = null, $type = null) {
        // check pass reliability if it's provided
        if ($pass !== null) {
            if (strlen($pass) < 6 || !(
                    preg_match('~[a-z0-9]+~i', $pass) &&
                    preg_match('~[_=&"\.\+\-\*\?\']+~', $pass)
            )) { throw new CrypteeException(
                    '<code>Error: <b>%s</b> on line <b>%s</b><br>
                    -> <b>Cryptee::__construct()</b><br>
                    -> <b>Cryptee::$pass</b><br>
                    Password length must be at least 6 chars and contain alp-num & printable chars!<br>
                    Pick up the random password below generated for once instead <b>%s</b>.<br>
                    Password: <b style="color:red">%s</b></code>', __file__, __line__, $pass, htmlentities(self::generateSalt())
                );
            }
            $this->pass = strval($pass);
        }

        // set type
        if ($type !== null) {
            $this->type = $type;
        }
    }

    /**
     * Encrypt/Decrypt inputs.
     *
     * @param  string $input
     * @return binary
     */
    public function crypt($input) {
        $bin = b'';
        $key = $cnt = [];
        for ($i = 0, $len = strlen($this->pass); $i < 255; $i++) {
            $key[$i] = ord(substr($this->pass, ($i % $len) + 1, 1));
            $cnt[$i] = $i;
        }

        for ($i = 0, $x = 0; $i < 255; $i++) {
            $x = ($x + $cnt[$i] + $key[$i]) % 256;
            $s = $cnt[$i];
            $cnt[$i] = $cnt[$x];
            $cnt[$x] = $s;
        }

        for ($i = 0, $x = -1, $y = -1, $len = strlen($input); $i < $len; $i++) {
            $x = ($x + 1) % 256;
            $y = ($y + $cnt[$x]) % 256;
            $z = $cnt[$x];
            $cnt[$x] = isset($cnt[$y]) ? $cnt[$y] : 1;
            $cnt[$y] = $z;
            $ord  = ord(substr($input, $i, 1)) ^ $cnt[($cnt[$x] + $cnt[$y]) % 256];
            $bin .= chr($ord);
        }

        return $bin;
    }

    /**
     * Encode and convert inputs to readable string.
     *
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function encode($input, $translate = false) {
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
     * Decode and convert inputs to original string.
     *
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function decode($input, $translate = false) {
        if ($this->type == self::B64) {
            if ($translate) {
                $input = strtr($input, '-_', '+/');
            }
            $input = base64_decode($input);
            $input = $this->crypt($input);
        } elseif ($this->type == self::HEX) {
            $input = $this->crypt($this->hexbin($input));
        }

        return $input;
    }

    /**
     * Convert hex inputs to bin.
     *
     * @param  string $hex
     * @return binary
     */
    public function hexbin($hex) {
        $bin = b'';
        for ($i = 0, $len = strlen($hex); $i < $len; $i += 2) {
            $bin .= chr(hexdec(substr($hex, $i, 2)));
        }

        return $bin;
    }

    /**
     * Generate a salt string.
     *
     * @param  int $len
     * @return string
     */
    public static function generateSalt($len = self::SALT_LENGTH) {
        $salt = '';
        for ($i = 0; $i < $len; $i++) {
            $salt .= chr(rand(33, 126));
        }

        return $salt;
    }
}
