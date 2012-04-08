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


define('MINICOUNTER_VERSION', '1beta1');


/**
 * Returns the data folder.
 *
 * @return string
 */
function minicounter_data_folder() {
    global $pth, $plugin_cf;

    $pcf = $plugin_cf['minicounter'];

    if ($pcf['folder_data'] == '') {
	$fn = $pth['folder']['plugins'].'minicounter/data/';
    } else {
	$fn = $pth['folder']['base'].$pcf['folder_data'];
    }
    if (substr($fn, -1) != '/') {
	$fn .= '/';
    }
    if (file_exists($fn)) {
	if (!is_dir($fn)) {
	    e('cntopen', 'folder', $fn);
	}
    } else {
	if (!mkdir($fn, 0777, TRUE)) {
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
function minicounter_increase() {
    $fn = minicounter_data_folder().'count.txt';
    if (($fh = fopen($fn, 'a')) === FALSE
	    || fwrite($fh, '*') === FALSE) {
	e('cntwriteto', 'file', $fn);
    }
    if ($fh !== FALSE) {fclose($fh);}
}


/**
 * Returns the visitor counter.
 *
 * @access public
 * @return string  The (X)HTML.
 */
function minicounter() {
    global $plugin_tx;

    return sprintf($plugin_tx['minicounter']['html'], $_SESSION['minicounter_count'][CMSIMPLE_ROOT]);
}


/**
 * Set visitor number.
 */
if (!isset($_SESSION)) {session_start();}
if (!isset($_SESSION['minicounter_count'][CMSIMPLE_ROOT])) {
    $_SESSION['minicounter_count'][CMSIMPLE_ROOT] = filesize(minicounter_data_folder().'count.txt') + 1;
}


/**
 * Handle the permanent count.
 */
if (!$adm && $f != 'login' && !$logout) {
    if (!isset($_SESSION['minicounter_counted'][CMSIMPLE_ROOT])) {
	$_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = FALSE;
    } else {
	if ($_SESSION['minicounter_counted'][CMSIMPLE_ROOT] === FALSE) {
	    minicounter_increase();
	    $_SESSION['minicounter_counted'][CMSIMPLE_ROOT] = TRUE;
	}
    }
}

?>
