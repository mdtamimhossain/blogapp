<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private AuthService $service;
    function __construct(AuthService $service)
    {
        $this->service=$service;
    }

    /**
     * @return Application|Factory|View
     */
    public function login (): View|Factory|Application
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function processLogin (LoginRequest $request): RedirectResponse
    {

        $response=$this->service->processLogin($request->all());
        return $response['success']? redirect()->route('home')->with('success',$response['message'])
            :redirect()->back()->with('error',$response['message']);
    }

    /**
     * @return Application|Factory|View
     */
    public function register (): View|Factory|Application
    {
        return view('auth.register');
    }


    public function processRegistration (RegistrationRequest $request): RedirectResponse
    {
        $response=$this->service->processRegistration($request->all());
        return $response['success']? redirect()->route('verification')->with('success',$response['message'])
            :redirect()->back()->with('error',$response['message']);
    }
    /**
     * @return Application|Factory|View
     */
    public function verification (): View|Factory|Application
    {
        return view('auth.verification');
    }

    /**
     * @param VerificationRequest $request
     * @return RedirectResponse
     */
    public function processVerification (VerificationRequest $request): RedirectResponse
    {
        $response=$this->service->processVerification($request->all());
        return $response['success']? redirect()->route('verification')->with('success',$response['message'])
            :redirect()->back()->with('error',$response['message']);
    }

    /**
     * @return RedirectResponse
     */
    public function logout (): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('home')->with('success', "Registration Successful!");
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $code
     * @return void
     */
    private function _sendVerificationMail (string $email, string $name, string $code): void
    {
        $data['appName'] = 'Blog App';
        $data['code'] = $code;
        Mail::send('emails.email_verification', $data, function ($message) use ($email, $name) {
            $message->from('blog@gmail.com', 'Blog App');
            $message->to($email, $name)->subject('Verification Code');
        });
    }
}
