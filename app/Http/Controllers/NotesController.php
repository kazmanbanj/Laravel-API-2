<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    private function notFoundMessage()
    {
        return [
            'status' => 404,
            'message' => 'Note not found',
            'success' => false,
        ];
    }

    private function successfulMessage($code, $count, $payload, $status, $message)
    {
        return [
            'status' => $code,
            'count' => $count,
            'data' => $payload,
            'message' => $status,
            'success' => $message,
        ];
    }

    // view all notes
    public function allNotes()
    {
        $notes = Note::all();
        $response = $this->successfulMessage(200, $notes->count(), $notes, 'Successful', true);

        return response($response);
    }

    //create new note
    public function create()
    {
        //
    }

    //create new note
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3|unique:notes',
            'body' => 'required|string|min:5',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['data'] = $validator;
            return $response;
        }

        $note = new Note;
        $note->name = $request->name;
        $note->body = $request->body;
        $note->save();
        $response = $this->successfulMessage(201, $note->count(), $note, 'Successfully created', true);
        return response($response);
    }


    public function show($id)
    {
        $note = Note::findOrFail($id);
        $response = $this->successfulMessage(200, $note->count(), $note, 'Successful', true);

        return response($response);
    }

    public function update(Request $request, Note $note)
    {
        $rules = [
            'name' => 'required|string|min:3|unique:notes',
            'body' => 'required|string|min:5',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['data'] = $validator;
            return $response;
        }

        $note = new Note;
        $note->name = $request->name;
        $note->body = $request->body;
        $note->update();
        $response = $this->successfulMessage(201, $note->count(), $note, 'Successfully updated', true);

        return response($response);
    }

    // permanently delete a data
    public function permanentDelete($id)
    {
        $note = Note::destroy($id);
        if ($note) {
            $response = $this->successfulMessage(200,  0, '', 'Successfully deleted', true);
        } else {
            $response = $this->notFoundMessage();        
        }

        return response($response);
    }

    // deleting a data softly
    public function softDelete($id)
    {
        $note = Note::destroy($id);
        if ($note) {
            $response = $this->successfulMessage(200, 0, '', 'Successfully deleted', true);
        } else {
            $response = $this->notFoundMessage();
        }

        return response($response);
    }

    //returns both non-deleted and softdeleted
    public function notesWithSoftDelete()
    {
        $notes = Note::withTrashed()->get();
        $response = $this->successfulMessage(200, $notes->count(), $notes, 'Successful', true);

        return response($response);
    }

    // returns only softDeleted data
    public function softDeleted()
    {
        $notes = Note::onlyTrashed()->get();
        $response = $this->successfulMessage(200, $notes->count(), $notes, 'Successful', true);

        return response($response);
    }

    // restores a soft deleted data
    public function restore($id)
    {
        $note = Note::onlyTrashed()->find($id);

        if (!is_null($note)) {
            $note->restore();
            $response = $this->successfulMessage(200, $note->count(), $note, 'Successfully restored', true);
        } else {
            $response = $this->notFoundMessage();
            return response($response);
        }

        return response($response);
    }

    // permanently deleting a soft deleted data
    public function permanentDeleteSoftDeleted($id)
    {
        $note = Note::onlyTrashed()->find($id);

        if (!is_null($note)) {
            $note->forceDelete();
            $response = $this->successfulMessage(200, $note->count(), $note, 'Deleted permanently', true);
        } else {
            $response = $this->notFoundMessage();
            return response($response);
        }

        return response($response);
    }
}
