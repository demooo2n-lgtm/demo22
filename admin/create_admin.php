<?php
// Ejecutar únicamente desde consola: php admin/create_admin.php correo@dominio.com ContraseñaSegura Nombre
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('Este instalador solo puede ejecutarse desde la consola.'); }
require_once __DIR__ . '/../includes/db.php';
[$script, $email, $password, $name] = array_pad($argv, 4, null);
if (!$email || !$password || !$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) { exit("Uso: php admin/create_admin.php correo@dominio.com ContraseñaSegura Nombre\nLa contraseña debe tener al menos 10 caracteres.\n"); }
try { supabase_request('POST', 'admins', [['name'=>$name,'email'=>$email,'password_hash'=>password_hash($password,PASSWORD_DEFAULT)]], ['Prefer: return=minimal']); echo "Administrador creado correctamente.\n"; } catch(Throwable $e) { exit("No se pudo crear el administrador: " . $e->getMessage() . "\n"); }
