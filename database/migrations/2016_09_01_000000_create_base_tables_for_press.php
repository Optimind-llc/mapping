<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBaseTablesForPress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::connection('press')->create('vehicles', function (Blueprint $table) {
            $table->string('code', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('code');
        });

        Schema::connection('press')->create('lines', function (Blueprint $table) {
            $table->string('code', 16);
            $table->string('code_inQR', 16)->unique();
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('code');
        });

        Schema::connection('press')->create('part_types', function (Blueprint $table) {
            $table->string('pn', 10);
            $table->string('name', 32)->unique()->nullable();
            $table->tinyInteger('capacity')->unsigned()->default(1);
            $table->string('en', 32)->unique()->nullable();
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('pn');
        });

        Schema::connection('press')->create('part_type_pair', function (Blueprint $table) {
            $table->increments('id');
            $table->string('left_pn', 10);
            $table->string('right_pn', 10);

            /**
             * Add Foreign
             */
            $table->foreign('left_pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('right_pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('press')->create('figures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pt_pn', 10);
            $table->string('path');
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('pt_pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('press')->create('combinations', function (Blueprint $table) {
            $table->string('line_code', 16);
            $table->string('vehicle_code', 16);
            $table->string('pt_pn', 10);
            $table->timestamps();

            /**
             * Add Foreign
             */

            $table->foreign('line_code')
                ->references('code')
                ->on('lines')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('vehicle_code')
                ->references('code')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('pt_pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['line_code', 'vehicle_code', 'pt_pn']);
        });


        Schema::connection('press')->create('chokus', function (Blueprint $table) {
            $table->string('code', 1);
            $table->string('name', 16)->unique();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('code');
        });

        Schema::connection('press')->create('workers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->string('yomi', 16);
            $table->integer('sort')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('choku_code', 1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('choku_code')
                ->references('code')
                ->on('chokus')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('press')->create('worker_related', function (Blueprint $table) {
            $table->integer('worker_id')->unsigned();
            $table->string('line_code', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('worker_id')
                ->references('id')
                ->on('workers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('line_code')
                ->references('code')
                ->on('lines')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['worker_id', 'line_code']);
        });


        Schema::connection('press')->create('failure_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16)->unique();
            $table->integer('label')->unsigned()->default(1);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::connection('press')->drop('failure_types');
        Schema::connection('press')->drop('worker_related');
        Schema::connection('press')->drop('workers');
        Schema::connection('press')->drop('chokus');
        Schema::connection('press')->drop('combinations');
        Schema::connection('press')->drop('figures');
        Schema::connection('press')->drop('part_type_pair');
        Schema::connection('press')->drop('part_types');
        Schema::connection('press')->drop('lines');
        Schema::connection('press')->drop('vehicles');
    }
}
