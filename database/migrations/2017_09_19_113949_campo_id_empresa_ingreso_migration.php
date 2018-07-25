<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoIdEmpresaIngresoMigration extends Migration
{
    public function up()
    {
        // Creo un campo apellidos "last_name"
        Schema::table('ingreso', function($table){
          $table->string('id_empresa', 10);
        });
    }

    public function down()
    {
        // Elimino el campo last_name
        Schema::table('ingreso', function ($table) {
            $table->dropColumn('id_empresa');
        });
    }
}
