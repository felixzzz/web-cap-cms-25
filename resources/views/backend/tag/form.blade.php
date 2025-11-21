<x-forms.text-input
    name="name"
    label="{{ ucfirst('name') }}"
    placeholder="{{ ucfirst('name') }}"
    value="{{ old('name') ?? (isset($model) ? $model->name : '') }}"
    isSide="1"
    text=""
    required="1"/>


