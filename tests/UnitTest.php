<?php

namespace Tests;

use App\Bar;
use App\Foo;
use App\Main;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;

class UnitTest extends TestCase
{
    public function testBind()
    {
        $container = new Container;

        $container->bind('foo', Foo::class);

        $this->assertEquals('bar', $container->make('foo')->foo());
    }

    public function testBildUsingClosure()
    {
        $container = new Container;

        $container->bind('foo', function () {
            return new Foo;
        });

        $this->assertEquals('bar', $container->make('foo')->foo());
    }

    public function testSingleton()
    {
        $container = new Container;

        $container->singleton('foo', Foo::class);

        $this->assertEquals(1, $container->make('foo')->incr());
        $this->assertEquals(2, $container->make('foo')->incr());
        $this->assertEquals(3, $container->make('foo')->incr());
    }

    public function testSingletonUsingClosure()
    {
        $container = new Container;

        $container->singleton('foo', function () {
            return new Foo;
        });

        $this->assertEquals(1, $container->make('foo')->incr());
        $this->assertEquals(2, $container->make('foo')->incr());
        $this->assertEquals(3, $container->make('foo')->incr());
    }

    public function testInstance()
    {
        $container = new Container;

        $container->instance('foo', new Foo);

        $this->assertEquals('bar', $container->make('foo')->foo());
        $this->assertEquals(1, $container->make('foo')->incr());
        $this->assertEquals(2, $container->make('foo')->incr());
        $this->assertEquals(3, $container->make('foo')->incr());
    }

    public function testInstanceWithString()
    {
        $container = new Container;

        $container->instance('foo', 'bar');

        $this->assertEquals('bar', $container->make('foo'));
    }

    public function testBound()
    {
        $container = new Container;

        $this->assertFalse($container->bound('foo'));

        $container->instance('foo', 'bar');

        $this->assertTrue($container->bound('foo'));
    }

    public function testAlias()
    {
        $container = new Container;

        $container->singleton(Foo::class, Foo::class);
        $container->alias(Foo::class, 'foo');

        $this->assertEquals(1, $container->make(Foo::class)->incr());
        $this->assertEquals(2, $container->make('foo')->incr());
        $this->assertEquals(3, $container->make('foo')->incr());
    }

    public function testArrayAccess()
    {
        $container = new Container;

        $container->instance('foo', 'bar');

        $this->assertEquals('bar', $container['foo']);
    }

    public function testResolving()
    {
        $container = new Container;

        $container->singleton('foo', Foo::class);

        $container->resolving('foo', function (Foo $foo) {
            $foo->incr();
        });

        $this->assertEquals(2, $container->make('foo')->incr());
        $this->assertEquals(3, $container->make('foo')->incr());
    }

    public function testExtend()
    {
        $container = new Container;

        $container->singleton('foo', Foo::class);

        $this->assertEquals('bar', $container->make('foo')->foo());

        $container->extend('foo', function (Foo $foo) {
            return new Bar($foo);
        });

        $this->assertEquals('bar bar', $container->make('foo')->foo());
        $this->assertEquals('bar bar', $container->make('foo')->foo());
    }

    public function testCall()
    {
        $container = new Container;

        $this->assertEquals('bar', $container->call('App\\Foo@foo'));
    }

    public function testBindMethod()
    {
        $container = new Container;

        $container->singleton('foo', Foo::class);

        $container->bindMethod('App\\Foo@bar', function (Foo $foo) {
            return 'bar bar';
        });

        $this->assertEquals('bar bar', $container->call('foo@bar'));
    }

    public function testTag()
    {
        $container = new Container;

        $container->tag(Foo::class, 'plugins');
        $container->tag(Bar::class, 'plugins');

        $this->assertInstanceOf(
            Foo::class,
            $container->tagged('plugins')[0]
        );
        $this->assertInstanceOf(
            Bar::class,
            $container->tagged('plugins')[1]
        );
    }
}
