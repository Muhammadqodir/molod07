<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GrantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category' => $this->category,
            'cover' => $this->cover ? asset('storage/' . $this->cover) : null,
            'address' => $this->address,
            'settlement' => $this->settlement,
            'deadline' => $this->deadline?->format('Y-m-d'),
            'conditions' => $this->conditions,
            'requirements' => $this->requirements,
            'reward' => $this->reward,
            'docs' => $this->formatDocs(),
            'web' => $this->web,
            'telegram' => $this->telegram,
            'vk' => $this->vk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
            ],
        ];
    }

    private function formatDocs()
    {
        if (!$this->docs) {
            return [];
        }

        return collect($this->docs)->map(function ($doc) {
            return asset('storage/' . $doc);
        })->toArray();
    }
}
