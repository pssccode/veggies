<?php

namespace App\Http\Controllers;

use App\Models\SalesHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        return view('clients.main_page');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('main.page');
    }

    public function getShortJournal(Request $request)
    {
        return SalesHistory::with('culture')->get()->toArray();
    }

}
