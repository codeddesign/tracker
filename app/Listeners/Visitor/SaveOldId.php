<?php

namespace App\Listeners\Visitor;

use App\Events\Visitor\ViewedPage;
use App\Models\Visitor;
use App\Models\VisitorOldId;
use App\Utilities\UniqueCode;

class SaveOldId
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ViewedPage $event
     */
    public function handle(ViewedPage $event)
    {
        $u = $event->request->get('u');

        if (!(new UniqueCode($u))->isValid()) {
            return false;
        }

        if ($event->visitor->unique_id == $u) {
            return false;
        }

        if (!$old = Visitor::byUniqueId($u)) {
            return false;
        }

        VisitorOldId::create([
            'new_id' => $event->visitor->id,
            'old_id' => $old->id,
        ]);
    }
}
