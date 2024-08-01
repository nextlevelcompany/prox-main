<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\File;
use GrahamCampbell\Markdown\Facades\Markdown;

class UpdateController extends Controller
{
    public function index()
    {
        return view('system.update.index');
    }

    public function version()
    {
        $id = New Process(['git describe --tags']);
        $id->setWorkingDirectory(base_path());
        $id->run();
        $res_id = $id->getOutput();
        return json_encode($res_id);
    }

    public function branch()
    {
        $process = new Process(['git', 'rev-parse --abbrev-ref HEAD']);
        $process->setWorkingDirectory(base_path());
        $process->run();
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function changelog() {

        $file = File::get(base_path('CHANGELOG.md'));
        return Markdown::convert($file);
    }
}
