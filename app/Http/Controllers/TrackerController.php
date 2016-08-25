<?php

namespace App\Http\Controllers;

use App\Events\Visitor\ViewedPage;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * This route is being cached in order
     * to hold the image returned
     * by Pixel middleware.
     */
    public function cache()
    {
    }

    /**
     * Save information about user's visit.
     *
     * @param Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function visit(Request $request)
    {
        $visitor = Visitor::byUniqueId($request->uniqueId);

        event(new ViewedPage($request, $visitor));
    }
}
