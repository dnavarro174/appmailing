<?php

namespace App\Exports;
use App\Models\User;

//use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable; 

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExportView implements FromView
{
    use Exportable;
    
    # from view
    public function view(): View
    {
        return view('export.user', [
            'users' => User::get()
        ]);
    }
}
