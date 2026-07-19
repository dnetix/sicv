<?php

namespace App\Http\Controllers;

use App\Actions\Contracts\ExtendContract;
use App\Actions\Contracts\ForfeitContract;
use App\Actions\Contracts\RedeemContract;
use App\Actions\Contracts\VoidContract;
use App\Models\Contract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContractOperationController extends Controller
{
    public function extend(Request $request, Contract $contract, ExtendContract $extend): RedirectResponse
    {
        $validated = $request->validate(
            ['amount' => ['required', 'integer']],
            [],
            ['amount' => 'valor del abono'],
        );

        $extension = $extend($contract, $validated['amount'], $request->user());

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', sprintf(
                'Abono de %s registrado (%s meses).',
                money($extension->amount),
                number_format($extension->months, 2, ',', '.'),
            ));
    }

    public function redeem(Request $request, Contract $contract, RedeemContract $redeem): RedirectResponse
    {
        $validated = $request->validate(
            ['amount' => ['required', 'integer', 'min:0']],
            [],
            ['amount' => 'valor a cobrar'],
        );

        $redeem($contract, $validated['amount'], $request->user());

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', 'El contrato ha sido cancelado: el cliente retira su artículo.');
    }

    public function void(Request $request, Contract $contract, VoidContract $void): RedirectResponse
    {
        $validated = $request->validate(
            ['reason' => ['required', 'string', 'min:3']],
            [],
            ['reason' => 'motivo'],
        );

        $void($contract, $validated['reason'], $request->user());

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', 'El contrato ha sido anulado.');
    }

    public function forfeit(Request $request, Contract $contract, ForfeitContract $forfeit): RedirectResponse
    {
        $validated = $request->validate(
            ['price' => ['required', 'integer', 'min:0']],
            [],
            ['price' => 'valor de venta'],
        );

        $forfeit($contract, $validated['price'], $request->user());

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', 'El artículo ha sido movido al almacén.');
    }

    public function removeFromQueue(Contract $contract): RedirectResponse
    {
        $contract->repossession()->delete();

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', 'El contrato fue removido de la pre-saca.');
    }
}
