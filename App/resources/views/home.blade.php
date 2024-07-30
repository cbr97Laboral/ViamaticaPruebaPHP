@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Bienvenido') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- Datos de Persona -->
                    <p>Nombres: {{ $persona->Nombres }}</p>
                    <p>Apellidos: {{ $persona->Apellidos }}</p>
                    <p>Identificación: {{ $persona->Identificacion }}</p>

                    @if ($ultimaSesion)
                        <p>Última sesión activa</p>
                        <p>Ultimo ingreso: {{ $ultimaSesion->FechaIngreso }}</p>
                        <p>Ultimo cierre: {{ $ultimaSesion->FechaCierre }}</p>
                    @else
                        <p>No hay registros de sesión activa previa.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
