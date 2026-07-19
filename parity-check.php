<?php

// One-off parity check: report components legacy vs new, several ranges.
// Run: docker exec -u www-data sicv php artisan tinker storage-parity-check.php

use App\Models\Contract;
use App\Models\ContractExtension;
use App\Models\Expense;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

$ranges = [['2026-06-01', '2026-06-30'], ['2026-01-01', '2026-06-30'], ['2025-01-01', '2025-12-31']];

foreach ($ranges as [$from, $to]) {
    $f = $from.' 00:00:00';
    $t = $to.' 23:59:59';

    $legacyExt = (int) DB::connection('legacy')->table('prorroga')->whereBetween('fecha', [$from, $to])->sum('valor');
    $newExt = (int) ContractExtension::query()->whereBetween('paid_at', [$f, $t])->sum('amount');

    $legacyLoans = (int) DB::connection('legacy')->table('contrato')->whereBetween('fechaingreso', [$f, $t])->sum('valor');
    $newLoans = (int) Contract::query()->whereBetween('started_at', [$f, $t])->sum('amount');

    $legacyGastos = (int) DB::connection('legacy')->table('gasto')->whereBetween('fecha', [$f, $t])->sum('valor');
    $newGastos = (int) Expense::query()->whereBetween('spent_at', [$f, $t])->sum('amount');

    $legacySales = (int) DB::connection('legacy')->table('notacobro')->whereBetween('fecha', [$from, $to])->selectRaw('COALESCE(SUM(COALESCE(total, (SELECT SUM(valor) FROM detalle WHERE detalle.notacobro = notacobro.idnotacobro), 0)), 0) AS s')->value('s');
    $newSales = (int) Sale::query()->whereBetween('sold_at', [$from, $to])->sum('total');

    $legacyRed = DB::connection('legacy')->table('contrato')->where('estado', 2)->whereBetween('fechasalida', [$f, $t])->selectRaw('COALESCE(SUM(valor),0) c, COALESCE(SUM(valorcancelado),0) s')->first();
    $newRed = Contract::query()->where('status', 2)->whereBetween('ended_at', [$f, $t])->selectRaw('COALESCE(SUM(amount),0) c, COALESCE(SUM(settled_amount),0) s')->first();

    $ok = $legacyExt === $newExt
        && $legacyLoans === $newLoans
        && $legacyGastos === $newGastos
        && $legacySales === $newSales
        && (int) $legacyRed->c === (int) $newRed->c
        && (int) $legacyRed->s === (int) $newRed->s;

    echo sprintf(
        "%s..%s: ext %d=%d loans %d=%d gastos %d=%d ventas %d=%d redeemed %d=%d => %s\n",
        $from, $to, $legacyExt, $newExt, $legacyLoans, $newLoans,
        $legacyGastos, $newGastos, $legacySales, $newSales,
        (int) $legacyRed->s, (int) $newRed->s, $ok ? 'MATCH' : 'MISMATCH',
    );
}
