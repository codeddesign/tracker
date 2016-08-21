<?php

namespace App\Http\Controllers;

use App\Events\Visitor\ViewedPage;
use App\Http\Traits\TrackerCookie;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    use TrackerCookie;

    /**
     * Create unique id if it doesn't exist.
     *
     * Update or set cookie value.
     *
     * @return use Illuminate\Http\Response
     */
    public function unique(Request $request)
    {
        if (!$uniqueId = $this->cookie()) {
            $uniqueId = Visitor::add();
        }

        $cookie = $this->cookie($uniqueId);

        return response($uniqueId)->withCookie($cookie);
    }

    /**
     * Save information about user's visit.
     *
     * @todo: Re-check the value of cookie 'u'?
     * @todo: Fallback to value 'u' value?
     *
     * @param Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function visit(Request $request)
    {
        $uniqueId = $this->cookie();
        $visitor = Visitor::whereUniqueId($uniqueId)->first();

        event(new ViewedPage($request, $visitor));
    }
}
