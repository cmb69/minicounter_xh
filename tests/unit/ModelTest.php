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

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var Model
     */
    private $model;

    /**
     * @return void
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->basePath = vfsStream::url('test') . '/';

        $this->model = new Model();
    }

    /**
     * @return void
     */
    public function testPluginIconPath()
    {
        global $pth;

        $pth = array('folder' => array('plugins' => $this->basePath));
        $expected = $this->basePath . 'minicounter/minicounter.png';
        $actual = $this->model->pluginIconPath();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testStateIconFolder()
    {
        global $pth;

        $pth = array('folder' => array('plugins' => $this->basePath));
        $exptected = $this->basePath . 'minicounter/images/';
        $actual = $this->model->stateIconFolder();
        $this->assertEquals($exptected, $actual);
    }

    /**
     * @return void
     */
    public function testDataFolder()
    {
        global $pth;

        $pth = array('folder' => array('base' => './', 'content' => $this->basePath . 'content/'));
        $expected = $this->basePath . 'content/';
        $actual = $this->model->dataFolder();
        $this->assertEquals($expected, $actual);
        $this->assertTrue(is_dir($expected));
    }

    /**
     * @return void
     */
    public function testIgnoreIp()
    {
        global $plugin_cf;

        $plugin_cf = array(
            'minicounter' => array('ignore_ips' => '0.1.2.3, 127.0.0.1 ')
        );
        $actual = $this->model->ignoreIp('127.0.0.1');
        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function testIncreaseCountIncreasesCount()
    {
        global $pth;

        $pth = array('folder' => array('base' => './', 'content' => $this->basePath . 'content/'));
        $oldCount = $this->model->count();
        $this->model->increaseCount();
        $newCount = $this->model->count();
        $this->assertEquals($oldCount + 1, $newCount);
    }
}
