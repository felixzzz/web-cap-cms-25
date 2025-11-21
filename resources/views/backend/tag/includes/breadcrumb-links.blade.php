@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="btn btn-sm btn-light btn-active-danger" :href="route('admin.tag.deleted')" :text="__('Trashes')" />
@endif
