<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = Config::get('url-shortener.table_name', 'short_url') . '_favicons';
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
                $table->bigIncrements('id');

                $table->string('domain', 200)->comment('Site address');
                $table->text('favicon')->comment('Site favicon');

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
