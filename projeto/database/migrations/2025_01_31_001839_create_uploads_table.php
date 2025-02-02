<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_histories_id');
            $table->foreign('upload_histories_id')
                ->references('id')
                ->on('upload_histories')
                ->onDelete('cascade');
            $table->date('RptDt')->nullable();
            $table->string('TckrSymb', 50)->nullable();
            $table->string('MktNm', 50)->nullable();
            $table->string('SctyCtgyNm', 50)->nullable();
            $table->string('ISIN', 50)->nullable();
            $table->string('CrpnNm', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
