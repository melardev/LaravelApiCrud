<?php


namespace App\Dtos\Response\Todo;


use App\Dtos\Response\Shared\PageMetaDto;
use App\Dtos\Response\Shared\SuccessResponse;

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