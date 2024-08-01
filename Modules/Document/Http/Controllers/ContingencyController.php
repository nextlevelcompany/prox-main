<?php

namespace Modules\Document\Http\Controllers;

use Modules\Document\Http\Resources\DocumentCollection;
use Modules\Document\Models\Document;
use Modules\Establishment\Models\Series;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\OfflineTrait;

class ContingencyController extends Controller
{
    use OfflineTrait;

    public function __construct()
    {

    }

    public function index()
    {
        $is_client = $this->getIsClient();

        return view('tenant.contingencies.index', compact('is_client'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $series = Series::select('number')->where('contingency', true)->get();

        $records = Document::where($request->column, 'like', "%{$request->value}%")
                            ->whereIn('series',$series)
                            ->whereTypeUser()
                            ->latest();

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        $is_contingency = 1;

        return view('tenant.documents.form', compact('is_contingency'));
    }
}
