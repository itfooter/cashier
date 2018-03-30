<?php namespace TaoTui\Cashier\Facades;

use Illuminate\Support\Facades\Facade;

class Cashier extends Facade
{
    /**
     * Get the registered name of the
     *
     * .
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cashier';
    }
}