<?php

namespace Putchi\StateKeepers;

use Closure;

interface StateKeeper {
    public static function keep(Closure $callable);
}