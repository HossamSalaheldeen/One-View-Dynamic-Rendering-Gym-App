<?php

namespace App\Models;

use App\Traits\HasFiles;
use App\Traits\HasStaticTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property string $path
 * @property int|null $attachable_id
 * @property string|null $attachable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $attachable
 * @property-read mixed $attachment_download
 * @property-read mixed $attachment_extension
 * @property-read mixed $attachment_size
 * @property-read mixed $attachment_url
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AttachmentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    use HasFactory, HasFiles, HasStaticTableName;

    protected $fillable = [
        "path",
        "attachable_id",
        "attachable_type",
    ];

    public function getAttachmentUrlAttribute()
    {
        return $this->getFileUrl($this->path);
    }

    public function getAttachmentDownloadAttribute()
    {
        return $this->getDownloadFileUrl($this->path);
    }

    public function getAttachmentSizeAttribute()
    {
        return round($this->getFileSize($this->path)/1000);
    }

    function getAttachmentExtensionAttribute($name) {
        $n = strrpos($this->filename,".");
        return ($n===false) ? "" : substr($this->filename,$n+1);
    }

    public function attachable()
    {
        return $this->morphTo();
    }

//    public function user(){
//        return $this->belongsTo(User::class);
//    }


}
