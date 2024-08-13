<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Usuario extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new UsuarioModel();
        $data['usuarios'] = $model->findAll();

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
        //
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
        $model = new UsuarioModel();

        // Validación de los datos de entrada
        $validation = \Config\Services::validation();
        $rules = [
            'nombre'              => 'required|string|max_length[50]',
            'primerApellido'      => 'required|string|max_length[50]',
            'segundoApellido'     => 'permit_empty|string|max_length[50]',
            'fechaNacimiento'     => 'required|valid_date[Y-m-d]',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'email'               => 'required|valid_email|is_unique[usuario.email]', // Validación
            'password'            => 'required|string|min_length[8]' 
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Obtener los datos de entrada
        $data = [
            'nombre'              => $this->request->getPost('nombre'),
            'primerApellido'      => $this->request->getPost('primerApellido'),
            'segundoApellido'     => $this->request->getPost('segundoApellido'),
            'fechaNacimiento'     => $this->request->getPost('fechaNacimiento'),
            'estado'              => $this->request->getPost('estado') ?? 1,
            'fechaCreacion'       => $this->request->getPost('fechaCreacion'),
            'ultimaActualizacion' => $this->request->getPost('ultimaActualizacion'),
            'email'               => $this->request->getPost('email'),
            'password'            => $this->request->getPost('password'),
        ];

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
        //
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
        //
    }
}
