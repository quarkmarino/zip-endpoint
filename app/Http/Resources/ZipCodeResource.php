<?php

namespace App\Http\Resources;

use App\Http\Resources\FederalEntityResource;
use App\Http\Resources\SettlementResource;
use App\Services\Presenter\Contracts\Presentable;
use App\Services\Presenter\Presenters\ZipCodePresenter;
use App\Services\Presenter\Traits\HasPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class ZipCodeResource extends JsonResource implements Presentable
{
    use HasPresenter;

    # Presenter

    protected $presenter = ZipCodePresenter::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'zip_code'       => $this->d_codigo,
            'locality'       => $this->present()->locality,
            'federal_entity' => $this->relationLoaded('entidadFederativa')
                ? new FederalEntityResource($this->entidadFederativa)
                : new FederalEntityResource($this),
            'settlements'    => SettlementResource::collection($this->asentamientos),
            'municipality'   => $this->relationLoaded('municipio')
                ? new MunicipalityResource($this->municipio)
                : new MunicipalityResource($this),
        ];
    }
}
