<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeleccionController extends Controller
{
    public function salario_x($salario, $dias){
        $result = ($salario * $dias) / 30;
        return $result;
    }
    public function calcular_salario($salario, $ventas, $dias) {
        if ($ventas > 5000) $resultado = $salario + ($salario * 0.1);
        elseif ($ventas > 1000) $resultado = $salario + ($salario * 0.05);
        else $resultado = $salario + ($salario * 0.01);
        return $resultado;
    }
    public function comision($salario, $ventas, $dias) {
        if ($ventas > 5000) $resultado = $salario * 0.1;
        elseif ($ventas > 1000) $resultado = $salario * 0.05;
        else $resultado = $salario * 0.01;
        return $resultado;
    }
    public function porcentaje_prorrateo($salario_base, $salario){
        $result = ($salario * 100) / $salario_base;
        return round($result,2);
    }
    public function datos(Request $request){
        if($request->input("user") === "admin@admin.com" && $request->input("password") === "Clave123") {
            $rules = [
                'salario_base' => 'required|numeric|between:0,999999.99',
                'dias_trabajados' => 'required|numeric|min:0|not_in:0',
                'valor_de_las_ventas' => 'required|numeric|min:0|'
            ];
            $validator = \Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->all()
                ], 400);
            }
            $salario_base = $request->input('salario_base');
            $dias = $request->input('dias_trabajados');
            $ventas = $request->input('valor_de_las_ventas');
            $salario = $this->salario_x($salario_base, $dias);
            $salario_calculado = $this->calcular_salario($salario, $ventas, $dias);
            $comision = $this->comision($salario, $ventas, $dias);
            $prorrateo = $this->porcentaje_prorrateo($salario_base, $salario);
            return response()->json([
                'Salario base' => $salario_base,
                'Días trabajados' => $dias,
                'Valos de las ventas' => $ventas,
                'Salario calculado' => $salario_calculado,
                'Comisiones ganadas' => $comision,
                'Porcentaje de prorroteo' => $prorrateo.'%'
            ], 200);
        }

        return response()->json([
            'errors' => 'credenciales incorrectas'
        ], 400);

    }
}
