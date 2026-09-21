<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CareerController extends Controller
{
    public function index()
    {
        return view('frontend.career');
    }

    public function submit(Request $request)
{
    $request->validate([
        'name'  => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'email' => 'required|email',
        'phone' => 'required',
        'role'  => 'required',
        'cv'    => 'nullable|mimes:pdf,doc,docx|max:2048',
    ]);

    try {

        $filename = null;

        // Upload CV
        if ($request->hasFile('cv')) {

            $file = $request->file('cv');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/cv'),
                $filename
            );
        }


        // Form Data
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'role'    => $request->role,
            'message' => $request->message ?? '',
        ];


        /*
        |--------------------------------------------------------------------------
        | ADMIN EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::send(
            'emails.career',
            [
                'data' => $data,
                'type' => 'admin',
            ],
            function ($message) use ($data, $filename) {

                $message->to('arorashivani053@gmail.com')
                    ->subject('New Career Application - ' . $data['name']);

                // Attach CV
                if ($filename) {

                    $message->attach(
                        public_path('uploads/cv/' . $filename)
                    );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::send(
            'emails.career',
            [
                'data' => $data,
                'type' => 'customer',
            ],
            function ($message) use ($data) {

                $message->to($data['email'])
                    ->subject('Thank You for Your Career Application - Time To Furnish');
            }
        );


        return redirect()->route('home')->with([
            'flash_notification' => collect([
                [
                    'level' => 'success',
                    'message' => 'Thank you for applying. We will contact you if your profile matches our requirements.'
                ]
            ])
        ]);

    } catch (\Throwable $e) {

        \Log::error('Career application email failed', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return redirect()->back()->with([
            'flash_notification' => collect([
                [
                    'level' => 'danger',
                    'message' => 'Email Error: ' . $e->getMessage()
                ]
            ])
        ]);
    }
}
}
