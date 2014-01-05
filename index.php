<?php

/**
 * Front-end of Minicounter_XH.
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

require_once $pth['folder']['plugin_classes'] . 'Model.php';
require_once $pth['folder']['plugin_classes'] . 'Views.php';

/**
 * The version number.
 */
define('MINICOUNTER_VERSION', '@MINICOUNTER_VERSION@');

/**
 * Handles the visitor counting.
 *
 * @return void
 *
 * @global bool              Whether we're in admin mode.
 * @global string            The requested system function.
 * @global bool              Whether logout is requested.
 * @global array             The configuration of the plugins.
 * @global Minicounter_Model The model.
 *
 * @todo Adapt for XH 1.6.
 */
function Minicounter_doCount()
{
    global $adm, $f, $logout, $plugin_cf, $_Minicounter_model;

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
            = $_Minicounter_model->count() + 1;
    }
    $ips = explode(',', $pcf['ignore_ips']);
    $ips = array_map('trim', $ips);
    if (!$adm && $f != 'login' && !$logout
        && !in_array($_SERVER['REMOTE_ADDR'], $ips)
    ) {
        if (!isset($_SESSION['minicounter_counted'][CMSIMPLE_ROOT])) {
            $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = false;
        } else {
            if ($_SESSION['minicounter_counted'][CMSIMPLE_ROOT] === false) {
                $_Minicounter_model->increaseCount();
                $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = true;
            }
        }
    }
}

/**
 * Returns the visitor counter.
 *
 * @return string (X)HTML.
 *
 * @global array             The localization of the plugins.
 * @global Minicounter_Model The model.
 * @global Minicounter_Views The views.
 */
function minicounter()
{
    global $plugin_tx, $_Minicounter_model, $_Minicounter_views;

    $count = isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])
        ? $_SESSION['minicounter_count'][CMSIMPLE_ROOT]
        : $_Minicounter_model->count() + 1;
    return $_Minicounter_views->counter($count);
}

$_Minicounter_model = new Minicounter_Model();
$_Minicounter_views = new Minicounter_Views($_Minicounter_model);

/*
 * Handle the visitor counting.
 */
Minicounter_doCount();

?>
