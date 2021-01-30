<?php
require_once 'models/pedido.php';

class PedidoController{
    
    public function hacer(){

        
        require_once 'views/pedido/hacer.php';
    }
    
    public function add(){
        if(isset($_SESSION['identity'])){
            // guardar pedido
            $usuario_id = $_SESSION['identity']->id;
            $provincia = isset($_POST['provincia']) ? $_POST['provincia'] : false;
            $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : false;
            $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : false;
            
            $stats = Utils::statsCarrito();
            $coste = $stats['total'];
            
            if($provincia && $localidad && $direccion){
                 // Guardar pedido
                $pedido = new Pedido();
                $pedido->setUsuario_id($usuario_id);
                $pedido->setProvicia($provincia);
                $pedido->setLocalidad($localidad);
                $pedido->setDireccion($direccion);
                $pedido->setCoste($coste);
                
                $save = $pedido->save();
                
                // Guardar linea pedido
                $save_linea = $pedido->save_linea();
                
                if($save && $save_linea){
                    $_SESSION['pedido'] = "complete";
                } else {
                      $_SESSION['pedido'] = "failed";
                }
            } else {
                $_SESSION['pedido'] = "failed";
            }
            
            header("Location:".base_url.'pedido/confirmado');
            
        } else {
            // Redirigir a index}
            header("Location".base_url);
        }
    }
    
    public function confirmado() {
        if(isset($_SESSION['identity'])){
            $identity = $_SESSION['identity'];
            $pedido = new Pedido();
            $pedido->setUsuario_id($identity->id);
            
            $Tpedido = $pedido->getOneByUser();
            
            $pedido_productos = new Pedido();
            $productos = $pedido_productos->getProductosBypedido($Tpedido->id);
//            var_dump($productos);
//            die();           
        }        
        require_once 'views/pedido/confirmado.php';        
    }
    
    public function functionName($param) {
        
    }
    
}