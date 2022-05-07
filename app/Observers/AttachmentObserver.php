<?php

namespace App\Observers;

use App\Models\Attachment;
use App\Traits\HasFiles;

class AttachmentObserver
{
    use HasFiles;
    /**
     * Handle the Attachment "created" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function created(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the Attachment "updated" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function updated(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the Attachment "deleted" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function deleted(Attachment $attachment)
    {
        if ($attachment->path != 'default-avatar.png') {
            $this->deleteFile($attachment->path);
        }
    }

    /**
     * Handle the Attachment "restored" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function restored(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the Attachment "force deleted" event.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return void
     */
    public function forceDeleted(Attachment $attachment)
    {
        //
    }
}
