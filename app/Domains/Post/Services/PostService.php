<?php

namespace App\Domains\Post\Services;

use App\Domains\Post\Http\Requests\StorePostRequest;
use App\Domains\Post\Models\Post;
use App\Services\Antikode\CrudService;
use Auth;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

class PostService extends CrudService
{
    public function change_post_status(Post $post): void
    {
        if ($post->post_status === 'draft' || $post->post_status === 'review') {
            $status = 'publish';
        } else {
            $status = 'draft';
        }
        $post->update([
            'post_status' => $status
        ]);
    }

    /**
     * @param StorePostRequest $request
     * @return void
     */
    public function create_post_handler($data, $type = null) : Post
    {
        $data['type'] = $type;
        $data['slug'] = isset($data['slug']) && $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $maxSortValue = Post::where('type', $type)
        ->max('sort');
        $newSortValue = $maxSortValue !== null ? $maxSortValue + 1 : 1;
        $data['sort'] = $newSortValue;
        $post = Post::create(array_merge(['user_id' => Auth::user()->id], $data));
        if ($post) {
			if (isset($data['categories'])) {
				$post->category()->sync($data['categories']);
			}

            if (isset($data['featured_image'])) {
                $this->filepond_resolver($data['featured_image'], 'featured_image', $post);
            }
            if (isset($data['tags']) || isset($data['tags_id'])) {
                $this->sync_tags($data['tags'], $data['tags_id'] ?? [], $post);
            }
        }

        return $post;
    }

    /**
     * @param Post $post
     * @param StorePostRequest $request
     * @return void
     */
    public function update_post_handler(Post $post, $data) : Post
    {
        if (isset($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        $update = $post->update($data);
        if ($update) {
			if (isset($data['categories'])) {
				$post->category()->sync($data['categories']);
			}

            if (isset($data['featured_image'])) {
                $this->filepond_resolver($data['featured_image'], 'featured_image', $post);
            }

            if (isset($data['tags'])) {
                $this->sync_tags($data['tags'],$data['tags_id'], $post);
            }
        }
        return $post;
    }

    /**
     * @param StorePostRequest $request
     * @return \Illuminate\Support\Collection
     */
    protected function parsingTags(StorePostRequest $request): \Illuminate\Support\Collection
    {
        $decode = collect(json_decode($request->tags));
        $map = $decode->map(function ($val) {
            return $val->value;
        });
        return $map;
    }

    /**
     * @param $post
     * @param string $collection
     * @param string $key
     * @return void
     */
    protected function add_media_from_request($post, string $collection, string $key): void
    {
        if ($post->getFirstMedia($collection) != null) {
            $post->clearMediaCollection($collection);
        }
        $post->addMediaFromRequest($key)
            ->toMediaCollection($collection);
    }

    /**
     * @param $tagsInput
     * @param $post
     * @return void
     */
    protected function sync_tags($tagsInput, $tagsInputID, $post): void
    {
        // Decode the JSON inputs
        $decodedTags = collect(json_decode($tagsInput));
        $decodedTagsID = collect(json_decode($tagsInputID));

        // Map tag names and IDs
        $tags = $decodedTags->map(function ($val) {
            return $val->value; // Extract tag names
        })->toArray();

        // Prepare translations based on index
        $translations = $decodedTagsID->map(function ($val, $index) use ($decodedTags) {
            $tagName = $decodedTags[$index]->value; // Original tag name
            return [
                'name' => $tagName,
                'locale' => 'id',
                'translation' => $val->value
            ];
        })->toArray();

        // Create or find tags and set translations
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName, // Use default name initially
            ]);

            // Set translations for the tag
            foreach ($translations as $translation) {
                if ($translation['name'] === $tagName) {
                    $tag->setTranslation('name', $translation['locale'], $translation['translation']);
                }
            }

            // Save tag after setting translations
            $tag->save();
        }
        // Sync the tags with the post
        $post->syncTags($tags);
    }

    private function detachOldTags($post, $newTagNames)
    {
        // Get current tag IDs
        $currentTagIds = $post->tags->pluck('id')->toArray();

        // Find IDs of new tags
        $newTagIds = Tag::whereIn('name', $newTagNames)->pluck('id')->toArray();

        // Detach tags that are not in the new list
        $tagsToDetach = array_diff($currentTagIds, $newTagIds);

        if (!empty($tagsToDetach)) {
            $post->tags()->whereIn('id', $tagsToDetach)->detach();
        }
    }
}
