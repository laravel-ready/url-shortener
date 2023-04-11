<?php

namespace LaravelReady\UrlShortener\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelReady\UrlShortener\Traits\Shortable;
use LaravelReady\UrlShortener\Enums\ShortingType;
use Illuminate\Database\Eloquent\Relations\HasOne;
use LaravelReady\UrlShortener\Models\ShortUrlGroup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    use SoftDeletes, Shortable;

    public function __construct(array $attributes = [])
    {
        $this->table = Str::plural(Config::get('url-shortener.table_name', 'short_url'));

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    protected $table = 'short_urls';

    protected $fillable = [
        'user_id',
        'group_id',
        'favicon_id',
        'short_code',
        'type',
        'url',
        'status',
        'delay',
        'expire_date',
        'password',
        'title',
        'description',
    ];

    protected $casts = [
        'type' => ShortingType::class,
        'status' => 'boolean',
        'expire_date' => 'datetime',
    ];

    protected $appends = [
        'expires_in',
        'expire_status',
        'short_url',
    ];

    public function getExpiresInAttribute(): int
    {
        if ($this->expiration_date < now()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expiration_date);
    }

    public function getExpireStatusAttribute(): string
    {
        if ($this->expiration_date && $this->expiration_date < now()) {
            return 'expired';
        }

        return 'active';
    }

    public function getShortUrlAttribute(): string
    {
        return urldecode(route('url-shortener.redirect.index', $this->short_code));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ShortUrlGroup::class);
    }

    public function favicon(): HasOne
    {
        return $this->hasOne(ShortUrlFavicon::class);
    }

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->format('Y-m-d H:i');
    }
    public function getUpdatedAtAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->format('Y-m-d H:i');
    }
}
