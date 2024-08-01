<?php

namespace Modules\Quotation\Http\Controllers\Api;

use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Quotation\Http\Resources\QuotationCollection;
use Modules\Quotation\Mail\QuotationMail;
use Modules\Quotation\Models\Quotation;
use Modules\Company\Models\Company;
use Modules\Quotation\Http\Controllers\QuotationController as QuotationControllerWeb;
use Modules\Establishment\Models\Series;


class QuotationController extends Controller
{
    public function list(Request $request)
    {
        $records = Quotation::where('id', 'like', "%{$request->input}%")
            ->take(config('tenant.items_per_page'))
            ->latest()
            ->get();

        return new QuotationCollection($records);
    }

    public function store(Request $request)
    {
        $request['establishment_id'] = $request['establishment_id'] ? $request['establishment_id'] : auth()->user()->establishment_id;

        DB::connection('tenant')->transaction(function () use ($request) {
            $quotation_web = new QuotationControllerWeb;
            $data = $quotation_web->mergeData($request);
            $data['terms_condition'] = $quotation_web->getTermsCondition();

            $this->quotation = Quotation::create($data);

            foreach ($data['items'] as $row) {
                $this->quotation->items()->create($row);
            }

            $quotation_web->savePayments($this->quotation, $data['payments']);

//            $this->setFilename();
            $quotation_web->createPdf($this->quotation, "a4", $this->quotation->filename);
        });

        return [
            'success' => true,
            'data' => [
                'number_full' => $this->quotation->number_full,
                'external_id' => $this->quotation->external_id,
                'filename' => $this->quotation->filename,
                'print_a4' => url('') . "/quotations/print/{$this->quotation->external_id}/a4",
                'print_ticket' => $this->quotation->getUrlPrintPdf('ticket'),
            ],
        ];
    }

//    private function setFilename()
//    {
//        $name = [$this->quotation->prefix, $this->quotation->id, date('Ymd')];
//        $this->quotation->filename = join('-', $name);
//        $this->quotation->save();
//    }

    public function email(Request $request)
    {
        $company = Company::active();
        $quotation = Quotation::find($request->id);
        $email = $request->input('email');
        $mailable = new QuotationMail($company, $quotation);
        $id = (int)$request->id;
        $sendIt = MailHelper::SendMail($email, $mailable, $id, 3);

        return [
            'success' => true,
            'message' => 'Email enviado correctamente.'
        ];
    }

    
    /**
     *
     * @return array
     */
    public function tables()
    {
        $series = Series::filterDataByDocumentType('U5');

        return compact('series');
    }

}
