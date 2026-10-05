<?php
/** Configuración general. Ajuste estos valores antes de publicar el sitio. */
define('SITE_NAME', 'GL Logística Aduanera SRL');
/* Se adapta automáticamente: localhost durante el uso local y el dominio real al publicar. */
$requestHost = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8080';
$forwardedProtocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
$requestScheme = ($forwardedProtocol === 'https' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')) ? 'https' : 'http';
define('SITE_URL', $requestScheme . '://' . $requestHost); // Sin barra final.
define('CONTACT_EMAIL', 'contacto@gllogistica.com.py');
/* Datos de contacto de demostración: reemplazar antes de la publicación final. */
define('WHATSAPP_NUMBER', '595994572050');
define('COMPANY_ADDRESS', 'Av. España 1280, Asunción, Paraguay');
define('COMPANY_HOURS', 'Lunes a viernes · 08:00 a 17:30');
define('MAPS_EMBED_URL', 'https://www.google.com/maps?q=Asunci%C3%B3n%2C%20Paraguay&output=embed');

/*
 * Base de datos en la nube: Supabase (PostgreSQL).
 * Copiar los datos desde Supabase > Project Settings > API. La clave secret
 * NO debe incluirse en archivos JavaScript ni subirse a repositorios públicos.
 */
define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://hwljmrydcwlraixquhaf.supabase.co');
define('SUPABASE_SECRET_KEY', getenv('SUPABASE_SECRET_KEY') ?: 'sb_publishable_GHxa6MTDDwT64hUXD6l8Gw_lkgWCNpX');
