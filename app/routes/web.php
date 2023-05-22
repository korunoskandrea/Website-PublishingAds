<?php // defeniranje poti z prazno telo -> pot -> katero logiko handla ->katera metoda bo se izvedla v controllerju
if(Route::get('/','AdsController','adList')) {}
else if (Route::get('/ads/detail/{id}', 'AdsController', 'adDetail')) {}
else if (Route::get('/ads/my-ads', 'AdsController', 'adsUser')) {}
else if (Route::get('/ads/publish', 'AdsController', 'adPublishView')) {}
else if (Route::post('/ads/publish', 'AdsController', 'adPublishSafe')) {}
else if (Route::get('/ads/edit/{id}', 'AdsController', 'adEditView')) {}
else if (Route::post('/ads/edit/{id}', 'AdsController', 'adEditSafe')) {}
else if (Route::get('/ads/soft-del/{id}', 'AdsController', 'adSoftDelete')) {}
else if (Route::get('/ads/delete-ad', 'AdsController', 'adDeletedView')) {} // deleted View
else if (Route::get('/ads/delete-ad/delete/{id}', 'AdsController', 'adHardDelete')) {} // deleted View
else if (Route::get('/ads/delete-ad/restore/{id}', 'AdsController', 'adRestore')) {} // deleted View
else if (Route::get('/ads/', 'AdsController', 'adRemove')) {} // remove todo plus java scritp da se dojdaj i se dr so falese
else if (Route::get('/auth/login', 'AuthController', 'loginView')) {}
else if (Route::post('/auth/login', 'AuthController', 'loginSafe')) {}
else if (Route::get('/auth/logout', 'AuthController', 'logout')) {}
else if (Route::get('/auth/register', 'AuthController', 'registerView')) {}
else if (Route::post('/auth/register', 'AuthController', 'registerSafe')) {}
else if (Route::get('/user/settings', 'UserController', 'settingsView')) {}
else if (Route::post('/user/settings', 'UserController', 'settingsSafe')) {}
else if (Route::get('/user/admin', 'AdminController', 'userList')) {}
else if (Route::get('/user/admin/delete/{id}', 'AdminController', 'userDelete')) {}
else if (Route::get('/user/admin/edit/{id}', 'AdminController', 'userEdit')) {}
else if (Route::post('/user/admin/edit/{id}', 'AdminController', 'userEditSafe')) {}
else if (Route::post('/user/admin/create-users', 'AdminController', 'addNewUserSafe')) {}
else if (Route::get('/user/admin/create-users', 'AdminController', 'addNewUser')) {}
else if (Route::get('/api/comments', 'CommentController', 'showComments')) {}
else if (Route::get('/api/comments/{ad_id}', 'CommentController', 'showAdComments')) {}
else if (Route::post('/api/comments/{ad_id}', 'CommentController', 'postComment')) {}
else if (Route::delete('/api/comments/delete/{comment_id}', 'CommentController', 'deleteComment')) {}
else {
    echo "Stran ne obstaja ";
}
