<?php

namespace App\Domains\Tag\Services;

use App\Exceptions\GeneralException;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Str;
use Log;
use Spatie\Tags\Tag;

class TagService extends BaseService
{
    /**
     * TagService constructor.
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    /**
     * @param array $data
     * @return Tag
     * @throws GeneralException
     */
    public function create(array $data = []): Tag
    {
        $tag = null;

        try {
            $data['slug'] = Str::slug($data['name']);

            $tag = $this->model::create($data);
        } catch (Exception $e) {
            Log::error(['TagService@create: ', $e]);

            throw new GeneralException(__('There was a problem creating this Tag. Please try again.'));
        }

        return $tag;
    }

    /**
     * @param  Tag  $tag
     * @param  array  $data
     *
     * @return Tag
     * @throws \Throwable
     */
    public function update(Tag $tag, array $data = []): Tag
    {
        try {
            $tag->name = $data['name'];
            $tag->save();
        } catch (Exception $e) {
            Log::error(['TagService@update: ', $e]);

            throw new GeneralException(__('There was a problem updating this Tag. Please try again.'));
        }

        return $tag;
    }
}
