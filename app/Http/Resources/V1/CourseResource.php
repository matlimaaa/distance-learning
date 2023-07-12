<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uui' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'date' => Carbon::make($this->created_at)->format('Y-m-d')
        ];
    }
}
