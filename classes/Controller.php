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
     * @return array
     */
    protected function systemChecks()
    {
        global $pth, $tx, $plugin_tx;

        $phpVersion = '5.0.0';
        $ptx = $plugin_tx['minicounter'];
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok': 'fail';
        foreach (array('session') as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = extension_loaded($ext) ? 'ok' : 'fail';
        }
        $checks[$ptx['syscheck_magic_quotes']]
            = !get_magic_quotes_runtime() ? 'ok' : 'fail';
        $checks[ $ptx['syscheck_encoding']]
            =  strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
        $folders = array();
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'minicounter/' . $folder;
        }
        $folders[] = $this->model->dataFolder();
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = is_writable($folder) ? 'ok' : 'warn';
        }
        return $checks;
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
                $o .= $this->views->info($this->systemChecks());
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
