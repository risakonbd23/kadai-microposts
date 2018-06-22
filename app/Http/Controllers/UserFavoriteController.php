<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->fav($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        \Auth::user()->unfav($id);
        return redirect()->back();
    }
    
    
}
