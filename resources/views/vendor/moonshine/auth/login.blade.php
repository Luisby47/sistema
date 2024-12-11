@extends("vendor.moonshine.layouts.login")

@section('content')
    <style>
        .authentication{
            min-height: initial;
            background-color: #D9D9D9;
        }
        footer{
            padding: 1rem;
        }
        .main_content{
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100dvh;
        }
    </style>

    <div class="main_content">
        <header class="w-full bg-blue-900 py-8 flex justify-center items-center ">

            <a href="/admin" rel="home" class="text-center ">
                <img class="h-32"
                     src="{{ asset('images/login-logo.png') }}"
                     alt="{{ config('moonshine.title') }}"
                >
            </a>
        </header>

        <div class="authentication">

            <div class="authentication-content">
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


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                {!! $form() !!}

            </div>
        </div>
        <footer class="w-full bg-blue-900 text-center text-white">
            <p>&copy; Todos los derechos reservados 2024 | Sistema de planillas de RRHH</p>
        </footer>
    </div>

@endsection
