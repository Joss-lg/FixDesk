{{-- Recibe: $stats ['entregadas', 'canceladas', 'entregadas_retardo', 'total'] --}}

<section class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-6">

    {{-- Total del periodo --}}
    <article class="rounded-2xl bg-gradient-to-br from-[#7C3AED] to-[#2D1B69] p-4 text-white shadow-lg md:p-6">
        <p class="text-3xl font-bold md:text-4xl">{{ $stats['total'] }}</p>
        <p class="text-purple-200 text-sm mt-1">Órdenes en el periodo</p>
    </article>

    {{-- Entregadas --}}
    <article class="rounded-2xl border border-emerald-200 bg-white p-4 shadow-md md:p-6">
        <p class="text-3xl font-bold text-emerald-600 md:text-4xl">{{ $stats['entregadas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Entregadas</p>
    </article>

    {{-- Canceladas --}}
    <article class="rounded-2xl border border-rose-200 bg-white p-4 shadow-md md:p-6">
        <p class="text-3xl font-bold text-rose-600 md:text-4xl">{{ $stats['canceladas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Canceladas</p>
    </article>

    {{-- Entregadas con retardo --}}
    <article class="rounded-2xl border border-amber-200 bg-white p-4 shadow-md md:p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-3xl font-bold text-amber-600 md:text-4xl">{{ $stats['entregadas_retardo'] }}</p>
                <p class="text-gray-500 text-sm mt-1">Entregadas con retardo</p>
            </div>
            @if($stats['entregadas'] > 0)
            <span class="mt-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">
                {{ round($stats['entregadas_retardo'] / $stats['entregadas'] * 100) }}%
            </span>
            @endif
        </div>
    </article>

</section>