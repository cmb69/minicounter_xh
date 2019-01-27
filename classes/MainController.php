<?php

/**
 * Copyright 2012-2019 Christoph M. Becker
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

namespace Minicounter;

class MainController
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $conf;

    /**
     * @return void
     */
    public function __construct()
    {
        global $plugin_cf;

        $this->model = new Model;
        $this->conf = $plugin_cf['minicounter'];
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $bjs;

        if ($this->conf['honor_dnt'] && isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT']) {
            return;
        }
        if (!$this->isRequestIgnorable()) {
            if (!isset($_COOKIE['minicounter'])) {
                setcookie('minicounter', -($this->model->count() + 1));
            } elseif ($_COOKIE['minicounter'] < 0) {
                $this->model->increaseCount();
                setcookie('minicounter', -$_COOKIE['minicounter']);
            }
            if (!isset($_COOKIE['minicounter']) || $_COOKIE['minicounter'] < 0) {
                $bjs .= '<img src="?&amp;minicounter_image" width="1" height="1" style="position: absolute">';
            }
        }
    }

    /**
     * @return bool
     */
    private function isRequestIgnorable()
    {
        global $f, $logout;

        return XH_ADM || $f == 'login' || $logout
            || $this->model->ignoreIp($_SERVER['REMOTE_ADDR']);
    }
}
