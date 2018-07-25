<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersIdEmpresaMigration extends Migration
{
    public function up()
    {
        // Creo un campo apellidos "last_name"
        Schema::table('users', function($table){
          $table->string('id_empresa', 250);
        });
    }

    public function down()
    {
        // Elimino el campo last_name
        Schema::table('users', function ($table) {
            $table->dropColumn('id_empresa');
        });
    }
}
