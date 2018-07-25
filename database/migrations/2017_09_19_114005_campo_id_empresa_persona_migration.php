<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoIdEmpresaPersonaMigration extends Migration
{
    public function up()
    {
        // Creo un campo apellidos "last_name"
        Schema::table('persona', function($table){
          $table->string('id_empresa', 10);
        });
    }

    public function down()
    {
        // Elimino el campo last_name
        Schema::table('persona', function ($table) {
            $table->dropColumn('id_empresa');
        });
    }
}
