<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GUSApiService;

class GusController extends Controller
{
    protected GUSApiService $gus;

    public function __construct(GUSApiService $gus)
    {
        $this->gus = $gus;
    }

    public function show(string $nip)
    {
        if (is_null($nip) || !preg_match('/^\d{10}$/', $nip)) {
            return response()->json(['error' => 'Nieprawidłowy NIP'], 400);
        }

        $result = $this->gus->getCompanyByNip($nip);

        if (is_string($result)) {
            return response()->json(['error' => $result], 404);
        }

        if (is_null($result)) {
            return response()->json(['message' => 'Brak danych'], 404);
        }

        return response()->json($result, 200);
    }
}