<?php


namespace App\Dtos\Response\Todo;


class TodoListDto
{
    public static function build($todos)
    {
        foreach ($todos as $index => $todo) {
            $todos[$index] = TodoDto::build($todo);
        }

        return $todos;
    }
}