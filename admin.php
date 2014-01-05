<?php

/**
 * Back-End of Minicounter_XH.
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

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Returns the requirements information view.
 *
 * @return string (X)HTML.
 *
 * @global array             The paths of system files and folders.
 * @global array             The localization of the core.
 * @global array             The localization of the plugins.
 * @global Minicounter_Model The model.
 */
function Minicounter_systemChecks()
{
    global $pth, $tx, $plugin_tx, $_Minicounter_model;

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
    $folders[] = $_Minicounter_model->dataFolder();
    foreach ($folders as $folder) {
        $checks[sprintf($ptx['syscheck_writable'], $folder)]
            = is_writable($folder) ? 'ok' : 'warn';
    }
    return $checks;
}

/*
 * Handle the plugin administration.
 */
if (isset($minicounter) && $minicounter == 'true') {
    $o .= print_plugin_admin('off');
    switch ($admin) {
    case '':
        $o .= $_Minicounter_views->about() . tag('hr')
            . $_Minicounter_views->systemCheck(Minicounter_systemChecks());
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
