<?php

namespace Modules\Document\Http\ViewComposers;
use Modules\Document\Models\Document;

class DocumentViewComposer
{
    public function compose($view)
    {
        $view->vc_document = Document::whereNotSent()->count();

        $view->vc_document_regularize_shipping = Document::whereRegularizeShipping()->count();
    }
}
