<?php

/**
 * The views class.
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
 * The views class.
 *
 * @category CMSimple_XH
 * @package  Minicounter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */
class Minicounter_Views
{
    /**
     * The model.
     *
     * @var Minicounter_Model
     */
    private $_model;

    /**
     * Initializes a new instance.
     *
     * @param Minicounter_Model $model A model.
     */
    public function __construct(Minicounter_Model $model)
    {
        $this->_model = $model;
    }

    /**
     * Returns a string with TAGCs adjusted to the configured markup language.
     *
     * @param string $string A string.
     *
     * @return string (X)HTML.
     *
     * @global array The configuration of the core.
     */
    protected function xhtml($string)
    {
        global $cf;

        if (!$cf['xhtml']['endtags']) {
            $string = str_replace(' />', '>', $string);
        }
        return $string;
    }

    /**
     * Returns the plugin's about view.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     *
     * @todo Fix TAGCs.
     */
    public function about()
    {
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['minicounter'];
        $version = MINICOUNTER_VERSION;
        $count = sprintf($ptx['html_admin'], $this->_model->count());
        $o = <<<EOT
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
        return $this->xhtml($o);
    }

    /**
     * Returns the view of a system check item.
     *
     * @param string $check     A system check label.
     * @param string $state     A system check state ('ok', 'warn', 'fail').
     * @param string $imgFolder An image folder path.
     *
     * @return string (X)HTML.
     */
    protected function systemCheckItem($check, $state, $imgFolder)
    {
        $o = <<<EOT
<li><img src="$imgFolder$state.png" alt="$state" /> $check</li>
EOT;
        return $this->xhtml($o);
    }

    /**
     * Returns the system check view.
     *
     * @param array $checks An array of system checks.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    public function systemCheck($checks)
    {
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['minicounter'];
        $imgdir = $pth['folder']['plugins'] . 'minicounter/images/';
        $items = '';
        foreach ($checks as $check => $state) {
            $items .= $this->systemCheckItem($check, $state, $imgdir);
        }
        return <<<EOT
<h4>$ptx[syscheck_title]</h4>
<ul style="list-style: none">
    $items
</ul>
EOT;
    }

    /**
     * Returns the counter view.
     *
     * @param int $count A visitor count.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    public function counter($count)
    {
        global $plugin_tx;

        return sprintf($plugin_tx['minicounter']['html'], $count);
    }
}

?>
