<?php

namespace App\Http\Controllers;

use App\Helpers\RepositoryHelper;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function create()
    {
        return view('contract.create', [
            'articleTypes' => RepositoryHelper::forArticles()->getArticleTypes(),
        ]);
    }
}
