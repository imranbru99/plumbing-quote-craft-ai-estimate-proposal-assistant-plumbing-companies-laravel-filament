<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_technician_id')->nullable()->constrained('technicians')->nullOnDelete();
            $table->foreignId('intake_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('request_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('job_type')->nullable();
            $table->string('priority')->default('normal');
            $table->string('status')->default('intake');
            $table->string('site_address')->nullable();
            $table->date('preferred_date')->nullable();
            $table->decimal('estimated_hours', 8, 1)->nullable();
            $table->unsignedInteger('outlet_count')->nullable();
            $table->unsignedInteger('panel_amps')->nullable();
            $table->string('source')->default('phone');
            $table->json('raw_intake')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};