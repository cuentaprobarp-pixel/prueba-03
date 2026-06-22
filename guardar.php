<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

$archivo = 'datos_carta.json';

// Si el archivo no existe, creamos la estructura base inicial
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

// Procesar el guardado si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonRecibido = file_get_contents('php://input');
    $datosNuevos = json_decode($jsonRecibido, true);
    
    if ($datosNuevos) {
        $datosActuales = json_decode(file_get_contents($archivo), true);
        
        // Combinamos los campos que se hayan enviado
        if (isset($datosNuevos['menu'])) $datosActuales['menu'] = $datosNuevos['menu'];
        if (isset($datosNuevos['colores'])) $datosActuales['colores'] = $datosNuevos['colores'];
        if (isset($datosNuevos['textos'])) $datosActuales['textos'] = $datosNuevos['textos'];
        
        file_put_contents($archivo, json_encode($datosActuales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(["status" => "success", "message" => "Guardado exitoso"]);
        exit;
    }
}

// Responder con los datos guardados para peticiones GET convencionales
echo file_get_contents($archivo);
?>
