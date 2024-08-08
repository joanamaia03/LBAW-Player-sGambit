<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function showFAQ()
    {
        return view('pages.faqPage');
    }
    public function showAboutUs()
    {
        return view('pages.aboutUsPage');
    }

    public function showGuide ()
    {
        return view('pages.guidePage');
    }
}
