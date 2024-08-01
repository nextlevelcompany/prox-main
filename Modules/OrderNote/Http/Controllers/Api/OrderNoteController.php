<?php

namespace Modules\OrderNote\Http\Controllers\Api;

use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OrderNote\Http\Resources\OrderNoteCollection;
use Modules\OrderNote\Models\OrderNote;
use Modules\OrderNote\Mail\OrderNoteEmail;
use Modules\Person\Models\Person;
use Modules\Establishment\Models\Series;


class OrderNoteController extends Controller
{
    
    /**
     *
     * @return array
     */
    public function tables()
    {
        $series = Series::filterDataByDocumentType('U6');

        return compact('series');
    }


    public function email(Request $request)
    {
        $order_note = OrderNote::find($request->id);
        $client = Person::find($order_note->customer_id);
        $customer_email = $request->input('email');

        $email = $customer_email;
        $mailable = new OrderNoteEmail($client, $order_note);
        $id = (int) $order_note->id;
        $model = __FILE__.";;".__LINE__;
        $sendIt = MailHelper::SendMail($email, $mailable, $id, $model);

        return [
            'success' => true,
            'message'=> 'Email enviado correctamente.'
        ];
    }

    public function lists(Request $request)
    {
        $records = OrderNote::where(function($q) use($request){
                                $q->where('prefix', 'like', "%{$request->input}%" )
                                    ->orWhere('id','like', "%{$request->input}%");
                            })
                            ->orderBy('id', 'desc')
                            ->take(config('tenant.items_per_page'))
                            ->get();

        return new OrderNoteCollection($records);
    }
}
