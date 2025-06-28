<?php


namespace App\Lib;

use Illuminate\Support\Facades\DB;


class Migration
{

    public static function commonColumn($table)
    {
        $table->boolean('status')->default(1);
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $usersTable = DB::getDriverName() === 'sqlite' ? 'users' : config('database.connections.mysql.database') . '.users';

        $table->foreign('created_by')->references('id')->on($usersTable)->cascadeOnDelete();
        $table->foreign('updated_by')->references('id')->on($usersTable)->cascadeOnDelete();
        $table->foreign('deleted_by')->references('id')->on($usersTable)->cascadeOnDelete();
    }


}
