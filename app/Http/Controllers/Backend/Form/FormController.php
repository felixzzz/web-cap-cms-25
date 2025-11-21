<?php

namespace App\Http\Controllers\Backend\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Form\StoreFormRequest;
use App\Http\Requests\Backend\Form\UpdateFormRequest;
use App\Models\Form\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FormController extends Controller
{
    public function index()
    {
        Gate::authorize('admin.access.forms.show');
        return view('backend.form.index');
    }

    public function create()
    {
        Gate::authorize('admin.access.forms.create');
        return view('backend.form.create');
    }

    public function store(StoreFormRequest $request)
    {
        Form::create($request->validated());
        return redirect()->route('admin.form')->withFlasshSuccess('Form has been created successfully');
    }

    public function update(UpdateFormRequest $request, Form $form)
    {
        $form->update($request->validated());
        return back()->withFlashSuccess('Form has been updated successfully');
    }

    public function edit(Form $form)
    {
        Gate::authorize('admin.access.forms.edit');
        return view('backend.form.edit', compact('form'));
    }

    public function show(Form $form)
    {
        Gate::authorize('admin.access.forms.show');
        $form->loadCount('field');
        return view('backend.form.show', compact('form'));
    }

    public function destroy(Form $form)
    {
        Gate::authorize('admin.access.forms.delete');
        $form->delete();
        return back()->withFlashSuccess('Form has been deleted successfully');
    }
}
