<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user' => $this->whenLoaded('user', UserDetail::make($this->user)),
            'total_price' => $this->getPrice(),
            'plate_number' => $this->plate_number,
            'paid_at' => $this->paid_at,
            'spot' => $this->whenLoaded('spot', SpotResource::make($this->spot)),
        ];
    }
}
