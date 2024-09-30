<?php

use App\MoonShine\Resources\CrnubeSpreedsheatConceptosResource;
use App\Models\CrnubeSpreedsheatConceptos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MoonShine\Fields\Field;
use MoonShine\Fields\Relationships\BelongsTo;

uses(RefreshDatabase::class);

/**
 * CrnubeSpreadsheetConceptos Tests
 */
test('CrnubeSpreadsheetConceptos uses the correct model', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreedsheatConceptos::class);
});

test('CrnubeSpreadsheetConceptos defines the correct BelongsTo relationship for category', function () {
    // Mock the belongsTo relationship for category
    $concepto = Mockery::mock(CrnubeSpreedsheatConceptos::class)->makePartial();
    $concepto->shouldReceive('category')->andReturn(Mockery::mock(BelongsTo::class));

    // Verify that the category() relationship returns an instance of BelongsTo
    expect($concepto->category())->toBeInstanceOf(BelongsTo::class);
});

test('CrnubeSpreadsheetConceptos returns correct validation rules', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    $model = CrnubeSpreedsheatConceptos::factory()->make();
    $rules = $resource->rules($model);

    expect($rules)->toBeArray();
    expect($rules)->toHaveKeys(['motivo', 'tipo_concepto', 'tipo_valor', 'valor', 'observaciones']);
});

test('CrnubeSpreadsheetConceptos returns correct active actions', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['create','view', 'update', 'delete', 'massDelete']);
});

test('CrnubeSpreadsheetConceptos disables import and export', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});
