<div class="aside-menu flex-column-fluid">
    
    <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
        data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px" style="height: 237px;">
        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="kt_aside_menu" data-kt-menu="true">
            @php
                $userPermissions = auth()
                    ->user()
                    ->userPermissions();
                $user = auth()->user();
                $isSuperAdmin = $user->roles->isNotEmpty() &&
                    $user->roles->first()->name === 'Administrator' &&
                    $user->roles->first()->type === 'admin';
            @endphp
            @foreach ($menus as $menu)
                @php
                     $showMenu = $isSuperAdmin || !$menu->permissions?->count() ||
                        in_array($menu->permissions->first()?->name, $userPermissions);
                @endphp
                @if ($showMenu)
                    @if ($menu->children?->count() === 0)
                        <div class="menu-item ">
                            <x-utils.menu-link :href="$menu->url" class="menu-link" :active="activeClass(url()->current() === $menu->url, 'active')"
                                :text="__($menu->title)" />
                        </div>
                    @else

                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            @php($active = $menu->children->where('url', url()->current())->first())
                            <span class="menu-link">
                                <span class="menu-title">{{ __($menu->title) }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion {{ $active ? 'show' : '' }}">
                                @foreach ($menu->children as $child)
                                    <div class="menu-item">
                                        <x-utils.menu-link :href="$child->url" class="menu-link" :text="__($child->title)"
                                                           :active="url()->current() === $child->url ? 'active' : ''" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            @if (\App\Domains\Post\Models\PostType::count() > 0)
                @foreach (\App\Domains\Post\Models\PostType::loop() as $item)
                    @php(
                        $menuArray = [
                            route('admin.post.index', $item->slug),
                            route('admin.category.index', $item->slug),
                        ]
                    )
                    @php(
                        $activeMenu = in_array(url()->current(), $menuArray)
                    )
                    @if ($item->is_category)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                    <span class="menu-title">{{ __($item->name) }}</span>
                                    <span class="menu-arrow"></span>
                                </span>
                            <div class="menu-sub menu-sub-accordion {{ $activeMenu ? 'show' : '' }}">
                                <div class="menu-item">
                                    <x-utils.menu-link :href="route('admin.post.index', $item->slug)" class="menu-link" :text="__('All '.$item->name)" active="{{ url()->current() === route('admin.post.index', $item->slug) ? 'active' : '' }}" />
                                </div>
                                <div class="menu-item">
                                    <x-utils.menu-link :href="route('admin.category.index', $item->slug)" class="menu-link" :text="__('Category')" active="{{url()->current() === route('admin.category.index', $item->slug) ? 'active' : ''}}" />
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="menu-item ">
                            <x-utils.menu-link :href="route('admin.post.index', $item->slug)" class="menu-link" :active="activeClass(url()->current() === route('admin.post.index', $item->slug), 'active')"
                                               :text="__($item->name)" />
                        </div>
                    @endif
                @endforeach
            @endif
            @if ($logged_in_user->hasAllAccess() ||($logged_in_user->can('admin.access.user.list') ||
                                    $logged_in_user->can('admin.access.user.deactivate') ||
                                    $logged_in_user->can('admin.access.user.reactivate') ||
                                    $logged_in_user->can('admin.access.user.clear-session') ||
                                    $logged_in_user->can('admin.access.user.impersonate') ||
                                    $logged_in_user->can('admin.access.user.change-password')))
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.role.*'), 'here show') }}">
                    <span class="menu-link">
                        <span class="menu-title">Admin & Role</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if (
                            $logged_in_user->hasAllAccess() ||
                                ($logged_in_user->can('admin.access.user.list') ||
                                    $logged_in_user->can('admin.access.user.deactivate') ||
                                    $logged_in_user->can('admin.access.user.reactivate') ||
                                    $logged_in_user->can('admin.access.user.clear-session') ||
                                    $logged_in_user->can('admin.access.user.impersonate') ||
                                    $logged_in_user->can('admin.access.user.change-password')))
                            <div class="menu-item">
                                <x-utils.menu-link :href="route('admin.auth.user.index')" class="menu-link" :text="__('User Management')"
                                    :active="activeClass(Route::is('admin.auth.user.*'), 'active')" />
                            </div>
                            @if ($logged_in_user->hasAllAccess())
                            <div class="menu-item">
                                <x-utils.menu-link :href="route('admin.role.index')" class="menu-link" :text="__('Role Management')"
                                    :active="activeClass(Route::is('admin.role.*'), 'active')" />
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

            @endif


        </div>
        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="kt_aside_menu" data-kt-menu="true">
            @if ($logged_in_user->hasAllAccess())
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ activeClass(Route::is('admin.general.index'), 'here show') }} {{ activeClass(Route::is('admin.smtp.index'), 'here show') }} {{ activeClass(Route::is('admin.posttype.index'), 'here show') }} {{ activeClass(Route::is('admin.general.sidebar-menu.index'), 'here show') }}">
                    <span class="menu-link">
                        <span class="menu-title">Settings</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('admin.general.index')" class="menu-link" :text="__('General')"
                                :active="activeClass(Route::is('admin.general.index'), 'active')" />
                        </div>
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('admin.smtp.index')" class="menu-link" :text="__('SMTP')"
                                :active="activeClass(Route::is('admin.smtp.index'), 'active')" />
                        </div>
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('admin.posttype.index')" class="menu-link" :text="__('Post Types')"
                                               :active="activeClass(Route::is('admin.posttype.index'), 'active')" />
                        </div>
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('admin.general.sidebar-menu.index')" class="menu-link" :text="__('Sidebar Menu')"
                                :active="activeClass(Route::is('admin.general.sidebar-menu.index'), 'active')" />
                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-title">Logs</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('log-viewer::dashboard')" class="menu-link" :text="__('Dashboard')" />
                        </div>
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('log-viewer::logs.list')" class="menu-link" :text="__('Logs')" />
                        </div>
                        <div class="menu-item">
                            <x-utils.menu-link :href="route('admin.user-activity-log.index')" class="menu-link" :text="__('User Activity Log')" />
                        </div>
                    </div>
                </div>
            @endif
            <div class="menu-item ">
                <a href="{{ route('frontend.auth.logout') }}" class="menu-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="menu-title">Log Out</span>
                </a>
            </div>
        </div>
    </div>
</div>
