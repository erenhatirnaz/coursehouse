@extends("layouts.app")

@section('title', $course->name . " - " .  __('app.application_name'))

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">
                    {{ __('app.homepage') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('course.explorer') }}">
                    {{ __('app.courses') }}
                </a>
            </li>
            <li class="breadcrumb-item active">
                {{ $course->name }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-4">
            <div class="card h-100 shadow">
                <img class="card-img-top" src="{{ $course->image_path }}"/>
                <div class="card-body">
                    <h4 class="card-title text-center">
                        {{ $course->name }}
                    </h4>

                    <table class="table table-borderless table-hover">
                        <tr>
                            <th scope="col" class="text-right">{{ __('app.category') }}:</th>
                            <td>{{ $course->category->name }}</td>
                        </tr>
                        <tr>
                            <th scope="col" class="text-right">{{ __('app.total_student_count') }}:</th>
                            <td>{{ $course->students_count }}</td>
                        </tr>
                        <tr>
                            <th scope="col" class="text-right">{{ __('app.status') }}:</th>
                            <td>{{ __("app." . $course->status) }}</td>
                        </tr>
                        <tr>
                            <th scope="col" class="text-right">{{ __('app.created_at') }}:</th>
                            <td data-toggle="tooltip" data-placement="right" title="{{ $course->created_at->toDateString() }}">
                                {{ $course->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    </table>

                    <a href="{{ $course->go_to_announcements_link }}" class="btn btn-success btn-block">
                        {{ __('app.go_to_announcements') }}
                        <span class="badge badge-light">{{ $course->announcements_count }}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    {!! $course->description !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <div class="card">
                <div class="card-header font-weight-bold">
                    {{ __('app.teachers') }}
                    <span class="badge badge-primary rounded-circle card-count-label">{{ $course->teachers_count }}</span>
                </div>
                <div class="card-body card-overflow-panel">
                    <div class="row h-100">
                        @foreach ($course->teachers as $teacher)
                        <div class="col-5">
                            <div class="card h-100 shadow-sm">
                                <div class="w-100 text-center mt-2">
                                    <img src="{{ $teacher->profile_photo }}" class="rounded-circle" width="110" height="110" />
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bolder text-center word-wrap-normal">
                                        {{ $teacher->full_name }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card h-100">
                <div class="card-header font-weight-bolder">
                    {{ __('app.class_rooms') }}
                    <span class="badge badge-primary rounded-circle card-count-label">{{ $course->class_rooms_count }}</span>
                </div>
                <div class="card-body card-overflow-panel">
                    <div class="row h-100">
                        @foreach ($course->classRooms()->with('course')->get() as $classRoom)
                        <div class="col-5">
                            <div class="card h-100 shadow-sm">
                                <div class="text-center mt-2">
                                    <img src="{{ asset('img/default-cr.png') }}" class="rounded-circle" width="110" height="110" />
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bolder text-center word-wrap-normal">
                                        <a href="{{ $classRoom->link }}">{{ $classRoom->name }}</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header font-weight-bolder">
                    {{ __('app.organizers') }}
                    <span class="badge badge-primary rounded-circle card-count-label">{{ $course->organizers()->count() }}</span>
                </div>
                <div class="card-body card-overflow-panel">
                    <div class="row h-100">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('auth.name') }} {{ __('auth.surname') }}</th>
                                    <th scope="col">{{ __('auth.phone_no') }}</th>
                                    <th scope="col">{{ __('auth.email') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->organizers as $organizer)
                                <tr>
                                    <th scope="row"><i class="fas fa-user-edit"></i> {{ $organizer->full_name }}</th>
                                    <th>0{{ $organizer->phone_no }}</th>
                                    <th>{{ $organizer->email }}</th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
