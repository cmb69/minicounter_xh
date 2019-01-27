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

class Plugin
{
    const VERSION = '2.0RC1';

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
    }

    /**
     * @return void
     */
    public function run()
    {
        (new MainController)->defaultAction();
        if (XH_ADM) {
            XH_registerStandardPluginMenuItems(false);
            if (XH_wantsPluginAdministration('minicounter')) {
                $this->handleAdministration();
            }
        }
        if (isset($_GET['minicounter_image'])) {
            $this->sendTrackingImage();
        }
    }

    /**
     * @return void
     */
    private function handleAdministration()
    {
        global $o, $admin, $action, $pth;

        $o .= print_plugin_admin('off');
        switch ($admin) {
            case '':
                $view = new View('info');
                $view->count = $this->model->count();
                $view->logo = "{$pth['folder']['plugins']}minicounter/minicounter.png";
                $view->version = self::VERSION;
                $view->checks = (new SystemCheckService)->getChecks();
                $o .= $view;
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'minicounter');
        }
    }

    /**
     * @return void
     */
    private function sendTrackingImage()
    {
        header('Content-Type: image/gif');
        echo base64_decode(
            'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='
        );
        exit;
    }
}
