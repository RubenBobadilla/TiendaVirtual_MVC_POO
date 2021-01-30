<?php
require_once 'models/producto.php';

class ProductoController{
    
    public function index(){
        $producto = new Producto();
        $productos = $producto->getRandom(6);
        
        //var_dump($productos->fetch_object());
                
        // renderizar a vista
        require_once 'views/producto/destacados.php';
    }
    
    public function ver(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            //die(var_dump($id));
            $producto = new Producto();
            $producto->setId($id);
            
            $product = $producto->getOne();
        }
        require_once 'views/producto/ver.php';
    }


    public function gestion(){
        Utils::isAdmin();
        
        $producto = new Producto();
        $productos = $producto->getAll();
        
        require_once 'views/producto/gestion.php';
    }
    
    public function crear() {
        Utils::isAdmin();
        
        require_once 'views/producto/crear.php';
    }
    
    public function save(){
        Utils::isAdmin();
        if(isset($_POST)){
            //var_dump($_POST);
            // Recoger los datos
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
            $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : false;
            $precio = isset($_POST['precio']) ? $_POST['precio'] : false;
            $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
            $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : false;
            //$imagen = isset($_POST['imagen']) ? $_POST['imagen'] : false;    
            

            // Comprobar si existen
            if($nombre && $descripcion && $precio && $stock && $categoria){
                // instanciar el objeto - y - set o enviar los datos  - para guardarlos
                $productos = new Producto();
                $productos->setNombre($nombre);
                $productos->setDescripcion($descripcion);
                $productos->setPrecio($precio);
                $productos->setStock($stock);
                $productos->setCategoria_id($categoria);
                
                // Guardar la imagen
                if(isset($_FILES['imagen'])){
                    $file = $_FILES['imagen'];
                    $filename = $file['name'];
                    $mimetype = $file['type'];

    //                var_dump($file);
    //                die();
                    if($mimetype == "image/jpg" || $mimetype == "image/png" || $mimetype == "image/jpeg" || $mimetype == "image/gif" ){

                       // var_dump($file); die();

                       if(!is_dir('uploads/images')){
                           mkdir('uploads/images', 0777, true);
                       }
                       
                       $productos->setImagen($filename);
                       move_uploaded_file($file['tmp_name'], 'uploads/images/'.$filename);
                    }
                }                
                
                // Comprobar si llega el id por get edite, sino save
                if($_GET['id']){
                    $id = $_GET['id'];
                    $productos->setId($id);
                    $save = $productos->edit();
                } else {
                    $save = $productos->save();
                }
                // Crear la sesion   
              
                if($save){
                    $_SESSION['producto'] = "complete";
                } else {
                    $_SESSION['producto'] = 'failed';
                }
            } else {
                $_SESSION['producto'] = 'failed';
            }    
        } else {
            $_SESSION['producto'] = 'failed';
        }
        
        header("Location:".base_url."producto/gestion");
    }
    
    public function eliminar(){
        //var_dump($_GET);
        Utils::isAdmin();
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $producto = new Producto();
            $producto->setId($id);
            
            $delete = $producto->delete();
            if($delete){
                $_SESSION['delete'] = "complete";
            } else {
                $_SESSION['delete'] = "failed";
            }
        } else {
            $_SESSION['delete'] = "failed";
        }
        
        header("Location:".base_url.'producto/gestion');
    }
    
    public function editar() {
        Utils::isAdmin();
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $editar = true;
            
            $productos = new Producto();
            $productos->setId($id);
            $pro = $productos->getOne();
            
            require_once 'views/producto/crear.php';
        } else {
            header("Location:".base_url.'producto/gestion');
        }  
    }
    
}