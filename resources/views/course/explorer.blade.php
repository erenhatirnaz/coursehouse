@extends('layouts.app')

@section('title', __('app.application_name') . " - " . __('app.course_explorer'))
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">
                    {{ __('app.homepage') }}
                </a>
            </li>
            <li class="breadcrumb-item active">
                {{ __('app.course_explorer') }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    {{ __('app.filters') }}
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if ($filters->isNotEmpty())
                    <div id="filters" class="mb-3">
                        <h5>{{ __('app.active_filters') }}</h5>
                        @foreach ($filters as $type => $value)
                        @if ($type == "category")
                        <span class="badge m-1 p-1 badge-primary">
                            {{ __("app.category") }}: {{ $value }}
                        </span>
                        @elseif ($type == "teachers")
                        @foreach ($value as $teacher)
                        <span class="badge m-1 p-1 badge-success">
                            {{ __("app.teacher") }}: {{ $teacher->full_name }}
                        </span>
                        @endforeach
                        @elseif ($type == "status")
                        <span class="badge m-1 p-1 badge-secondary">
                            {{ __("app.status") }}: {{ __("app." . $value) }}
                        </span>
                        @endif
                        @endforeach
                    </div>
                    @endif

                    <form action="{{ route('course.explorer') }}" method="get">
                        <div id="categories" class="mb-2">
                            <h5>{{ __('app.category') }}</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="cat-all" value="all" checked />
                                <label class="form-check-label" for="cat-all">
                                    {{ __('app.all') }} ({{ $total_course_count }})
                                </label>
                            </div>
                            @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category"
                                id="cat-{{ $category->slug }}" value="{{ $category->slug }}"
                                @if ($filters->has('category') && $filters->get("category") == $category->name)
                                checked
                                @endif
                                />
                                <label class="form-check-label" for="cat-{{ $category->slug }}">
                                    {{ $category->name }} ({{ $category->courses_count }})
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div id="teachers" class="mb-2">
                            <h5>{{ __('app.teachers') }}</h5>
                            <div id="teacher-list">
                                @foreach ($teachers as $teacher)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                    id="teach-{{ $teacher->id }}"
                                    name="teacher[]" value="{{ $teacher->id }}"
                                    @if ($filters->has('teachers') && $filters->get('teachers')->firstWhere('id', $teacher->id))
                                    checked
                                    @endif
                                    />
                                    <label class="form-check-label" for="teach-{{ $teacher->id }}">
                                        {{ $teacher->full_name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="status">
                            <h5>{{ __('app.status') }}</h5>
                            <div class="form-check">
                                <input class="form-check-input" name="status" type="radio"
                                id="stat-all" value="all" checked/>
                                <label class="form-check-label" for="stat-all">
                                    {{ __('app.all') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="status" type="radio"
                                id="stat-active" value="active"
                                @if ($filters->has('status') && $filters->get('status') == "active")
                                checked
                                @endif
                                />
                                <label class="form-check-label" for="stat-active">
                                    {{ __('app.active') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="status" type="radio"
                                id="stat-passive" value="passive"
                                @if ($filters->has('status') && $filters->get('status') == "passive")
                                checked
                                @endif
                                />
                                <label class="form-check-label" for="stat-passive">
                                    {{ __('app.passive') }}
                                </label>
                            </div>
                        </div>
                        <div id="buttons">
                            <button class="btn btn-primary btn-block mt-4" type="submit"
                            @if ($courses->count() == 0) disabled @endif
                            >
                            {{ __('app.apply') }}
                        </button>
                        @if ($filters->isNotEmpty())
                        <a class="btn btn-link btn-sm btn-block" href="{{ route('course.explorer') }}">
                            {{ __('app.clear') }}
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                {{ __("app.courses") }}
            </div>
            <div class="card-body">
                @if ($filters->isNotEmpty())
                <h4> {{ __('course.results_founded', ['count' => $courses->count()]) }}</h4>
                @endif
                @if($courses->count() > 0)
                @foreach ($courses as $course)
                <div class="card mb-3 shadow-sm" style="max-width: 100%">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="{{ $course->image_path }}" class="card-img">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <span class="badge badge-primary p-2 text-right font-weight-bold"
                                style="position:absolute; top: 0; right: 0; border-radius: 0px 0.25rem 0px 20px;"
                                >
                                {{ $course->category->name }}
                            </span>
                            <h5 class="card-title">
                                <a href="{{ $course->link }}">
                                    {{ $course->name }}
                                </a>
                            </h5>
                            <p class="card-subtitle text-muted">
                                @foreach ($course->teachers as $teacher)
                                {{ $teacher->full_name }}@if (!$loop->last), @endif
                                @endforeach
                            </p>
                            <p class="card-text"> {{ $course->description_summary }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="d-flex flex-column justify-content-center align-items-center m-3">
                <i class="fas fa-ban fa-5x"></i>
                <h3 class="mt-2 font-weight-bold">
                    {{ __('course.list_blank') }}
                </h3>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
@endsection
