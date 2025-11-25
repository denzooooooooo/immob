<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'agent', 'client'])->default('client')->after('email');
            $table->string('phone')->nullable()->after('email');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('phone');
            $table->string('avatar')->nullable()->after('status');
            $table->string('company_name')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('bio');
            $table->string('address')->nullable()->after('website');
            $table->string('city')->nullable()->after('address');
            $table->boolean('email_notifications')->default(true)->after('city');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            $table->boolean('property_alerts')->default(true)->after('sms_notifications');
            $table->boolean('price_alerts')->default(false)->after('property_alerts');
            $table->timestamp('last_login_at')->nullable()->after('price_alerts');
            $table->string('verification_code')->nullable()->after('last_login_at');
            $table->timestamp('phone_verified_at')->nullable()->after('verification_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'status', 'avatar', 'company_name', 'bio', 
                'website', 'address', 'city', 'email_notifications', 
                'sms_notifications', 'property_alerts', 'price_alerts',
                'last_login_at', 'verification_code', 'phone_verified_at'
            ]);
        });
    }
};
