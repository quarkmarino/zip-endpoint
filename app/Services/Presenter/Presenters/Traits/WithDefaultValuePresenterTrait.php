<?php

namespace App\Services\Presenter\Presenters\Traits;

trait WithDefaultValuePresenterTrait
{
    /**
     * Presents a default value when entity's property of passed property name is falsy
     *
     * @param  string      $propertyName The name of a property on the underliying Entity
     * @param  string|null $default      The deddault value to use when property value is falsy
     * @return string                    The very property value or the default string
     */
    public function useDefaultWhenEmpty(string $propertyName, string $default = null): string
    {
        return (string) ($this->$propertyName ?: $default);
    }
}
