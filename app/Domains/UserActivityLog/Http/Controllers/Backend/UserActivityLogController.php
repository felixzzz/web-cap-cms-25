<?php

namespace App\Domains\UserActivityLog\Http\Controllers\Backend;

use App\Domains\Post\Http\Requests\StorePostRequest;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\PostService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class UserActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')
            ->whereHas('causer',function ($q){
                $q->when(request()->query('search'),function ($q){
                    $search = request()->query('search');
                    $q->where('name','LIKE',"%{$search}%")
                        ->orWhere('email','LIKE',"%{$search}%");
                });
            })
            ->latest('created_at')->paginate();
        $data = json_decode(ActivityResource::collection($query)->toJson());
        return view('backend.user-activity-log.index',compact('data', 'query'));
    }

    public function show(Activity $activity)
    {
        $activity->properties->toArray();
        return view('backend.user-activity-log.show',compact('activity'));
    }
}
