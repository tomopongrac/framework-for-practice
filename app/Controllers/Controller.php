<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;
use App\Exceptions\ValidationException;

abstract class Controller
{
    public function validate(ServerRequestInterface $request, array $rules)
    {
        $validator = new Validator($request->getParsedBody());
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            throw new ValidationException($request, $validator->errors());
        }

        return $request->getParsedBody();
    }
}