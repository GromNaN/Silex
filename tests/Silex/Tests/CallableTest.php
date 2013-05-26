<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Silex\Tests;

use Silex\Application;

/**
 * Dynamic application methods test cases.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class CallableTest extends \PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $app = new Application();

        $app['hello'] = $app->protect(function ($name) {
            return 'Hello ' . $name;
        });

        $this->assertEquals('Hello world', $app->hello('world'));
    }

    public function testNotCallable()
    {
        $this->setExpectedException('\BadMethodCallException', 'The service "hello" is not callable.');

        $app = new Application();

        $app['hello'] = function () {
            return 10;
        };

        $app->hello();
    }

    public function testNotDefined()
    {
        $this->setExpectedException('\BadMethodCallException', 'Call to undefined method Silex\Application::hello()');
        $app = new Application();

        $app->hello();
    }
}
