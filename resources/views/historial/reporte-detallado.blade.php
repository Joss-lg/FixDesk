@extends('plantillas.base')

@section('titulo-pestana', 'Reporte — ' . $reparacion->folio)

@section('contenido-principal')

@php
$estadoColors = [
    'Recibido'        => 'bg-slate-100 text-slate-700',
    'En Revisión'     => 'bg-blue-100 text-blue-700',
    'Esperando Pieza' => 'bg-amber-100 text-amber-700',
    'Reparado'        => 'bg-emerald-100 text-emerald-700',
    'Retardo'         => 'bg-red-100 text-red-700',
    'Entregado'       => 'bg-gray-100 text-gray-500',
    'Cancelado'       => 'bg-rose-100 text-rose-700',
];
$colorClass = $estadoColors[$reparacion->estado] ?? 'bg-gray-100 text-gray-600';
@endphp

<div class="mx-auto max-w-6xl space-y-5 md:space-y-6">

    {{-- Banner de solo lectura --}}
    <div class="flex items-center gap-3 rounded-lg border-l-4 border-amber-400 bg-amber-50 p-4 text-amber-800 shadow-sm">
        <svg class="h-5 w-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-sm font-medium">
            Reporte histórico de solo lectura —
            <a href="{{ route('historial.index') }}" class="underline hover:text-amber-900">Volver al historial</a>
        </p>
    </div>

    {{-- Ficha principal --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#2D1B69] to-[#1E1B2E] px-4 py-5 md:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-3">
                    <a href="{{ route('historial.index') }}" title="Volver al historial"
                        class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div class="min-w-0">
                        <h1 class="text-2xl font-bold text-white md:text-3xl">{{ $reparacion->folio }}</h1>
                        <p class="mt-1 text-sm text-purple-200">Reporte histórico de orden</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full px-3 py-1.5 text-xs font-medium {{ $colorClass }}">
                        {{ $reparacion->estado }}
                    </span>
                    @if($reparacion->estaRetrasada())
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        RETARDO
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Grid de información --}}
        <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2 md:gap-6 md:p-6">

            {{-- Dispositivo --}}
            <div class="rounded-xl bg-gray-50 p-4 md:p-5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                    <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 3h14a2 2 0 011 2v14a2 2 0 01-1 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                    Dispositivo
                </h2>
                <dl class="space-y-2">
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Tipo:</dt><dd class="font-medium text-gray-800">{{ $reparacion->tipo_equipo }}</dd></div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Marca:</dt><dd class="text-gray-800">{{ $reparacion->marca }}</dd></div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Modelo:</dt><dd class="text-gray-800">{{ $reparacion->modelo }}</dd></div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Serie:</dt><dd class="text-gray-800">{{ $reparacion->numero_serie ?? '—' }}</dd></div>
                    <div class="flex flex-wrap">
                        <dt class="w-32 text-sm text-gray-500">Nivel:</dt>
                        <dd><span class="rounded-full bg-[#7C3AED]/10 px-2 py-0.5 text-xs text-[#7C3AED]">Nivel {{ $reparacion->nivel->nivel }} — {{ $reparacion->nivel->nombre }}</span></dd>
                    </div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Problema:</dt><dd class="text-gray-800">{{ $reparacion->problema_reportado }}</dd></div>
                </dl>
            </div>

            {{-- Cliente --}}
            <div class="rounded-xl bg-gray-50 p-4 md:p-5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                    <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Cliente
                </h2>
                <dl class="space-y-2">
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Nombre:</dt><dd class="text-gray-800">{{ $reparacion->cliente->nombre }}</dd></div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Teléfono:</dt><dd class="text-gray-800">{{ $reparacion->cliente->telefono ?? '—' }}</dd></div>
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Email:</dt><dd class="text-gray-800">{{ $reparacion->cliente->email ?? '—' }}</dd></div>
                </dl>
            </div>

            {{-- Control de tiempos --}}
            <div class="rounded-xl bg-gray-50 p-4 md:p-5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                    <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Control de tiempos
                </h2>
                <dl class="space-y-2">
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Ingreso:</dt><dd class="text-gray-800">{{ $reparacion->hora_ingreso->format('d/m/Y H:i') }}</dd></div>
                    <div class="flex flex-wrap">
                        <dt class="w-32 text-sm text-gray-500">Hora límite:</dt>
                        <dd class="{{ $reparacion->estaRetrasada() ? 'font-medium text-red-600' : 'text-gray-800' }}">
                            {{ $reparacion->hora_limite?->format('d/m/Y H:i') ?? '—' }}
                        </dd>
                    </div>
                    @if($reparacion->hora_fin)
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Finalizado:</dt><dd class="text-gray-800">{{ $reparacion->hora_fin->format('d/m/Y H:i') }}</dd></div>
                    @endif
                    <div class="flex flex-wrap"><dt class="w-32 text-sm text-gray-500">Técnico:</dt><dd class="text-gray-800">{{ $reparacion->tecnico->name ?? 'Sin asignar' }}</dd></div>
                </dl>
            </div>

            {{-- Diagnóstico y costo --}}
            <div class="rounded-xl bg-gray-50 p-4 md:p-5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                    <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Diagnóstico registrado
                </h2>
                <p class="text-sm leading-relaxed text-gray-700">{{ $reparacion->diagnostico_tecnico ?? '—' }}</p>
                @if($reparacion->costo_final)
                <div class="mt-3 border-t border-gray-200 pt-3">
                    <span class="text-sm text-gray-500">Costo final: </span>
                    <span class="font-semibold text-[#2D1B69]">${{ number_format($reparacion->costo_final, 2) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Historial de escalamientos --}}
    @if($reparacion->escalamientos->isNotEmpty())
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
        <div class="border-b border-gray-100 bg-gradient-to-r from-[#7C3AED]/5 to-[#EC4899]/5 px-4 py-4 md:px-6">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Historial de escalamientos
            </h2>
        </div>
        <div class="space-y-3 p-4 md:p-6">
            @foreach($reparacion->escalamientos as $esc)
            <div class="rounded-r-lg border-l-4 border-[#7C3AED] bg-gray-50 py-2 pl-4">
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <span class="font-semibold text-[#2D1B69]">{{ $esc->nivelAnterior->nombre }}</span>
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    <span class="font-semibold text-[#7C3AED]">{{ $esc->nivelNuevo->nombre }}</span>
                    <span class="w-full text-xs text-gray-400 sm:ml-auto sm:w-auto">
                        por {{ $esc->user->name ?? 'Sistema' }} el {{ $esc->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <p class="text-sm italic text-gray-600">"{{ $esc->motivo }}"</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Historial de mensajes (solo lectura) --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
        <div class="border-b border-gray-100 bg-gradient-to-r from-[#7C3AED]/5 to-[#EC4899]/5 px-4 py-4 md:px-6">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-[#2D1B69]">
                <svg class="h-5 w-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Historial de mensajes
            </h2>
        </div>
        <div class="p-4 md:p-6">
            @php $mensajes = $reparacion->mensajes()->orderBy('created_at')->get(); @endphp
            @if($mensajes->isEmpty())
                <p class="py-6 text-center text-sm text-gray-400">No hubo mensajes en esta orden.</p>
            @else
            <div class="space-y-3">
                @foreach($mensajes as $msg)
                <div class="flex flex-col {{ $msg->es_del_cliente ? 'items-start' : 'items-end' }}">
                    <div class="max-w-[92%] rounded-2xl px-4 py-2 md:max-w-[80%] {{ $msg->es_del_cliente ? 'bg-gray-200 text-gray-800' : 'bg-[#7C3AED] text-white' }}">
                        <div class="mb-1 flex items-center gap-2">
                            <strong class="text-sm">{{ $msg->es_del_cliente ? $reparacion->cliente->nombre : ($msg->user->name ?? 'Taller') }}</strong>
                            <time class="text-[10px] opacity-70">{{ $msg->created_at->format('d/m/Y H:i') }}</time>
                        </div>
                        <p class="break-words text-sm">{{ $msg->contenido }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

@endsection