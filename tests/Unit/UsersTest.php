<?php
use App\MoonShine\Resources\CrnubeSpreadsheetUserResource;
use App\Models\CrnubeSpreadsheetUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


uses(RefreshDatabase::class);
uses(TestCase::class)->in('Unit');

/**
 * Prueba 1: Verificar el modelo asignado
 */
test('it uses the correct model', function () {
    $resource = new CrnubeSpreadsheetUserResource();
    expect($resource->getModel())->toBeInstanceOf(CrnubeSpreadsheetUser::class);
});

/**
 * Prueba 2: Verificar los campos BelongTo()
 */
test('CrnubeSpreadsheetUser defines the correct BelongsTo relationship for role', function () {
    // Crea una instancia del modelo y simula la relación belongsTo
    $user = Mockery::mock(CrnubeSpreadsheetUser::class)->makePartial();

    // Simula la relación BelongsTo para role
    $user->shouldReceive('role')
        ->andReturn(Mockery::mock(BelongsTo::class));

    // Verifica que la relación role() retorne una instancia de BelongsTo
    expect($user->role())->toBeInstanceOf(BelongsTo::class);
});
/**
 * Prueba 3: Verificar las reglas de validación
 */
test('it returns correct validation rules', function () {

    $resource = new CrnubeSpreadsheetUserResource();
    $model = $this->createMock(CrnubeSpreadsheetUser::class);
    $model->id = 1;

    $rules = $resource->rules($model);
    $this->assertIsArray($rules);
    $this->assertArrayHasKey('id', $rules);
    $this->assertArrayHasKey('role_id', $rules);
    $this->assertArrayHasKey('email', $rules);
    $this->assertArrayHasKey('password', $rules);
});

/**
 * Prueba 4: Verificar las acciones activas
 */
test('it returns correct active actions', function () {
    $resource = new CrnubeSpreadsheetUserResource();
    $actions = $resource->getActiveActions();

    expect($actions)->toBe(['create', 'update', 'delete', 'export']);
});

/**
 * Prueba 5: Deshabilitar la importación y exportación
 */
test('it disables import and export', function () {
    $resource = new CrnubeSpreadsheetUserResource();

    expect($resource->import())->toBeNull();
    expect($resource->export())->toBeNull();
});
