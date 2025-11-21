<?php

namespace App\Http\Resources;

use App\Domains\Auth\Models\User;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostType;
use App\Domains\PostCategory\Models\Category;
use App\Models\Form\Field;
use App\Models\Form\Form;
use App\Models\Option;
use App\Models\User\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $properties = $this->properties->toArray();
        $description = $this->causer?->name.' ';
        if ($this->log_name !== 'role') {
            $description .= $this->description.' ';
            switch ($this->subject) {
                case $this->subject instanceof Post :
                    $description .= $this->subject?->type.' of '.$this->subject?->title;
                    break;
                case $this->subject instanceof User :
                    if (isset($properties['attributes'])) {
                        if (count($properties['attributes']) > 1) {
                            $description .= 'user detail of '.$this->subject?->name;
                        } else {
                            if (isset($properties['attributes']['last_login_at'])) {
                                $description = $this->causer?->name.' authenticated';
                            } else {
                                $description .= 'user detail of '.$this->subject?->name;
                            }
                        }
                    } else {
                        $description .= 'user detail of '.$this->subject?->name;
                    }
                    break;
                case $this->subject instanceof PostType :
                    $description .= 'post type detail of '.$this->subject?->name;
                    break;
                case $this->subject instanceof Category :
                    $description .= 'category detail of '.$this->subject?->name;
                    break;
                case $this->subject instanceof Form :
                    $description .= 'form of '.$this->subject?->name;
                    break;
                case $this->subject instanceof Field :
                    $description .= 'field of '.$this->subject?->name.' on form '.$this->subject?->form?->name;
                    break;
                case $this->subject instanceof Option :
                    $description .= 'setting '.$this->subject?->option_name;
                    break;
                default:
                    $description .= $this->subject?->name;
            }

        } else {
            $description = $this->description;
        }

        return [
            'id' => $this->id,
            'formatted' => $description,
            'created_at' => $this->created_at->format('jS F Y H:i a'),
        ];
    }
}
