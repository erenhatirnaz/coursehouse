<?php

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return show_error(501, "Not implemented!");
})->name('home');

Route::get('/switchLocale', function () {
    return show_error(501, "Not implemented!");
})->name('switchLocale');

Route::get('/search', function () {
    return show_error(501, "Not implemented!");
})->name('search');

Route::name('course.')->group(function () {
    Route::get('/course/explorer', function () {
        return show_error(501, "Not implemented!");
    })->name('explorer');
    Route::get('/course/{course}', function () {
        return show_error(501, "Not implemented!");
    })->name('details');
    Route::get('/course/{course}/classrooms/{classRoom}', function () {
        return show_error(501, "Not implemented!");
    })->name('classroom.details');
});

Route::name('announcement.')->group(function () {
    Route::get('/announcement/explorer', function () {
        return show_error(501, "Not implemented!");
    })->name('explorer');
    Route::get('/announcement/{announcement}', function () {
        return show_error(501, "Not implemented!");
    })->name('details');
    Route::post('announcement/{announcement}/apply', function () {
        return show_error(501, "Not implemented!");
    })->name('apply');
});

Route::name('teacher.')->middleware(['role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return show_error(501, "Not implemented!");
    })->name('dashboard');

    Route::name('course.')->group(function () {
        Route::get('/teacher/course/list', function () {
            return show_error(501, "Not implemented!");
        })->name('index');
        Route::get('/teacher/course/{course}', function ($course) {
            return show_error(501, "Not implemented!");
        })->name('show');

        Route::get('/teacher/course/add', function () {
            return show_error(501, "Not implemented!");
        })->name('create');
        Route::post('/teacher/course/create', function () {
            return show_error(501, "Not implemented!");
        })->name('save');

        Route::get('/teacher/course/{course}/edit', function ($course) {
            return show_error(501, "Not implemented!");
        })->name('edit');
        Route::put('/teacher/course/{course}', function ($course) {
            return show_error(501, "Not implemented!");
        })->name('update');

        Route::delete('/teacher/course/{course}', function ($course) {
            return show_error(501, "Not implemented!");
        })->name('delete');
    });

    Route::name('classroom.')->group(function () {
        Route::get('/teacher/course/{course}/classroom/{classroom}', function () {
            return show_error(501, "Not implemented!");
        })->name('show');

        Route::get('/teacher/course/{course}/classroom/add', function () {
            return show_error(501, "Not implemented!");
        })->name('create');
        Route::get('/teacher/course/{course}/classroom/create', function () {
            return show_error(501, "Not implemented!");
        })->name('save');

        Route::get('/teacher/course/{course}/classroom/{classRoom}/update', function () {
            return show_error(501, "Not implemented!");
        })->name('edit');
        Route::get('/teacher/course/{course}/classroom/{classRoom}', function () {
            return show_error(501, "Not implemented!");
        })->name('update');

        Route::delete('/teacher/course/{course}', function () {
            return show_error(501, "Not implemented!");
        })->name('delete');
    });
});

Route::name('organizer.')->group(function () {
    Route::get('/organizer/dashboard', function () {
        return show_error(501, "Not implemented!");
    })->name('dashboard');
});

Route::name('admin.')->group(function () {
    Route::get('/admin/dashboard', function () {
        return show_error(501, "Not implemented!");
    })->name('dashboard');
});
