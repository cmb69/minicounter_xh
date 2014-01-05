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
 * Returns the plugin version information view.
 *
 * @return string (X)HTML.
 *
 * @global array The paths of system files and folders.
 * @global array The localization of the plugins.
 *
 * @todo Fix TAGCs.
 */
function Minicounter_version()
{
    global $pth, $plugin_tx;

    $version = MINICOUNTER_VERSION;
    $count = sprintf($plugin_tx['minicounter']['html_admin'], Minicounter_count());
    return <<<EOT
<h1><a href="http://3-magi.net/?CMSimple_XH/Minicounter_XH">Minicounter_XH</a></h1>
<img src="{$pth['folder']['plugins']}minicounter/minicounter.png"
     alt="Plugin Icon" style="float: left; margin: 0" />
<p>Version: $version</p>
<p>Copyright &copy; 2012-2014 <a href="http://3-magi.net">Christoph M. Becker</a></p>
<p>$count</p>
<p style="text-align: justify">
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
</p>
<p style="text-align: justify">
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
</p>
<p style="text-align: justify">
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see
    <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.
</p>
EOT;
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
function Minicounter_systemCheck()
{
    global $pth, $tx, $plugin_tx, $_Minicounter_model;

    $phpVersion = '5.0.0';
    $ptx = $plugin_tx['minicounter'];
    $imgdir = $pth['folder']['plugins'] . 'minicounter/images/';
    $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
    $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
    $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
    $o = '<h4>' . $ptx['syscheck_title'] . '</h4>'
        . (version_compare(PHP_VERSION, $phpVersion) >= 0 ? $ok : $fail)
        . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], $phpVersion)
        . tag('br');
    foreach (array('session') as $ext) {
        $o .= (extension_loaded($ext) ? $ok : $fail)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext)
            . tag('br');
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
        . '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br');
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
        . '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br') . tag('br');
    $folders = array();
    foreach (array('config/', 'languages/') as $folder) {
        $folders[] = $pth['folder']['plugins'] . 'minicounter/' . $folder;
    }
    $folders[] = $_Minicounter_model->dataFolder();
    foreach ($folders as $folder) {
        $o .= (is_writable($folder) ? $ok : $warn)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_writable'], $folder)
            . tag('br');
    }
    return $o;
}

/*
 * Handle the plugin administration.
 */
if (isset($minicounter) && $minicounter == 'true') {
    $o .= print_plugin_admin('off');
    switch ($admin) {
    case '':
        $o .= Minicounter_version() . tag('hr') . Minicounter_systemCheck();
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
