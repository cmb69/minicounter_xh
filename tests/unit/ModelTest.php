<?php

/**
 * A test case for the model class.
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

require_once 'vfsStream/vfsStream.php';

require_once './classes/Model.php';

/**
 * A test case for the model class.
 *
 * @category CMSimple_XH
 * @package  Minicounter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Minicounter_XH
 */
class ModelTest extends PHPUnit_Framework_TestCase
{
    private $_basePath;

    private $_model;

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->_basePath = vfsStream::url('test') . '/';

        $this->_model = new Minicounter_Model();
    }

    public function testDefaultDataFolder()
    {
        global $pth;

        $pth = array('folder' => array('plugins' => $this->_basePath));
        $expected = $this->_basePath . 'minicounter/data/';
        $actual = $this->_model->dataFolder();
        $this->assertEquals($expected, $actual);
        $this->assertTrue(is_dir($expected));
    }

    public function testCustomDataFolder()
    {
        global $pth, $plugin_cf;

        $pth = array('folder' => array('base' => $this->_basePath));
        $plugin_cf = array('minicounter' => array('folder_data' => 'custom'));
        $expected = $this->_basePath . 'custom/';
        $actual = $this->_model->dataFolder();
        $this->assertEquals($expected, $actual);
        $this->assertTrue(is_dir($expected));
    }

    public function testIgnoreIp()
    {
        global $plugin_cf;

        $plugin_cf = array(
            'minicounter' => array('ignore_ips' => '0.1.2.3, 127.0.0.1 ')
        );
        $actual = $this->_model->ignoreIp('127.0.0.1');
        $this->assertTrue($actual);
    }

    public function testIncreaseCountIncreasesCount()
    {
        global $pth;

        $pth = array('folder' => array('plugins' => $this->_basePath));
        $oldCount = $this->_model->count();
        $this->_model->increaseCount();
        $newCount = $this->_model->count();
        $this->assertEquals($oldCount + 1, $newCount);
    }
}

?>
