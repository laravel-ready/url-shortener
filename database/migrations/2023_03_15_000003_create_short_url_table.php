<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = Str::plural(Config::get('url-shortener.table_name', 'short_url'));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $groupsTableName = Config::get('url-shortener.table_name', 'short_url') . '_groups';
                $faviconsTableName = Config::get('url-shortener.table_name', 'short_url') . '_favicons';

                $table->bigIncrements('id');

                $table->nullableMorphs('shortable');

                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')->comment('User Id');

                $table->foreignId('group_id')
                    ->nullable()
                    ->constrained($groupsTableName)->comment('Group Id');

                $table->foreignId('favicon_id')
                    ->nullable()
                    ->constrained($faviconsTableName)->comment('Favicon Id');

                $table->string('title', 200)->nullable()->default(null)->comment('URL title');
                $table->string('description', 1000)->nullable()->default(null)->comment('URL description');
                $table->text('short_code')->comment('Short URL code, exp: example.com/r/{short_code}');
                $table->string('hash', 32)->comment('Short code md5 hash')
                $table->enum('type', ['random', 'custom', 'emoji_random', 'emoji_custom'])->comment('Shorting type');
                $table->string('url', 2048)->comment('Redirect URL address');
                $table->boolean('status')->default(true)->comment('URL redirect status, true: active, false: inactive');
                $table->integer('delay')->default('0')->comment('Delay time for redirect (in seconds)');
                $table->dateTime('expire_date')->nullable()->default(null)->comment('URL expiration date');
                $table->text('password')->nullable()->default(null)->comment('URL password');

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->dropIfExists();
            });
        }
    }
};
