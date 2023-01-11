<?php

namespace App\Services\Presenter\Presenters;

use App\Services\Presenter\Presenter;
use App\Services\Presenter\Presenters\Traits\InEnglishPresenterTrait;

class FederalEntityPresenter extends Presenter
{
    use InEnglishPresenterTrait;

    public function name()
    {
        return strtoupper((string) $this->englishCharsOnly('d_estado'));
    }
}
