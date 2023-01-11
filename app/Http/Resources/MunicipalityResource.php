<?php

namespace App\Http\Resources;

use App\Services\Presenter\Contracts\Presentable;
use App\Services\Presenter\Presenters\MunicipalityPresenter;
use App\Services\Presenter\Traits\HasPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource implements Presentable
{
    use HasPresenter;

    # Presenter

    protected $presenter = MunicipalityPresenter::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key'  => (int) $this->c_mnpio,
            'name' => $this->present()->name,
        ];
    }
}
