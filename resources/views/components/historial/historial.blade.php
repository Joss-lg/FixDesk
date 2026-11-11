@extends('plantillas.base')

@section('titulo-pestana', 'Historial')

@section('contenido-principal')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold bg-[#1E1B2E] bg-clip-text text-transparent">
            Historial de órdenes
        </h1>
        <p class="text-gray-500 text-sm mt-1">Consulta y filtra el registro completo de reparaciones del taller</p>
    </div>

    {{-- Targets históricos --}}
    @include('components.historial.targets-historicos', ['stats' => $stats])

    {{-- Buscador --}}
    @include('components.historial.buscador', [
        'buscar'     => $buscar,
        'folio'      => $folio,
        'folioGrupo' => $folioGrupo,
        'desde'      => $desde,
        'hasta'      => $hasta,
        'orden'      => $orden,
    ])

    {{-- Tabla --}}
    @include('components.historial.tabla', ['reparaciones' => $reparaciones])

    {{-- Paginación --}}
    <div>{{ $reparaciones->links() }}</div>

</div>

@endsection