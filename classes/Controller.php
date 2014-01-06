<?php

/**
 * The controller class.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Minicounter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */

/**
 * The controller class.
 *
 * @category CMSimple_XH
 * @package  Minicounter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */
class Minicounter_Controller
{
    /**
     * The model.
     *
     * @var Minicounter_Model
     */
    private $_model;

    /**
     * The views.
     *
     * @var Minicounter_Views
     */
    private $_views;

    /**
     * Initializes a new instance.
     */
    public function __construct()
    {
        $this->_model = new Minicounter_Model();
        $this->_views = new Minicounter_Views($this->_model);
        $this->dispatch();
    }

    /**
     * Handles all plugin related requests.
     *
     * @return void
     *
     * @global bool   Whether we're in admin mode.
     * @global bool   Whether the plugin's administration is requested.
     */
    protected function dispatch()
    {
        global $adm, $minicounter;

        $this->count();
        if ($adm && isset($minicounter) && $minicounter == 'true') {
            $this->administrate();
        }
    }

    /**
     * Returns the system checks.
     *
     * @return array.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the core.
     * @global array The localization of the plugins.
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
        $folders[] = $this->_model->dataFolder();
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = is_writable($folder) ? 'ok' : 'warn';
        }
        return $checks;
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The (X)HTML to insert into the contents area.
     * @global string The value of the admin GP parameter.
     * @global string The value of the action GP paramater.
     */
    protected function administrate()
    {
        global $o, $admin, $action;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->_views->info($this->systemChecks());
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'minicounter');
        }
    }

    /**
     * Whether the current request shall be ignored (opposed to being counted).
     *
     * @return bool
     *
     * @global bool   Whether we're in admin mode.
     * @global string The current system function.
     * @global bool   Whether logout is requested.
     */
    protected function ignoreRequest()
    {
        global $adm, $f, $logout;

        return $adm || $f == 'login' || $logout
            || $this->_model->ignoreIp($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Handles the visitor counting.
     *
     * @return void
     *
     * @global bool   Whether we're in admin mode.
     * @global string The requested system function.
     * @global bool   Whether logout is requested.
     * @global array  The configuration of the plugins.
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
                = $this->_model->count() + 1;
        }
        if (!$this->ignoreRequest()) {
            if (!isset($_SESSION['minicounter_counted'][CMSIMPLE_ROOT])) {
                $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = false;
            } else {
                if ($_SESSION['minicounter_counted'][CMSIMPLE_ROOT] === false) {
                    $this->_model->increaseCount();
                    $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = true;
                }
            }
        }
    }

    /**
     * Returns the visitor counter.
     *
     * @return string (X)HTML.
     */
    public function counter()
    {
        $count = isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])
            ? $_SESSION['minicounter_count'][CMSIMPLE_ROOT]
            : $this->_model->count() + 1;
        return $this->_views->counter($count);
    }
}

?>
