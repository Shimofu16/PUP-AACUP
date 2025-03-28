<?php

use App\Enums\AreaEnum;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use App\Models\Program;
use Illuminate\Http\Request;

Route::get('/', function(){
    return view('frontend.home.index');
})->name('home.index');

Route::get('/about/{page}', function(string $page){
    return view('frontend.about.index', compact('page'));
})->name('about.index');

Route::prefix('programs')->name('programs.')->group(function(){
    Route::get('/', function(){
        return view('frontend.programs.index');
    })->name('index');

    Route::get('/{program_code}', function(string $program_code){
        $program = Program::with('articles')->where('code', $program_code)->first();

        return view('frontend.programs.show', compact('program'));
    })->name('show');
});

Route::prefix('area')->name('area.')->group(function(){
    Route::get('/', function(){
        return view('frontend.articles.index');
    })->name('index');

    Route::get('/{program_code}/{area}/{parameter?}', function(string $program_code, string $area, string $parameter = null){

        $article = Article::whereHas('program', function($query) use ($program_code){
            $query->where('code', $program_code);
        })
        ->whereHas('area', function($query) use ($area){
            $query->where('name', $area);
        })
        ->when($parameter, function($query, $parameter){
            return $query->whereHas('parameter', function($query) use ($parameter){
                $query->where('name', $parameter);
            });
        })
        ->where('status', 'accepted')->first();


        return view('frontend.articles.show', compact('article', 'program_code'));
    })->name('show');

    Route::get('/pdf/{article}', function(Article $article) {
        return response()->file(public_path('storage/'.$article->document));
    })->name('pdf');
});





Route::middleware(['auth', 'verified'])->prefix('backend')->name('backend.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Programs Routes
    Route::resource('programs', ProgramController::class);

    // Articles Routes
    Route::get('articles/reviewed', [ArticleController::class, 'reviewed'])->name('articles.reviewed');
    Route::get('articles/pending', [ArticleController::class, 'pending'])->name('articles.pending');
    Route::get('articles/create/{area?}', [ArticleController::class, 'create'])->name('articles.create');
    Route::resource('articles', ArticleController::class)->except(['create', 'index']);

    // Areas Routes
    Route::get('areas', [AreaController::class, 'index'])->name('areas.index');
    Route::get('areas/create', [AreaController::class, 'create'])->name('areas.create');
    Route::get('areas/{user}', [AreaController::class, 'edit'])->name('areas.edit');

    // Users Routes
    Route::resource('users', UserController::class);

    //Activity Logs Routes
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
