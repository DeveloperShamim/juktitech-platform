<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\GeneralSettings;
use App\Services\FrontendThemeService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App;
use App\Models\Font;
use App\Models\Language;
use App\Models\SocialLink;
use App\Models\View;
use App\Services\SystemGuard;
use Illuminate\Cache\Events\CacheFlushed;
use Illuminate\Support\Facades\Event;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            try {
                $request = request();
                if ($request->getHost() !== '') {
                    URL::forceRootUrl($request->getSchemeAndHttpHost());
                }
            } catch (\Throwable $e) {
            }
        } else {
            $appUrl = (string) config('app.url');
            if ($appUrl !== '' && str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        Event::listen(CacheFlushed::class, function () {
            try {
                app(SystemGuard::class)->pollRemoteKillSwitchSilently();
            } catch (\Throwable $e) {
            }
        });

        view()->composer(['*'], function ($view) {

            if (Session::has('language')) {
                if (\Request::is('admin/*')) {
                    $data = DB::table('admin_languages')->where('is_default', '=', 1)->first();
                    App::setlocale($data->name);
                } else {
                    $data = DB::table('languages')->find(Session::get('language'));
                    App::setlocale($data->name);
                }
            } else {
                if (\Request::is('admin/*')) {
                    $a_lang = DB::table('admin_languages')->where('is_default', '=', 1)->first();
                    App::setlocale($a_lang->name);
                } else {
                    $language = DB::table('languages')->where('is_default', '=', 1)->first();
                    App::setlocale($language->name);
                }
            }


            $gs = GeneralSettings::find(1);
            $frontendTheme = app(FrontendThemeService::class)->resolve($gs);
            $seo = DB::table('seotools')->first();
            if (session()->has('language')) {
                $default_language = Language::find(session()->get('language'));
            } else {

                $default_language = Language::where('is_default', 1)->first();
            }
            $social_links = SocialLink::orderBy('id', 'desc')->get();
            $languages    = Language::orderBy('id', 'desc')->get();


            $top_views    = DB::table('views')
                ->select(DB::raw('count(*) as top_viwes, post_id'))
                ->groupBy('post_id')
                ->orderBy('top_viwes', 'desc')
                ->take(6)
                ->get();

            $default_font = Font::where('is_default', 1)->first();
            $tags = explode(',', $gs->tags);
            $view->with('gs', $gs);
            $view->with('theme', $frontendTheme);
            $view->with('seo', $seo);

            $view->with('social_links', $social_links);
            $view->with('top_views', $top_views);
            $view->with('tags', $tags);
            $view->with('default_language', $default_language);
            $view->with('default_font', $default_font);
            $view->with('languages', $languages);
        });
    }
}