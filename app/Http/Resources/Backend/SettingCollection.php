<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    $data->key => str_contains($data->value, 'setting/') ? uploaded_file($data->value) : $data->value,
                ];
            }),
        ];
    }
}
