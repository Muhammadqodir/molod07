<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category' => $this->category,
            'type' => $this->type,
            'cover' => $this->cover ? asset('storage/' . $this->cover) : null,
            'address' => $this->address,
            'settlement' => $this->settlement,
            'start' => $this->start?->format('Y-m-d'),
            'end' => $this->end?->format('Y-m-d'),
            'supervisor_name' => $this->supervisor_name,
            'supervisor_l_name' => $this->supervisor_l_name,
            'supervisor_phone' => $this->supervisor_phone,
            'supervisor_email' => $this->supervisor_email,
            'docs' => $this->docs,
            'images' => $this->formatImages(),
            'videos' => $this->formatVideos(),
            'web' => $this->web,
            'telegram' => $this->telegram,
            'vk' => $this->vk,
            'roles' => $this->roles,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
            ],
        ];
    }

    private function formatImages()
    {
        if (!$this->images) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            return asset('storage/' . $image);
        })->toArray();
    }

    private function formatVideos()
    {
        if (!$this->videos) {
            return [];
        }

        return collect($this->videos)->map(function ($video) {
            return asset('storage/' . $video);
        })->toArray();
    }
}
