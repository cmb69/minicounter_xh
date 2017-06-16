<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Minicounter_XH.
 *
 * Minicounter_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Minicounter_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Minicounter_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Minicounter;

class Views
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    protected function about()
    {
        global $plugin_tx;

        $view = new View('info');
        $view->logo = $this->model->pluginIconPath();
        $view->version = MINICOUNTER_VERSION;
        return (string) $view;
    }

    /**
     * @param string $check
     * @param string $state
     * @return string
     */
    protected function systemCheckItem($check, $state)
    {
        $imgFolder = $this->model->stateIconFolder();
        return <<<EOT
<li><img src="$imgFolder$state.png" alt="$state"> $check</li>
EOT;
    }

    /**
     * @param array $checks
     * @return string
     */
    protected function systemCheck($checks)
    {
        global $plugin_tx;

        $ptx = $plugin_tx['minicounter'];
        $items = '';
        foreach ($checks as $check => $state) {
            $items .= $this->systemCheckItem($check, $state);
        }
        return <<<EOT
<h4>$ptx[syscheck_title]</h4>
<ul style="list-style: none">
    $items
</ul>
EOT;
    }

    /**
     * @param array $checks
     * @return string
     */
    public function info($checks)
    {
        global $plugin_tx;

        $ptx = $plugin_tx['minicounter'];
        $count = sprintf($ptx['html_admin'], $this->model->count());
        $systemCheck = $this->systemCheck($checks);
        $about = $this->about();
        $o = <<<EOT
<h1>Minicounter_XH</h1>
<p>$count</p>
$systemCheck
$about
EOT;
        return $o;
    }

    /**
     * @param int $count
     * @return string
     */
    public function counter($count)
    {
        global $plugin_tx;

        return sprintf($plugin_tx['minicounter']['html'], $count);
    }

    /**
     * @return string
     */
    public function trackingImage()
    {
        $o = <<<EOT
<img src="?&amp;minicounter_image" width="1" height="1">
EOT;
        return $o;
    }
}
