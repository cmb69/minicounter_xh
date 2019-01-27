<?php

/**
 * Copyright 2019 Christoph M. Becker
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

use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;

class BugTest extends TestCase
{
    private $webDriver;

    private $xhDir;

    public function setUp()
    {
        $this->webDriver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            DesiredCapabilities::chrome()
        );
        $this->xhDir = getenv('CMSIMPLEDIR');
    }

    protected function tearDown()
    {
        $this->webDriver->quit();
    }

    public function testBug11()
    {
        $this->webDriver->get("http://localhost{$this->xhDir}");

        $this->webDriver->manage()->deleteAllCookies();
        $cookie = new Cookie('minicounter', '-1234');
        $cookie->setPath($this->xhDir);
        $this->webDriver->manage()->addCookie($cookie);

        $this->webDriver->navigate()->refresh();

        $this->assertRegExp(
            '/[^-]1234/',
            $this->webDriver->findElement(WebDriverBy::id('minicounter'))->getText()
        );
    }
}
