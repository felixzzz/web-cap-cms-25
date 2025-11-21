@props(['active' => '', 'text' => '', 'hide' => false, 'icon' => false, 'permission' => false])

@if ($permission)
    @if ($logged_in_user->can($permission))
        @if (!$hide)
            <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }}">
                @if ($icon)
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <i class="{{ $icon }}"></i>
                        </span>
                    </span>
                @else
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                @endif
                <span class="menu-title">{{ strlen($text) ? $text : $slot }}</span>
            </a>
        @endif
    @endif
@else
    @if (!$hide)
        <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }}">
            <!-- UDAH GA KEPAKE BASED ON VD -->
            {{-- @if ($icon)
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2">
                        <i class="{{ $icon }}"></i>
                    </span>
                </span>
            @else
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
            @endif --}}
            <span class="menu-title">{{ strlen($text) ? $text : $slot }}</span>
        </a>
    @endif
@endif
