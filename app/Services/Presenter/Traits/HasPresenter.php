<?php

namespace App\Services\Presenter\Traits;

use App\Services\Presenter\Presenter;

trait HasPresenter
{
    public function present(): Presenter
    {
        if (is_null($this->presenter)) {
            throw new Exception('Presenter Class attribute must be provided.');
        }

        $presenter = new $this->presenter;

        return $presenter->presentEntity($this);
    }

    public function presentAs(Presenter $presenter): Presenter
    {
        return $presenter->presentEntity($this);
    }
}
