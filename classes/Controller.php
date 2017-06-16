<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
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

class Controller
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var Views
     */
    private $views;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->model = new Model();
        $this->views = new Views($this->model);
        $this->dispatch();
    }

    /**
     * @return void
     */
    protected function dispatch()
    {
        global $adm, $minicounter;

        $this->count();
        if ($adm && isset($minicounter) && $minicounter == 'true') {
            $this->administrate();
        }
        if (isset($_GET['minicounter_image'])) {
            $this->sendTrackingImage();
        }
    }

    /**
     * @return void
     */
    protected function administrate()
    {
        global $o, $admin, $action;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $o .= $this->views->info();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'minicounter');
        }
    }

    /**
     * @return bool
     */
    protected function ignoreRequest()
    {
        global $adm, $f, $logout;

        return $adm || $f == 'login' || $logout
            || $this->model->ignoreIp($_SERVER['REMOTE_ADDR']);
    }

    /**
     * @return void
     */
    protected function count()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['minicounter'];
        if ($pcf['honor_dnt']
            && isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT']
        ) {
            return;
        }
        if (session_id() == '') {
            session_start();
        }
        if (!isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])) {
            $_SESSION['minicounter_count'][CMSIMPLE_ROOT]
                = $this->model->count() + 1;
        }
        if (!$this->ignoreRequest()) {
            if (!isset($_SESSION['minicounter_counted'][CMSIMPLE_ROOT])) {
                $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = false;
            } else {
                if ($_SESSION['minicounter_counted'][CMSIMPLE_ROOT] === false) {
                    $this->model->increaseCount();
                    $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = true;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function counter()
    {
        $count = isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])
            ? $_SESSION['minicounter_count'][CMSIMPLE_ROOT]
            : $this->model->count() + 1;
        $o = $this->views->counter($count);
        if (empty($_SESSION['minicounter_counted'][CMSIMPLE_ROOT])) {
            $o .= $this->views->trackingImage();
        }
        return $o;
    }

    /**
     * @return void
     */
    protected function sendTrackingImage()
    {
        header('Content-Type: image/gif');
        echo base64_decode(
            'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='
        );
        exit;
    }
}
