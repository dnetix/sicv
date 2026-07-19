<?php

namespace App\Http\Controllers;

use App\Enums\ClientNoteSeverity;
use App\Models\Client;
use App\Models\ClientNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientNoteController extends Controller
{
    public function store(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate(
            [
                'body' => ['required', 'string', 'min:3', 'max:500'],
                'severity' => ['required', Rule::enum(ClientNoteSeverity::class)],
            ],
            [],
            ['body' => 'nota', 'severity' => 'tipo'],
        );

        $client->notes()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Se agregó la nota del cliente.');
    }

    /**
     * Only administrators can remove a note — flags like "did not pay back"
     * must not be silently discarded by any operator.
     */
    public function destroy(Request $request, Client $client, ClientNote $note): RedirectResponse
    {
        abort_unless($request->user()->isAdministrator(), 403);

        $note->delete();

        return back()->with('status', 'Se eliminó la nota del cliente.');
    }
}
