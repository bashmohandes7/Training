<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Transformers\Api\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $skip = ($request->has('skip')) ? $request->skip : 0;
        $users = new User();
        // search by name of user
        if ($request->has('q')) {
            $users = $users->where('name','LIKE', "%{$request->q}%");
        }
        $count = $users->count();
        if ($request->has('skip')) {
            $users = $users->orderBy('created_at', 'DESC')->take(10)->skip($skip)->get();
        } else {
            $users = $users->orderBy('created_at', 'DESC')->get();
        }
        $fractal = fractal()
            ->collection($users)
            ->transformWith(new UserTransformer())
            ->toArray();
        return $this->ResponseApi("", $fractal, 200, ['count' => $count]);
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'job_title' => $request->input('job_title'),
            'administration' => $request->input('administration'),
            'password' => $request->input('password')
        ]);
        $fractal = fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
        return $this->ResponseApi('User Created Successfully', $fractal);
    }
    public function show($id)
    {
        $fractal = fractal()
            ->item(User::findOrFail($id))
            ->transformWith(new UserTransformer())
            ->toArray();
        return $this->ResponseApi('', $fractal);
    }
    public function update(UserRequest $request , $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->input('name') ?? $user->name,
            'email' => $request->input('email') ?? $user->email,
            'phone' => $request->input('phone') ?? $user->phone,
            'job_title' => $request->input('job_title') ?? $user->job_title,
            'administration' => $request->input('administration') ?? $user->administration,
            'password' => $request->input('password') ?? $user->password,
        ]);
        $fractal = fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
        return $this->ResponseApi('User Updated Successfully', $fractal);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->ResponseApi('User Deleted Successfully');
    }
}
