<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprobanteModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Comprobante extends ResourceController
{
    public function create()
    {
        $model = new ComprobanteModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'nombre'      => 'required|string|max_length[100]',
            'catindad'    => 'required|decimal',
            'igv'         => 'required|decimal',
            'serie'       => 'required|decimal',
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
}
