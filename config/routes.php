<?php

return [
    ['GET', '/', 'PageController@home'],
    ['GET', '/register', 'PageController@register'],
    ['POST', '/register', 'RegistrationController@store', ['csrf']],
    ['GET', '/re-register', 'PageController@reRegister'],
    ['GET', '/dashboard', 'PageController@dashboard'],
    ['GET', '/student-detail', 'PageController@studentDetail'],
    ['GET', '/search-attribute', 'PageController@searchAttribute'],
    ['GET', '/search-text', 'PageController@searchText'],
    ['GET', '/search-content', 'PageController@searchContent'],
    ['GET', '/history', 'PageController@history'],
    ['POST', '/delete-file', 'RegistrationController@deleteFile', ['csrf']],
];
