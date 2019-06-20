<?php

namespace Putchi\StateKeepers\Keepers;

use Putchi\StateKeepers\StateKeeper;

use Closure;
use Illuminate\Support\Facades\DB;

class DBStateKeeper implements StateKeeper {

    /**
     * @param Closure $anonymousFunction
     * @return mixed
     */
    public static function keep(Closure $anonymousFunction) {
        return DB::transaction($anonymousFunction);
    }
}