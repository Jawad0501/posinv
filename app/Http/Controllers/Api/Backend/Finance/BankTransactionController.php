<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BankTransactionRequest;
use App\Http\Resources\Backend\Finance\BankTransactionCollection;
use App\Http\Resources\Backend\Finance\BankTransactionResource;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

/**
 * @group BankTransaction Management
 *
 * APIs to BankTransaction
 */
class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\BankTransactionCollection
     *
     * @apiResourceModel App\Models\BankTransaction
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam keyword
     */
    public function index(Request $request)
    {
        $transactions = BankTransaction::query()->with('bank:id,name');

        if ($request->has('keyword')) {
            $transactions = $transactions->where('withdraw_deposite_id', 'like', "%$request->keyword%")
                ->orWhere('amount', 'like', "%$request->keyword%")
                ->orWhere('type', 'like', "%$request->keyword%")
                ->orWhere('decsription', 'like', "%$request->keyword%")
                ->orWhere('date', 'like', "%$request->keyword%")
                ->orWhereRelation('bank', 'name', 'like', "%$request->keyword%");
        }
        $transactions = $transactions->latest('id')->paginate();

        return new BankTransactionCollection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Bank transaction added successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(BankTransactionRequest $request)
    {
        $input = $request->validated();
        $input['bank_id'] = $request->bank;

        $transaction = BankTransaction::create($input);

        $this->balanceAddOrUpdate($transaction);

        return response()->json(['message' => 'Bank transaction added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\BankTransactionResource
     *
     * @apiResourceModel App\Models\BankTransaction
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(BankTransaction $bankTransaction)
    {
        return new BankTransactionResource($bankTransaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "BankTransaction updated successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Unprocessable Content."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(BankTransactionRequest $request, BankTransaction $bankTransaction)
    {
        $input = $request->validated();
        $input['bank_id'] = $request->bank;

        $this->balanceAddOrUpdate($bankTransaction);

        $bankTransaction->update($input);

        return response()->json(['message' => 'Bank transaction updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Bank transaction deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(BankTransaction $bankTransaction)
    {
        $this->balanceAddOrUpdate($bankTransaction);
        $bankTransaction->delete();

        return response()->json(['message' => 'Bank transaction deleted successfully']);
    }

    /**
     * balanceAddOrUpdate
     */
    public function balanceAddOrUpdate($transaction)
    {
        $creadit = BankTransaction::TYPE_CREADIT;
        $debit = BankTransaction::TYPE_DEBIT;
        $bank = $transaction->bank;
        $type = $transaction->type;
        $amount = $transaction->amount;

        if (request()->isMethod('POST')) {
            $type == $debit ? $bank->balance += $amount : $bank->balance -= $amount;
            $bank->save();
        }
        if (request()->isMethod('DELETE')) {
            $type == $debit ? $bank->balance -= $amount : $bank->balance += $amount;
            $bank->save();
        }

        if (request()->isMethod('PUT')) {
            $reqBank = Bank::find(request()->bank);
            if ($reqBank) {
                $reqAmount = request()->amount;
                $reqType = request()->type;

                if ($reqType == $type) {
                    if ($amount > $reqAmount) {
                        $currAmount = $amount - $reqAmount;
                    } else {
                        $currAmount = $reqAmount - $amount;
                    }
                    $reqBank->balance += $currAmount;
                } else {
                    if ($type == $debit && $reqType == $creadit) {
                        $bank->balance -= $amount;
                        $bank->save();

                        $reqBank->balance -= $reqAmount;
                    } elseif ($type == $creadit && $reqType == $debit) {
                        $bank->balance += $amount;
                        $bank->save();

                        $reqBank->balance += $reqAmount;
                    }
                }
                $reqBank->save();
            }
        }

        return true;
    }
}
