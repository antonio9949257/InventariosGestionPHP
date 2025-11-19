# Flujo Técnico Detallado de la Integración con IA Gemini

Este documento describe, paso a paso, la secuencia exacta de llamadas a archivos y funciones que ocurren cuando un usuario realiza una consulta al asistente de IA.

**Escenario de ejemplo**: El usuario escribe en el chat: `¿Cuál es el stock de Paracetamol?`

---

### **Paso 1: Interfaz de Usuario - Captura de la Pregunta**

1.  **Archivo**: `chat_asistente.php`
2.  **Componente**: Formulario de chat y panel de conversación.
3.  **Acción**: El código JavaScript dentro de la etiqueta `<script>` en este mismo archivo gestiona la interacción.
    *   Se captura el texto del `textarea` cuando el usuario envía el formulario.
    *   Se añade el mensaje del usuario al historial (`chatHistory`).
    *   Se ejecuta una llamada `fetch()` al endpoint del backend.
        *   **Función Clave (JS)**: `fetch('api/chat.php', { method: 'POST', ... })`
        *   **Payload**: Se envía un objeto JSON que contiene el `chatHistory` completo.

---

### **Paso 2: Backend - Recepción y Preparación**

1.  **Archivo**: `api/chat.php`
2.  **Acción**: Este script es el punto de entrada del backend.
3.  **Funciones/Lógica Clave**:
    *   **`require '../config.php';`**: Carga la constante `GEMINI_API_KEY`.
    *   **`require '../lib/tools.php';`**: Carga las definiciones de las funciones PHP que pueden interactuar con la base de datos (las "herramientas").
    *   Se decodifica el JSON recibido (`file_get_contents('php://input')`) para obtener el historial de mensajes.
    *   Se define la variable **`$tools`**: un array PHP que describe la firma de cada función disponible en `lib/tools.php`. Por ejemplo, para `obtener_stock_producto`, la descripción incluiría su nombre, descripción y los parámetros que espera (ej: `nombre_producto`).
    *   Se invoca a la función `enviarA_Gemini()`, que está definida en este mismo archivo.
        *   **Llamada**: `enviarA_Gemini($mensajes, $tools)`

---

### **Paso 3: Primera Llamada a la API de Gemini**

1.  **Archivo**: `api/chat.php`
2.  **Función**: `enviarA_Gemini($mensajes, $tools)`
3.  **Acción**:
    *   Se construye el cuerpo (`body`) de la solicitud para la API de Gemini, incluyendo el historial de mensajes y la estructura de las `$tools`.
    *   Se realiza una petición **HTTP POST** a la URL de la API de Gemini (`https://generativelanguage.googleapis.com/...`) usando cURL o un cliente HTTP.
    *   El script espera la respuesta de Gemini.

---

### **Paso 4: Interpretación de Gemini y Ejecución de Herramienta**

1.  **Respuesta de Gemini**: La API devuelve un JSON. En este caso, no contiene texto para el usuario, sino un objeto `functionCall`.
    *   **Ejemplo de `functionCall`**: `{ "name": "obtener_stock_producto", "args": { "nombre_producto": "Paracetamol" } }`
2.  **Archivo**: `api/chat.php`
3.  **Acción**: El script PHP parsea la respuesta.
    *   Detecta la presencia de `functionCall`.
    *   Extrae el nombre de la función (`$functionName = 'obtener_stock_producto'`) y los argumentos (`$args = ['nombre_producto' => 'Paracetamol']`).
    *   Utiliza la función de PHP `call_user_func_array()` para invocar dinámicamente la función solicitada por Gemini.
        *   **Llamada Clave**: `call_user_func_array($functionName, $args)`

---

### **Paso 5: Acceso a la Base de Datos**

1.  **Archivo Invocado**: `lib/tools.php`
2.  **Función Ejecutada**: `obtener_stock_producto('Paracetamol')`
3.  **Acción**:
    *   **`require_once __DIR__ . '/../conexion.php';`**: Se incluye el script de conexión para obtener la variable `$conexion` (el objeto `mysqli`).
    *   Se prepara y ejecuta una consulta SQL segura para evitar inyección SQL.
        *   **Función Clave (MySQLi)**: `$stmt = $conexion->prepare("SELECT stock FROM productos WHERE nombre LIKE ?");`
        *   **Función Clave (MySQLi)**: `$stmt->bind_param("s", $nombre_producto);`
        *   **Función Clave (MySQLi)**: `$stmt->execute();`
    *   La función `obtener_stock_producto` retorna el resultado obtenido de la base de datos (ej: `150`).

---

### **Paso 6: Segunda Llamada a Gemini con el Resultado**

1.  **Archivo**: `api/chat.php`
2.  **Acción**:
    *   El valor de retorno (`150`) se recibe en `api/chat.php`.
    *   Se construye un nuevo mensaje especial para el historial, indicando que es el resultado de la `functionCall`.
    *   Se vuelve a llamar a la función `enviarA_Gemini()`, pero esta vez el historial de mensajes contiene la pregunta original, la solicitud de función de Gemini y el resultado de esa función.
        *   **Llamada**: `enviarA_Gemini($historial_actualizado, $tools)`

---

### **Paso 7: Respuesta Final y Visualización**

1.  **Respuesta de Gemini**: Ahora la API de Gemini tiene todo el contexto y responde con un mensaje de texto final.
    *   **Ejemplo de respuesta**: `"El stock de Paracetamol es de 150 unidades."`
2.  **Archivo**: `api/chat.php`
3.  **Acción**:
    *   El script recibe esta respuesta final.
    *   La empaqueta en un JSON de respuesta para el frontend.
        *   **Función Clave**: `echo json_encode(['reply' => $respuesta_final]);`
4.  **Archivo**: `chat_asistente.php`
5.  **Acción**:
    *   El `fetch()` de JavaScript en el frontend recibe la respuesta.
    *   El `.then(data => ...)` del `fetch` extrae el texto de la respuesta.
    *   Se crea un nuevo elemento en el DOM del chat para mostrar la respuesta del asistente al usuario.
