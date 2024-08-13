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

    public function login()
    {
        $model = new UsuarioModel();

        // Obtener los datos de la solicitud (suponiendo que vienen en JSON)
        $data = $this->request->getJSON(true);

        // Validar las credenciales
        if (empty($data['email']) || empty($data['password'])) {
            return $this->failValidationErrors('El email y la contraseña son obligatorios.');
        }

        // Buscar el usuario por email
        $user = $model->select('usuario.*, rol.nombre as rol_nombre, rol.descripcion as rol_descripcion')
            ->join('rol', 'rol.id = usuario.id_rol', 'left')
            ->where('usuario.email', $data['email'])
            ->first();

        if (!$user) {
            return $this->failNotFound('Usuario no encontrado.');
        }

        // Verificar la contraseña
        if (!password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized('Contraseña incorrecta.');
        }

        // Si las credenciales son correctas, devolver la información del usuario
        // (No incluir la contraseña en la respuesta)
        unset($user['password']);

        return $this->respond([
            'status' => 200,
            'message' => 'Inicio de sesión exitoso.',
            'user' => $user
        ]);
    }


    public function crearVendedor()
    {
        $model = new UsuarioModel();
        $data = $this->request->getJSON(true);
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

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['id_rol'] = 2;


        try {
            $model->save($data);
            $email = \Config\Services::email();

            $email->setFrom('nvlab@northvelia.com', 'Tienda MIRAMAR');
            $email->setTo($data['email']);
            $email->setSubject('Bienvenido a nuestra empresa NOMBRE');
            $email->setMessage('<p>Gracias por trabajar con nosotros. Click aquí para restablecer tu contraseña <a href="' . site_url('reset-password?token=' . $data['email']) . '">y</a> mantener tu seguridad.</p>');

            if (!$email->send()) {
                return $this->fail('Error al enviar el email');
            }
            return $this->respondCreated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

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
        $data = $this->request->getJSON(true);
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

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['id_rol'] = 1;


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
        $model = new UsuarioModel();
        $data = $this->request->getJSON(true);
        // Validación de los datos de entrada
        $rules = [
            'nombre'              => 'required|string|max_length[50]',
            'primerApellido'      => 'required|string|max_length[50]',
            'segundoApellido'     => 'permit_empty|string|max_length[50]',
            'fechaNacimiento'     => 'required|valid_date[Y-m-d]',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'email'               => 'required|valid_email',
            'password'            => 'permit_empty|string|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $usuario = $model->find($id);
        if (!$usuario) {
            return $this->failNotFound('Usuario no encontrado');
        }

        // Encriptar la contraseña si se proporciona
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // Si no se proporciona una nueva contraseña, conservar la actual
            $data['password'] = $usuario['password'];
        }

        // Actualizar los datos
        $model->update($id, $data);

        return $this->respondUpdated($data);
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
        $model = new UsuarioModel();

        // Verificar si el usuario existe
        if (!$model->find($id)) {
            return $this->failNotFound('Usuario no encontrado');
        }

        // Realizar la eliminación lógica
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Usuario eliminado lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar el usuario');
        }
    }
}
