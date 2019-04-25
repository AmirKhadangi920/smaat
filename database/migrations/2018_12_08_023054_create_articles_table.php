<?php

use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new Blueprint($table, $callback);
        });

        $schema->create('articles', function (Blueprint $table) {
            $table->table([
                'info',
                'body',
                'image' => 'array',
                'reading_time' => 'nullable|mediumInteger|comment:How much time need for reading the article in minute',
                'jalali_created_at' => 'datetime|nullable'
            ], [
                'tenants',
                'users',
            ], 'uuid');
        });

        $schema->create('article_subject', function (Blueprint $table) {
            $table->interface('articles', 'subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema->dropIfExists('articles');
    }
}
