<?php

namespace App\Providers;

use App\Models\Categories;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::composer là một cơ chế để chia sẻ dữ liệu giữa các view một cách dễ dàng
        view()->composer('*', function ($view) {
            $categories = Categories::with('categories_children')->whereNull('parent_id')->get(); // with được sử dụng để tải trước mối quan hệ // whereNull lấy các danh mục mà trường parent_id là null

            // admin
            $admin = auth()->guard('admin')->user();
            $permission = $admin ? json_decode($admin->administrationGroup->permission) : [];
            $view->with(compact('permission', 'categories'));
        });
    }
}
