@if ($general->where('type', $type)->count())
    <h5 class="mb-3">@lang('General Permissions')</h5>

    <div class="row">
        <div class="col">
            @foreach($general->where('type', $type) as $permission)
                <span class="d-block">
                        <input type="checkbox" name="permissions[]" {{ in_array($permission->id, $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->id }}" id="{{ $permission->id }}" />
                        <label for="{{ $permission->id }}">{{ $permission->description ?? $permission->name }}</label>
                    </span>
            @endforeach
        </div><!--col-->
    </div><!--row-->
@endif

@if ($general->where('type', $type)->count() && $categories->where('type', $type)->count())
    <hr/>
@endif

@if ($categories->where('type', $type)->count())
    @foreach($categories->where('type', $type) as $permission)
        @php
            $name = str_replace(['manage', 'Manage', 'all', 'All'], '', $permission->description);
        @endphp
        <h5 class="my-3">{{ ucwords($name) }}</h5>
        <ul class="permission-tree m-0 p-0 list-unstyled">
            <li>
                <input type="checkbox" name="permissions[]" {{ in_array($permission->id, $usedPermissions ?? [], true) ? 'checked' : '' }} value="{{ $permission->id }}" id="{{ $permission->id }}" />
                <label for="{{ $permission->id }}">{{ $permission->description ?? $permission->name }}</label>

                @if($permission->children->count())
                    @include('backend.auth.role.includes.children', ['children' => $permission->children])
                @endif
            </li>
        </ul>
    @endforeach
@endif

@if (!$general->where('type', $type)->count() && !$categories->where('type', $type)->count())
    <p class="mb-0"><em>@lang('There are no additional permissions to choose from for this type.')</em></p>
@endif
