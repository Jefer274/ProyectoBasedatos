<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");

// Datos de conexión a la base de datos
$host = "bctlszlf0mmvfsswviyx-mysql.services.clever-cloud.com";
$db = "bctlszlf0mmvfsswviyx";
$user = "uf7pbq1fmb8mhzmp";
$password = "Au21q0L1meucH4Ktkicp";
$port = "3306";

// Crear conexión
$conn = new mysqli($host, $user, $password, $db, $port);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Leer datos enviados desde la app (POST)
$input = json_decode(file_get_contents("php://input"), true);

// Validar datos
if (isset($input["accion"])) {
    $accion = $input["accion"];

    switch ($accion) {
        case "insertar":
            $nombre = $conn->real_escape_string($input["nombre"]);
            $dni = $conn->real_escape_string($input["dni"]);
            $maquina = $conn->real_escape_string($input["maquina"]);
            $materiales = $conn->real_escape_string($input["materiales"]);
            $proceso = $conn->real_escape_string($input["proceso"]);
            $costo = $conn->real_escape_string($input["costo"]);

            $sql = "INSERT INTO pedidos (nombre, dni, maquina, materiales, proceso, costo) 
                    VALUES ('$nombre', '$dni', '$maquina', '$materiales', '$proceso', '$costo')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["mensaje" => "Registro insertado correctamente"]);
            } else {
                echo json_encode(["error" => "Error al insertar: " . $conn->error]);
            }
            break;

        case "consultar":
            $sql = "SELECT * FROM pedidos";
            $result = $conn->query($sql);

            $datos = [];
            while ($fila = $result->fetch_assoc()) {
                $datos[] = $fila;
            }
            echo json_encode($datos);
            break;

        default:
            echo json_encode(["error" => "Acción no válida"]);
    }
} else {
    echo json_encode(["error" => "No se recibió ninguna acción"]);
}

// Cerrar conexión
$conn->close();
?>