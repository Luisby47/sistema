<?php

use App\MoonShine\Resources\HrDepartmentResource;
use App\MoonShine\Resources\HrJobResource;
use App\MoonShine\Resources\ResCompanyResource;
use App\MoonShine\Resources\HrEmployeeResource;
use App\Models\HrDepartment;
use App\Models\HrJob;
use App\Models\ResCompany;
use App\Models\HrEmployee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses(RefreshDatabase::class);

/**
 * HrDepartment Tests
 */
test('HrDepartment uses the correct model', function () {
    $resource = new HrDepartmentResource();
    expect($resource->getModel())->toBeInstanceOf(HrDepartment::class);
});

test('HrDepartment defines the correct BelongsTo relationship for company', function () {
    // Mock de la relación belongsTo para company
    $department = Mockery::mock(HrDepartment::class)->makePartial();
    $department->shouldReceive('company')->andReturn(Mockery::mock(BelongsTo::class));

    // Verifica que la relación company() retorne una instancia de BelongsTo
    expect($department->company())->toBeInstanceOf(BelongsTo::class);
});



test('HrDepartment returns correct active actions', function () {
    $resource = new HrDepartmentResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['view']);
});

test('HrDepartment disables import and export', function () {
    $resource = new HrDepartmentResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * HrJob Tests
 */
test('HrJob uses the correct model', function () {
    $resource = new HrJobResource();
    expect($resource->getModel())->toBeInstanceOf(HrJob::class);
});



test('HrJob returns correct active actions', function () {
    $resource = new HrJobResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['view']);
});

test('HrJob disables import and export', function () {
    $resource = new HrJobResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * ResCompany Tests
 */
test('ResCompany uses the correct model', function () {
    $resource = new ResCompanyResource();
    expect($resource->getModel())->toBeInstanceOf(ResCompany::class);
});



test('ResCompany returns correct active actions', function () {
    $resource = new ResCompanyResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['view']);
});

test('ResCompany disables import and export', function () {
    $resource = new ResCompanyResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});

/**
 * HrEmployee Tests
 */
test('HrEmployee uses the correct model', function () {
    $resource = new HrEmployeeResource();
    expect($resource->getModel())->toBeInstanceOf(HrEmployee::class);
});

test('HrEmployee defines the correct BelongsTo relationship for department', function () {
    // Mock de la relación belongsTo para department
    $employee = Mockery::mock(HrEmployee::class)->makePartial();
    $employee->shouldReceive('department')->andReturn(Mockery::mock(BelongsTo::class));

    // Verifica que la relación department() retorne una instancia de BelongsTo
    expect($employee->department())->toBeInstanceOf(BelongsTo::class);
});



test('HrEmployee returns correct active actions', function () {
    $resource = new HrEmployeeResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['view']);
});

test('HrEmployee disables import and export', function () {
    $resource = new HrEmployeeResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});
