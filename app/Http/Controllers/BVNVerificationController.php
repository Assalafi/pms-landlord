<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Exception;

class BVNVerificationController extends Controller
{
    protected $flutterwaveService;

    public function __construct(FlutterwaveService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    public function verifyBVN(Request $request)
    {
        // $request->validate([
        //     'bvn' => 'required|string|min:11|max:11',
        // ]);

        try {
            // 22541207421
            // $bvn = $request->input('bvn');
            $bvn = '123478322';
            $verification = $this->flutterwaveService->verifyBVN($bvn);

            return response()->json([
                'success' => true,
                'data' => $verification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
