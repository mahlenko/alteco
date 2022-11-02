<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Models;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Database\factories\PortfolioTransactionFactory;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'portfolio_transaction';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'coin_uuid',
        'price',
        'quantity',
        'fee',
        'date_at',
        'type',
        'transfer_type'
    ];

    protected $casts = [
        'date_at' => 'datetime',
        'price' => 'double',
        'quantity' => 'double',
        'fee' => 'double',
    ];

    public function total(): Attribute
    {
        return Attribute::get(function() {
            return $this->price * $this->quantity;
        });
    }

    public function totalWithFee(): Attribute
    {
        return Attribute::get(function() {
            return $this->total - $this->fee;
        });
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quantityModifiedByType()
    {
        if ($this->type == TransactionTypeEnum::Sell) {
            $this->quantity = abs($this->quantity) * -1;
        }

        if ($this->type == TransactionTypeEnum::Transfer) {
            if (!$this->transfer_type) {
                throw new TransferException('Не выбрано направление перевода.');
            }
        } else {
            $this->transfer_type = null;
        }

        if ($this->type == TransactionTypeEnum::Transfer
            && $this->transfer_type == TransferTypeEnum::Out) {
            $this->quantity = abs($this->quantity) * -1;
        }
    }

    public function getTypeAttribute()
    {
        foreach (TransactionTypeEnum::cases() as $case) {
            if ($case->name === $this->attributes['type']) {
                return $case;
            }
        }

        return null;
    }

    public function setTypeAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['type'] = $value;
        } else {
            $this->attributes['type'] = $value->name;
        }
    }

    public function getTransferTypeAttribute()
    {
        if ($this->attributes['transfer_type']) {
            foreach (TransferTypeEnum::cases() as $case) {
                if ($case->name === $this->attributes['transfer_type']) {
                    return $case;
                }
            }
        }

        return $this->attributes['transfer_type'];
    }

    public function setTransferTypeAttribute($value)
    {
        if ($value instanceof TransferTypeEnum) {
            $value = $value->name;
        }

        $this->attributes['transfer_type'] = $value;
    }

    protected static function newFactory(): PortfolioTransactionFactory
    {
        return PortfolioTransactionFactory::new();
    }

    protected static function booted()
    {
        parent::booted();

        parent::creating(function(self $transaction) {
            if (!$transaction->uuid) {
                $transaction->uuid = Uuid::uuid4()->toString();
            }

            $transaction->quantityModifiedByType();
        });

        parent::updating(function(self $transaction) {
            $transaction->quantityModifiedByType();
        });
    }
}
