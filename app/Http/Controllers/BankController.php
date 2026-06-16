<?php

namespace App\Http\Controllers;

use App\Services\PaystackService;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Verify a bank account.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyBankAccount(Request $request)
    {
        // $request->validate([
        //     'account_number' => 'required|digits:10',
        //     'bank_code' => 'required|string',
        // ]);

        //dd($request->all());

        $result = $this->paystackService->verifyBankAccount(
            $request->account_number,
            $request->bank_code
        );

        //dd($request->all());
        return response()->json($result);
    }

    public function showVerifyBankForm(PaystackService $paystackService)
    {
        $banks = $paystackService->getNigerianBanks();

        return view('bank.verify', compact('banks'));
    }
}
