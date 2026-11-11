{{-- Recibe: $reparaciones (paginado) --}}

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
@endphp

{{-- Vista móvil --}}
<div class="space-y-3 md:hidden">
    @forelse($reparaciones as $rep)
    <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
        <div class="flex items-start justify-between gap-2 mb-2">
            <div>
                <p class="text-xs font-mono text-gray-400">{{ $rep->folio }}</p>
                <p class="font-semibold text-[#2D1B69]">{{ $rep->cliente->nombre }}</p>
                <p class="text-sm text-gray-500">{{ $rep->marca }} {{ $rep->modelo }}</p>
            </div>
            <span class="flex-shrink-0 rounded-full px-2 py-1 text-xs font-medium {{ $estadoColors[$rep->estado] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $rep->estado }}
            </span>
        </div>
        <div class="flex items-center justify-between text-xs text-gray-400">
            <span>{{ $rep->created_at->format('d/m/Y') }}</span>
            <a href="{{ route('historial.show', $rep) }}"
                class="font-medium text-[#7C3AED] hover:text-[#2D1B69]">
                Ver reporte →
            </a>
        </div>
    </article>
    @empty
    <div class="rounded-2xl bg-white px-6 py-12 text-center shadow-sm">
        <p class="text-gray-400">No hay órdenes en este periodo</p>
    </div>
    @endforelse
</div>

{{-- Vista desktop --}}
<div class="hidden overflow-hidden rounded-2xl bg-white shadow-lg md:block">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-[#2D1B69] to-[#1E1B2E]">
                <tr>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Folio</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Cliente</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Artículo / Equipo</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Nivel</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Estado</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Técnico</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Fecha límite</th>
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-purple-200">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reparaciones as $rep)
                @php
                    // Nivel más alto registrado (último escalamiento o nivel actual)
                    $nivelMostrado = $rep->escalamientos->last()?->nivelNuevo ?? $rep->nivel;
                    $colorEstado   = $estadoColors[$rep->estado] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <tr class="transition-colors hover:bg-purple-50/30">

                    {{-- Folio --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="text-xs font-mono font-semibold text-[#2D1B69]">{{ $rep->folio }}</div>
                        @if($rep->folio !== $rep->folio_grupo)
                        <div class="text-[10px] text-gray-400 font-mono">Lote: {{ $rep->folio_grupo }}</div>
                        @endif
                    </td>

                    {{-- Cliente --}}
                    <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-800">
                        {{ $rep->cliente->nombre }}
                    </td>

                    {{-- Artículo --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                        <div>{{ $rep->tipo_equipo }}</div>
                        <div class="text-xs text-gray-400">{{ $rep->marca }} {{ $rep->modelo }}</div>
                    </td>

                    {{-- Nivel más reciente --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="rounded-full bg-[#7C3AED]/10 px-2 py-1 text-xs font-medium text-[#7C3AED]">
                            Nivel {{ $nivelMostrado->nivel }} — {{ $nivelMostrado->nombre }}
                        </span>
                    </td>

                    {{-- Estado --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-medium {{ $colorEstado }}">
                            {{ $rep->estado }}
                        </span>
                    </td>

                    {{-- Técnico --}}
                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                        {{ $rep->tecnico->name ?? '—' }}
                    </td>

                    {{-- Fecha límite --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        @if($rep->hora_limite)
                            <span class="{{ $rep->estaRetrasada() ? 'font-medium text-red-600' : 'text-gray-600' }}">
                                {{ $rep->hora_limite->format('d/m/Y H:i') }}
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>

                    {{-- Acción --}}
                    <td class="whitespace-nowrap px-6 py-4">
                        <a href="{{ route('historial.show', $rep) }}"
                            class="inline-flex items-center gap-1 text-sm font-medium text-[#7C3AED] transition-colors hover:text-[#2D1B69] group">
                            Ver reporte
                            <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="font-medium text-gray-400">No hay órdenes en este periodo</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>