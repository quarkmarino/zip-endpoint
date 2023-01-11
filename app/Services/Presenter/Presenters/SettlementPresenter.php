<?php

namespace App\Services\Presenter\Presenters;

use App\Services\Presenter\Presenter;
use App\Services\Presenter\Presenters\Traits\InEnglishPresenterTrait;

class SettlementPresenter extends Presenter
{
    use InEnglishPresenterTrait;

    public function name()
    {
        return strtoupper((string) $this->englishCharsOnly('d_asenta'));
    }

    public function zone_type()
    {
        return strtoupper((string) $this->englishCharsOnly('d_zona'));
    }
}
