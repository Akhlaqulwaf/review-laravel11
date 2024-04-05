<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    public static $wrap = 'candidate';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->email,
            'job_id' => new JobResource($this->job)
        ];
    }

    public static function collection($resource)
    {
        return ['candidate' => $resource->map(function ($resource){
            return new CandidateResource($resource);
        })];
    }
}
