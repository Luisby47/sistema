@extends("vendor.moonshine.layouts.login")

@section('content')
    <style>
        .authentication{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 75vh;
            background-color: #D9D9D9;
        }
        footer{
            padding: 20px;
        }
    </style>
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
                    Cambiar contraseña.
                </h1>

                <p class="description">
                    Ingresa tu nueva contraseña.
                </p>
                <p class="description">
                    <p class="description"> La contraseña debe seguir el siguiente formato:<p>
                    <p class="description"> - Al menos 8 caracteres</p>
                    <p class="description"> - Al menos una letra mayúscula</p>
                    <p class="description"> - Al menos una letra minúscula</p>
                    <p class="description"> - Al menos un número (0-9)</p>
                    <p class="description"> - Al menos un caracter especial</p>



                </p>
            </div>

            @csrf

            <input type="hidden" name="token" value="{{$token}}">

            {!! $form() !!}


        </div>
    </div>
    <footer class="w-full bg-blue-900 text-center text-white">
        <p>&copy; Todos los derechos reservados 2024 | Sistema de planillas de RRHH</p>
    </footer>
@endsection
