<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => '#' . $this->id,
            'name' => $this->name,
            'user_name' => 'Ari.patin',
            'email' => $this->email,
            'adress_line_1' => 'Lorem ipsum delore viapa',
            'status' => ['name' => 'Active', 'color' => 'iq-bg-warning'],
            'phone' => '+129 637 335 222',
            'role' =>  $this->role ? $this->role->name : '--',
            'account' => $this->account ? $this->account->name : '--',
            'created_at' => $this->todate($this->created_at),
            'action' => [ 'link' => '<a data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" href="#"  v-on:click="edit()"><i class="ri-pencil-line"></i></a><a data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" href="#" @click="remove()"><i class="ri-delete-bin-line"></i></a>' ],
            'editable' => false
        ];
    }

    public function toDate($date) {
        $timestamp = date_create($date);
        return date_format($timestamp , 'Y-m-d');
    }
}
