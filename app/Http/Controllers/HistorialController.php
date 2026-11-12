<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\User;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $tallerId = auth()->user()->taller_id;

        // ── Parámetros de búsqueda ────────────────────────────
        $buscar      = trim((string) $request->query('buscar', ''));
        $folioGrupo  = trim((string) $request->query('folio_grupo', ''));
        $folio       = trim((string) $request->query('folio', ''));
        $desde       = $request->query('desde');
        $hasta       = $request->query('hasta');
        $orden       = $request->query('orden', 'reciente'); // reciente | antiguo | az | za

        // ── Query base: último mes por defecto ────────────────
        $query = Reparacion::delTaller($tallerId)
            ->with([
                'cliente:id,nombre',
                'tecnico:id,name',
                'nivel:id,nivel,nombre',
                'escalamientos.nivelNuevo:id,nivel,nombre',
            ]);

        // Si no hay filtros de fecha manuales, mostrar el último mes
        if (! $desde && ! $hasta && ! $buscar && ! $folio && ! $folioGrupo) {
            $query->where('created_at', '>=', now()->subMonth());
        }

        // ── Filtros ───────────────────────────────────────────
        if ($buscar) {
            $query->whereHas('cliente', fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"));
        }

        if ($folio) {
            $query->where('folio', 'like', "%{$folio}%");
        }

        if ($folioGrupo) {
            $query->where('folio_grupo', 'like', "%{$folioGrupo}%");
        }

        if ($desde) {
            $query->where('created_at', '>=', $desde);
        }

        if ($hasta) {
            $query->where('created_at', '<=', $hasta . ' 23:59:59');
        }

        // ── Ordenamiento ──────────────────────────────────────
        match ($orden) {
            'antiguo' => $query->oldest(),
            'az'      => $query->join('clientes', 'clientes.id', '=', 'reparaciones.cliente_id')
                               ->orderBy('clientes.nombre')->select('reparaciones.*'),
            'za'      => $query->join('clientes', 'clientes.id', '=', 'reparaciones.cliente_id')
                               ->orderByDesc('clientes.nombre')->select('reparaciones.*'),
            default   => $query->latest(),
        };

        $reparaciones = $query->paginate(25)->withQueryString();

        // ── Targets históricos (mismo scope de filtros) ───────
        $statsQuery = Reparacion::delTaller($tallerId);

        if (! $desde && ! $hasta && ! $buscar && ! $folio && ! $folioGrupo) {
            $statsQuery->where('created_at', '>=', now()->subMonth());
        }

        if ($desde) $statsQuery->where('created_at', '>=', $desde);
        if ($hasta) $statsQuery->where('created_at', '<=', $hasta . ' 23:59:59');

                $stats = [
            'entregadas'      => (clone $statsQuery)->where('estado', 'Entregado')->count(),
            'canceladas'      => (clone $statsQuery)->where('estado', 'Cancelado')->count(),
            'esperando_pieza' => (clone $statsQuery)->where('estado', 'Esperando Pieza')->count(),
            'total'           => (clone $statsQuery)->count(),
        ];

        $tecnicos = User::where('taller_id', $tallerId)
            ->where('rol', 'tecnico')
            ->get(['id', 'name']);

        return view('historial.historial', compact(
            'reparaciones', 'stats',
            'buscar', 'folio', 'folioGrupo', 'desde', 'hasta', 'orden',
            'tecnicos'
        ));
    }

    // ── Reporte detallado de una orden (modo historial) ───────
    public function show(Reparacion $reparacion)
    {
        abort_if((int) $reparacion->taller_id !== (int) auth()->user()->taller_id, 403);

        $reparacion->load([
            'cliente', 'tecnico', 'nivel',
            'mensajes.user:id,name',
            'escalamientos.nivelAnterior:id,nombre',
            'escalamientos.nivelNuevo:id,nombre',
            'escalamientos.user:id,name',
        ]);

        return view('historial.reporte-detallado', compact('reparacion'));
    }
}