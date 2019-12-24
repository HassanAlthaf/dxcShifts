<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function settings()
    {
        return view('settings');
    }

    protected function updateConfiguration($key, $newValue, $delimeter = '')
    {
        $path = base_path('.env');
        $oldValue = env($key);

        if ($key == 'EMAILS_ON') {
            $oldValue = env($key) ? 'true' : 'false';
        }

        if ($oldValue === $newValue) {
            return;
        }

        if (file_exists($path)) {
            file_put_contents(
                $path, str_replace(
                    $key . '=' . $delimeter . $oldValue . $delimeter,
                    $key . '=' . $delimeter . $newValue . $delimeter,
                    file_get_contents($path)
                )
            );
        } else {
            throw new FileNotFoundException("dotEnv file not Found!");
        }
    }

    public function updateSettings(Request $request)
    {
        Validator::make($request->all(), [
            'target_email' => 'required|email'
        ])->validate();

        $emailsOn = $request->has('emails_on') ? 'true' : 'false';

        $this->updateConfiguration('TARGET_EMAIL', $request->get('target_email'));
        $this->updateConfiguration('EMAILS_ON', $emailsOn);

        return redirect()->back()->with(['success' => 'Your settings have been updated!']);
    }
}
