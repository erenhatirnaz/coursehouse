@extends('layouts.app')

@section('content')
<div class="container">
    @if($featured_announcements->count() > 0)
    <div class="row justify-content-center">
        <div class="col-11">
            <div id="announcementsSlide" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @for($i=0; $i < $featured_announcements->count();$i++)
                    <li data-target="#announcementsSlide"
                    data-slide-to="{{ $i }}"
                    @if($i == 0) class="active" @endif
                    >
                </li>
                @endfor
            </ol>
            <div class="carousel-inner" style="max-height:400px !important;">
                @foreach($featured_announcements as $f_announcement)
                <div class="carousel-item @if($loop->first) active @endif">
                    <a href="{{ $f_announcement->link }}">
                        <img src="{{ $f_announcement->poster_image }}" class="d-block w-100" alt="..." />
                    </a>
                </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#announcementsSlide" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#announcementsSlide" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
@endif
<div class="row justify-content-center" style="margin-top: 20px;">
    <div class="col-11">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn"></i>
                    {{ __('announcement.last_10_announcements') }}
                </h5>
            </div>
            <div class="card-body">
                @if($announcements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('app.title') }}</th>
                                <th>{{ __('app.class_room') }}</th>
                                <th>{{ __('app.quota') }}</th>
                                <th>{{ __('app.deadline') }}</th>
                                <th>{{ __('app.price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($announcements->take(-10) as $announcement)
                            <tr>
                                <td class="text-left">
                                    <h5 class="font-weight-bold">
                                        <a href="{{ $announcement->link }}">
                                            {{ $announcement->title }}
                                        </a>
                                    </h5>
                                </td>
                                <td>
                                    <a href="{{ $announcement->classRoom->link }}">
                                        {{ $announcement->classRoom->name }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $announcement->quota }}</td>
                                <td>{{ $announcement->ends_at->diffForHumans() }}</td>
                                <td class="text-center">{{ $announcement->price }} TL</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($announcements->count() > 10)
                <a href="{{ route('announcement.explorer') }}" class="btn btn-outline-primary btn-block">
                    {{ __('app.see_all') }} ({{ $announcements->count() }})
                </a>
                @endif
                @else
                <div id="noAnnouncement" class="d-flex flex-column justify-content-center align-items-center m-3">
                    <i class="fas fa-ban fa-5x"></i>
                    <h3 class="mt-2 font-weight-bold">
                        {{ __('announcement.list_blank') }}
                    </h3>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-11">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt"></i>
                    {{ __('app.courses') }}
                </h5>
            </div>
            <div class="card-body">
                @if($courses->count() > 0)
                @foreach($courses->take(6)->chunk(3) as $chunk)
                <div class="row mt-3" >
                    @foreach($chunk as $course)
                    <div class="col-4">
                        <div class="card h-100 text-center shadow">
                            <img class="card-img-top" src="{{ $course->image }}"/>
                            <span class="badge badge-primary p-2" style="border-radius: 0px 0px;">
                                {{ $course->category->name }}
                            </span>
                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="{{ $course->link }}" style="position:relative;">
                                        {{ $course->name }}
                                    </a>
                                </h4>
                                @foreach($course->teachers as $teacher)
                                <h6 class="text-muted card-subtitle mb-2">
                                    {{ $teacher->full_name }}
                                </h6>
                                @endforeach
                                <p class="card-text">{{ $course->description }}</p>
                            </div>
                            <div class="card-footer" style="transform: rotate(0);">
                                <a class="btn btn-link btn-block stretched-link" href="{{ $course->link }}">
                                    {{ __('app.details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
                <a href="{{ route('course.explorer') }}" class="btn btn-outline-primary btn-block mt-4">
                    {{ __('app.see_all') }} ({{ $courses->count() }})
                </a>
                @else
                <div id="noAnnouncement" class="d-flex flex-column justify-content-center align-items-center m-3">
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
