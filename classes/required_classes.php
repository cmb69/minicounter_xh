<?php

/**
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of Minicounter_XH.
 *
 * Minicounter_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Minicounter_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Minicounter_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

spl_autoload_register(
    function ($class) {
        $parts = explode('_', $class, 2);
        if ($parts[0] === 'Minicounter') {
            include_once __DIR__ . "/{$parts[1]}.php";
        }
    }
);
