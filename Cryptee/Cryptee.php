<?php
/**
 * Copyright (c) 2008-2015 Kerem Gunes
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
 * @since   v1.0
 * @author  Kerem Güneş <qeremy@gmail>
 */
final class Cryptee
{
    private
        // Set your pass key here or call first of all Cryptee::generateSalt()
        $_pass = '>~i:kN}\w=uH`#6}Qh4/H)S<q+J6kH]>b+BERj=kiQcDDv:No"6{*V=.]QasJO,P',
        // Output type (base64 or hex, for readable strings)
        $_type;

    const
        // Base64
        B64 = 1,
        // Hex
        HEX = 2;


    /**
     * Initialize Cryptee object, set pass and type.
     *
     * @param string  $pass
     * @param integer $type
     */
    public function __construct($pass = null, $type = self::B64) {
        // Check pass reliability if it's provided
        if ($pass != null) {
            if (strlen($pass) < 6 || !(
                    preg_match('~[a-z0-9]+~i', $pass) &&
                    preg_match('~[_=&"\.\+\-\*\?\']+~', $pass)
            )) {
                $error = sprintf(
                    '<code>Error: <b>%s</b> on line <b>%s</b><br>
                    -> <b>Cryptee::__construct()</b><br>
                    -> <b>Cryptee::$_pass</b><br>
                    Password length must be at least 6 chars and contain alp-num & printable chars!<br>
                    Pick up the random password below generated for once instead <b>%s</b>.<br>
                    Password: <b style="color:red">%s</b></code>', __FILE__, __LINE__, $pass, htmlentities(self::generateSalt())
                );
                die($error);
            }
            $this->_pass = strval($pass);
        }
        $this->_type = $type;
    }

    /**
     * Encrypt/Decrypt inputs.
     *
     * @param  string $data
     * @return binary
     */
    public function crypt($data) {
        if (empty($this->_pass)) {
            $error = sprintf(
                '<code>Error: <b>%s</b> on line <b>%s</b><br>
                -> <b>Cryptee::crypt()</b><br>
                -> <b>Cryptee::$_pass</b><br>
                Password not assigned!<br>
                Pick up the random password below generated for once.<br>
                Password: <b style="color:red">%s</b></code>', __FILE__, __LINE__, htmlentities(self::generateSalt())
            );
            die($error);
        }

        $pwdl = strlen($this->_pass);
        for ($i = 0; $i < 255; $i++) {
            $key[$i] = ord(substr($this->_pass, ($i % $pwdl) + 1, 1));
            $cnt[$i] = $i;
        }
        for ($i = 0; $i < 255; $i++) {
            $x = ($x + $cnt[$i] + $key[$i]) % 256;
            $s = $cnt[$i];
            $cnt[$i] = $cnt[$x];
            $cnt[$x] = $s;
        }
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $cnt[$a]) % 256;
            $t = $cnt[$a];
            $cnt[$a] = $cnt[$j];
            $cnt[$j] = $t;
            $ord  = ord(substr($data, $i, 1)) ^ $cnt[($cnt[$a] + $cnt[$j]) % 256];
            $chr .= chr($ord);
        }

        return $chr;
    }

    /**
     * Encode and convert inputs to readable string.
     *
     * @param  string $input
     * @return string
     */
    public function encode($input = '') {
        $input =@ $this->crypt($input);
        if ($this->_type == self::B64) {
            $input = base64_encode($input);
            $input = strtr($input, array('+' => '-', '/' => '_', '=' => ''));
        } elseif ($this->_type == self::HEX) {
            $input = bin2hex($input);
        }
        return $input;
    }

    /**
     * Decode and convert inputs to original string.
     *
     * @param  string $input
     * @return string
     */
    public function decode($input = '') {
        if ($this->_type == self::B64) {
            $input = strtr($input, array('-' => '+', '_' => '/'));
            $input = base64_decode($input);
            $input =@ $this->crypt($input);
        } elseif ($this->_type == self::HEX) {
            $input = $this->hexbin($input);
            $input =@ $this->crypt($input);
        }
        return $input;
    }

    /**
     * Convert hex inputs to bin.
     *
     * @param  string $hex
     * @return string
     */
    public function hexbin($hex) {
        for ($i = 0, $len = strlen($hex); $i < $len; $i += 2) {
            $bin .= chr(hexdec(substr($hex, $i, 2)));
        }
        return $bin;
    }

    /**
     * Generate a salt string.
     *
     * @param  integer $len
     * @return string
     */
    public static function generateSalt($len = 64) {
        $salt = '';
        for ($i = 0; $i < $len; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }
}
