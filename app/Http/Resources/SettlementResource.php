<?php

namespace App\Http\Resources;

use App\Services\Presenter\Contracts\Presentable;
use App\Services\Presenter\Presenters\SettlementPresenter;
use App\Services\Presenter\Traits\HasPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class SettlementResource extends JsonResource implements Presentable
{
    use HasPresenter;

    # Presenter

    protected $presenter = SettlementPresenter::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key'             => (int) $this->id_asenta_cpcons,
            'name'            => $this->present()->name,
            'zone_type'       => $this->present()->zone_type,
            'settlement_type' => $this->tipo,
        ];
    }
}
