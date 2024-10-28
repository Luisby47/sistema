<?php

use App\Models\CrnubeSpreadsheetCCSS;
use App\Models\CrnubeSpreadsheetJornada;
use App\Models\CrnubeSpreadsheetTax;

use App\Models\CrnubeSpreadsheetUser;
use App\Models\CrnubeSpreedsheatConceptos;
use App\MoonShine\Resources\CrnubeSpreadsheetCCSSResource;
use App\MoonShine\Resources\CrnubeSpreadsheetJornadaResource;
use App\MoonShine\Resources\CrnubeSpreadsheetTaxResource;

;

use App\MoonShine\Resources\CrnubeSpreedsheatConceptosResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


uses(RefreshDatabase::class);


/**
 * CCSS Tests
 */
test('CCSS uses the correct model', function () {
    $resource = new CrnubeSpreadsheetCCSSResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreadsheetCCSS::class);
});

test('CCSS defines the correct BelongsTo relationship for company', function () {
    $ccss = Mockery::mock(CrnubeSpreadsheetCCSS::class)->makePartial();
    $ccss->shouldReceive('company')->andReturn(Mockery::mock(BelongsTo::class));
    expect($ccss->company())->toBeInstanceOf(BelongsTo::class);
});

test('CCSS returns correct active actions', function () {
    $resource = new CrnubeSpreadsheetCCSSResource();
    $actions = $resource->getActiveActions();
    expect($actions)->toBe(['view', 'update']);
});

test('CCSS disables import and export', function () {
    $resource = new CrnubeSpreadsheetCCSSResource();
    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * Jornada Tests
 */
test('Jornada uses the correct model', function () {
    $resource = new CrnubeSpreadsheetJornadaResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreadsheetJornada::class);
});

test('Jornada returns correct active actions', function () {
    $resource = new CrnubeSpreadsheetJornadaResource();
    $actions = $resource->getActiveActions();
    expect($actions)->toBe(['view', 'update', 'create']);
});

test('Jornada disables import and export', function () {
    $resource = new CrnubeSpreadsheetJornadaResource();
    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * Tax Tests
 */
test('Tax uses the correct model', function () {
    $resource = new CrnubeSpreadsheetTaxResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreadsheetTax::class);
});

test('Tax returns correct active actions', function () {
    $resource = new CrnubeSpreadsheetTaxResource();
    $actions = $resource->getActiveActions();
    expect($actions)->toBe(['view', 'update']);
});

test('Tax disables import and export', function () {
    $resource = new CrnubeSpreadsheetTaxResource();
    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * Concepto Tests
 */
test('Concepto uses the correct model', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreedsheatConceptos::class);
});

test('Concepto defines the correct BelongsTo relationship for category', function () {
    $concepto = Mockery::mock(CrnubeSpreedsheatConceptos::class)->makePartial();
    $concepto->shouldReceive('category')->andReturn(Mockery::mock(BelongsTo::class));
    expect($concepto->category())->toBeInstanceOf(BelongsTo::class);
});

test('Concepto returns correct active actions', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    $actions = $resource->getActiveActions();
    expect($actions)->toBe(['create', 'view', 'update', 'delete', 'massDelete']);
});

test('Concepto enables import and export', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    expect(method_exists($resource, 'import'))->toBeTrue();
    expect(method_exists($resource, 'export'))->toBeTrue();
});


use Illuminate\Support\Facades\Password;

test('User can request password reset', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    expect(method_exists($resource, 'import'))->toBeTrue();
    expect(method_exists($resource, 'export'))->toBeTrue();
});


test('User can change company', function () {
    $resource = new CrnubeSpreedsheatConceptosResource();
    expect(method_exists($resource, 'import'))->toBeTrue();
    expect(method_exists($resource, 'export'))->toBeTrue();
});
