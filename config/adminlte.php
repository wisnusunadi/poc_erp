<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'logo' => '<b>Sinko Prima</b> Alloy',
    'logo_img' => 'assets/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => 'bg-gradient-dark',
    'classes_auth_body' => 'bg-gradient-dark',
    'classes_auth_footer' => 'bg-gradient-dark',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar2' => 'sidebar-dark-primary royal-bg  elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */
    'menu' => [


        [
            'header' => 'Meeting',
            'auth'   => [26, 28,  17, 9, 13, 23, 15, 12, 16, 31, 2, 30, 22, 34],
        ],
        [
            'text' => 'Ruangan Meeting',
            'url' => '/meeting/ruangan',
            'icon' => 'fas fa-door-open',
            'auth'   => [34],
        ],

        [
            'text' => 'Meeting',
            'url' => '/meeting/hr/',
            'icon' => 'fas fa-users',
            'auth'   => [26, 28,  17, 9, 13, 23, 15, 12, 16, 31, 2, 30, 22, 34],
        ],

        [
            'header' => 'Meeting',
            'auth' => [14],
        ],
        [
            'text' => 'Meeting',
            'url' => '/meeting/jadwalmeeting',
            'icon' => 'fas fa-users',
            'auth' => [14],
        ],

        [
            'text' => 'Ubah Password',
            'icon' => 'fa-solid fa-gear',
            'url' => '/edit_pwd',
            'auth'   => [26, 28,  17, 9, 13, 23, 15, 12, 16, 31, 2, 30, 14, 22, 34],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */

    'filters' => [
        App\Menu\Filters\GateFilter::class,
        App\Menu\Filters\HrefFilter::class,
        App\Menu\Filters\SearchFilter::class,
        App\Menu\Filters\ActiveFilter::class,
        App\Menu\Filters\ClassesFilter::class,
        App\Menu\Filters\LangFilter::class,
        App\Menu\Filters\DataFilter::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */
    'plugins' => [
        // core plugins
        'FontAwesome' => [
            'css' => [
                'vendor/fontawesome-free/css/all.min.css',
            ]
        ],
        'jQuery' => [
            'js' => [
                'vendor/jquery/jquery.min.js',
            ],
        ],
        'BootStrap' => [
            'js' => [
                'vendor/bootstrap/js/bootstrap.bundle.min.js',
            ],
        ],
        // dependecies plugins
        // 'DataTables' => [
        //     'js' => [
        //         'vendor/datatables/jquery.dataTables.min.js',
        //         'vendor/datatables/dataTables.bootstrap4.min.js',
        //         'vendor/datatables/dataTables.responsive.min.js',
        //     ],
        //     'css' => [
        //         'vendor/datatables/dataTables.bootstrap4.min.css',
        //     ],
        // ],
        'overlayScrollbars' => [
            'js' => [
                'vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
            ],
            'CSS' => [
                'vendor/overlayScrollbars/css/OverlayScrollbars.min.css',
            ],
        ],
        'Select2' => [
            'js' => [
                'vendor/select2/js/select2.full.min.js',

            ],
            'css' => [
                'vendor/select2/css/select2.min.css',
                'vendor/select2/css/select2-bootstrap4.min.css',
            ],
        ],
        'Chartjs' => [
            'js' => [
                'vendor/charts/chart.js',
            ],
        ],
        'AdminLte' => [
            'js' => [
                'vendor/adminlte/dist/js/adminlte.min.js',
            ],
            'css' => [
                'vendor/adminlte/dist/css/adminlte.min.css',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    */
    'livewire' => false,
];
