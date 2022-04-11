<?php 
namespace Concaveit\Media;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider{

    //Loaded after app starts
    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views','concaveit_media');
        $path = public_path('media');
        if(!\File::isDirectory($path)){
            \File::makeDirectory($path, 0777, true, true);
        }
    }


}


