<?php

/**
 * A test case for the views class.
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

use PHPUnit_Framework_TestCase;
 
define('MINICOUNTER_VERSION', '@MINICOUNTER_VERSION@');

/**
 * A test case for the views class.
 *
 * @category CMSimple_XH
 * @package  Minicounter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */
class ViewsTest extends PHPUnit_Framework_TestCase
{
    private $_views;

    public function setUp()
    {
        $model = $this->getMockBuilder('Minicounter\Model')
            ->disableOriginalConstructor()
            ->getMock();
        $model->expects($this->any())
            ->method('count')
            ->will($this->returnValue(1));
        $this->_views = new Views($model);
    }

    public function testCounterShowsCount()
    {
        global $plugin_tx;

        $plugin_tx = array('minicounter' => array('html' => '%d'));
        $expected = '4711';
        $actual = $this->_views->counter(4711);
        $this->assertEquals($expected, $actual);
    }
}

?>
