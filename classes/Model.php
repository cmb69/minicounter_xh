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

class Model
{
    /**
     * @return string
     */
    public function dataFolder()
    {
        global $pth;

        return preg_replace(
            '/(?:\/[^\/]+\/\.\.\/|\/\.\/)$/',
            '/',
            "{$pth['folder']['content']}{$pth['folder']['base']}"
        );
    }

    /**
     * @return string
     */
    protected function filename()
    {
        return $this->dataFolder() . 'minicounter.txt';
    }

    /**
     * @param string $ip
     * @return bool
     */
    public function ignoreIp($ip)
    {
        global $plugin_cf;

        $ips = explode(',', $plugin_cf['minicounter']['ignore_ips']);
        $ips = array_map('trim', $ips);
        return in_array($ip, $ips);
    }

    /**
     * @return void
     */
    public function increaseCount()
    {
        $filename = $this->filename();
        $success = file_put_contents($filename, '*', FILE_APPEND) !== false;
        return $success;
    }

    /**
     * @return int
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
