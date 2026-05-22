<?php

declare(strict_types=1);

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->enum('type', array_column(PropertyType::cases(), 'value'))
                ->default(PropertyType::House->value)
                ->index();

            $table->enum('status', array_column(PropertyStatus::cases(), 'value'))
                ->default(PropertyStatus::Active->value)
                ->index();

            $table->decimal('price', 12, 2);
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->decimal('area', 10, 2)->nullable()->comment('Square feet');

            $table->string('city')->index();
            $table->string('address');

            $table->string('featured_image')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'type', 'city']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
