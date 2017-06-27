<?php

namespace App;

class Bar
{
    protected $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function foo()
    {
        return $this->foo->foo() . ' bar';
    }
}
