<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sn_categories', function (Blueprint $table) {
            $table->comment('分类');
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('scope_type', 20)->nullable()->comment('范围类型');
            $table->unsignedBigInteger('scope_id')->default(0)->comment('范围');

            $table->nestedSet();        // Nested Set fields for hierarchical structure
            $table->integer('type_id')->default(0)->comment('类别');
            $table->string('name')->nullable()->comment('名称');
            $table->string('description')->nullable()->comment('描述');

            $table->json('options')->nullable()->comment('选项');
            $table->string('status')->nullable()->comment('状态');
            $table->timestamps();
            $table->index(['scope_type', 'scope_id']);
            $table->index('type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_categories');
    }
};
