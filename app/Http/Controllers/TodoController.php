<?php

namespace App\Http\Controllers;

use App\Dtos\Response\Todo\TodoDto;
use App\Dtos\Response\Todo\TodoListDto;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TodoController extends BaseController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')
            ->get($columns = ['id', 'title', 'completed', 'created_at', 'updated_at']);

        return response()->json(TodoListDto::build($todos), 200);
    }


    public function getCompleted()
    {
        $todos = Todo::where('completed', true)
            ->orderBy('created_at', 'desc')
            ->get($columns = ['id', 'title', 'completed', 'created_at', 'updated_at']);

        return response()->json(TodoListDto::build($todos));
    }


    public function getPending()
    {
        $todos = Todo::where('completed', false)
            ->orderBy('created_at', 'desc')
            ->get($columns = ['id', 'title', 'completed', 'created_at', 'updated_at']);

        return response()->json(TodoListDto::build($todos));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::find($id);
        if ($todo == null)
            return $this->sendErrorResponse('Todo not found');
        else
            return response()->json(TodoDto::build($todo, true));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // \Illuminate\Support\Facades\Input::only(['title', 'description']);
        // $request->only('title', 'description', 'completed');
        // $request->only(['title', 'description', 'completed']);
        $todo = new Todo;
        $todo->title = $request->title;
        $todo->description = $request->get('description', '');
        $todo->completed = $request->completed;
        $todo->save();
        return response()->json(TodoDto::build($todo, true), 201 /* CREATED Http status */);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id /*Todo $todo*/)
    {

        $todo = Todo::find($id);
        if ($todo == null)
            return $this->sendErrorResponse('Todo not found');


        $data = $request->only(['title', 'description', 'completed']);


        if (array_key_exists('title', $data) && !is_null($data['title']))
            $todo->title = $data['title'];


        if (array_key_exists('description', $data) && !is_null($data['description']))
            $todo->description = $data['description'];


        if (array_key_exists('completed', $data) && !is_null($data['completed']))
            $todo->completed = boolval($data['completed']);

        $todo->save();
        return response()->json(TodoDto::build($todo, true));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        $todo->delete();
        return response()->noContent();
    }

    public function destroyAll()
    {
        $deletedTodos = Todo::whereNotNull('id')->delete();
        // $deletedTodos = Todo::truncate();
        return response()->json(null, 204);
    }
}
