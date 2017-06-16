<?php

/**
 * Initialization.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Minicounter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2017 Christoph M. Becker <http://3-magi.net/>
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
define('MINICOUNTER_VERSION', '@MINICOUNTER_VERSION@');

/**
 * Returns the visitor counter.
 *
 * A procedural wrapper around Minicounter_Controller::counter() for plugin calls.
 *
 * @return string (X)HTML.
 */
function minicounter()
{
    global $_Minicounter;

    return $_Minicounter->counter();
}

/**
 * The controller.
 *
 * @var Minicounter_Controller
 */
$_Minicounter = new Minicounter\Controller();

?>
