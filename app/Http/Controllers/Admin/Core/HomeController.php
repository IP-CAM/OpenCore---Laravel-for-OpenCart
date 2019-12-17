<?php

namespace AwebCore\App\Http\Controllers\Admin\Core;

use AwebCore\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use AwebCore\Startup;
use AwebCore\App\General\OcCore;
use AwebCore\App\Task;

class HomeController extends Controller
{
    function index()
    {
        $loader = Startup::getRegistry('load');

        $header = $loader->controller('common/header');
        $column_left = $loader->controller('common/column_left');
        $footer = $loader->controller('common/footer');

        $token = (new OcCore())->getTokenStr();

        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view("admin.core.home", compact('header', 'column_left', 'footer', 'tasks', 'token'));
    }

    function store(Request $request)
    {
        /**
         * Lumen does not support sessions out of the box, so the $errors view variable that is available
         * in every view in Laravel is not available in Lumen. Should validation fail, the $this->validate
         * helper will throw Illuminate\Validation\ValidationException with embedded JSON response that
         * includes all relevant error messages. If you are not building a stateless API that solely sends
         * JSON responses, you should use the full Laravel framework.
         *
         * https://lumen.laravel.com/docs/5.4/validation
         */
        /* $this->validate($request, [
            'name' => 'required|max:255',
        ]); */

        /* $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        */
        $redirect = '/admin/core/home?' . (new OcCore())->getTokenStr();

        /* if ($validator->fails()) {
            //return $validator->errors();
            return redirect($redirect)
                ->withInput()
                ->withErrors($validator);
        } */

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return redirect($redirect);
    }

    public function destroy(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        //$this->authorize('destroy', $task);

        $task->delete();

        return redirect('/admin/core/home?' . (new OcCore())->getTokenStr());
    }
}