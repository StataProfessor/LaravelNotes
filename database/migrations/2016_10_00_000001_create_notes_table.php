<?php

declare(strict_types=1);

use Arcanedev\LaravelNotes\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateDiscussionsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @see \Arcanedev\LaravelNotes\Models\Note
 */
return new class extends Migration
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * CreateParticipantsTable constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('notes.notes.table', 'notes'));
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createSchema(function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->string('title')->default('null');
            $table->morphs('noteable');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->timestamps();

            $table->foreign('author_id')
                  ->references('id')
                  ->on((string) config('notes.authors.table', 'users'))
                  ->onDelete('cascade');
                  
            // Foreign key for folder
            $table->foreign('folder_id')
                  ->references('id')
                  ->on('folders') 
                  ->onDelete('set null'); 
        });
    }
};
