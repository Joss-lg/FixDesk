{{-- Recibe: $buscar, $folio, $folioGrupo, $desde, $hasta, $orden --}}

<section class="rounded-2xl border border-purple-100 bg-white p-4 shadow-md md:p-5">
    <form method="GET" action="{{ route('historial.index') }}" class="space-y-4">

        {{-- Fila 1: nombre + folio individual + folio grupo --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            {{-- Búsqueda por nombre --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Nombre del cliente
                </label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m1.1-5.15a6.25 6.25 0 11-12.5 0 6.25 6.25 0 0112.5 0z"/>
                    </svg>
                    <input type="search" name="buscar" value="{{ $buscar }}"
                        placeholder="Buscar por nombre…"
                        class="w-full rounded-xl border border-gray-200 py-2.5 pl-10 pr-4 text-sm focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20" />
                </div>
            </div>

            {{-- Folio individual --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Folio individual
                </label>
                <input type="search" name="folio" value="{{ $folio }}"
                    placeholder="Ej: FF-TALL-2026-0001-A1"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20" />
            </div>

            {{-- Folio grupal --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Folio de lote
                </label>
                <input type="search" name="folio_grupo" value="{{ $folioGrupo }}"
                    placeholder="Ej: FF-TALL-2026-0001"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20" />
            </div>
        </div>

        {{-- Fila 2: fechas + orden + botones --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">

            {{-- Desde --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Desde
                </label>
                <input type="date" name="desde" value="{{ $desde }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-[#2D1B69] focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 [color-scheme:light]" />
            </div>

            {{-- Hasta --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Hasta
                </label>
                <input type="date" name="hasta" value="{{ $hasta }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-[#2D1B69] focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 [color-scheme:light]" />
            </div>

            {{-- Orden alfabético / cronológico --}}
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Ordenar por
                </label>
                <select name="orden"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-[#2D1B69] focus:border-[#7C3AED] focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20">
                    <option value="reciente" {{ $orden === 'reciente' ? 'selected' : '' }}>Más reciente primero</option>
                    <option value="antiguo"  {{ $orden === 'antiguo'  ? 'selected' : '' }}>Más antiguo primero</option>
                    <option value="az"       {{ $orden === 'az'       ? 'selected' : '' }}>Cliente A → Z</option>
                    <option value="za"       {{ $orden === 'za'       ? 'selected' : '' }}>Cliente Z → A</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="flex flex-1 items-center justify-center rounded-xl bg-[#1E1B2E] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#2D1B69]">
                    Filtrar
                </button>
                @if($buscar || $folio || $folioGrupo || $desde || $hasta || $orden !== 'reciente')
                <a href="{{ route('historial.index') }}"
                    class="flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-200">
                    Limpiar
                </a>
                @endif
            </div>
        </div>

    </form>
</section>