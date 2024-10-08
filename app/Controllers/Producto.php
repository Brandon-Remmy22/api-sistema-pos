<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Producto extends ResourceController
{
    public function index()
    {
        $model = new ProductoModel();
        $data['productos'] = $model->select('producto.*, categoria.nombre as categoria_nombre,')
            ->join('categoria', 'categoria.id = producto.id_categoria', 'left')->where('producto.estado', 1)->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new ProductoModel();

        // Obtener el producto por ID
        $producto = $model->where('id', $id)->first();

        if (!$producto) {
            return $this->failNotFound('Producto no encontrado.');
        }

        return $this->respond($producto);
    }

    public function create()
    {
        $model = new ProductoModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'descripcion'         => 'permit_empty|string|max_length[200]',
            'precio'              => 'required|decimal',
            'stock'               => 'required|decimal',
            'codigo'              => 'required|string|max_length[50]',
            'img'                 => 'permit_empty|string',
            'sexo'                 => 'permit_empty|string',
            'talla'                 => 'permit_empty|string',
            'color'                 => 'permit_empty|string',
            'id_categoria'        => 'permit_empty|integer',
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

    public function uploadImg()
    {
        // Definir las reglas de validación
        $rules = [
            'img' => 'permit_empty|uploaded[img]|max_size[img,4048]|is_image[img]|mime_in[img,image/jpg,image/jpeg,image/png]'
        ];
        $imgName = null;
        // Validar los datos de la solicitud
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        // Obtener los datos de la solicitud
        $data = $this->request->getPost();

        // Manejar la carga de la imagen
        $img = $this->request->getFile('img');
        if ($img && $img->isValid()) {
            // Mover la imagen al directorio de uploads y obtener la URL
            $imgName  = $img->getRandomName();
            $img->move(WRITEPATH . '../public/uploads', $imgName);
            $data['img'] = base_url('uploads/' . $imgName);
        }

        // Insertar el producto en la base de datos
        // if ($this->model->insert($data)) {
        return $this->respond([
            'status' => 'success',
            'message' => 'Producto almacenado correctamente',
            'img' => $imgName // Devolvemos el nombre de la imagen
        ]);
    }

    public function update($id = null)
    {
        $model = new ProductoModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'              => 'required|string|max_length[100]',
            'descripcion'         => 'permit_empty|string|max_length[200]',
            'precio'              => 'required|decimal',
            'stock'               => 'required|decimal',
            'codigo'              => 'required|string|max_length[50]',
            'img'                 => 'permit_empty|string',
            'sexo'                 => 'permit_empty|string',
            'talla'                 => 'permit_empty|string',
            'color'                 => 'permit_empty|string',
            'id_categoria'        => 'permit_empty|integer',
            'estado'              => 'permit_empty|integer|in_list[0,1]',
            'fechaCreacion'       => 'permit_empty|valid_date[Y-m-d H:i:s]',
            'ultimaActualizacion' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $producto = $model->find($id);
        if (!$producto) {
            return $this->failNotFound('producto no encontrado');
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
        $model = new ProductoModel();

        // Verificar si el usuario existe
        if (!$model->find($id)) {
            return $this->failNotFound('Producto no encontrado');
        }

        // Realizar la eliminación lógica
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Producto eliminado lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar el Producto');
        }
    }
}
