<?php
// Permitir que cualquier dispositivo lea o envíe datos
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

$archivo = 'datos_carta.json';

// Si el archivo no existe, definimos la estructura inicial por defecto
if (!file_exists($archivo)) {
    $datosIniciales = [
        "menu" => [
            [
                "id" => "espresso", "label" => "Espresso", "items" => [
                    ["name" => "Espresso", "desc" => "Doble carga de café intenso", "price" => "$2.700", "featured" => false],
                    ["name" => "Americano", "desc" => "Doble carga de espresso con agua caliente", "price" => "$2.900", "featured" => false]
                ]
            ],
            [
                "id" => "leche", "label" => "Con Leche", "items" => [
                    ["name" => "Latte", "desc" => "Espresso suave con leche vaporizada", "price" => "$4.300", "featured" => false]
                ]
            ]
        ],
        "colores" => ["verde" => "#3d4f38", "marron" => "#6b5744", "crema" => "#f5f0e8", "hero" => "#3d4f38"],
        "textos" => ["sub" => "nuestra", "main" => "carta", "address" => "O'Higgins 978, Concepción · Frente a tribunales"]
    ];
    file_put_contents($archivo, json_encode($datosIniciales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Si la petición es POST, significa que el Administrador envió cambios para guardar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonRecibido = file_get_contents('php://input');
    $datosNuevos = json_decode($jsonRecibido, true);
    
    if ($datosNuevos) {
        // Leemos lo que ya existe para no borrar secciones enteras si solo se actualiza una parte
        $datosActuales = json_decode(file_get_contents($archivo), true);
        
        // Fusionamos o reemplazamos según corresponda
        if (isset($datosNuevos['menu'])) $datosActuales['menu'] = $datosNuevos['menu'];
        if (isset($datosNuevos['colores'])) $datosActuales['colores'] = $datosNuevos['colores'];
        if (isset($datosNuevos['textos'])) $datosActuales['textos'] = $datosNuevos['textos'];
        
        // Guardamos físicamente en el archivo de texto del servidor
        file_put_contents($archivo, json_encode($datosActuales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(["status" => "success", "message" => "Datos guardados correctamente"]);
        exit;
    }
}

// Si es una petición normal (GET), simplemente devolvemos el contenido actual del archivo
echo file_get_contents($archivo);
?>