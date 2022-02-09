<?php

namespace App\Http\Controllers;

use App\Providers\DatabaseProvider;
use App\Providers\ValidatorProvider;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function createParent(Request $request)
    {
        $validator = ValidatorProvider::globalValidation($request->all());

        if ($validator->fails()) {
            return ValidatorProvider::errorResponse($validator);
        }

        $countParent = count($request->all());

        // TODO: Доделать добавление родителей.
    }
}
