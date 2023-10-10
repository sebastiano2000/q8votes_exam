<?php

namespace App\Http\Controllers;

use App\Exports\ExportTransaction;
use App\Exports\ExportTransactionSheets;
use App\Models\Financial_transaction;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FinancialTrasanctionController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.financial.index',[
                'financial_transactions' => Financial_transaction::filter($request->all())->orderByDesc('created_at')->paginate(50),
            ]);
        elseif(Auth::user()->role_id == 2){
            return view('admin.pages.financial.index',[
                'financial_transactions' => Financial_transaction::WhereHas('tenancy', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->orderByDesc('created_at')->paginate(50),
            ]);
        }
        elseif(Auth::user()->role_id == 3){
            return view('admin.pages.financial.index',[
                'financial_transactions' => Financial_transaction::where('tenant_id', Auth::user()->id)->orderByDesc('created_at')->paginate(50)
            ]);
        }
        else 
            abort(404);
    }

    public function filter(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.financial.index',[
                'financial_transactions' => Financial_transaction::filter($request->all())->orderByDesc('created_at')->paginate(50),
            ]);
        elseif(Auth::user()->role_id == 2){
            return view('admin.pages.financial.index',[
                'financial_transactions' => Financial_transaction::WhereHas('tenancy', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->orderByDesc('created_at')->paginate(50),
            ]);
        }
        elseif(Auth::user()->role_id == 3){
            return view('admin.pages.financial.index',[
               'financial_transactions' => Financial_transaction::where('tenant_id', Auth::user()->id)->orderByDesc('created_at')->paginate(50)
            ]);
        }
        else 
            abort(404);
    }

    public function show(Request $request){
        if(Auth::user()->isSuperAdmin())
        return view('admin.pages.financial.invoice',[
            'financial_transaction' => Financial_transaction::find($request->id),
        ]);
        elseif(Auth::user()->role_id == 3){
            return view('admin.pages.financial.invoice',[
                'financial_transaction' => Financial_transaction::where('tenant_id', Auth::user()->id)->find($request->id),
            ]);
        }
        else 
        abort(404);
    }
    
    public function destroy(Financial_transaction $financial_transaction){
        if($financial_transaction->roleback($financial_transaction))
        return true;
        else
        return false;
        
    }

    public function exportTransactions(Request $request) {
        $excel_name = date('F', strtotime($request->transaction_month)) ."-". Carbon::now()->format('H:i:s');

        if(Auth::user()->isSuperAdmin())
            return Excel::download(new ExportTransaction($request), "$excel_name.xlsx");
        else
            abort(404);
    }
}
