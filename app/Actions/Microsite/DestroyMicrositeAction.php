<?php

namespace App\Actions\Microsite;

use App\Models\Microsite;

class DestroyMicrositeAction
{
    public function execute(Microsite $microsite): void
    {
        $microsite->delete();
    }
}
