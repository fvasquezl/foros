<?php

//Posts
Route::get('posts/create',[
'uses'=>'CreatePostController@create',
'as'=>'posts.create'
]);
Route::post('posts/create',[
'uses'=>'CreatePostController@store',
'as'=>'posts.store'
]);

//Comments
Route::post('posts/{post}/comment',[
    'uses' => 'CommentController@store',
    'as' => 'comments.store'
]);

Route::post('comments/{comment}/accept',[
    'uses' => 'CommentController@accept',
    'as' => 'comments.accept'
]);

Route::post('posts/{post}/subscribe',[
    'uses' => 'SubscriptionController@subscribe',
    'as' => 'post.subscribe'
]);

Route::delete('posts/{post}/subscribe',[
    'uses' => 'SubscriptionController@unsubscribe',
    'as' => 'post.unsubscribe'
]);