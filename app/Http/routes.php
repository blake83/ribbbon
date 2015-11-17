<?php
Route::get('/', 'HomeController@index');
Route::get('register', function(){ return View::make('register')->with('pTitle', "Register"); });
Route::get('signin', function(){ return View::make('signin')->with('pTitle', "Login"); });
Route::get('about', 'HomeController@index');
Route::get('faq', function(){ return View::make('faq')->with('pTitle', "FAQ"); });

//----------------- User routes
Route::resource('users', 'UsersController', array('except' => array('create', 'store', 'show','edit','update')));
Route::post('login', 'UsersController@login');
Route::post('make', 'UsersController@register');
Route::get('logout', 'UsersController@logout');
Route::post('request', 'UsersController@request');
Route::post('resetPassword/{id}','UsersController@resetPassword');

//----------------- Auth routes
Route::group(array('before' => 'auth'), function()
{	
	Route::resource('clients', 'ClientsController');

	Route::resource('projects', 'ProjectsController', array('except' => array('store')));
	Route::post('projects/{id}/invite', array('uses' => 'ProjectsController@invite', 'as' => 'projects.invite' ));
	Route::delete('projects/{id}/remove', array('uses' => 'ProjectsController@remove', 'as' => 'projects.remove') );
	Route::get('projects/{id}/credentials', array('uses' => 'ProjectsController@credentials', 'as' => 'projects.credentials' ));
	Route::get('projects/{id}/manage', array('uses' => 'ProjectsController@edit', 'as' => 'projects.edit' ));
    Route::get('projects/{id}/files', array('uses' => 'ProjectsController@files', 'as' => 'projects.files' ));
    Route::post('projects/{id}/files', array('uses' => 'FilesController@store', 'as' => 'files.store' ));
    Route::delete('projects/{id}/files', array('uses' => 'FilesController@destroy', 'as' => 'files.remove' ));

	Route::resource('credentials', 'CredentialsController', array('only' => array('create', 'destroy')));
	Route::resource('tasks', 'TasksController');
	Route::get('hud', 'HomeController@index');
	Route::get('search', 'HomeController@search');
	Route::get('profile', 'UsersController@index');
});

//----------------- API routes
Route::group(['prefix' => '/api/'], function()
{	
	// USER 
	Route::get('{key}/authId', 'ApiController@authId');

	// CLIENT
	Route::get('clients', 'ApiController@getAllUserClients');
	Route::get('clients/{id}', 'ApiController@getClient');
	Route::put('clients/{id}', 'ApiController@updateClient');
	Route::post('clients', 'ApiController@storeClient');

	// PROJECT
	Route::put('projects/{id}', 'ApiController@updateProject');
	Route::post('projects', 'ApiController@storeProject');

	// TASK
    Route::post('tasks', 'ApiController@storeTask');
    Route::delete('tasks/{id}', 'ApiController@removeTask');

	// CREDENTIALS

});

//----------------- Admin routes
Route::group(array('before' => 'admin'), function()
{
    Route::get('invite','AdminController@invite');
});