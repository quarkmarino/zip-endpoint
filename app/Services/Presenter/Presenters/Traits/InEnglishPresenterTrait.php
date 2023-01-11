<?php

namespace App\Services\Presenter\Presenters\Traits;

trait InEnglishPresenterTrait
{
    /**
     * Presents a the string value in english only characters then converts the string into caps
     *
     * @param  string      $propertyName The name of a property on the underliying Entity
     * @return string                    The string value with english chars only
     */
    public function englishCharsOnly(string $propertyName): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $this->$propertyName);
    }
}
