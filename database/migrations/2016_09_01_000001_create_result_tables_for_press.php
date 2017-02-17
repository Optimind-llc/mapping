<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultTablesForPress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::connection('press')->create('inspection_results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vehicle_code', 16);
            $table->string('line_code', 16);
            $table->string('pt_pn', 10);
            $table->integer('figure_id')->unsigned()->nullable();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->tinyInteger('discarded')->unsigned()->default(0);
            $table->string('created_choku', 8);
            $table->string('updated_choku', 8)->nullable();
            $table->string('created_by', 8);
            $table->string('updated_by', 8)->nullable();
            $table->integer('palet_num')->unsigned();
            $table->integer('palet_max')->unsigned();
            $table->string('ft_ids', 512)->nullable();
            $table->string('f_comment', 255)->nullable();
            $table->string('m_comment', 255)->nullable();
            $table->timestamp('processed_at');
            $table->timestamp('inspected_at');
            $table->timestamp('modificated_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->tinyInteger('latest')->unsigned()->default(1);
            $table->integer('control_num')->unique()->nullable();
            $table->timestamps();

            /*
             * Add Foreign
             */
            $table->foreign('vehicle_code')
                ->references('code')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('line_code')
                ->references('code')
                ->on('lines')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('pt_pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        Schema::connection('press')->create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('figure_id')->unsigned()->nullable();
            $table->integer('x')->unsigned()->default(0);
            $table->integer('y')->unsigned()->default(0);
            $table->integer('sub_x')->unsigned()->default(0);
            $table->integer('sub_y')->unsigned()->default(0);
            $table->integer('f_qty')->unsigned()->default(0);
            $table->integer('m_qty')->unsigned()->nullable();
            $table->string('responsible_for', 1)->nullable();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('failure_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::connection('press')->drop('failures');
        Schema::connection('press')->drop('inspection_results');
    }
}





