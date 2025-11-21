<?php

namespace App\Domains\Post\Services;

use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostMeta;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;

/**
 * Class PostMetaService.
 */
class PostMetaService extends BaseService
{
    /**
     * PostMetaService constructor.
     *
     * @param PostMeta $postmeta
     */
    public function __construct(PostMeta $postmeta)
    {
        $this->model = $postmeta;
    }

    /**
     * @param array $data
     *
     * @return Post
     */
    public function savePageMeta(Post $post, array $data = [])
    {
        // var_dump($data, $post->getFillable());
        $data = array_diff_key($data, array_flip($post->getFillable()));
        $ret = $this->loopInsertPageMeta($post->id, $data);

        return $ret;
    }

    private function loopInsertPageMeta(int $post_id, array $data, $update_flag = false)
    {
        $ret = [];
        foreach ($data as $keyMeta => $value) {
            $is_file_upload_input = false;
            if ($value instanceof UploadedFile) {
                $value = Storage::disk(config('filesystems.default'))->putFile('images/post', $value, 'public');
                $is_file_upload_input = true;
            }
            if ($value) {
                $uploadedKeys = [];
                if (is_array($value)) {
                    $newValue = [];
                    foreach ($value as $key => $row) {
                        $newRow = [];
                        foreach ($row as $keyInside => $val1) {
                            if ($val1 instanceof UploadedFile) {
                                $uploadedKeys[] = "$key$keyInside";
                                $val1 = Storage::disk(config('filesystems.default'))->putFile('images/post', $val1, 'public');
                            }
                            $newRow[$keyInside] = $val1;
                        }
                        $newValue[$key] = $newRow;
                    }
                    $value = $newValue;
                }
                // dd($value, $newValue, $keyMeta);
                $meta = $this->model::where('post_id', $post_id)
                    ->where('key', $keyMeta)
                    ->first();
                // dd($post_id, $keyMeta,$meta);
                if ($meta) {
                    $metaValue = json_decode($meta->value, true);

                    if ($meta->key == 'section_0_period') {
                        $meta->value = $value;
                    } else {
                        if (is_array($metaValue)) {
                            // dd('meta1', $metaValue);
                            $existedValue = [];
                            foreach ($metaValue as $keyRow => $row) {
                                $newRow = [];
                                foreach ($row as $key1 => $val1) {
                                    // dd($val1);

                                    if (strpos($val1, 'http') !== false) {
                                        $newRow[$key1] = $val1;
                                    }

                                    if (Str::contains($val1, ['<p', '<ul', '<ol', '<div', '#'])) {
                                        continue;
                                    }

                                    if (Storage::disk(config('filesystems.default'))->exists($val1)) {
                                        if (in_array($key1, $uploadedKeys)) {
                                            Storage::disk(config('filesystems.default'))->delete($val1);
                                        } else {
                                            $newRow[$key1] = $val1;
                                        }
                                    }
                                }
                                $existedValue[] = $newRow;
                            }
                        } else {
                            // if (Storage::disk(config('filesystems.default'))->exists($meta->value) && $value) {
                            //     Storage::disk(config('filesystems.default'))->delete($meta->value);
                            // }
                        }
                        // dd('meta', $value,$existedValue, $meta);
                        if (isset($existedValue) && is_array($value)) {
                            for ($i = 0; $i < count($value); $i++) {
                                if (isset($existedValue[$i])) {
                                    $value[$i] = $value[$i] + $existedValue[$i];
                                }
                            }
                            ksort($value);
                            $value = array_values($value);
                        }
                        // dd('meta2', $value, $existedValue, $meta);
                        $meta->value = is_string($value) ? $value : json_encode($value);
                    }

                    $meta->save();
                } else {
                    // dd($value);
                    $insertData = [
                        'post_id' => $post_id,
                        'key' => $keyMeta,
                        'value' => is_string($value) ? $value : json_encode($value),
                        'type' => is_string($value) ? 'text' : 'repeater',
                        'section' => (substr($keyMeta, 0, 7) == 'section') ? substr($keyMeta, 0, 9) : null,
                    ];
                    $ret[] = $this->model::create($insertData);
                }
            } else {
                // When value is null set meta to empty string or [] for repeater

                if (! $update_flag) {
                    continue; // On create no need update null value
                }

                $meta = $this->model::where('post_id', $post_id)
                    ->where('key', $keyMeta)
                    ->first();
                if ($meta) {
                    if ($is_file_upload_input) {
                        // No need update meta on file upload type
                        continue;
                    }

                    json_decode($meta->value);
                    if (
                        json_last_error() === JSON_ERROR_NONE &&
                        ! is_numeric($meta->value) // To avoid number treated as json
                    ) {
                        $meta->value = '[]';
                    } else {
                        $meta->value = '';
                    }
                    $meta->save();
                }
            }
        }
        // dd($ret);
        return $ret;
    }

    /**
     * @param array $data
     *
     * @return Post
     */
    public function updatePageMeta(Post $post, array $data = [])
    {
        // var_dump($data, $post->getFillable());
        $data = array_diff_key($data, array_flip($post->getFillable()));
        // dd($data);
        $ret = $this->loopInsertPageMeta($post->id, $data, true);
        $this->deleteUnsetPageMeta($post, array_keys($data));

        return $ret;
    }

    private function deleteUnsetPageMeta($post, $keys)
    {
        $find = $this->model::where('post_id', $post->id)
            ->whereNotIn('key', $keys);
        $findAll = $find->get();
        foreach ($findAll as $row) {
            json_decode($row->value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->model::find($row->id)->delete();
            }
        }
    }

    // New Meta for XP Prepain
    //

    /**
     * @param array $data
     *
     * @return Post
     */
    public function updatePageMetaV2(Post $post, array $data = [])
    {
        unset($data['_token']);
        unset($data['_method']);
        unset($data['title']);
        unset($data['title_en']);
        unset($data['status']);
        unset($data['published_at']);
        unset($data['parent']);
        unset($data['tags']);
        unset($data['tags_id']);
        unset($data['featured']);
        unset($data['featured_image']);
        unset($data['featured_image_remove']);
        unset($data['page']);
        $data = array_diff_key($data, array_flip($post->getFillable()));
        $ret = $this->loopInsertPageMetaV2($post, $data, true);
        return $ret;
    }

    private function loopInsertPageMetaV2(Post $post, array $data, $update_flag = false)
    {
        $ret = [];
        foreach ($data as $keyName => $fields) {
//            try {
            foreach ($fields as $keyMeta => $value) {
                if (isJson($value)) {
                    $lastWord = explode("_", $keyMeta);
                    if ($keyName != 'core_values' && end($lastWord) == 'en') {
                        $data = new \stdClass();
                        $metaData = json_decode($value, true);
                       
                        foreach ($metaData as $key => $item) {
                            $keysToSearch = ['image', 'logo', 'picture', 'thumbnail_image', 'image_mobile', 'image_desktop', 'mision_image', 'visson_image', 'image1', 'image2','icon'];
                            if (is_array($item)) {
                                $filteredArray = array_filter($item, function ($key) use ($keysToSearch) {
                                    return in_array($key, $keysToSearch);
                                }, ARRAY_FILTER_USE_KEY);
                    
                                foreach ($filteredArray as $filteredKey => $filteredValue) {
                                    $keyMetaUpdated = implode("_", array_merge(array_slice(explode("_", $keyMeta), 0, -1), ['id']));
                                    
                                    $metaDataId = $this->model::where('post_id', $post->id)
                                        ->where('key', $keyMetaUpdated)
                                        ->where('section', $keyName)
                                        ->first();
                                    
                                    if ($metaDataId) {
                                        $metaId = json_decode($metaDataId->value, true);
                                        foreach ($metaId as $keyId => $itemid) {
                                            // $wordToCheck = 'images/post';
                                            // if(isset($itemid[$filteredKey])  && strpos($itemid[$filteredKey], $wordToCheck) !== false){
                                                $metaData[$keyId][$filteredKey] = $metaId[$keyId][$filteredKey];
                                            // }
                                        }
                                    }
                                }
                            }
                        }
                        $value = json_encode($metaData);
                    }
                }
                $value = $value ?? "";
                $meta = $this->model::where('post_id', $post->id)
                    ->where('key', $keyMeta)
                    ->where('section', $keyName)
                    ->first();
                if ($meta) {
                    $meta->value = $value;
                    $meta->save();
                } else {
                    $insertData = [
                        'post_id' => $post->id,
                        'key' => $keyMeta,
                        'value' => $value,
                        'type' => is_numeric($value) ? 'text' : (isJson($value) ? 'repeater' : 'text'),
                        'section' => $keyName,
                    ];
                    $this->model::create($insertData);
                }
            }
//            } catch (\Exception $exception){
//                dd($fields);
//            }
        }

        return $post;
    }
}
