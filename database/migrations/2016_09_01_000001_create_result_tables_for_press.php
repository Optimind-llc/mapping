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
            $table->string('QRcode', 134)->unique();
            $table->string('line_code', 16);
            $table->string('vehicle_code', 16);
            $table->string('pt_pn', 10);
            $table->integer('figure_id')->unsigned()->nullable();
            $table->string('mold_type_num', 4);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->tinyInteger('f_keep')->unsigned()->default(0);
            $table->tinyInteger('m_keep')->unsigned()->default(0);
            $table->tinyInteger('discarded')->unsigned()->default(0);

            $table->string('inspected_choku', 8);
            $table->string('modificated_choku', 8)->nullable();
            $table->string('updated_choku', 8)->nullable();

            $table->string('inspected_by', 8);
            $table->string('modificated_by', 8)->nullable();
            $table->string('updated_by', 8)->nullable();
            $table->string('picked_by')->nullable();

            $table->integer('palet_num')->unsigned();
            $table->integer('palet_max')->unsigned();
            $table->string('re_print_sec', 255)->nullable();
            $table->integer('control_num')->nullable();

            $table->string('inspected_iPad_id', 255);
            $table->string('modificated_iPad_id', 255)->nullable();
            $table->string('updated_iPad_id', 255)->nullable();

            $table->string('tpsResponce', 255)->nullable();
            $table->tinyInteger('tpsResponceStatus')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamp('inspected_at');
            $table->timestamp('modificated_at')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->tinyInteger('latest')->unsigned()->default(1);

            $table->string('ft_ids', 512)->nullable();
            $table->string('f_comment', 255)->nullable();
            $table->string('m_comment', 255)->nullable();

            $table->timestamps();

            /*
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
            $table->integer('x1')->unsigned()->default(0);
            $table->integer('y1')->unsigned()->default(0);
            $table->integer('x2')->unsigned()->default(0);
            $table->integer('y2')->unsigned()->default(0);
            $table->integer('f_qty')->unsigned()->default(0);
            $table->integer('m_qty')->unsigned()->nullable();
            $table->tinyInteger('responsible_for')->unsigned()->nullable();
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

        Schema::connection('press')->create('memos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('line_code', 16);
            $table->string('vehicle_code', 16);
            $table->string('pt_pn', 10);
            $table->tinyInteger('discarded')->unsigned()->default(0);
            $table->integer('figure_id')->unsigned()->nullable();
            $table->tinyInteger('keep')->unsigned()->default(0);
            $table->string('comment', 255)->nullable();
            $table->string('ft_ids', 512)->nullable();
            $table->string('created_choku', 8);
            $table->string('updated_choku', 8)->nullable();
            $table->string('created_by', 8);
            $table->string('updated_by', 8)->nullable();
            $table->string('created_iPad_id', 255);
            $table->string('updated_iPad_id', 255);
            $table->timestamps();

            /*
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

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        Schema::connection('press')->create('memo_failures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('memo_id')->unsigned();
            $table->integer('figure_id')->unsigned()->nullable();
            $table->integer('x1')->unsigned()->default(0);
            $table->integer('y1')->unsigned()->default(0);
            $table->integer('x2')->unsigned()->default(0);
            $table->integer('y2')->unsigned()->default(0);
            $table->integer('palet_first')->unsigned()->default(0);
            $table->integer('palet_last')->unsigned()->nullable();
            $table->date('modificated_at');
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('failure_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('memo_id')
                ->references('id')
                ->on('memos')
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
        Schema::connection('press')->drop('memo_failures');
        Schema::connection('press')->drop('memos');
        Schema::connection('press')->drop('failures');
        Schema::connection('press')->drop('inspection_results');
    }
}





