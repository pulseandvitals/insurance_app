<?php

namespace App\Services;

use App\Models\VehicleBrand;
use App\Models\VehicleColor;
use App\Models\VehicleModel;
use App\Models\VehicleVariant;
use Illuminate\Support\Collection;

class VehicleCatalogService
{
    /**
     * The full Brand -> Model -> Variant tree, grouped by vehicle class, for
     * the Step 1 form to search and cascade through client-side.
     */
    public function tree(): array
    {
        return VehicleBrand::with('models.variants')
            ->get()
            ->groupBy('vehicle_class')
            ->map(fn (Collection $brands) => $brands
                ->sortBy('name')
                ->values()
                ->map(fn (VehicleBrand $brand) => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->models->sortBy('name')->values()->map(fn (VehicleModel $model) => [
                        'id' => $model->id,
                        'name' => $model->name,
                        'variants' => $model->variants->sortBy('name')->values()->map(fn (VehicleVariant $variant) => [
                            'id' => $variant->id,
                            'name' => $variant->name,
                        ])->all(),
                    ])->all(),
                ]))
            ->all();
    }

    /**
     * Find-or-create the Brand/Model/Variant chain for a submitted quote, so a
     * producer typing a new combination registers it for every future quote.
     * Matching is case-insensitive to avoid near-duplicate entries
     * ("Vios" vs "VIOS"); the first-typed casing is what sticks.
     */
    public function register(string $vehicleClass, string $brand, string $model, string $variant): void
    {
        $brand = $this->findOrCreate(VehicleBrand::class, ['vehicle_class' => $vehicleClass], $brand);
        $model = $this->findOrCreate(VehicleModel::class, ['vehicle_brand_id' => $brand->id], $model);
        $this->findOrCreate(VehicleVariant::class, ['vehicle_model_id' => $model->id], $variant);
    }

    /**
     * The registered color list, for the pre-flight vehicle-details form.
     */
    public function colors(): array
    {
        return VehicleColor::orderBy('name')->pluck('name')->all();
    }

    /**
     * Find-or-create a Color by name (case-insensitive), same registration
     * pattern as Brand/Model/Variant.
     */
    public function registerColor(string $color): void
    {
        $this->findOrCreate(VehicleColor::class, [], $color);
    }

    /**
     * @param  class-string<VehicleBrand|VehicleModel|VehicleVariant|VehicleColor>  $modelClass
     */
    private function findOrCreate(string $modelClass, array $scope, string $name): VehicleBrand|VehicleModel|VehicleVariant|VehicleColor
    {
        $name = trim($name);

        return $modelClass::query()->where($scope)->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? $modelClass::create([...$scope, 'name' => $name]);
    }
}
