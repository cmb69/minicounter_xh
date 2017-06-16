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
     * @return void
     */
    public function __construct()
    {
        $this->model = new Model();
        $this->dispatch();
    }

    /**
     * @return void
     */
    protected function dispatch()
    {
        global $o;

        $this->count();
        if (XH_ADM) {
            XH_registerStandardPluginMenuItems(false);
            if (XH_wantsPluginAdministration('minicounter')) {
                $this->administrate();
            }
        }
        if (!isset($_COOKIE['minicounter']) || $_COOKIE['minicounter'] < 0) {
            $o .= '<img src="?&amp;minicounter_image" width="1" height="1">';
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
        global $o, $admin, $action, $pth;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $view = new View('info');
                $view->count = $this->model->count();
                $view->logo = "{$pth['folder']['plugins']}minicounter/minicounter.png";
                $view->version = MINICOUNTER_VERSION;
                $view->checks = (new SystemCheckService)->getChecks();
                $o .= $view;
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
        global $f, $logout;

        return XH_ADM || $f == 'login' || $logout
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
        if (!$this->ignoreRequest()) {
            if (!isset($_COOKIE['minicounter'])) {
                setcookie('minicounter', -($this->model->count() + 1));
            } elseif ($_COOKIE['minicounter'] < 0) {
                $this->model->increaseCount();
                setcookie('minicounter', -$_COOKIE['minicounter']);
            }
        }
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
