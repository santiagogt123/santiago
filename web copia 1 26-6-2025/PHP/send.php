<?php
// Datos de conexión a la base de datos (ajustalos a tu servidor)
$host = "localhost";
$dbname = "tu_base_de_datos";
$user = "root";  // por defecto en XAMPP
$pass = "";      // por defecto en XAMPP

// Inicializo variables para mostrar mensajes
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos del formulario
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validar que los campos no estén vacíos
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Todos los campos son obligatorios.";
    }
    // Validar el nombre: solo letras, espacios, guiones o apóstrofes (internacional)
    elseif (!preg_match("/^[\p{L}\s'-]+$/u", $name)) {
        $error = "El nombre solo puede contener letras y espacios (válido internacionalmente).";
    }
    // Validar el email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } else {
        // Sanear el mensaje para evitar inyección de código
        $safe_message = htmlspecialchars($message);

        // Enviar email
        $to = "tu-email@dominio.com";  // Cambia por tu email real
        $subject = "Nuevo mensaje de contacto de $name";
        $body = "Nombre: $name\n";
        $body .= "Email: $email\n\n";
        $body .= "Mensaje:\n$message\n";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";

        $mail_sent = mail($to, $subject, $body, $headers);

        // Guardar en la base de datos
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
            $stmt->bindValue(':nombre', $name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':mensaje', $message, PDO::PARAM_STR);
            $stmt->execute();

            if ($mail_sent) {
                $success = "✅ Mensaje enviado y guardado con éxito.";
            } else {
                $success = "Mensaje guardado, pero hubo un error enviando el correo.";
            }
        } catch (PDOException $e) {
            $error = "Error al guardar en la base de datos: " . $e->getMessage();
        }
    }
}
?>