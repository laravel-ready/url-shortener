<?php

namespace LaravelReady\UrlShortener\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LaravelReady\UrlShortener\Http\Requests\UpdateUnicodeEmojiStatusRequest;
use LaravelReady\UrlShortener\Supports\Emoji;
use LaravelReady\UrlShortener\Supports\Eloquent;
use LaravelReady\UrlShortener\Models\Emoji\UnicodeEmoji;
use LaravelReady\UrlShortener\Traits\EmojiCacheTrait;

class EmojiController extends Controller
{
    use EmojiCacheTrait;

    /**
     * Get stable unicode emojis from SQLite database
     * 
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $emojisQueryForCacheKey = Emoji::getBaseEmojiQuery([]);
        $sqlQuery = $emojisQueryForCacheKey->toSql();
        $sqlQueryBinding = $emojisQueryForCacheKey->getBindings();
        $sqlQueryWithBindings = Str::replaceArray('?', $sqlQueryBinding, $sqlQuery);
        $page = $request->integer('page', 1);
        $perPage = $request->integer('perPage', 0);

        $cacheKey = Config::get('url-shortener.table_name', 'short_url') . '_emojis.' . $page . $perPage . md5($sqlQueryWithBindings);

        $emojis = $this->getCachedEmojis($cacheKey);

        if ($emojis) {
            return response()->json($emojis);
        }

        Eloquent::initNewDbConnection();

        $perPage = $request->integer('perPage', 0);

        $emojis = $perPage > 0 ? Emoji::getBaseEmojiQuery()->paginate($perPage, ['*'], 'page', $page) : Emoji::getBaseEmojiQuery()->get();

        Eloquent::restorePreviousDbConnection();

        $this->setCacheForEmojis($cacheKey, $emojis);

        return response()->json($emojis);
    }

    /**
     * Get all unicode emojis from SQLite database
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $perPage = $request->integer('perPage', 0);
        $page = $request->integer('page', 1);

        $emojis = $this->getCachedEmojis("all:{$perPage}:{$page}");

        if ($emojis) {
            return response()->json($emojis);
        }

        Eloquent::initNewDbConnection();

        $emojis = $perPage > 0 ? Emoji::getBaseEmojiQuery()->paginate($perPage, ['*'], 'page', $page) : Emoji::getBaseEmojiQuery()->get();

        $this->setCacheForEmojis("all:{$perPage}:{$page}", $emojis);

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
        // TODO: load direcly from databse
        $emojis = $this->getCachedEmojis('all');

        if ($emojis) {
            $emoji = collect($emojis)->firstWhere('emoji', $emoji);

            if ($emoji) {
                return response()->json($emoji);
            }
        }

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

        $this->clearEmojisCache();

        return response()->json([
            'message' => 'Emoji status updated successfully',
        ]);
    }
}
