<?php

/**
 * Front-End of Minicounter_XH.
 *
 * Copyright (c) 2012 Christoph M. Becker (see license.txt)
 */


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


define('MINICOUNTER_VERSION', '1rc1');


/**
 * Returns the data folder.
 *
 * @return string
 */
function Minicounter_dataFolder()
{
    global $pth, $plugin_cf;

    $pcf = $plugin_cf['minicounter'];

    if ($pcf['folder_data'] == '') {
	$fn = $pth['folder']['plugins'].'minicounter/data/';
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
	    || fwrite($fh, '*') === false) {
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
 */
function Minicounter_doCount()
{
    global $adm, $f, $logout, $plugin_cf;

    if (!isset($_SESSION)) {
	session_start();
    }
    if (!isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])) {
	$_SESSION['minicounter_count'][CMSIMPLE_ROOT] = Minicounter_count() + 1;
    }
    $ips = explode(',', $plugin_cf['minicounter']['ignore_ips']);
    $ips = array_map('trim', $ips);
    if (!$adm && $f != 'login' && !$logout && !in_array($_SERVER['REMOTE_ADDR'], $ips)) {
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
 * @access public
 * @return string  The (X)HTML.
 */
function minicounter()
{
    global $plugin_tx;

    return sprintf($plugin_tx['minicounter']['html'],
		   $_SESSION['minicounter_count'][CMSIMPLE_ROOT]);
}


/*
 * Handle the visitor counting.
 */
Minicounter_doCount();

?>
