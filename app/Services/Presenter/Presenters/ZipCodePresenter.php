<?php

namespace App\Services\Presenter\Presenters;

use App\Services\Presenter\Presenter;
use App\Services\Presenter\Presenters\Traits\InEnglishPresenterTrait;

class ZipCodePresenter extends Presenter
{
    use InEnglishPresenterTrait;

    public function locality()
    {
        return strtoupper((string) $this->englishCharsOnly('d_ciudad'));
    }
}
