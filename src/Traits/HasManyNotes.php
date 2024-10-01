<?php

declare(strict_types=1);

namespace Arcanedev\LaravelNotes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class     HasManyNotes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Database\Eloquent\Collection  notes
 */
trait HasManyNotes
{
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * The notes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes(): MorphMany
    {
        return $this->morphMany((string) config('notes.notes.model'), 'noteable');
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a note.
     *
     * @param  string                                    $content
     * @param  \Illuminate\Database\Eloquent\Model|null  $author
     * @param  bool                                      $reload
     *
     * @return \Arcanedev\LaravelNotes\Models\Note
     */
    public function createNote($content, $author = null, $reload = true, $title = null, $folder_id = null)
    {
        /** @var \Arcanedev\LaravelNotes\Models\Note $note */
        $note = $this->notes()->create(
            $this->prepareNoteAttributes($content, $author, $title, $folder_id)
        );

        // Attach tags to the note
        if (!empty($tags)) {
            $note->attachTags($tags);
        }

        if ($reload) {
            $relations = array_merge(
                ['notes'],
                method_exists($this, 'authoredNotes') ? ['authoredNotes'] : []
            );

            $this->load($relations);
        }

        return $note;
    }

    /**
     * Retrieve a note by its ID.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findNote($id)
    {
        return $this->notes()->find($id);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Prepare note attributes.
     *
     * @param  string                                    $content
     * @param  \Illuminate\Database\Eloquent\Model|null  $author
     *
     * @return array
     */
    protected function prepareNoteAttributes($content, Model $author = null, $title = null, $folder_id = null)
    {
        return [
            'author_id' => is_null($author) ? $this->getCurrentAuthorId() : $author->getKey(),
            'content'   => $content,
            'title'     => $title,
            'folder_id' => $folder_id,
        ];
    }

    /**
     * Get the current author's id.
     *
     * @return int|null
     */
    protected function getCurrentAuthorId()
    {
        return null;
    }
}
