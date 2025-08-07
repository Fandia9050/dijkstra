<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>

                {{-- First Google Link --}}
                @php
                    $item = [
                        'text' => 'Google',
                        'class' => 'nav-link',
                        'href' => 'https://google.com',
                        'icon' => 'fas fa-globe',
                        'target' => '_blank',
                    ];
                @endphp
                @include('adminlte::partials.sidebar.menu-item-link', compact('item'))

                {{-- Second Google Link --}}
                @php
                    $item = [
                        'text' => 'Another Google',
                        'class' => 'nav-link',
                        'href' => 'https://google.com',
                        'icon' => 'fas fa-globe',
                        'target' => '_blank',
                    ];
                @endphp
                @include('adminlte::partials.sidebar.menu-item-link', compact('item'))

                {{-- Third Google Link --}}
                @php
                    $item = [
                        'text' => 'Yet Another Google',
                        'class' => 'nav-link',
                        'href' => 'https://google.com',
                        'icon' => 'fas fa-globe',
                        'target' => '_blank',
                    ];
                @endphp
                @include('adminlte::partials.sidebar.menu-item-link', compact('item'))

            </ul>
        </nav>
    </div>

</aside>
