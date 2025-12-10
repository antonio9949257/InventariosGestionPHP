# Sistema de Gestión de Inventario con Asistente de IA

Este es un sistema web completo para la gestión de inventarios, desarrollado en PHP puro con una base de datos MySQL. El proyecto no solo implementa las funcionalidades CRUD (Crear, Leer, Actualizar, Borrar) esenciales para la gestión de un almacén, sino que también integra un innovador asistente conversacional basado en la **API de Google Gemini** para realizar consultas en lenguaje natural.

## ✨ Características Principales

*   **Gestión de Productos**: Control total sobre los productos del inventario, incluyendo nombre, descripción, stock, precios, etc.
*   **Gestión de Categorías**: Organiza los productos en diferentes categorías.
*   **Gestión de Proveedores**: Lleva un registro de los proveedores de tus productos.
*   **Gestión de Movimientos**: Registra las entradas y salidas de productos del inventario.
*   **Gestión de Usuarios**: Maneja los usuarios que tienen acceso al sistema.
*   **Generación de Reportes PDF**: Exporta listados de las diferentes secciones a archivos PDF utilizando la librería FPDF.
*   **Asistente de IA Conversacional**: Interactúa con el sistema a través de un chat inteligente. Puedes preguntar cosas como:
    *   `"¿Cuál es el stock actual de la Aspirina?"`
    *   `"Lista los productos de la categoría 'Analgésicos'"`
    *   `"¿Qué productos están por debajo de las 20 unidades?"`
    *   `"Dame el teléfono del proveedor 'PharmaCorp'"`

## 🛠️ Tecnologías Utilizadas

*   **Backend**: PHP 8.x
*   **Base de Datos**: MySQL
*   **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
*   **Inteligencia Artificial**: Google Gemini API (con Function Calling)
*   **Dependencias PHP**:
    *   `fpdf/fpdf`: Para la generación de documentos PDF.
    *   Gestionadas a través de **Composer**.

## 📂 Estructura del Proyecto

El proyecto sigue una estructura modular para separar las responsabilidades:

```
/
├── adi_bootstrap/      # Archivos de la librería Bootstrap
├── api/                # Endpoints de la API (ej. para el chat)
├── categorias/         # Módulo CRUD para Categorías
├── database/           # Scripts SQL para la base de datos
├── lib/                # Librerías y funciones reutilizables (ej. tools.php)
├── movimientos/        # Módulo CRUD para Movimientos
├── productos/          # Módulo CRUD para Productos
├── proveedores/        # Módulo CRUD para Proveedores
├── templates/          # Partes de la plantilla (header, footer)
├── usuarios/           # Módulo CRUD para Usuarios
├── vendor/             # Dependencias de Composer
├── chat_asistente.php  # Interfaz del Asistente de IA
├── conexion.php        # Script de conexión a la BD
├── index.php           # Página de inicio / login
├── README.md           # Este archivo
└── README_IA.md        # Explicación detallada de la IA
```

## 🚀 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en un entorno de desarrollo local.

### Requisitos

*   Un servidor web local (XAMPP, WAMP, MAMP, o similar) con PHP 8.x y MySQL.
*   Composer para gestionar las dependencias de PHP.
*   Git para clonar el repositorio.

### Pasos

1.  **Clonar el Repositorio**:
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd <NOMBRE_DEL_DIRECTORIO>
    ```

2.  **Instalar Dependencias**:
    Ejecuta Composer en la raíz del proyecto para descargar las librerías necesarias (como FPDF).
    ```bash
    composer install
    ```

3.  **Configurar la Base de Datos**:
    *   Inicia tu servidor MySQL.
    *   Crea una nueva base de datos. Puedes llamarla `inventariosphp` o como prefieras.
    *   Importa la estructura y los datos iniciales. Puedes usar el archivo `database/bd.sql` o ejecutar el script `import_db.sh` si estás en un entorno Linux/Mac.
        ```bash
        # Ejemplo de importación por línea de comandos
        mysql -u tu_usuario -p tu_base_de_datos < database/bd.sql
        ```
    *   Abre el archivo `conexion.php` y actualiza las credenciales para que coincidan con tu configuración local:
        ```php
        $servidor = "localhost";
        $usuario = "tu_usuario_mysql";
        $password = "tu_contraseña_mysql";
        $database = "tu_base_de_datos";
        ```

4.  **Configurar la API de Gemini**:
    *   Necesitas una clave API de Google AI Studio. Puedes obtenerla gratis [aquí](https://aistudio.google.com/app/apikey).
    *   En la raíz del proyecto, crea un nuevo archivo llamado `config.php`.
    *   Añade el siguiente contenido al archivo `config.php`, reemplazando `'TU_API_KEY_AQUI'` con tu clave real:
        ```php
        <?php
        define('GEMINI_API_KEY', 'TU_API_KEY_AQUI');
        ?>
        ```
    *   **Importante**: El archivo `config.php` está intencionadamente ignorado por Git (a través de `.gitignore`) para no exponer claves secretas en el repositorio.

5.  **Ejecutar el Proyecto**:
    *   Copia la carpeta del proyecto al directorio de tu servidor web (ej. `htdocs` en XAMPP).
    *   Inicia tu servidor Apache.
    *   Abre tu navegador y ve a `http://localhost/<NOMBRE_DEL_DIRECTORIO>`.

## Uso

Una vez que el proyecto esté corriendo, serás recibido por la pantalla de login. Puedes registrar un nuevo usuario o usar las credenciales por defecto si las has configurado en el script de la base de datos.

Dentro del sistema, podrás navegar por los diferentes módulos usando el menú lateral. Para usar el asistente de IA, simplemente ve a la sección "Asistente de IA" y comienza a escribir tus preguntas en el chat.
