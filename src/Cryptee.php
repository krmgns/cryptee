<?php
/**
 * Copyright (c) 2008 · Kerem Güneş
 * Apache License 2.0 · https://github.com/okerem/cryptee
 */
declare(strict_types=1);

namespace Cryptee;

/**
 * @package Cryptee
 * @class   Cryptee\Cryptee
 * @author  Kerem Güneş
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
    private string $key;

    /**
     * Output type (base64 or hex, for readable strings)
     * @var int
     */
    private int $type = self::B64;

    /**
     * Constructor.
     *
     * @param string   $key
     * @param int|null $type
     */
    public function __construct(string $key, int $type = null)
    {
        // Check key validity.
        if (strlen($key) < 6 || !(
            preg_match('~[a-z0-9]+~i', $key) &&
            preg_match('~[_=&"\.\+\-\*\?\']+~', $key)
        )) {
            throw new CrypteeException(sprintf(
                "Key length must be at least 6 chars and contain alp-num & printable chars!\n".
                "Pick up random this key generated for once: '%s'\n",
                str_replace("'", "\'", self::generateKey())
            ));
        }

        $this->key = $key;

        if ($type) {
            $this->type = $type;
        }
    }

    /**
     * Get key.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function type(): int
    {
        return $this->type;
    }

    /**
     * Crypt.
     *
     * @param  string $input
     * @return string
     */
    public function crypt(string $input): string
    {
        static $top = 256;
        $key = $cnt = [];

        for ($i = 0, $il = strlen($this->key); $i < $top; $i++) {
            $key[$i] = ord(substr($this->key, ($i % $il) + 1, 1));
            $cnt[$i] = $i;
        }

        for ($i = 0, $a = 0; $i < $top; $i++) {
            $a = ($a + $cnt[$i] + $key[$i]) % $top;
            $t = $cnt[$i];

            $cnt[$i] = $cnt[$a] ?? 0;
            $cnt[$a] = $t;
        }

        $ret = b'';

        for ($i = 0, $a = -1, $b = -1, $il = strlen($input); $i < $il; $i++) {
            $a = ($a + 1) % $top;
            $b = ($b + $cnt[$a]) % $top;
            $t = $cnt[$a];

            $cnt[$a] = $cnt[$b] ?? 0;
            $cnt[$b] = $t;

            $ret .= chr(ord(substr($input, $i, 1)) ^ $cnt[($cnt[$a] + $cnt[$b]) % $top]);
        }

        return $ret;
    }

    /**
     * Encode.
     *
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function encode(string $input, bool $translate = false): string
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
     *
     * @param  string $input
     * @param  bool   $translate
     * @return string
     */
    public function decode(string $input, bool $translate = false): string
    {
        if ($this->type == self::B64) {
            if ($translate) {
                $input = strtr($input, '-_', '+/');
            }
            $input = base64_decode($input);
            $input = $this->crypt($input);
        } elseif ($this->type == self::HEX) {
            $input = $this->crypt(hex2bin($input));
        }

        return $input;
    }

    /**
     * Generate key.
     *
     * @param  int $length
     * @return string
     */
    public static function generateKey(int $length = self::KEY_LENGTH): string
    {
        $key = '';

        srand();
        for ($i = 0; $i < $length; $i++) {
            $key .= chr(rand(33, 126));
        }

        return $key;
    }
}
