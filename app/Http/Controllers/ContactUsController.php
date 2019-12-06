<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Mail;

class ContactUsController extends Controller
{
    //show the contact form
    public function show()
    {
        return view('contact');
    }

    // Send email
    public function sendEmail(Request $request)
    {
        // Validate form
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'message' => 'required|min:10'
        ]);


        $request->merge(["emailFrom"=>$request->contact."@thevinylshop.com"]);

        // Send email
        $email = new ContactMail($request);
        Mail::to($request) // or Mail::to($request->email, $request->name)
            ->send($email);


        // Flash a success message to the session
        session()->flash('success', 'Thanks for you message.<br>We\'ll contact you as soon as possible.');
        // Redirect to the contact-us link ( NOT to view('contact')!!! )
        return redirect('contact-us');

        // Flash filled-in form values to the session
        $request->flash();
        // Flash a success message to the session
        session()->flash('success', 'Thank you for your message.<br>We\'ll contact you as soon as possible.');

        // Redirect to the contact-us link ( NOT to view('contact')!!! )
        return redirect('contact-us');
    }
}
