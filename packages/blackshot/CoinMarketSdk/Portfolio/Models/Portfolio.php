<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Models;

use App\Models\User;
use Database\Factories\PortfolioFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function name(): Attribute {
        return Attribute::get(fn($value) => ucfirst($value));
    }

    public function isUserTo(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    protected static function newFactory()
    {
        return PortfolioFactory::new();
    }
}
