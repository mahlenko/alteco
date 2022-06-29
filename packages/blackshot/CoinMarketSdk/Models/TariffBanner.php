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

class TariffBanner extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    const PICTURE_IMAGE_FOLDER = 'public/banners';

    protected $fillable = [
        'body',
        'start',
        'end',
        'is_active',
        'created_user_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
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

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(TariffModel::class, 'tariff_id');
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

        self::creating(function(TariffBanner $banner) {
            $banner->uuid = Uuid::uuid4();
            $banner->views = 0;
        });

    }


}
