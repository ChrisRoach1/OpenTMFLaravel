<?php

use App\Http\Controllers\DocumentMetadataController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\StudyConfigurationController;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::get('file-upload', [FileUploadController::class, 'index'])->name('file-upload');

    Route::get('study-configuration', [StudyConfigurationController::class, 'index'])->name('study-configuration.index');

    Route::patch('study-configuration/{study}', [StudyConfigurationController::class, 'update'])->name('study-configuration.update');


    Route::get('metadata-configuration', [DocumentMetadataController::class, 'index'])->name('metadata-configuration.index');

    Route::post('metadata-configuration', [DocumentMetadataController::class, 'create'])->name('document-metadata.create');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

/* study configuration notes
 * Ability to set "study level" details - sponsor name, custom logo??, protocol, friendly name (like project ID)
 * define the properties you want set on a file (this will be outside of the actual "level" so site, country are separate selections)
 *
 */


/* Reference Model configuration
 * ability to drop an excel sheet and generate via that
 * ability to add more as needed
 * tab for artifacts and tab for subartifacts
 */
