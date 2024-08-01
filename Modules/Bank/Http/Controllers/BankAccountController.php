<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Bank\Http\Requests\BankAccountRequest;
use Modules\Bank\Http\Resources\BankAccountCollection;
use Modules\Bank\Http\Resources\BankAccountResource;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankAccount;
use Modules\Catalog\Models\CurrencyType;
use Exception;
use Modules\Establishment\Models\Establishment;
use Modules\Company\Models\Configuration;


class BankAccountController extends Controller
{
    public function index()
    {
        return view('tenant.bank_accounts.index');
    }

    public function records()
    {
        $records = BankAccount::all();

        return new BankAccountCollection($records);
    }

    public function create()
    {
        return view('tenant.bank_accounts.index');
    }

    public function tables()
    {
        $banks = Bank::all();
        $currency_types = func_get_table_currency_types();
        $establishments = Establishment::filterDataForTables()->get();
        $select_establishment_bank_account = Configuration::getRecordIndividualColumn('select_establishment_bank_account');

        return compact('banks', 'currency_types', 'establishments', 'select_establishment_bank_account');
    }


    public function record($id)
    {
        $record = new BankAccountResource(BankAccount::findOrFail($id));

        return $record;
    }

    public function store(BankAccountRequest $request)
    {
        $id = $request->input('id');
        $bank_account = BankAccount::firstOrNew(['id' => $id]);
        $bank_account->fill($request->all());
        $bank_account->save();

        return [
            'success' => true,
            'message' => ($id)?'Cuenta bancaria editada con éxito':'Cuenta bancaria registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {

            $bank_account = BankAccount::findOrFail($id);
            $bank_account->delete();

            return [
                'success' => true,
                'message' => 'Cuenta bancaria eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'La Cuenta bancaria esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la cuenta bancaria'];

        }
    }
}
