<?php

namespace App\Domains\Core\Http\Controllers\Backend;

use App\Domains\Core\Http\Requests\Setting\StoreGeneralRequest;
use App\Domains\Core\Http\Requests\Setting\StoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use function view;

class GeneralController extends Controller
{
    public function index()
    {
        return view('backend.core.setting.general');
    }

    public function store(StoreGeneralRequest $request)
    {

        $this->save_options_array($this->getValidated($request));

        return redirect()->route('admin.general.index')->withFlashSuccess(__('The setting has been updated successfully'));
    }

    /**
     * @param StoreRequest $request
     * @return array|\string[][][]
     */
    protected function getValidated($request): array
    {
        if (!$request->has('app.register.admin')) {
            $admin = '0';
        } else {
            $admin = '1';
        }

        if (!$request->has('app.register.user')) {
            $user = '0';
        } else {
            $user = '1';
        }

        $array = $request->validated();
        $array['app']['register']['admin'] = $admin;
        $array['app']['register']['user'] = $user;
        $merge = array_merge($array, $array);
        return Arr::dot($merge);
    }
}
