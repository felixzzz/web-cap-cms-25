@if ($logged_in_user->hasAllAccess())
    <x-utils.view-button :href="route('admin.tag.show', $model)" />
    <x-utils.edit-button :href="route('admin.tag.edit', $model)" />
@endif

@if ($logged_in_user->hasAllAccess())
    <x-utils.delete-button :href="route('admin.tag.destroy', $model)" />
@endif
