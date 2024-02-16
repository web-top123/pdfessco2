<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Mail;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    protected $email = 'andrei.popa@snowberry.co';

    public function store (ContactRequest $request) {

        Mail::to($request->email)->send(new ContactMail($request->all()));
        return back()->with('status', 'Mail sent!');
    }
}
