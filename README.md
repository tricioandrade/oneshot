# OneShot
_Laravel_ artisan extra commands

[![Latest Stable Version](http://poser.pugx.org/tricioandrade/oneshot/v)](https://packagist.org/packages/tricioandrade/oneshot) [![Total Downloads](http://poser.pugx.org/tricioandrade/oneshot/downloads)](https://packagist.org/packages/tricioandrade/oneshot) [![License](http://poser.pugx.org/tricioandrade/oneshot/license)](https://packagist.org/packages/tricioandrade/oneshot) [![PHP Version Require](http://poser.pugx.org/tricioandrade/oneshot/require/php)](https://packagist.org/packages/tricioandrade/oneshot)

<p> "OneShot" is a development package in Laravel projects, particularly for APIs. It is a <i>resource</i> generator for items such as controllers, resources, requests, models, migrations, traits, and enums (PHP 8.1). </p>


## Installation
<p>Open your terminal and run:</p>

```
composer require tricioandrade/oneshot
```

## Provider Registration
<p>In the config/app.php file, register the package provider in the providers array. Add an entry for the provider in the 'providers' section:</p>

```php
'providers' => [
    // ...
    \OneShot\Builder\OneShotServiceProvider::class,
], 
```
## Publish the configuration file
<p>You must run:</p>

```
php artisan oneshot:publish
```

## Generate your files
<p>Create <i>Enum</i> files, your file will be created at app/Enum in yor Laravel project</p>

### Enum
```
php artisan make:enum EmployeeFunctions
```
<p>Will create EmployeeFuncionsEnum.php file, like this:</p>
<pre>EmployeeFunctionsEnum.php</pre>

```php
<?php

namespace App\Enums;

enum EmployeeFunctions
{

    /**
     * Return all cases values as array
     *
     * @return array
     */
    public function values(): array
    {
        return array_column(self::cases(), 'values');
    }
}

```
### Traits
<p>The same for <i>Traits</i> files, your file will be created at app/Traits in your Laravel project.</p>

```
php artisan make:trait EmployeeFunctions
```
<p>Will create EmployeeFuncions.php file, like this:</p>
<pre>EmployeeFunctions.php</pre>

```php
<?php

namespace App\Traits;

trait EmployeeFunctions
{
    //
}
```
### Services
<p>If you like to create <i>services</i>, you can also do do the same. But his template requires a model. Like this example: </p>

```
php artisan make:service EmployeeFunctionsService
```
<p>Will create EmployeeFunctionsService.php file, like this:</p>

<pre>app/Services/EmployeeFunctionsService.php</pre>

<p>The imported class: </p>

```
use App\Models\EmployeeFunctionsService\EmployeeFunctionsServiceModel
```

<p>Importing the model <i>EmployeeFunctionsServiceModel</i> and other classes is optional, it will not exist after creating the <i>service</i>. You can adapt the code however you want. Setup his template as you wish at: 

<pre>stubs\create.service.stub</pre>

```php
<?php

namespace App\Services;

use App\Models\EmployeeFunctionsService\EmployeeFunctionsServiceModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EmployeeFunctionsService
{
    protected array $relations = [

    ];

    /**
     * Get all data from the database
     *
     * @throws UnauthorizedException
     */
    public function getAll(): array|Collection
    {
        if (!true) return null;
        return EmployeeFunctionsServiceModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new data in the database
     *
     * @throws UnauthorizedException
     */
    public function create(array $attributes) {
        if (!true) return null;
        $employeeFunctionsService = EmployeeFunctionsServiceModel::create($attributes);

        return $employeeFunctionsService->load($this->relations);
    }

    /**
     * Get a data from the database by id
     *
     * @param int $id
     * @return Model|array|Collection|Builder|null
     * @throws UnauthorizedException
     */
    public function getById(int $id): Model|array|Collection|Builder|null
    {
        if (!true) return null;
        return EmployeeFunctionsServiceModel::withTrashed()->with($this->relations)->findOrFail($id);
    }

    /**
     * Update a specific data in the database
     *
     * @param array $attributes
     * @param int $id
     * @return Collection|Model
     * @throws UnauthorizedException
     */
    public function update(array $attributes, int $id): Model|Collection
    {
        if (!true) return null;

        $employeeFunctionsService = $this->getById($id);
        $employeeFunctionsService->update($attributes);
        return $employeeFunctionsService->load($this->relations);
    }

    /**
     * Trash a specified data in the database
     *
     * @param int $id
     * @return mixed
     * @throws UnauthorizedException
     */
    public function delete(int $id): mixed
    {
        if (!true) return null;
        $employeeFunctionsService = $this->getById($id);
        return $employeeFunctionsService->delete();
    }

    /**
     * Permanently delete a specific data in the database
     *
     * @param int $id
     * @return bool|null
     * @throws UnauthorizedException
     */
    public function forceDelete(int $id): ?bool
    {
        if (!true) return null;
        $employeeFunctionsService = $this->getById($id);
        return $employeeFunctionsService->forceDelete();
    }

    /**
     * Restore a specific data in the database
     *
     * @param int $id
     * @return bool|null
     * @throws UnauthorizedException
     */
    public function restore(int $id): ?bool
    {
        if (!true) return null;

        $employeeFunctionsService = $this->getById($id);
        return $employeeFunctionsService->restore();
    }
}

```

### Resources for APIs
<p>For  <i>resources</i>, this is a bit weird: 

```
php artisan make:api-resources User/Employee
```
<p>Will create some resources files like: </p>

#### 1. Controller
###### <i>Oneshot customized controller file:</i>

<pre>app/Http/Controllers/User/EmployeeController.php</pre>

#### 2. Request
###### <i>Default laravel request file:</i>
<pre>
app/Http/Requests/User/EmployeeRequest.php
</pre>

#### 3. Resource
###### <i>Default laravel resource file:</i>
<pre>
app/Http/Resource/User/EmployeeResource.php
</pre>

#### 4. Model
###### <i>Default laravel Model object and his migration at database/migrations folder:</i>

<pre>
app/Models/User/EmployeeModel.php
</pre>

<p>For those who like to save time, how about this last feature in this package?</p>
<p>You can see how the generated controller looks like:</p>

```php
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmployeeRequest;
use App\Http\Resources\User\EmployeeResource;
use App\Services\User\EmployeeService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{

    public function __construct(
        public EmployeeService $employeeService
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     * @throws UnauthorizedException
     */
    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->employeeService->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmployeeRequest $employeeRequest
     * @return EmployeeResource
     * @throws UnauthorizedException
     */
    public function store(EmployeeRequest $employeeRequest): EmployeeResource
    {
        $employeeRequest->validated($employeeRequest->all());
        $employee = $this->employeeService->create($employeeRequest->all());
        return new EmployeeResource($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return EmployeeResource
     * @throws UnauthorizedException
     */
    public function show(int $id): EmployeeResource
    {
        $employee = $this->employeeService->getById($id);
        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EmployeeRequest $employeeRequest
     * @param int $id
     * @return EmployeeResource
     * @throws UnauthorizedException
     */
    public function update(EmployeeRequest $employeeRequest, int $id): EmployeeResource
    {
        $employeeRequest->validated($employeeRequest->all());
        $employee = $this->employeeService->getById($id);
        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     * @throws UnauthorizedException
     */
    public function destroy(int $id): mixed
    {
        return $this->employeeService->delete($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     * @throws UnauthorizedException
     */
    public function forceDelete(int $id): mixed
    {
        return $this->employeeService->forceDelete($id);
    }
    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     * @throws UnauthorizedException
     */
    public function restore(int $id): mixed
    {
        return $this->employeeService->restore($id);
    }
}

```
