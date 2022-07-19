<?php

namespace Blackshot\CoinMarketSdk\Models;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Banner extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    const PICTURE_IMAGE_FOLDER = 'public/banners';

    protected $fillable = [
        'title',
        'body',
        'color_scheme',
        'button_text',
        'button_url',
        'start',
        'end',
        'type',
        'is_active',
        'delay_seconds',
        'not_disturb_hours',
        'created_user_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'is_active' => 'bool'
    ];

    public function pictureUrl()
    {
        return $this->picture
            ? Storage::url(self::PICTURE_IMAGE_FOLDER .'/'. $this->picture)
            : null;
    }

    /**
     * @param UploadedFile|null $file
     * @return $this
     */
    public function updatePicture(UploadedFile $file = null): static
    {
        if (is_null($file)) {
            return $this;
        }

        //
        $folder = trim(self::PICTURE_IMAGE_FOLDER, '/');
        $filename = Str::random(4) .'.'. $file->extension();

        //
        if ($file->storePubliclyAs($folder, $filename) !== false) {
            // delete last picture file
            $lastfile = $folder .'/'. $this->picture;

            if (!empty($this->picture) && Storage::exists($lastfile)) {
                Storage::delete($lastfile);
            }

            $this->picture = $filename;
        }

        return $this;
    }

    /**
     * @param bool $is_active
     * @return $this
     */
    public function active(bool $is_active): static
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @param DateTimeImmutable $start
     * @return $this
     */
    public function setStart(DateTimeImmutable $start): static
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param DateTimeImmutable|null $end
     * @return $this
     */
    public function setEnd(DateTimeImmutable $end = null): static
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setDelay(int $seconds = 0): static
    {
        $this->delay = $seconds;
        return $this;
    }

    /**
     * @return $this
     */
    public function resetViews()
    {
        $this->views = 0;
        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function creater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    protected static function booted()
    {
        parent::booted();

        self::creating(function(Banner $banner) {
            $banner->uuid = Uuid::uuid4();
            $banner->views = 0;
        });

    }


}
