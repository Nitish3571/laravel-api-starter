<?php


namespace App\Lib;


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

        $database = config('database.connections.mysql.database');
        // $database = 'ucsapp';
        $table->foreign('created_by')->on("$database.users")->references('id')->cascadeOnDelete();
        $table->foreign('updated_by')->on("$database.users")->references('id')->cascadeOnDelete();
        $table->foreign('deleted_by')->on("$database.users")->references('id')->cascadeOnDelete();
    }

}
