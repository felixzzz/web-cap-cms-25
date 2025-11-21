@if (!$model->isAdmin())
    <x-utils.view-button :href="route('admin.role.show', $model)" />
    <x-utils.edit-button :href="route('admin.role.edit', $model)" />
    <x-utils.delete-button :href="route('admin.role.destroy', $model)" />
@endif
