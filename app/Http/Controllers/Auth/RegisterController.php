<?php

namespace App\Http\Controllers\Auth;

use App\Student;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\StudentRepositoryInterface;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var StudentRepositoryInterface
     */
    private $studentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->middleware('guest');

        $this->studentRepository = $studentRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no' => ['required', 'alpha_dash', 'regex:/[0-9]{3}-[0-9]{3}-[0-9]{4}/', 'unique:users'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'after:1900-01-01',
                             'before:' . Carbon::now()->format('Y-m-d')],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Student
     */
    protected function create(array $data)
    {
        $student = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_no' => $data['phone_no'],
            'birth_date' => $data['birth_date'],
        ];
        return $this->studentRepository->create($student);
    }
}
