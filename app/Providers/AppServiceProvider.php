<?php

namespace App\Providers;

use App\Models\Ingredient;
use App\Models\PurchaseItem;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (request()->is('staff*')) {
            view()->composer('layouts.partials.header', function ($view) {
                $purchaseCounts = DB::table('purchase_items')->where('expire_date', '<', date('Y-m-d'))->count();
                $purchaseItems = PurchaseItem::with('ingredient:id,name')->where('expire_date', '<', date('Y-m-d'))->limit(15)->get(['id', 'ingredient_id', 'expire_date', 'quantity_amount']);

                $stocks = [];
                $stockOut = [];
                $ingredientIds = Ingredient::get(['id', 'alert_qty']);
                foreach ($ingredientIds as $ingredientId) {

                    $ingredient = Stock::with('ingredient:id,name,alert_qty')
                        ->where('ingredient_id', $ingredientId->id)
                        ->where('qty_amount', '<', $ingredientId->alert_qty)
                        ->first();
                    if ($ingredient) {
                        $stocks[] = $ingredient;
                        array_push($stockOut, $ingredientId->id);
                    }
                }
                count($stockOut) > 0 ? session()->put('stock_out', $stockOut) : session()->forget('stock_out');

                $view->with([
                    'purchaseItems' => $purchaseItems,
                    'purchaseCounts' => $purchaseCounts,
                    'stocks' => $stocks,
                ]);
            });
        }

        Builder::macro('filter', function ($key, $column = null, $compareWith = null, $filterIf = true) {
            if (($value = request($key, null)) !== null && $filterIf) {
                return $this->where($column ?? $key, $compareWith ?? '=', $value);
            }

            return $this;
        });

        Builder::macro('filterWith', function ($key, $column = null) {
            if ((request($key, null)) !== null) {
                $value = request($key, null);

                return $this->whereIn($column ?? $key, $value);
            }

            return $this;
        });

        Builder::macro('whereLike', function ($attributes, $searchTerm = null) {
            if ($searchTerm !== null) {
                $this->where(function (Builder $query) use ($attributes, $searchTerm) {

                    foreach ($attributes as $attribute) {
                        $query->when(
                            str_contains($attribute, ','),
                            function (Builder $query) use ($attribute, $searchTerm) {
                                [$relationName, $relationAttribute] = explode(',', $attribute);
                                $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                    $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                                });
                            },
                            function (Builder $query) use ($attribute, $searchTerm) {
                                $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                            }
                        );
                    }
                });
            }
            return $this;
        });

        Vite::macro('image', fn ($asset) => $this->asset("resources/assets/images/{$asset}"));

        settings();
        // appConfiguration();
    }
}
