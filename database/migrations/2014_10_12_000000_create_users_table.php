<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone')->unique();
            $table->integer('role');
            $table->string('unique_user')->unique();
            $table->rememberToken();
            $table->timestamps();
        });

        $users=User::create([
            'name'=>'doken',
            'email'=>'dokfongang34@gmail.com',
            'password'=>'dokenpro2024',
            'telephone'=>'651726743',
            'role'=>1,
            'unique_user'=>'df'
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
