<?php

namespace App\Services\Antikode;

use App\Models\Extra\TemporaryUpload;
use File;
use Illuminate\Support\Facades\DB;

class CrudService
{
    public function filepond_resolver($id, $targetCollection, $model)
    {
        $temp = TemporaryUpload::where('id', $id)->first();
        if ($temp) {
            $move = $temp->getFirstMedia()->move($model, $targetCollection);
            if ($move) {
                $temp->delete();
            }
            return true;
        }
        return false;
    }

}
