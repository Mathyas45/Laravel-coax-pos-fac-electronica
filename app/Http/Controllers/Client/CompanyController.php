<?php

namespace App\Http\Controllers\Client;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\CompanyResource;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::first();
        return response()->json([
            'company' => $company ? CompanyResource::make($company) : null,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $company = Company::first();
        if($company){
            $company->update($request->all());
        }else{
            $company = Company::create($request->all());
        }
        return response()->json([
            'code' => 200,
            'message' => 'se actualizo correctamente',
        ]);
        

    }
}
