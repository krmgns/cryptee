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
 * @object  Cryptee\CrypteeException
 * @extends \Exception
 * @since   1.0
 * @author  Kerem Güneş <qeremy@gmail>
*/
class CrypteeException
    extends \Exception
{
    /**
     * Object constructor.
     */
    public function __construct() {
        $args = func_get_args();
        $mesg = array_shift($args);

        // for 'Error on %d line', $line
        if (count($args)) {
            $mesg = vsprintf($mesg, $args);
        }

        parent::__construct($mesg);
    }
}
