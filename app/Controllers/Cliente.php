<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Cliente extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new ClienteModel();
        $data['clientes'] = $model->where('estado', 1)->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $model = new ClienteModel();

        // Obtener el cliente por ID
        $cliente = $model->where('id', $id)->first();

        if (!$cliente) {
            return $this->failNotFound('Cliente no encontrado.');
        }

        return $this->respond($cliente);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $model = new ClienteModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'direccion'           => 'required|string|max_length[200]',
            'telefono'            => 'permit_empty|string|max_length[50]',
            'numDocumento'        => 'permit_empty|string|max_length[50]',
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

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $model = new ClienteModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'direccion'           => 'required|string|max_length[200]',
            'telefono'            => 'permit_empty|string|max_length[50]',
            'numDocumento'        => 'permit_empty|string|max_length[50]',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $cliente = $model->find($id);
        if (!$cliente) {
            return $this->failNotFound('cliente no encontrado');
        }

        try {
            $model->update($id, $data);
            return $this->respondUpdated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $model = new ClienteModel();

        // Verificar si el usuario existe
        if (!$model->find($id)) {
            return $this->failNotFound('Cliente no encontrado');
        }

        // Realizar la eliminación lógica
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Cliente eliminado lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar el Cliente');
        }
    }
}
