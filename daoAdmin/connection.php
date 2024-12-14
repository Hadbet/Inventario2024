<?php

class LocalConector{
    private $host = "127.0.0.1:3306";
    private $usuario = "u909553968_invenUser";
    private $clave = "Grammer2024";
    private $db = "u909553968_Inventario2024";
    private $conexion;

    public function conectar(){
        $this->conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->db);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        //echo "conexion exitosa";
        return $this->conexion;
    }
}