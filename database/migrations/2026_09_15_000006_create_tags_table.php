<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            // Namen eNamen en slugs zijn uniek, zodat er geen dubbele tags ontstaan en de URL-filters duidelijk blijven.
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('project_tag', function (Blueprint $table) {
            //De koppeltabel zorgt ervoor dat dezelfde combinatie van een project en tag maar één keer kan voorkomen.
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tag');
        Schema::dropIfExists('tags');
    }
};
