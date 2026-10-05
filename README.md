# GL Logística Aduanera SRL

Sitio corporativo responsive con formulario, panel administrativo y Supabase (PostgreSQL en la nube). No requiere MySQL ni XAMPP.

## Abrir el sitio en tu PC (sin XAMPP y sin subirlo a Internet)

1. Abrí la carpeta del proyecto.
2. Hacé doble clic en `Iniciar GL Logistica.cmd`.
3. Se abrirá `http://127.0.0.1:8080` en el navegador.
4. Cuando termines, hacé doble clic en `Detener GL Logistica.cmd`.

El proyecto incluye una versión portátil de PHP dentro de `tools/php`; no instala ni modifica XAMPP. El sitio se puede ver desde esa PC aun sin Internet. Para que el formulario guarde mensajes, sí necesita conexión a Supabase.

## Idiomas

El idioma inicial es español. El selector del menú permite cambiar toda la interfaz pública entre **español (ES)**, **inglés (EN)** y **portugués (PT)**. La traducción funciona localmente en el navegador, sin enviar el contenido de la página a un traductor externo.

## Configuración de Supabase

1. Creá un proyecto gratuito en [Supabase](https://supabase.com/dashboard).
2. En **SQL Editor > New query**, pegá y ejecutá el contenido de `sql/supabase_setup.sql`.
3. En **Project Settings > API**, copiá el **Project URL** y la clave **secret** (la clave de servicio, no la que empieza con `sb_publishable_`).
4. En `includes/config.php`, reemplazá `SUPABASE_URL` y `SUPABASE_SECRET_KEY`. Esta clave se usa solo en PHP: no la copies a JavaScript ni la publiques.

## Publicación sin XAMPP

El proyecto sigue usando PHP 8 para mantener la validación segura, las sesiones y el panel. Subilo a cualquier hosting PHP 8 con cURL habilitado (por ejemplo, Hostinger, DonWeb o cPanel):

1. Subí todos los archivos por el administrador de archivos o FTP, dentro de `public_html`.
2. Configurá dirección, horario, mapa y las claves de Supabase en `includes/config.php`. `SITE_URL` se adapta automáticamente al dominio donde esté publicado.
3. Creá el primer administrador desde la terminal del hosting:

   ```bash
   php admin/create_admin.php tu-correo@dominio.com "UnaContraseñaSegura" "Administrador"
   ```

4. Ingresá al panel desde `https://tu-dominio.com/admin/login.php`.

## Publicar gratis en Render

El proyecto ya incluye `Dockerfile` y `render.yaml` para Render. Al crear un servicio nuevo, seleccioná el repositorio, elegí **Docker** y el plan **Free**. Render usará el puerto correcto automáticamente.

Antes del primer despliegue, agregá en **Environment** las variables privadas `SUPABASE_URL` y `SUPABASE_SECRET_KEY`. Esta segunda debe ser la clave de servicio de Supabase y nunca debe subirse a GitHub. Luego de publicar, la web quedará disponible en una dirección `https://<nombre>.onrender.com`.

El plan gratuito se suspende tras un período sin visitas; la primera visita posterior puede tardar aproximadamente un minuto.

## Diseño responsive

La interfaz está preparada para PC, tablet y teléfonos. Incluye menú hamburguesa, grillas que pasan a una columna, botones táctiles, formularios de 16 px para evitar zoom automático de iPhone y un ajuste adicional para pantallas angostas de 375 px.

## Estructura

- `assets/css` — estilos responsive del sitio y panel.
- `assets/js` — interacción, validación y animaciones sin librerías pesadas.
- `includes` — configuración, cliente seguro de Supabase y utilidades.
- `admin` — login, dashboard y gestión de consultas.
- `sql/supabase_setup.sql` — estructura y reglas de seguridad para Supabase.
