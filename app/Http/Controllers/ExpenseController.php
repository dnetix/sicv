<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Expense entry form with the month-to-date list beside it (the same
     * layout the legacy screen had).
     */
    public function index(): View
    {
        $expenses = Expense::query()
            ->with(['type', 'user'])
            ->whereBetween('spent_at', [now()->startOfMonth(), now()])
            ->orderByDesc('spent_at')
            ->get();

        return view('expenses.index', [
            'expenses' => $expenses,
            'expenseTypes' => ExpenseType::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'amount' => ['required', 'integer', 'min:1'],
                'expense_type_id' => ['required', 'exists:expense_types,id'],
                'description' => ['nullable', 'string'],
            ],
            [],
            [
                'amount' => 'valor',
                'expense_type_id' => 'tipo de gasto',
                'description' => 'concepto',
            ],
        );

        Expense::query()->create([
            ...$validated,
            // The recorded time is always the server's, as in the legacy app.
            'spent_at' => now(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('expenses.index')
            ->with('status', 'Se ha registrado el gasto exitosamente.');
    }
}
