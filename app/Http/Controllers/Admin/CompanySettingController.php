<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Support\Code128;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    /**
     * Logo bounds preserved from the legacy uploader.
     */
    private const int LOGO_MAX_WIDTH = 200;

    private const int LOGO_MAX_HEIGHT = 100;

    public function edit(): View
    {
        return view('admin.company', [
            'company' => CompanySetting::current(),
            'sampleBarcode' => Code128::encode('00000'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'legal_name' => ['required', 'string', 'max:120'],
                'tax_id' => ['required', 'string', 'max:60'],
                'name' => ['required', 'string', 'max:120'],
                'address' => ['required', 'string', 'max:120'],
                'phone' => ['required', 'string', 'max:40'],
                'city' => ['required', 'string', 'max:80'],
                'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif', 'max:1024'],
            ],
            [],
            [
                'legal_name' => 'razón social',
                'tax_id' => 'NIT',
                'name' => 'nombre',
                'address' => 'dirección',
                'phone' => 'teléfono',
                'city' => 'ciudad',
                'logo' => 'logotipo',
            ],
        );

        $company = CompanySetting::current();

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $this->storeResizedLogo($request->file('logo'), $company->logo_path);
        }

        unset($validated['logo']);

        $company->update($validated);

        return redirect()
            ->route('admin.company.edit')
            ->with('status', 'Se han actualizado los datos de la compraventa.');
    }

    /**
     * Resize to fit the print-header bounds (max 200×100, ratio kept) as the
     * legacy uploader did, and store on the private disk.
     */
    private function storeResizedLogo(UploadedFile $file, ?string $previousPath): string
    {
        $source = imagecreatefromstring((string) $file->getContent());

        abort_if($source === false, 422, 'El logotipo no es una imagen válida.');

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(self::LOGO_MAX_WIDTH / $width, self::LOGO_MAX_HEIGHT / $height, 1);

        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagepng($resized);
        $contents = (string) ob_get_clean();

        imagedestroy($source);
        imagedestroy($resized);

        $path = 'company/logo-'.now()->format('YmdHis').'.png';
        Storage::disk('local')->put($path, $contents);

        if ($previousPath !== null && $previousPath !== $path) {
            Storage::disk('local')->delete($previousPath);
        }

        return $path;
    }
}
