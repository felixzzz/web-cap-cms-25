<?php

namespace App\Traits;

use App\Models\Option;

trait Options
{
    protected $opt;

    public function __construct(Option $opt)
    {
        $this->opt = $opt;
    }

    public function save_options_array($keys): bool
    {
        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->opt->updateOrCreate([
                    'option_name' => $key
                ], [
                    'option_value' => $value
                ]);
            }
            $this->opt::refreshCache();
            return true;
        }
        return false;
    }

    public function get_option_value($keys)
    {
        if (is_array($keys)) {
            $arr = [];
            return $this->opt->whereIn('option_name', $keys)->get()->keyBy('option_name') // key every setting by its name
            ->transform(function ($setting) {
                return $setting->option_value;
            })->toArray();
        } else {
            $option = $this->opt->where('option_name', $keys)->first();
            if ($option) {
                return $option->value;
            }
            return null;
        }
    }
}
