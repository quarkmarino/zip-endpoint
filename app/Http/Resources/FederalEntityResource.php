<?php

namespace App\Http\Resources;

use App\Services\Presenter\Contracts\Presentable;
use App\Services\Presenter\Presenters\FederalEntityPresenter;
use App\Services\Presenter\Traits\HasPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class FederalEntityResource extends JsonResource implements Presentable
{
    use HasPresenter;

    # Presenter

    protected $presenter = FederalEntityPresenter::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key'  => (int) $this->c_estado,
            'name' => $this->present()->name,
            'code' => null,
        ];
    }
}
