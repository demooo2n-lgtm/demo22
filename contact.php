<?php
require_once __DIR__ . '/includes/db.php'; require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'message'=>'Método no permitido.']); exit; }
if (!verify_csrf()) { http_response_code(403); echo json_encode(['ok'=>false,'message'=>'La sesión expiró. Actualizá la página e intentá nuevamente.']); exit; }
if (!empty($_POST['website'])) { echo json_encode(['ok'=>true,'message'=>'Gracias, recibimos tu consulta.']); exit; }
$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW)); $company = trim((string) filter_input(INPUT_POST, 'company', FILTER_UNSAFE_RAW)); $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)); $phone = trim((string) filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW)); $service = trim((string) filter_input(INPUT_POST, 'service', FILTER_UNSAFE_RAW)); $message = trim((string) filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW));
if (mb_strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($phone) < 6 || mb_strlen($message) < 10) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Revisá los campos obligatorios e intentá nuevamente.']); exit; }
try {
  supabase_request('POST', 'contact_messages', [['name'=>$name, 'company'=>$company ?: null, 'email'=>$email, 'phone'=>$phone, 'service'=>$service ?: null, 'message'=>$message]], ['Prefer: return=minimal']);
  // El registro se guarda aun cuando el servidor local no tenga correo configurado.
  $subject = 'Nueva consulta web - GL Logística'; $body = "Nombre: $name\nEmpresa: $company\nCorreo: $email\nTeléfono: $phone\nServicio: $service\n\nMensaje:\n$message"; @mail(CONTACT_EMAIL, $subject, $body, "From: noreply@gl-logistica.com.py\r\nReply-To: $email");
  echo json_encode(['ok'=>true,'message'=>'¡Gracias! Tu consulta fue enviada correctamente. Te contactaremos a la brevedad.']);
} catch (Throwable $error) { error_log('Formulario GL Logística: ' . $error->getMessage()); http_response_code(500); echo json_encode(['ok'=>false,'message'=>'No pudimos enviar tu consulta por el momento. Por favor, escribinos por WhatsApp.']); }
