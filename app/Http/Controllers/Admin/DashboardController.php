<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Article;
use App\ArticleCategory;
use App\User;
use App\Photo;
use App\PhotoAlbum;

class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        view()->share('type', '');
    }

	public function index()
	{
        $title = "Dashboard";

        $article = Article::count();
        $articlecategory = ArticleCategory::count();
        $users = User::count();
        $photo = Photo::count();
        $photoalbum = PhotoAlbum::count();
		return view('admin.dashboard.index',  compact('title','article','articlecategory','photo','photoalbum',
            'users'));
	}
}