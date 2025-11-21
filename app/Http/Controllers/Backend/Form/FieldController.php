<?php

namespace App\Http\Controllers\Backend\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Form\StoreFieldRequest;
use App\Http\Requests\Backend\Form\UpdateFieldRequest;
use App\Models\Form\Field;
use App\Models\Form\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FieldController extends Controller
{
    public function indexForm(Form $form)
    {
        Gate::authorize('admin.access.form-fields.show');
        return view('backend.form.fields.index', compact('form'));
    }

    public function createForm(Request $request, Form $form)
    {
        Gate::authorize('admin.access.form-fields.create');
        if ($request->has('type')) {
            $type = $request->type;
            if ($type === 'input') {
                return view('backend.form.fields.create.input', compact('form', 'type'));
            } else if ($type === 'select') {
                return view('backend.form.fields.create.select', compact('form', 'type'));
            } else if ($type === 'textarea') {
                return view('backend.form.fields.create.textarea', compact('form', 'type'));
            }
        }
    }

    public function storeForm(StoreFieldRequest $request, Form $form)
    {
        $create = $form->field()->create($request->except(['options']));
        if ($create) {
            if ($request->has('options')) {
                $create->update([
                    'options' => json_encode($this->parser_options($request)),
                ]);
            }
        }
        return redirect()->route('admin.form.field', $form)->withFlashSuccess('The field has been created');
    }

    public function showForm(Field $field)
    {
        Gate::authorize('admin.access.form-fields.show');
        $model = $field;
        return view('backend.form.fields.show', compact('model'));
    }

    public function editForm(Field $field)
    {
        Gate::authorize('admin.access.form-fields.edit');
        if ($field->type === 'input') {
            return view('backend.form.fields.edit.input', compact('field'));
        } else if ($field->type === 'select') {
            $options = $this->decode_options($field);
            return view('backend.form.fields.edit.select', compact('field', 'options'));
        } else if ($field->type === 'textarea') {
            $options = $this->decode_options($field);
            return view('backend.form.fields.edit.textarea', compact('field', 'options'));
        }
    }

    public function updateForm(UpdateFieldRequest $request, Field $field)
    {
        if ($field->type === 'input') {
            $field->update($request->validated());
        } else if ($field->type === 'select') {
            $field->update($request->except('options'));
            if ($request->has('options')) {
                $field->update([
                    'options' => json_encode($this->parser_options($request))
                ]);
            }
        } else if ($field->type === 'textarea') {
            $field->update($request->except('options'));
            if ($request->has('options')) {
                $field->update([
                    'options' => json_encode($this->parser_options($request))
                ]);
            }
        }

        return back()->withFlashSuccess('The field has been updated');
    }

    public function deleteForm(Field $field)
    {
        Gate::authorize('admin.access.form-fields.delete');
        $field->delete();
        return back()->withFlashSuccess('The field has been deleted');
    }

    /**
     * @param StoreFieldRequest $request
     * @return array
     */
    public function parser_options(Request $request)
    {
        if ($request->type === 'select') {
            $arr = [];
            $parser = explode(PHP_EOL, $request->options);
            foreach ($parser as $item) {
                $data = explode(':', $item);
                $arr[] = [
                    'key' => trim($data[0]),
                    'value' => trim($data[1])
                ];

            }
            return $arr;
        } else {
            return $request->options;
        }

    }

    /**
     * @param Field $field
     * @return string
     */
    public function decode_options(Field $field)
    {
        if ($field->type === 'select') {
            $arr = [];
            foreach ($field->options as $item) {
                $arr[] = $item->key . ':' . $item->value;
            }
            $data = collect($arr);
            return $data->implode(PHP_EOL);
        } else {
            return collect($field->options);
        }
    }
}
