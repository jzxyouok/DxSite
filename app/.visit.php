<?php 
/// 访问规则
 Page::visitController((new Page_Controller(function ($id, $name) {
     echo 'OK ==> ', $id, $name;
 }))-> url('/{id}/{name}')->with('id', 'int')->with('name', 'string'));
    
Page::visit('/getUser/{id}',['admin\Hello','main'])
->with('id', 'int');

Page::visit('/QAQ',function () {})->use(404)->status(404)->name('404_page');

Page::default(function ($path) {
    View::set('title', '页面找不到了哦！');
    View::set('url', $path);
})->use(404)->status(404);
Page::auto('/admin', ['/admin']);
