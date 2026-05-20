<?php

return [
    ['GET',  '/',               'PageController@home'],
    ['GET',  '/login',          'LoginController@showForm'],
    ['POST', '/login',          'LoginController@store',          ['csrf']],
    ['POST', '/logout',         'LoginController@logout',         ['csrf']],
    ['GET',  '/register',       'PageController@register'],
    ['POST', '/register',       'RegistrationController@store',   ['csrf']],
    ['GET',  '/re-register',    'PageController@reRegister'],
    ['GET',  '/dashboard',      'DashboardController@index'],
    ['GET',  '/students',       'StudentController@index'],
    ['GET',  '/student-detail', 'StudentController@show'],
    ['POST', '/student-update',  'StudentController@update',        ['csrf', 'auth']],
    ['GET',  '/file',           'FileController@serve'],
    ['GET',  '/search-attribute', 'SearchController@attributeSearch'],
    ['GET',  '/search-text',    'SearchController@textSearch'],
    ['GET',  '/search-content', 'SearchController@contentSearch'],
    ['GET',  '/history',        'HistoryController@index'],
    ['POST', '/delete-file',    'RegistrationController@deleteFile', ['csrf']],
];
