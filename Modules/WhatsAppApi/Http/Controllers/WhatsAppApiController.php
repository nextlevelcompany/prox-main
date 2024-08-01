<?php

namespace Modules\WhatsAppApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WhatsAppApi\Services\WhatsAppCloudApi;
use Modules\WhatsAppApi\Http\Requests\Api\SendMessageRequest;

class WhatsAppApiController extends Controller
{
    public function sendMessage(SendMessageRequest $request)
    {
        return (new WhatsAppCloudApi())->sendMessage($request->all());
    }

}
