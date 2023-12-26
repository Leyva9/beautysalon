<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $table = '';
    protected static $fieldsDB = [];

    // Alertas y Mensajes
    protected static $alerts = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlert($tipo, $message) {
        static::$alerts[$tipo][] = $message;
    }

    // Validación
    public static function getAlerts() {
        return static::$alerts;
    }

    public function validate() {
        static::$alerts = [];
        return static::$alerts;
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function sqlQuery($query) {
        // Consultar la base de datos
        $result = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($register = $result->fetch_assoc()) {
            $array[] = static::createObject($register);
        }

        // liberar la memoria
        $result->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function createObject($register) {
        $object = new static;

        foreach($register as $key => $value ) {
            if(property_exists( $object, $key  )) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    // Identificar y unir los atributos de la BD
    public function iterateAtributes() {
        $atributes = [];
        foreach(static::$fieldsDB as $field) {
            if($field === 'id') continue;
            $atributes[$field] = $this->$field;
        }
        return $atributes;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizeAtributes() {
        $atributes = $this->iterateAtributes();
        $sanitized = [];
        foreach($atributes as $key => $value ) {
            $sanitized[$key] = self::$db->escape_string($value);
        }
        return $sanitized;
    }

    // Sincroniza BD con Objetos en memoria
    public function syncObjects($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function save() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $result = $this->update();
        } else {
            // Creando un nuevo registro
            $result = $this->create();
        }
        return $resultado;
    }

    // Todos los registros
    public static function get($limit = '') : array {
        $table = static::$table;
        if($limit !== '') {
            $query = "SELECT * FROM $table LIMIT $limit ";
        } else {
            $query = "SELECT * FROM $table ";
        }
        return self::sqlQuery($query);
    }

    // Busca un registro por su id
    public static function getById($id) {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = $id";
        $result = self::sqlQuery($query);
        return array_shift( $result ) ;
    }

    // crea un nuevo registro
    public function create() {
        // Sanitizar los datos
        $atributes = $this->sanitizeAtributes();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= join(', ', array_keys($atributes));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($atributes));
        $query .= " ') ";

        // Resultado de la consulta
        $result = self::$db->query($query);
        return [
           'result' =>  $result,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function update() {
        // Sanitizar los datos
        $atributes = $this->sanitizeAtributes();

        // Iterar para ir agregando cada campo de la BD
        $values = [];
        foreach($atributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$table ." SET ";
        $query .=  join(', ', $values );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $result = self::$db->query($query);
        return $result;
    }

    // Eliminar un Registro por su ID
    public function delete() {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);
        return $result;
    }

}