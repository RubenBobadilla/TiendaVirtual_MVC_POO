<?php if (isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'complete'): ?>
    <h1>Tu pedido se ha confirmado</h1>
    <p>Tu pedido ha sido confirmado con exito, una vez realizada la transferencia bancaria a la cuenta N°031-055-777,
        por el valor total del pedido será procesado y enviado.
    </p>
    <br/>
    <?php if(isset($pedido)):?>
        <h3>Datos del pedido:</h3>
        
            Numero de pedido: <?= $Tpedido->id ?> <br/>
            Total a pagar: <?= $Tpedido->coste ?> $ <br/>
            Productos:
            
            <?php while($producto = $productos->fetch_object()): ?>
            <ul>
                <li>
                    <?=$producto->nombre?> - <?=$producto->precio?> $ - x<?=$producto->unidades?>
                </li>
            </ul>
            <?php endwhile; ?>
        
    <?php endif; ?>
<?php elseif (isset($_SESSION['pedido']) && $_SESSION['pedido'] != 'complete'): ?>
    <h1>Tu pedido NO ha podido procesarse</h1>
<?php endif; ?>
