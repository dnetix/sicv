<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view($request->get('view') ?? 'user.login');
    }

    public function dashboard()
    {
        $contracts = RepositoryHelper::forContracts()->getLastContracts();
        return view('user.dashboard', [
            'contracts' => $contracts,
        ]);
    }
}