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

/**
 * The version number.
 */
define('MINICOUNTER_VERSION', '1rc1');

/**
 * Returns the data folder.
 *
 * @return string
 *
 * @global array The paths of system files and folders.
 * @global array The configuration of the plugins.
 */
function Minicounter_dataFolder()
{
    global $pth, $plugin_cf;

    $pcf = $plugin_cf['minicounter'];

    if ($pcf['folder_data'] == '') {
        $fn = $pth['folder']['plugins'] . 'minicounter/data/';
    } else {
        $fn = $pth['folder']['base'] . $pcf['folder_data'];
    }
    if (substr($fn, -1) != '/') {
        $fn .= '/';
    }
    if (file_exists($fn)) {
        if (!is_dir($fn)) {
            e('cntopen', 'folder', $fn);
        }
    } else {
        if (!mkdir($fn, 0777, true)) {
            e('cntwriteto', 'folder', $fn);
        }
    }
    return $fn;
}

/**
 * Increases the visitor counter by one.
 *
 * @return void
 */
function Minicounter_increase()
{
    $fn = Minicounter_dataFolder() . 'count.txt';
    if (($fh = fopen($fn, 'a')) === false
        || fwrite($fh, '*') === false
    ) {
        e('cntwriteto', 'file', $fn);
    }
    if ($fh !== false) {
        fclose($fh);
    }
}

/**
 * Returns the visitor count.
 *
 * @return int
 *
 * @global array The configuration of the plugins.
 */
function Minicounter_count()
{
    global $plugin_cf;

    $count = $plugin_cf['minicounter']['start_value'];
    $fn = Minicounter_dataFolder() . 'count.txt';
    if (is_readable($fn)) {
        $count += filesize($fn);
    }
    return $count;
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
 *
 * @todo Adapt for XH 1.6.
 */
function Minicounter_doCount()
{
    global $adm, $f, $logout, $plugin_cf;

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
        $_SESSION['minicounter_count'][CMSIMPLE_ROOT] = Minicounter_count() + 1;
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
                minicounter_increase();
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
 * @global array The localization of the plugins.
 */
function minicounter()
{
    global $plugin_tx;

    $count = isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])
        ? $_SESSION['minicounter_count'][CMSIMPLE_ROOT]
        : Minicounter_count() + 1;
    return sprintf($plugin_tx['minicounter']['html'], $count);
}

/*
 * Handle the visitor counting.
 */
Minicounter_doCount();

?>
