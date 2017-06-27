<?php

namespace App;

class Foo
{
    protected $count = 0;

    public function foo()
    {
        return 'bar';
    }

    public function incr()
    {
        return ++$this->count;
    }
}
