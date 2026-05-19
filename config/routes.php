<?php

return [
    ['GET', '/', 'PageController@home'],
    ['GET', '/register', 'PageController@register'],
    ['POST', '/register', 'RegistrationController@store', ['csrf']],
    ['GET', '/re-register', 'PageController@reRegister'],
    ['GET', '/dashboard', 'DashboardController@index'],
    ['GET', '/student-detail', 'StudentController@show'],
    ['GET', '/search-attribute', 'SearchController@attributeSearch'],
    ['GET', '/search-text', 'SearchController@textSearch'],
    ['GET', '/search-content', 'SearchController@contentSearch'],
    ['GET', '/history', 'HistoryController@index'],
    ['POST', '/delete-file', 'RegistrationController@deleteFile', ['csrf']],
];
