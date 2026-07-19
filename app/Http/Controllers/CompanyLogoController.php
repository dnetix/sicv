<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyLogoController extends Controller
{
    /**
     * Serves the stored company logo (kept on the private disk — a public
     * storage symlink is unreliable on the Windows dev bind mount).
     */
    public function __invoke(): BinaryFileResponse
    {
        $logoPath = CompanySetting::current()->logo_path;

        abort_if($logoPath === null || ! Storage::disk('local')->exists($logoPath), 404);

        return response()->file(Storage::disk('local')->path($logoPath));
    }
}
