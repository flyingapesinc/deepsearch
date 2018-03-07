<?php

namespace FlyingApesInc\DeepSearch;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return DeepSearch::class;
    }
}
