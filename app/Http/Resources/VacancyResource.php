<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'salary_from' => $this->salary_from,
            'salary_to' => $this->salary_to,
            'salary_negotiable' => $this->salary_negotiable,
            'salary_range' => $this->getSalaryRange(),
            'type' => $this->type,
            'experience' => $this->experience,
            'org_name' => $this->org_name,
            'org_phone' => $this->org_phone,
            'org_email' => $this->org_email,
            'org_address' => $this->org_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
            ],
        ];
    }
}
