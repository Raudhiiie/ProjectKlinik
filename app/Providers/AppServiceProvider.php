<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;

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
        View::composer('*', function ($view) {
        $produkHabisNotif = Produk::select('posisi', 'nama_produk', DB::raw('MAX(id) as max_id'))
            ->groupBy('posisi', 'nama_produk')
            ->get()
            ->map(function ($item) {
                return Produk::find($item->max_id);
            })
            ->filter(function ($produk) {
                return $produk && $produk->sisa <= 0;
            });

        $view->with('produkHabisNotif', $produkHabisNotif);
    });
    }
}
