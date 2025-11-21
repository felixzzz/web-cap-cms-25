@if ($logged_in_user->can('admin.access.form-fields.show'))
    <x-utils.view-button :href="route($route.'.show', $model)" />
@endif
@if ($logged_in_user->can('admin.access.form-fields.edit'))
    <x-utils.edit-button :href="route($route.'.edit', $model)" />
@endif
@if ($logged_in_user->can('admin.access.form-fields.delete'))
    <x-utils.delete-button :href="route($route.'.destroy', $model)" />
@endif
