<?php

namespace LaravelReady\UrlShortener\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class ShortUrlGroup extends Model
{
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        $prefix = Config::get('url-shortener.table_name', 'short_url') . '_groups';

        $this->table = "{$prefix}_licenses";

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

    protected $table = 'short_url_groups';

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shortUrls(): HasMany
    {
        return $this->hasMany(ShortUrl::class, 'group_id');
    }
}
