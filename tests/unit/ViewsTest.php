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
 
class ViewsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Views
     */
    private $views;

    /**
     * @return void
     */
    public function setUp()
    {
        $model = $this->getMockBuilder('Minicounter\Model')
            ->disableOriginalConstructor()
            ->getMock();
        $model->expects($this->any())
            ->method('count')
            ->will($this->returnValue(1));
        $this->views = new Views($model);
    }

    /**
     * @return void
     */
    public function testCounterShowsCount()
    {
        global $plugin_tx;

        $plugin_tx = array('minicounter' => array('html' => '%d'));
        $expected = '4711';
        $actual = $this->views->counter(4711);
        $this->assertEquals($expected, $actual);
    }
}
