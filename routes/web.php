<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\ResultsCompilationController;
use App\Http\Controllers\PerformanceAnalyticsController;

Route::middleware(['auth', 'dos'])->prefix('dos')->name('dos.')->group(function () {
    // Define the route for results.index
    Route::get('/results', [ResultsCompilationController::class, 'index'])->name('results.index');
});

Route::prefix('dos')->name('dos.')->group(function () {
    Route::resource('results', ResultsCompilationController::class);
});




Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dos/dashboard', [DosController::class, 'dashboard'])->name('dos.dashboard');
});


Route::middleware(['auth'])->get('/dos/dashboard', [DosController::class, 'dashboard'])->name('dos.dashboard');
Route::prefix('dos')->middleware(['auth'])->name('dos.')->group(function () {
    Route::get('/teachers', [TeacherSubjectController::class, 'showTeachers'])->name('teachers.index');
    Route::post('/teachers/register', [TeacherSubjectController::class, 'registerTeacher'])->name('teachers.register');

    Route::get('/subjects', [TeacherSubjectController::class, 'manageSubjects'])->name('subjects.index');
    Route::post('/subjects/create', [TeacherSubjectController::class, 'createSubject'])->name('subjects.create');
});
Route::post('/dos/teachers/register', [TeacherSubjectController::class, 'registerTeacher'])->name('dos.teachers.register');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dos')->middleware(['auth'])->group(function () {

    Route::get('/profile', [DosController::class, 'profile'])->name('profile');
    Route::get('/teachersubject', [DosController::class, 'teacherSubject'])->name('teachersubject');
    Route::get('/results', [DosController::class, 'results'])->name('results');
    Route::get('/analytics', [DosController::class, 'analytics'])->name('analytics');
    Route::get('/timetable', [DosController::class, 'timetable'])->name('timetable');
    Route::get('/reports', [DosController::class, 'reports'])->name('reports');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
    Route::get('/dos/dashboard', function () {
        return view('dos.dashboard');
    })->middleware(['auth'])->name('dos.dashboard');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
    // Add these to your routes/web.php file
Route::get('/dos/profile', [DosController::class, 'profile'])->name('dos.profile');
Route::get('/dos/teacher-subject', [DosController::class, 'teacherSubject'])->name('dos.teachersubject');
Route::get('/dos/results', [DosController::class, 'results'])->name('dos.results');
Route::get('/dos/analytics', [DosController::class, 'analytics'])->name('dos.analytics');

Route::get('/dos/reports', [DosController::class, 'reports'])->name('dos.reports');
Route::prefix('dos')->name('dos.')->middleware(['auth'])->group(function () {
    
});
Route::get('/dos/results', [ResultsController::class, 'index'])->name('dos.results.index');
Route::get('/dos/circulars', [CircularController::class, 'index'])->name('dos.circulars');
Route::get('/dos/result-reports', [ResultReportController::class, 'index'])->name('dos.result_reports');
Route::get('/dos/student-profiles', [StudentProfileController::class, 'index'])->name('dos.student_profiles');
Route::get('/dos/teacher-profiles', [TeacherProfileController::class, 'index'])->name('dos.teacher_profiles');
Route::get('/dos/salary', [SalaryController::class, 'index'])->name('dos.salary');
Route::get('/dos/subject-list', [SubjectController::class, 'index'])->name('dos.subject_list');


Route::post('/dos/results/submit', [ResultsController::class, 'submit'])->name('dos.submit_results');

Route::delete('/dos/teachers/{teacher}', [TeacherSubjectController::class, 'destroy'])->name('dos.teachers.destroy');
Route::prefix('dos')->middleware(['auth'])->group(function () {
    Route::get('results', [ResultsCompilationController::class, 'viewResults'])->name('dos.results.index');
    Route::post('results/compile', [ResultsCompilationController::class, 'compileResults'])->name('dos.results.compile');
});
Route::post('/dos/results/compile', [ResultsCompilationController::class, 'compileResults'])->name('dos.results.compile');
Route::get('performance-analytics', [PerformanceAnalyticsController::class, 'index']);
Route::get('performance-analytics/{studentId}', [PerformanceAnalyticsController::class, 'show']);

Route::get('/dos/timetable', [DosController::class, 'timetableIndex'])->name('dos.timetable');

Route::post('/dos/timetable/upload', [DosController::class, 'timetableUpload'])->name('dos.timetable.upload');
require __DIR__.'/auth.php';
