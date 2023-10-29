<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Requests\UpdateUnicodeEmojiStatusRequest;
use LaravelReady\UrlShortener\Supports\Emoji;
use LaravelReady\UrlShortener\Supports\Eloquent;
use LaravelReady\UrlShortener\Models\Emoji\UnicodeEmoji;

class EmojiController extends Controller
{
    /**
     * Get stable unicode emojis from SQLite database
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $emojisQueryForCacheKey = Emoji::getBaseEmojiQuery();
        $sqlQuery = $emojisQueryForCacheKey->toSql();
        $sqlQueryBinding = $emojisQueryForCacheKey->getBindings();
        $sqlQueryWithBindings = Str::replaceArray('?', $sqlQueryBinding, $sqlQuery);

        $cacheKey = Config::get('url-shortener.table_name', 'short_url') . '_emojis.' . md5($sqlQueryWithBindings);

        if (Cache::has($cacheKey)) {
            return response()->json(
                Cache::get($cacheKey, [])
            );
        }

        Eloquent::initNewDbConnection();

        $emojis = Emoji::getBaseEmojiQuery()->get();

        Eloquent::restorePreviousDbConnection();

        Cache::put($cacheKey, $emojis, now()->addYear());

        return response()->json($emojis);
    }

    /**
     * Get all unicode emojis from SQLite database
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(): JsonResponse
    {
        Eloquent::initNewDbConnection();

        $emojis = UnicodeEmoji::all();

        Eloquent::restorePreviousDbConnection();

        return response()->json($emojis);
    }

    /**
     * Show selected unicode emoji from SQLite database
     * 
     * @param int $emoji
     * @return JsonResponse|UnicodeEmoji
     */
    public function show(int $emoji): JsonResponse | UnicodeEmoji
    {
        Eloquent::initNewDbConnection();

        $unicodeEmoji = UnicodeEmoji::find($emoji);

        if (!$unicodeEmoji) {
            return response()->json([
                'message' => 'Emoji not found',
            ], 404);
        }

        $unicodeEmoji->load([
            'version',
            'emojiVersion',
        ]);

        Eloquent::restorePreviousDbConnection();

        return $unicodeEmoji;
    }

    public function updateStatus(UpdateUnicodeEmojiStatusRequest $request, int $emoji): JsonResponse
    {
        Eloquent::initNewDbConnection();

        $unicodeEmoji = UnicodeEmoji::find($emoji);

        if (!$unicodeEmoji) {
            return response()->json([
                'message' => 'Emoji not found',
            ], 404);
        }

        $result = $unicodeEmoji->update([
            'isActive' => $request->status,
        ]);

        Eloquent::restorePreviousDbConnection();

        if (!$result) {
            return response()->json([
                'message' => 'Failed to update emoji status',
            ], 500);
        }

        return response()->json([
            'message' => 'Emoji status updated successfully',
        ]);
    }
}
