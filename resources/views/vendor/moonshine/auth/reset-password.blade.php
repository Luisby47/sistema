@extends("moonshine::layouts.login")

@section('content')
    <div class="authentication">
        <div class="authentication-logo">
            <a href="/" rel="home">
                <img class="h-16"
                     src="{{ asset(config('moonshine.logo') ?? 'vendor/moonshine/logo.svg') }}"
                     alt="{{ config('moonshine.title') }}"
                >
            </a>
        </div>

        <div class="authentication-content">
            <div class="authentication-header">
                <h1 class="title">
                    Cambiar contraseña.
                </h1>

                <p class="description">
                    Ingresa tu nueva contraseña.
                </p>
            </div>

            {!! $form() !!}

            <p class="text-center text-2xs">
                {!! config('moonshine.auth.footer', '') !!}
            </p>

            <div class="authentication-footer">

            </div>
        </div>
    </div>
@endsection
