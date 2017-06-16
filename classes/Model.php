<?php

/**
 * The model class.
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

namespace Minicounter;

/**
 * The model class.
 *
 * @category CMSimple_XH
 * @package  Minicounter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */
class Model
{
    /**
     * Returns the path of the plugin icon.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     */
    public function pluginIconPath()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'minicounter/minicounter.png';
    }

    /**
     * Returns the path of the folder containing the state icons.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     */
    public function stateIconFolder()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'minicounter/images/';
    }

    /**
     * Returns the path of the data folder; <var>false</var> if it neither does
     * not exist nor can be created.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     */
    public function dataFolder()
    {
        global $pth, $plugin_cf;

        $pcf = $plugin_cf['minicounter'];

        if ($pcf['folder_data'] == '') {
            $filename = $pth['folder']['plugins'] . 'minicounter/data/';
        } else {
            $filename = $pth['folder']['base'] . $pcf['folder_data'];
            if (substr($filename, -1) != '/') {
                $filename .= '/';
            }
        }
        if (!file_exists($filename)) {
            if (!mkdir($filename, 0777, true)) {
                return false;
            }
        }
        return $filename;
    }

    /**
     * Returns the path of the counter file.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->dataFolder() . 'count.txt';
    }

    /**
     * Returns whether an IP address shall be ignored.
     *
     * @param string $ip An IP address.
     *
     * @return bool
     *
     * @global array The configuration of the plugins.
     */
    public function ignoreIp($ip)
    {
        global $plugin_cf;

        $ips = explode(',', $plugin_cf['minicounter']['ignore_ips']);
        $ips = array_map('trim', $ips);
        return in_array($ip, $ips);
    }

    /**
     * Increases the visitor count by one and returns whether that succeeded.
     *
     * @return void
     */
    public function increaseCount()
    {
        $filename = $this->filename();
        $success = file_put_contents($filename, '*', FILE_APPEND) !== false;
        return $success;
    }

    /**
     * Returns the visitor count.
     *
     * @return int
     *
     * @global array The configuration of the plugins.
     */
    public function count()
    {
        global $plugin_cf;

        $count = $plugin_cf['minicounter']['start_value'];
        $filename = $this->filename();
        if (is_readable($filename)) {
            $count += filesize($filename);
        }
        return $count;
    }

}

?>
