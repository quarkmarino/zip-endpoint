<?php

namespace App\Services\Presenter\Contracts;

use App\Services\Presenter\Presenter;

interface Presentable
{
    public function present(): Presenter;

    public function presentAs(Presenter $presenter): Presenter;
}
