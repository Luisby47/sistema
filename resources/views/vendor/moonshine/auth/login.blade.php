@extends("moonshine::layouts.login")

@section('content')
<style>
    .authentication {
        background-color: #F5F5F5;
    }
    footer{
        color: white;
        margin: 50px;
        height: auto;
    }
</style>
<header class="!bg-blue-900 py-4 ">
    <div class="flex justify-center">
        <img  src="{{ asset(config('moonshine.logo') ?? 'vendor/moonshine/logo.svg') }}" alt="{{ config('moonshine.title') }}" class="mx-auto w-40">
    </div>
</header>
    <div class="authentication !bg-white ">


        <div class="authentication-content ">
            <div class="authentication-header">
                <h1 class="title">
                    @lang(
                        'moonshine::ui.login.title',
                        ['moonshine_title' => config('moonshine.title')]
                    )
                </h1>

                <p class="description">
                    @lang('moonshine::ui.login.description')
                </p>
            </div>

            {!! $form() !!}

            <p class="text-center text-2xs">
                {!! config('moonshine.auth.footer', '') !!}
            </p>

        </div>


    </div>
<footer class="text-center mt-6">
    <p class="text-gray-500 text-sm">&copy; Todos los derechos reservados 2024 | Sistema de planillas de RRHH</p>
</footer>

@endsection
