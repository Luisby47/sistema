<?php

use App\Models\CrnubeSpreadsheetUser;
use App\MoonShine\Pages\Dashboard;
use App\MoonShine\Pages\LoginPage;
use MoonShine\Exceptions\MoonShineNotFoundException;
use MoonShine\Forms\LoginForm;
use MoonShine\Http\Middleware\Authenticate;
use MoonShine\Http\Middleware\SecurityHeadersMiddleware;
use MoonShine\Models\MoonshineUser;
use App\MoonShine\MoonShineLayout;
use App\MoonShine\Pages\ProfilePage;

return [
    'dir' => 'app/MoonShine',
    'namespace' => 'App\MoonShine',

    'title' => env('MOONSHINE_TITLE', 'Sistema de Planillas WG'),
    'logo' => env('MOONSHINE_LOGO', '/images/logo.svg'),
    'logo_small' => env('MOONSHINE_LOGO_SMALL', '/images/logo.svg'),
    'footer' => env('MOONSHINE_FOOTER', 'Sistema de Planillas WG'), //No funciona

    'styles' => [
        '.logo-lg' => [
            'width' => '200px',  // Cambia el tamaño aquí
            'height' => 'auto',  // Mantiene la proporción
        ],
    ],

    'route' => [
        'domain' => env('MOONSHINE_URL', ''),
        'prefix' => env('MOONSHINE_ROUTE_PREFIX', 'admin'),
        'single_page_prefix' => 'page',
        'index' => 'moonshine.index',
        'middlewares' => [
            SecurityHeadersMiddleware::class,
        ],
        'notFoundHandler' => MoonShineNotFoundException::class, //Es posible cambiar la página de error 404
    ],

    'use_migrations' => true,
    'use_notifications' => false,
    'use_theme_switcher' => true,




    'layout' => MoonShineLayout::class,

    'disk' => 'public',

    'disk_options' => [],

    'cache' => 'file',

    'assets' => [
        'js' => [
            'script_attributes' => [
                'defer',
            ]
        ],
        'css' => [
            'link_attributes' => [
                'rel' => 'stylesheet',
            ]
        ]
    ],

    'forms' => [
        'login' => \App\Form\LoginForm::class
    ],

    'pages' => [
        'dashboard' => Dashboard::class,
        'profile' => ProfilePage::class
    ],

    'model_resources' => [
        'default_with_import' => true,
        'default_with_export' => true,
    ],

    'auth' => [
        'enable' => true,
        'middleware' => Authenticate::class,
        'fields' => [ // Cambiar los campos de autenticación (Es decir lo requerido para editar o crear un usuario como en la parte de profile)
            'username' => 'email',
            'password' => 'password',
            'name' => 'name',
            'avatar' => 'avatar',
        ],
        'guard' => 'moonshine',
        'guards' => [
            'moonshine' => [
                'driver' => 'session',
                'provider' => 'moonshine',
            ],
        ],
        'providers' => [
            'moonshine' => [
                'driver' => 'eloquent',
                'model' => CrnubeSpreadsheetUser::class, // Cambiar el modelo de autenticación aquí
            ],

        ],
        'pipelines' => [],
    ],
    'providers' => [
        Illuminate\Translation\TranslationServiceProvider::class,
        App\Providers\MoonShineServiceProvider::class,

    ],




    'locales' => 'es',

    /*
        'locales' => [
            'enabled' => false,
        ],
        */


    'global_search' => [
        // User::class
    ],

    'tinymce' => [
        'file_manager' => false, // or 'laravel-filemanager' prefix for lfm
        'token' => env('MOONSHINE_TINYMCE_TOKEN', ''),
        'version' => env('MOONSHINE_TINYMCE_VERSION', '6'),
    ],

    'socialite' => [
        // 'driver' => 'path_to_image_for_button'
    ],


];
