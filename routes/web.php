<?php

use App\Enums\AreaEnum;
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

Route::get('/about', function(){
    return view('frontend.about.index');
})->name('about.index');
Route::prefix('programs')->name('programs.')->group(function(){
    Route::get('/', function(){
        return view('frontend.programs.index');
    })->name('index');

    Route::get('/{program_code}', function(string $program_code){
        $program = Program::where('code', $program_code)->first();
        return view('frontend.programs.show', compact('program'));
    })->name('show');
});
Route::prefix('area')->name('area.')->group(function(){
    Route::get('/', function(){
        return view('frontend.articles.index');
    })->name('index');

    Route::get('/{program_code}/{area}', function(string $program_code, string $area){
        $areas = AreaEnum::toArray();

        $article = Article::whereHas('program', function($query) use ($program_code){
            $query->where('code', $program_code);
        })->where('area', $area)->where('status', 'accepted')->first();


        return view('frontend.articles.show', compact('article', 'areas', 'program_code'));
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
    Route::get('articles/create/{area?}', [ArticleController::class, 'create'])->name('articles.create');
    Route::resource('articles', ArticleController::class)->except(['create']);


    // Areas Routes
    Route::resource('areas', AreaController::class);

    // Users Routes
    Route::resource('users', UserController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
