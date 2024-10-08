<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprobanteModel;
use App\Models\DetalleModel;
use App\Models\ProductoModel;
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
        $articuloModel = new ProductoModel();
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

            $this->save_detalle($detalleVentaModel, $articuloModel, $productos, $idVenta, $precios, $cantidades, $importes);

            return $this->respondCreated($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    protected function save_detalle($detalleVentaModel, $articuloModel, $productos, $idVenta, $precios, $cantidades, $importes)
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

            // Actualizar el stock en la tabla de articulos
            $articulo = $articuloModel->find($productos[$i]);
            if ($articulo) {
                $nuevoStock = $articulo['stock'] - $cantidades[$i];

                // Validar que el stock no sea negativo
                if ($nuevoStock < 0) {
                    return $this->fail("Stock insuficiente para el producto con ID: " . $productos[$i]);
                }

                // Actualizar el stock en la base de datos
                $articuloModel->update($productos[$i], ['stock' => $nuevoStock]);
            }
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
        $detalle = new DetalleModel();

        // Verificar si la venta existe
        $venta = $model->find($id);
        if (!$venta) {
            return $this->failNotFound('Venta no encontrada');
        }

        // Actualizar el estado de todos los detalles asociados a la venta
        $detalle->where('id_venta', $id)->set(['estado' => 0])->update();

        // Realizar la eliminación lógica de la venta
        $data = ['estado' => 0];
        if ($model->update($id, $data)) {
            return $this->respondDeleted(['message' => 'Venta eliminada lógicamente']);
        } else {
            return $this->failServerError('No se pudo eliminar la Venta');
        }
    }

    public function obtenerReporteVentas()
    {
        $model = new VentaModel();

        $productoModel = new ProductoModel();
        // Total de ventas
        $totalVentas = $model->selectSum('total')->where('estado', 1)->first();
        $totalStock = $productoModel->selectSum('stock')->first();
        // Total de ventas por día
        $totalVentasPorDia = $model->select("DATE(fechaCreacion) as fecha, SUM(total) as total_ventas")
            ->where('estado', 1)
            ->groupBy("DATE(fechaCreacion)")
            ->findAll();

        $data = [
            'total_ventas' => $totalVentas['total'],
            'ventas_por_dia' => $totalVentasPorDia,
            'stock' => $totalStock['stock']
        ];

        return $this->respond($data);
    }

    public function productosMasVendidos()
    {
        $model = new DetalleModel();





        // $data['ventas'] = $model->where('estado', 1)->join('producto', 'producto.id = detalle.id_producto', 'left')->findAll();

        // return $this->respond($data);

        $data['ventas'] = $model
            ->select('detalle.*, producto.nombre as producto_nombre, producto.precio as producto_precio') // Selecciona campos de ambas tablas
            ->where('detalle.estado', 1) // Filtra solo los detalles activos
            ->join('producto', 'producto.id = detalle.id_producto', 'left') // Une la tabla producto
            ->findAll(); // Obtén todos los resultados
        return $this->respond($data);
    }

    public function getTotalStock()
    {
        $model = new ProductoModel();

        // Realiza la suma total del stock
        $totalStock = $model->selectSum('stock')->first();

        // Retorna la suma total como un valor JSON
        return $this->respond(['total_stock' => $totalStock['stock']]);
    }
}
