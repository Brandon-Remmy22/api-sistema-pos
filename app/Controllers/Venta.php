<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprobanteModel;
use App\Models\DetalleModel;
use App\Models\VentaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Venta extends ResourceController
{

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new VentaModel();
        //$data['ventas'] = $model->where('estado', 1)->findAll();
        $data['ventas'] = $model->getVentasWithCliente();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new VentaModel();

        // Obtener el cliente por ID
        $cliente = $model->where('id', $id)->first();

        if (!$cliente) {
            return $this->failNotFound('Venta no encontrado.');
        }

        return $this->respond($cliente);
    }

    public function create()
    {
        // Reglas de validación
        $model = new VentaModel();
        $detalleVentaModel = new DetalleModel();
        $data = $this->request->getJSON(true);
        $rules = [
            'total'         => 'required|decimal',
            'num_documento' => 'required|max_length[50]',
            'serie'         => 'required|max_length[50]',
            'descuento'     => 'permit_empty|decimal',
            'igv'           => 'required|max_length[250]',
            'subtotal'      => 'required|max_length[250]',
            'estado'        => 'permit_empty|in_list[0,1]',
            'id_cliente'    => 'permit_empty|integer|is_not_unique[cliente.id]',
            'id_usuario'    => 'permit_empty|integer|is_not_unique[usuario.id]',
            'id_comprobante' => 'permit_empty|integer|is_not_unique[comprobante.id]',
        ];

        // Validar los datos
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        // Obtener los datos validados
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {

            $ultimoDocumento = $model->selectMax('num_documento')->first();
            $nuevoNumeroDocumento = isset($ultimoDocumento['num_documento']) ? $ultimoDocumento['num_documento'] + 1 : 1;

            // Agregar el nuevo número de documento a los datos
            $data['num_documento'] = $nuevoNumeroDocumento;

            $model->save($data);
            $idVenta = $model->getInsertID();

            // Guardar los detalles de la venta
            $productos = $data['productos'];
            $precios = $data['precios'];
            $cantidades = $data['cantidades'];
            $importes = $data['importes'];
            $idComprobante = $data['id_comprobante'];

            // Llamar al método para actualizar la cantidad en el comprobante
            $this->updateComprobante($idComprobante);

            $this->save_detalle($detalleVentaModel, $productos, $idVenta, $precios, $cantidades, $importes);

            return $this->respondCreated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    protected function save_detalle($detalleVentaModel, $productos, $idVenta, $precios, $cantidades, $importes)
    {
        for ($i = 0; $i < count($productos); $i++) {
            $data = [
                'id_producto' => $productos[$i],
                'id_venta' => $idVenta,
                'precio' => $precios[$i],
                'cantidad' => $cantidades[$i],
                'importe' => $importes[$i],
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'fecha_actualizacion' => date('Y-m-d H:i:s'),
                'eliminado' => "0"
            ];
            $detalleVentaModel->save($data);

            // Aquí puedes añadir lógica para actualizar el inventario de productos
            // $this->updateProducto($productos[$i], $cantidades[$i]);
        }
    }

    protected function updateComprobante($idComprobante)
    {
        $comprobanteModel = new ComprobanteModel();
        $comprobanteActual = $comprobanteModel->find($idComprobante);

        if ($comprobanteActual) {
            $newCantidad = $comprobanteActual['catindad'] + 1;

            $comprobanteModel->update($idComprobante, ['catindad' => $newCantidad]);
        }
    }

    public function update($id = null)
    {
        $model = new VentaModel();
        $data = $this->request->getJSON(true);

        // Reglas de validación
        $rules = [
            'total'         => 'required|decimal',
            'num_documento' => 'required|max_length[50]',
            'serie'         => 'required|max_length[50]',
            'descuento'     => 'permit_empty|decimal',
            'igv'           => 'required|max_length[250]',
            'subtotal'      => 'required|max_length[250]',
            'estado'        => 'permit_empty|in_list[0,1]',
            'id_cliente'    => 'permit_empty|integer|is_not_unique[cliente.id]',
            'id_usuario'    => 'permit_empty|integer|is_not_unique[usuario.id]',
            'id_comprobante' => 'permit_empty|integer|is_not_unique[comprobante.id]',
        ];

        // Validar los datos
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Verificar si la venta existe
        if (!$model->find($id)) {
            return $this->failNotFound('Venta no encontrada.');
        }

        // Actualizar los datos de la venta
        $data['id'] = $id;  // Asegurarse de que el ID esté en los datos para la actualización

        try {
            if ($model->save($data)) {
                return $this->respondUpdated($data);
            } else {
                return $this->fail('No se pudo actualizar la venta.');
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $model = new VentaModel();

        // Verificar si el usuario existe
        if (!$model->find($id)) {
            return $this->failNotFound('Venta no encontrado');
        }

        // Realizar la eliminación lógica
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Venta eliminado lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar el Venta');
        }
    }

    public function obtenerReporteVentas()
    {
        $model = new VentaModel();

        // Total de ventas
        $totalVentas = $model->selectSum('total')->where('estado', 1)->first();

        // Total de ventas por día
        $totalVentasPorDia = $model->select("DATE(fechaCreacion) as fecha, SUM(total) as total_ventas")
            ->where('estado', 1)
            ->groupBy("DATE(fechaCreacion)")
            ->findAll();

        $data = [
            'total_ventas' => $totalVentas['total'],
            'ventas_por_dia' => $totalVentasPorDia,
        ];

        return $this->respond($data);
    }
}
