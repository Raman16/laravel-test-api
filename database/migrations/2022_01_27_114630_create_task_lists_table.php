<?php

use App\Models\TaskList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // $table->unsignedBigInteger('todo_list_id')->default(0);
            $table->foreignId('todo_list_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('label_id')->nullable();
            $table->string('status')->default(TaskList::NOT_STARTED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_lists');
    }
}
