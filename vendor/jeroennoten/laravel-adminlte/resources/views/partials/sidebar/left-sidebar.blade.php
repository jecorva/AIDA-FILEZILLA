<?php

use App\Models\MenuPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
$typeEmployee = Session::get('type_employee');
?>

<?php

$menus = MenuPermission::select(
    'menu.id',
    'menu.name',
    'menu.key',
    'menu.icon'
)   ->join('menu_items', 'menu_items.id', '=', 'menu_permissions.menu_item_id')
    ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
    ->where('menu_permissions.user_id', Auth::user()->id)
    ->where('menu_permissions.permission', 1)
    ->groupBy('menu.id')
    ->groupBy('menu.name')
    ->groupBy('menu.key')
    ->groupBy('menu.icon')
    ->orderBy('menu.order', 'ASC')
    ->get();

$myMenus = MenuPermission::select(
    'items.name As Submenu',
    'items.key as KeySubmenu',
    'items.link_to_access',
    'menu_items.menu_id'
)->join('menu_items', 'menu_items.id', '=', 'menu_permissions.menu_item_id')
    ->join('items', 'items.id', '=', 'menu_items.item_id')
    ->where('menu_permissions.user_id', Auth::user()->id)
    ->where('menu_permissions.permission', 1)
    ->get();
?>

<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <!-- <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav> -->

        <nav class="pt-2 fs-14">
        <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}" data-widget="treeview" role="menu" @if(config('adminlte.sidebar_nav_animation_speed') !=300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif @if(!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
            <li class="nav-item">
                <a class="nav-link active" href="/home">
                    <i class="fas fa-tachometer-alt "></i>
                    <p class="ml-2">
                        Bienvenidos
                    </p>
                </a>
            </li>
            @foreach( $menus as $menu )
            <li id="{{ $menu->key }}" class="nav-item has-treeview">
                <a class="nav-link title" href="">
                <?php echo $menu->icon; ?>
                    <p class="ml-2">
                        {{ $menu->name }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach( $myMenus as $myMenu )
                        @if( $myMenu->menu_id == $menu->id )
                        <?php $href = $myMenu->link_to_access; ?>
                        <li class="nav-item">
                            <a id="{{ $myMenu->KeySubmenu }}" class="nav-link" href="{!! url($href) !!}">
                                <i class="fas fa-link ml-3"></i>
                                <p class="ml-2">
                                    {{ $myMenu->Submenu }}
                                </p>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </nav>

    </div>

</aside>
