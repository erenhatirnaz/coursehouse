@extends('layouts.app')

@section('content')
    <style>
        label.col-form-label::after {
            content: '*';
            color: red;
            font-weight: bold;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('auth.register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label
                                    for="name"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.name') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="name" type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        required autofocus autocomplete="given-name"
                                    />
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="surname"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.surname') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="surname" type="text" name="surname"
                                        class="form-control @error('surname') is-invalid @enderror"
                                        value="{{ old('surname') }}"
                                        required autocomplete="family-name"
                                    />
                                    @error('surname')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="email"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.email') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="email" type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        required autocomplete="email"
                                    />

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="password"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.password') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        required autocomplete="new-password"
                                    />

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.confirm_password') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="password-confirm" type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        required autocomplete="new-password"
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="phone_no"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.phone_no') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="phone_no" type="tel" name="phone_no"
                                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                        class="form-control @error('phone_no') is-invalid @enderror"
                                        value="{{ old('phone_no') }}"
                                        required autocomplete="tel"
                                    />
                                    <small class="form-text text-muted">Format: 123-456-7890</small>

                                    @error('phone_no')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="phone_no"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __('auth.birth_date') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="birth_date" type="date" name="birth_date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        value="{{ old('birth_date') }}"
                                        min="1900-01-01" max="{{ \Carbon\Carbon::now()->format('yy-m-d') }}"
                                        required autocomplete="bday"
                                    >
                                    <small class="form-text text-muted">Format: {{ __('app.dateformat_birthdate') }}</small>

                                    @error('birth_date')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <small class="float-right center" style="color: red">*: {{ __('app.required') }}</small>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('auth.register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
