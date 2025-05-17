<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [

        // 1 ESTACIÓN CARNE EN VARA
            ['codigo' => 'CAR-001', 'nombre' => '1Kg Surtido', 'unidad_medida' => 'kg', 'precio_venta' => 35.00, 'area_id' => 1],
            ['codigo' => 'CAR-002', 'nombre' => '1Kg Cochino', 'unidad_medida' => 'kg', 'precio_venta' => 35.00, 'area_id' => 1],
            ['codigo' => 'CAR-003', 'nombre' => '1Kg de Carne', 'unidad_medida' => 'kg', 'precio_venta' => 35.00, 'area_id' => 1],
            ['codigo' => 'CAR-004', 'nombre' => '1Kg de Carne Importada', 'unidad_medida' => 'kg', 'precio_venta' => 46.00, 'area_id' => 1],
            ['codigo' => 'CAR-005', 'nombre' => '1/2Kg Surtido', 'unidad_medida' => 'kg', 'precio_venta' => 18.00, 'area_id' => 1],
            ['codigo' => 'CAR-006', 'nombre' => '1/2Kg Cochino', 'unidad_medida' => 'kg', 'precio_venta' => 18.00, 'area_id' => 1],
            ['codigo' => 'CAR-007', 'nombre' => '1/2Kg de Carne', 'unidad_medida' => 'kg', 'precio_venta' => 18.00, 'area_id' => 1],
            ['codigo' => 'CAR-008', 'nombre' => '1/2Kg de Carne Importada', 'unidad_medida' => 'kg', 'precio_venta' => 23.00, 'area_id' => 1],
            ['codigo' => 'CAR-009', 'nombre' => '1/4Kg Surtido', 'unidad_medida' => 'kg', 'precio_venta' => 9.00, 'area_id' => 1],
            ['codigo' => 'CAR-010', 'nombre' => '1/4Kg Cochino', 'unidad_medida' => 'kg', 'precio_venta' => 9.00, 'area_id' => 1],
            ['codigo' => 'CAR-011', 'nombre' => '1/4Kg de Carne', 'unidad_medida' => 'kg', 'precio_venta' => 9.00, 'area_id' => 1],
            ['codigo' => 'CAR-012', 'nombre' => '1/4Kg de Carne Importada', 'unidad_medida' => 'kg', 'precio_venta' => 12.00, 'area_id' => 1],

        // 4 ESTACIÓN COCINA
            ['codigo' => 'COC-001', 'nombre' => 'Ración de Tequeños', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-002', 'nombre' => 'Ración de Papas', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-003', 'nombre' => 'Ración de Huevos de Codorniz', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-004', 'nombre' => 'Ración de Tostones con Atún', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-005', 'nombre' => 'Ración de Tostones con Queso', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-006', 'nombre' => 'Ración de Pastelito Carne/Queso', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-007', 'nombre' => 'Ración de Arepas C/ Queso Fundido', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-008', 'nombre' => 'Ración de Queso Fundido', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-009', 'nombre' => 'Ración de Queso Crema C/ Casabe', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-010', 'nombre' => 'Ración de Alitas a la BBQ', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-011', 'nombre' => 'Ración Chorizo a la Monserratina', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-012', 'nombre' => 'Ración de Morcilla', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-013', 'nombre' => 'Ración Mixta (Morcilla/Chorizo)', 'unidad_medida' => 'ración', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-014', 'nombre' => 'Ensalada', 'unidad_medida' => 'plato', 'precio_venta' => 10.00, 'area_id' => 4],
            ['codigo' => 'COC-015', 'nombre' => 'Salchipapa', 'unidad_medida' => 'plato', 'precio_venta' => 10.00, 'area_id' => 4],
            ['codigo' => 'COC-016', 'nombre' => 'Hamburguesa', 'unidad_medida' => 'unidad', 'precio_venta' => 10.00, 'area_id' => 4],
            ['codigo' => 'COC-017', 'nombre' => 'Combo Kids', 'unidad_medida' => 'combo', 'precio_venta' => 10.00, 'area_id' => 4],
            ['codigo' => 'COC-018', 'nombre' => 'Ceviche de Pescado', 'unidad_medida' => 'plato', 'precio_venta' => 14.00, 'area_id' => 4],
            ['codigo' => 'COC-019', 'nombre' => 'Ceviche Mixto', 'unidad_medida' => 'plato', 'precio_venta' => 16.00, 'area_id' => 4],
            ['codigo' => 'COC-020', 'nombre' => 'Sopa Costilla Grande', 'unidad_medida' => 'plato', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-021', 'nombre' => 'Sopa Costilla Pequeña', 'unidad_medida' => 'plato', 'precio_venta' => 5.00, 'area_id' => 4],
            ['codigo' => 'COC-022', 'nombre' => 'Sopa Mondongo Grande', 'unidad_medida' => 'plato', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-023', 'nombre' => 'Sopa Mondongo Pequeña', 'unidad_medida' => 'plato', 'precio_venta' => 5.00, 'area_id' => 4],
            ['codigo' => 'COC-024', 'nombre' => 'Sopa Pollo Grande', 'unidad_medida' => 'plato', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-025', 'nombre' => 'Sopa Pollo Pequeña', 'unidad_medida' => 'plato', 'precio_venta' => 5.00, 'area_id' => 4],
            ['codigo' => 'COC-026', 'nombre' => 'Picadillo Llanero Grande', 'unidad_medida' => 'plato', 'precio_venta' => 8.00, 'area_id' => 4],
            ['codigo' => 'COC-027', 'nombre' => 'Picadillo Llanero Pequeño', 'unidad_medida' => 'plato', 'precio_venta' => 5.00, 'area_id' => 4],
            ['codigo' => 'COC-028', 'nombre' => 'Mariscos Grande', 'unidad_medida' => 'plato', 'precio_venta' => 10.00, 'area_id' => 4],
            ['codigo' => 'COC-029', 'nombre' => 'Mariscos Pequeño', 'unidad_medida' => 'plato', 'precio_venta' => 6.00, 'area_id' => 4],

        // 2 ESTACIÓN CACHAPA
            ['codigo' => 'CAC-001', 'nombre' => 'Cachapa Sola', 'unidad_medida' => 'plato', 'precio_venta' => 6.00, 'area_id' => 2],
            ['codigo' => 'CAC-002', 'nombre' => 'Cachapa C/ Queso Telita', 'unidad_medida' => 'plato', 'precio_venta' => 10.00, 'area_id' => 2],
            ['codigo' => 'CAC-003', 'nombre' => 'Cachapa C/ Cochino', 'unidad_medida' => 'plato', 'precio_venta' => 14.00, 'area_id' => 2],
            ['codigo' => 'CAC-004', 'nombre' => 'Cachapa C/ Queso Telita y Cochino', 'unidad_medida' => 'plato', 'precio_venta' => 16.00, 'area_id' => 2],
            ['codigo' => 'CAC-005', 'nombre' => '1/2 Cachapa C/ Queso Telita', 'unidad_medida' => 'plato', 'precio_venta' => 5.00, 'area_id' => 2],
            ['codigo' => 'CAC-006', 'nombre' => 'Ración de Queso Telita', 'unidad_medida' => 'ración', 'precio_venta' => 6.00, 'area_id' => 2],

        // 3 ESTACIÓN BARRA

        // Postres
            ['codigo' => 'POS-001', 'nombre' => 'Torta Grande', 'unidad_medida' => 'unidad', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'POS-002', 'nombre' => 'Marquesa y Quesillo', 'unidad_medida' => 'unidad', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'POS-003', 'nombre' => 'Torta Pequeña', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],

        // Servicios - Whisky
            ['codigo' => 'WHI-001', 'nombre' => 'Buchanans', 'unidad_medida' => 'botella', 'precio_venta' => 60.00, 'area_id' => 3],
            ['codigo' => 'WHI-002', 'nombre' => 'Old Par', 'unidad_medida' => 'botella', 'precio_venta' => 50.00, 'area_id' => 3],
            ['codigo' => 'WHI-003', 'nombre' => 'Descorche', 'unidad_medida' => 'servicio', 'precio_venta' => 25.00, 'area_id' => 3],

        // Servicios - Varios
            ['codigo' => 'SER-001', 'nombre' => 'Servicio de Vino', 'unidad_medida' => 'servicio', 'precio_venta' => 25.00, 'area_id' => 3],
            ['codigo' => 'SER-002', 'nombre' => 'Servicio de Ron Carúpano', 'unidad_medida' => 'servicio', 'precio_venta' => 20.00, 'area_id' => 3],
            ['codigo' => 'SER-003', 'nombre' => 'Servicio de Ron Cacique', 'unidad_medida' => 'servicio', 'precio_venta' => 20.00, 'area_id' => 3],
            ['codigo' => 'SER-004', 'nombre' => 'Servicio Cacique 500 Años', 'unidad_medida' => 'servicio', 'precio_venta' => 35.00, 'area_id' => 3],
            ['codigo' => 'SER-005', 'nombre' => 'Servicio de Santa Teresa 0,75', 'unidad_medida' => 'servicio', 'precio_venta' => 25.00, 'area_id' => 3],
            ['codigo' => 'SER-006', 'nombre' => 'Servicio Vodka Gordon', 'unidad_medida' => 'servicio', 'precio_venta' => 25.00, 'area_id' => 3],
            ['codigo' => 'SER-007', 'nombre' => 'Servicio Grey Goose', 'unidad_medida' => 'servicio', 'precio_venta' => 60.00, 'area_id' => 3],
            ['codigo' => 'SER-008', 'nombre' => 'Servicio de Anís Cartujo', 'unidad_medida' => 'servicio', 'precio_venta' => 20.00, 'area_id' => 3],
            ['codigo' => 'SER-009', 'nombre' => 'Servicio de Anís El Mono', 'unidad_medida' => 'servicio', 'precio_venta' => 40.00, 'area_id' => 3],
            ['codigo' => 'SER-010', 'nombre' => 'Servicio de Caroreña', 'unidad_medida' => 'servicio', 'precio_venta' => 15.00, 'area_id' => 3],

        // Cervezas
            ['codigo' => 'CER-001', 'nombre' => 'Tobo 10 Cervezas Polar', 'unidad_medida' => 'tobo', 'precio_venta' => 10.00, 'area_id' => 3],
            ['codigo' => 'CER-002', 'nombre' => 'Tobo 10 Cervezas Zulia', 'unidad_medida' => 'tobo', 'precio_venta' => 10.00, 'area_id' => 3],
            ['codigo' => 'CER-003', 'nombre' => 'Tobo 10 Cervezas Solera', 'unidad_medida' => 'tobo', 'precio_venta' => 10.00, 'area_id' => 3],
            ['codigo' => 'CER-004', 'nombre' => 'Cerveza por Unidad Polar', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'CER-005', 'nombre' => 'Cerveza por Unidad Zulia', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'CER-006', 'nombre' => 'Cerveza por Unidad Solera', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'CER-007', 'nombre' => 'Cerveza por Unidad Verano', 'unidad_medida' => 'unidad', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'CER-008', 'nombre' => 'Cerveza por Unidad Breeze Ice', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],
            ['codigo' => 'CER-009', 'nombre' => 'Cerveza por Unidad Corona', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],
            ['codigo' => 'CER-010', 'nombre' => 'Cerveza por Unidad Presidente', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],

        // Bebidas
            ['codigo' => 'BEB-001', 'nombre' => 'Batido Mora', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-002', 'nombre' => 'Batido Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-003', 'nombre' => 'Batido Melón', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-004', 'nombre' => 'Batido Durazno', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-005', 'nombre' => 'Batido Lechoza', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-006', 'nombre' => 'Batido Parchita', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-007', 'nombre' => 'Batido Patilla', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-008', 'nombre' => 'Batido Guanábana', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-009', 'nombre' => 'Batido Mango', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-010', 'nombre' => 'Batido Piña', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'BEB-012', 'nombre' => 'Café', 'unidad_medida' => 'vaso', 'precio_venta' => 3.00, 'area_id' => 3],
            ['codigo' => 'BEB-013', 'nombre' => 'Nestea', 'unidad_medida' => 'vaso', 'precio_venta' => 3.00, 'area_id' => 3],

        //Batidos
            ['codigo' => 'BAT-001', 'nombre' => '1/2 Batido de Mora', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-002', 'nombre' => '1/2 Batido de Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-003', 'nombre' => '1/2 Batido de Melon', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-004', 'nombre' => '1/2 Batido de Durazno', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-005', 'nombre' => '1/2 Batido de Lechoza', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-006', 'nombre' => '1/2 Batido de Parchita', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-007', 'nombre' => '1/2 Batido de Patilla', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-008', 'nombre' => '1/2 Batido de Guanabana', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-009', 'nombre' => '1/2 Batido de Mango', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-010', 'nombre' => '1/2 Batido de Piña', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-011', 'nombre' => '1/2 Batido de Limonada', 'unidad_medida' => 'vaso', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'BAT-012', 'nombre' => 'Batido Potencia (Mora, Fresa y Tomate de Árbol)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'BAT-013', 'nombre' => 'Batido Rezágate (Mango, Piña y Maracuya)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'BAT-014', 'nombre' => 'Batido Happy (Guanabana y Parchita)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'BAT-015', 'nombre' => 'Batido Tropical (Fresa, Melon y Piña)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'BAT-016', 'nombre' => 'Batido Berry (Mora y Fresa)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'BAT-017', 'nombre' => '1/2 Batido Potencia (Mora, Fresa y Tomate de Árbol)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'BAT-018', 'nombre' => '1/2 Batido Rezágate (Mango, Piña y Maracuya)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'BAT-019', 'nombre' => '1/2 Batido Happy (Guanabana y Parchita)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'BAT-020', 'nombre' => '1/2 Batido Tropical (Fresa, Melon y Piña)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'BAT-021', 'nombre' => '1/2 Batido Berry (Mora y Fresa)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            
        //Merengadas
            ['codigo' => 'TOD-001', 'nombre' => 'TODYY', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'CRL-001', 'nombre' => 'Cerelac', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MAL-001', 'nombre' => 'Malteada', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'SAM-001', 'nombre' => 'Samba', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-001', 'nombre' => 'Merengada de Durazno', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-002', 'nombre' => 'Merengada de Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-003', 'nombre' => 'Merengada de Melon', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-004', 'nombre' => 'Merengada de Lechosa', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-005', 'nombre' => 'Merengada de Guanabana', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'MER-006', 'nombre' => '1/2 Merengada de Durazno', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'MER-007', 'nombre' => '1/2 Merengada de Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'MER-008', 'nombre' => '1/2 Merengada de Melon', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'MER-009', 'nombre' => '1/2 Merengada de Lechosa', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'MER-010', 'nombre' => '1/2 Merengada de Guanabana', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],

        //LIMONADAS
            ['codigo' => 'LIM-001', 'nombre' => 'Limonada', 'unidad_medida' => 'vaso', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'LIM-002', 'nombre' => 'Limonada (Limón y Parchita)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'LIM-003', 'nombre' => 'Limonada (Limón y Hierba Buena)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'LIM-004', 'nombre' => 'Limonada (Limón y Granadina de Frambuesa)', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'LIM-005', 'nombre' => '1/2 Limonada (Limón y Parchita)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'LIM-006', 'nombre' => '1/2 Limonada (Limón y Hierba Buena)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],
            ['codigo' => 'LIM-007', 'nombre' => '1/2 Limonada (Limón y Granadina de Frambuesa)', 'unidad_medida' => 'vaso', 'precio_venta' => 2.50, 'area_id' => 3],

        //TRAGOS
            ['codigo' => 'TRA-001', 'nombre' => 'Trago de Whisky Buchanan', 'unidad_medida' => 'vaso', 'precio_venta' => 8.00, 'area_id' => 3],
            ['codigo' => 'TRA-002', 'nombre' => 'Trago de Whisky Old Par', 'unidad_medida' => 'vaso', 'precio_venta' => 8.00, 'area_id' => 3],
            ['codigo' => 'TRA-003', 'nombre' => 'Cuba Libre', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-004', 'nombre' => 'Mojito de Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-005', 'nombre' => 'Mojito de Parchita', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-006', 'nombre' => 'Mojito de Limón', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-007', 'nombre' => 'Michelada', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-008', 'nombre' => 'Daikiri de Fresa', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'TRA-009', 'nombre' => 'Daikiri de Parchita', 'unidad_medida' => 'vaso', 'precio_venta' => 5.00, 'area_id' => 3],

        //EXTRAS
            ['codigo' => 'EX-001', 'nombre' => 'Jugo Yukery 1,5', 'unidad_medida' => 'unidad', 'precio_venta' => 6.00, 'area_id' => 3],
            ['codigo' => 'EX-002', 'nombre' => 'Juguito', 'unidad_medida' => 'unidad', 'precio_venta' => 1.50, 'area_id' => 3],
            ['codigo' => 'EX-003', 'nombre' => 'Refresco', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'EX-004', 'nombre' => 'Agua Minalba', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'EX-005', 'nombre' => 'Malta', 'unidad_medida' => 'unidad', 'precio_venta' => 1.00, 'area_id' => 3],
            ['codigo' => 'EX-006', 'nombre' => 'Refresco de Lata', 'unidad_medida' => 'unidad', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'EX-007', 'nombre' => 'Lipton', 'unidad_medida' => 'unidad', 'precio_venta' => 2.00, 'area_id' => 3],
            ['codigo' => 'EX-008', 'nombre' => 'Agua Perrier', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],
            ['codigo' => 'EX-009', 'nombre' => 'Gatorade', 'unidad_medida' => 'unidad', 'precio_venta' => 3.00, 'area_id' => 3],
            ['codigo' => 'EX-010', 'nombre' => 'Red Bull', 'unidad_medida' => 'unidad', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'EX-011', 'nombre' => 'Monster', 'unidad_medida' => 'unidad', 'precio_venta' => 5.00, 'area_id' => 3],
            ['codigo' => 'EX-012', 'nombre' => 'Refresco de 2Lt', 'unidad_medida' => 'unidad', 'precio_venta' => 4.00, 'area_id' => 3],

        //CIGARRILLOS
            ['codigo' => 'CIG-001', 'nombre' => 'Cigarrillo Belmon', 'unidad_medida' => 'caja', 'precio_venta' => 4.00, 'area_id' => 3],
            ['codigo' => 'CIG-002', 'nombre' => 'Cigarrillo Lucky', 'unidad_medida' => 'caja', 'precio_venta' => 5.00, 'area_id' => 3],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}