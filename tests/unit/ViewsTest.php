<?php

/**
 * A test case for the views class.
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

require_once './classes/Model.php';
require_once './classes/Views.php';

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
        $model = $this->getMockBuilder('Minicounter_Model')
            ->disableOriginalConstructor()
            ->getMock();
        $model->expects($this->any())
            ->method('count')
            ->will($this->returnValue(1));
        $this->_views = new Minicounter_Views($model);
    }

    public function testAboutShowsVersion()
    {
        $matcher = array('tag' => 'p', 'content' => '@MINICOUNTER_VERSION@');
        $actual = $this->_views->about();
        $this->assertTag($matcher, $actual);
    }

    public function testSystemCheck()
    {
        $checks = array('something' => 'ok');
        $matcher = array(
            'tag' => 'ul',
            'children' => array(
                'count' => 1,
                'only' => array('tag' => 'li')
            )
        );
        $actual = $this->_views->systemCheck($checks);
        $this->assertTag($matcher, $actual);
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