<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\RunTimeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiBaseController extends Controller implements ApiBaseControllerInterface
{
    protected string $resource;
    protected string $model;
    protected $storeRequestClass = null;
    protected $updateRequestClass = null;

    protected int $perPage = 15;

    public function index(Request $request): Response
    {
        $query = $this->newModelQuery();

        foreach ($request->query() as $field => $value) {
            if ($this->isFillable($field)) {
                $query->where($field, $value);
            }
        }

        $pagination = $query->paginate($request->get('per_page', $this->perPage));

        return response()->json([
            'data' => $pagination->items(),
            'meta' => [
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
                'links' => [
                    'first' => $pagination->url(1),
                    'last' => $pagination->url($pagination->lastPage()),
                    'next' => $pagination->nextPageUrl(),
                    'prev' => $pagination->previousPageUrl(),
                ],
            ],
        ], Response::HTTP_OK);
    }

    public function show($id): Response
    {
        $data =  $this->newModelQuery()->find($id);
        $data = $data->load($this->getRelationsToSync($data));

        if (!$data) {
            throw new ResourceNotFoundException("{$this->getResource()} não encontrado. ", Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request, $this->storeRequestClass);

        try {
            $data =  $this->model::create($validated);
            $this->syncRelations($data, $validated);
        } catch (\Throwable $e) {
            throw new RunTimeException("Erro ao criar {$this->getResource()}: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateRequest($request, $this->updateRequestClass);
        $model =  $this->newModelQuery()->find($id);
        
        if (!$model) {
            throw new ResourceNotFoundException("{$this->getResource()} não encontrado. ", Response::HTTP_NOT_FOUND);
        }

        try {
            $model->update($validated);
            $this->syncRelations($model, $validated);

        } catch (\Throwable $th) {
            throw new RunTimeException("Erro ao atualizar {$this->getResource()}.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $model->fresh(),
        ], Response::HTTP_CREATED);
    }

    public function destroy($id)
    {
        $model =  $this->newModelQuery()->find($id);
        if (!$model) {
            throw new ResourceNotFoundException("{$this->getResource()} não encontrado. ", Response::HTTP_NOT_FOUND);
        }
        try {
            $model->delete();
        } catch (\Throwable $th) {
            throw new RunTimeException("Erro ao deletar {$this->getResource()}.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
        ], Response::HTTP_NO_CONTENT);
    }

    protected function newModelQuery()
    {
        return App::make($this->model)->newQuery();
    }

    protected function isFillable(string $field): bool
    {
        $fillable = (new $this->model)->getFillable();

        return in_array($field, $fillable);
    }

    protected function validateRequest(Request $request, ?string $requestClass)
    {
        if (!$requestClass) {
            return $request->all();
        }

        return App::make($requestClass)->validated();
    }

    public function getResource(): string
    {
        if ($this->resource) {
            return ucfirst($this->resource);
        }

        return "Recurso";
    }

    protected function syncRelations(Model $model, array $data)
    {
        if(!method_exists($model, 'getRelationsToSync')) {
            return;
        }

        foreach ($model->getRelationsToSync() ?? [] as $relation => $field) {
            if (isset($data[$field])) {
                $model->{$relation}()->sync($data[$field]);
            }
        }
    }

    public function getRelationsToSync(Model $model): array
    {
        if(!method_exists($model, 'getRelationsToSync')) {
            return [];
        }

        return array_values($model->getRelationsToSync());
    }
}
