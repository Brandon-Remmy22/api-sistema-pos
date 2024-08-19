<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\categoriaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class categoria extends ResourceController
{
    public function index()
    {

        $model = new CategoriaModel();
        $data['categorias'] = $model->where('estado', 1)->findAll();

        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new CategoriaModel();

        // Obtener el Categoria por ID
        $categoria = $model->where('id', $id)->first();

        if (!$categoria) {
            return $this->failNotFound('categoria no encontrado.');
        }

        return $this->respond($categoria);
    }

    public function create()
    {
        $model = new categoriaModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'descripcion'         => 'required|string|max_length[300]',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $model->save($data);
            return $this->respondCreated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function update($id = null)
    {
        $model = new categoriaModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'descripcion'         => 'required|string|max_length[300]',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $categoria = $model->find($id);
        if (!$categoria) {
            return $this->failNotFound('categoria no encontrado');
        }

        try {
            $model->update($id, $data);
            return $this->respondUpdated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $model = new categoriaModel();

        // Verificar si el usuario existe
        if (!$model->find($id)) {
            return $this->failNotFound('Categoria no encontrado');
        }

        // Realizar la eliminación lógica
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Categoria eliminado lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar el Categoria');
        }
    }
}
