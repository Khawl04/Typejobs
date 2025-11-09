<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    background: #274c3d;
}
.pago-main {
    display:flex;
    flex-wrap: wrap;
    gap: 45px;
    justify-content: center;
    color: #ffffff;
    margin-bottom: 30px;
    padding: 0 15px;
}
.pago-form {
    flex: 1;
    min-width: 340px;
    max-width: 570px;
    background: #252d3a;
    border-radius: 11px;
    padding: 28px 28px 22px 28px;
    margin-bottom: 30px;
}
.pago-form h2 { 
    margin-top: 0; 
    margin-bottom: 21px; 
    font-size: 1.3em;
}
.pago-form label { 
    display: block; 
    margin: 12px 0 6px 2px; 
    font-weight: 500;
}
.pago-form input, .pago-form select, .pago-form textarea {
    width: 100%;
    border-radius: 7px;
    padding: 10px 9px;
    border: 1px solid #ccc;
    font-size: 1em;
    margin-bottom: 10px;
}
.pago-form button {
    width: 100%;
    padding: 11px 0;
    font-size: 1.08em;
    background: #3756e3;
    color: #fff;
    border: none;
    border-radius: 7px;
    margin-top: 18px;
    cursor: pointer;
}
.pago-form button:hover {
    background: #2745c5;
}
.pago-orden {
    min-width: 320px;
    max-width: 400px;
    background: #252d3a;
    border-radius: 9px;
    padding: 23px 18px;
}
.pago-orden h2 { margin-top:0; }
.pago-orden b { font-size: 1.05em; }

/* ?? Responsive para móviles (hasta 480px) */
@media (max-width: 480px) {
    body {
        background: #274c3d;
    }

    .pago-main {
        flex-direction: column;
        align-items: stretch;
        gap: 25px;
        padding: 10px;
    }

    .pago-form,
    .pago-orden {
        width: 100%;
        min-width: auto;
        max-width: 100%;
        padding: 20px 18px;
    }

    .pago-form h2,
    .pago-orden h2 {
        font-size: 1.15em;
        text-align: center;
    }

    .pago-form label {
        font-size: 0.95em;
    }

    .pago-form input,
    .pago-form select,
    .pago-form textarea {
        font-size: 0.95em;
        padding: 9px;
    }

    .pago-form button {
        font-size: 1em;
        padding: 10px;
        margin-top: 15px;
    }

    .pago-orden div {
        font-size: 0.95em;
    }

    /* Ajuste de los inputs en fila (vencimiento y CVV) */
    #tarjeta-fields div[style*="display:flex"] {
        flex-direction: column;
        gap: 0;
    }

    /* Centrar textos importantes */
    #mercadopago-fields b {
        display: block;
        text-align: center;
        font-size: 0.95em;
    }
}
</style>


<div class="pago-main">
    <form method="post" action="<?= BASE_URL ?>/pago/orden" class="pago-form" enctype="multipart/form-data">
        <h2>Su compra</h2>
        <div style="margin-bottom:18px;">
            <b>Datos personales</b>
        </div>
        <label>Primer nombre *</label>
        <input type="text" name="primer_nombre" required value="<?= htmlspecialchars($usuario['primer_nombre'] ?? '') ?>">

        <label>Primer apellido *</label>
        <input type="text" name="primer_apellido" required value="<?= htmlspecialchars($usuario['primer_apellido'] ?? '') ?>">

        <label>Ciudad *</label>
        <input type="text" name="ciudad" required value="<?= htmlspecialchars($usuario['ciudad'] ?? '') ?>">

        <label>Departamento</label>
        <select name="departamento" required>
            <option value="">Seleccionar</option>
            <option value="Artigas">Artigas</option>
            <option value="Canelones">Canelones</option>
            <option value="Cerro Largo">Cerro Largo</option>
            <option value="Colonia">Colonia</option>
            <option value="Durazno">Durazno</option>
            <option value="Flores">Flores</option>
            <option value="Florida">Florida</option>
            <option value="Lavalleja">Lavalleja</option>
            <option value="Maldonado">Maldonado</option>
            <option value="Montevideo">Montevideo</option>
            <option value="Paysandú">Paysandú</option>
            <option value="Río Negro">Río Negro</option>
            <option value="Rivera">Rivera</option>
            <option value="Rocha">Rocha</option>
            <option value="Salto">Salto</option>
            <option value="San José">San José</option>
            <option value="Soriano">Soriano</option>
            <option value="Tacuarembó">Tacuarembó</option>
            <option value="Treinta y Tres">Treinta y Tres</option>
        </select>

        <label>Dirección *</label>
        <input type="text" name="direccion" required placeholder="Calle, número, apto" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">

        <label>Celular *</label>
        <input type="text" name="celular" required placeholder="099 123 456" value="<?= htmlspecialchars($usuario['celular'] ?? '') ?>">

        <label>Método de pago *</label>
        <select name="metodo_pago" id="metodo_pago" required>
            <option value="">Seleccionar</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="transferencia">Transferencia</option>
            <option value="efectivo">Efectivo</option>
        </select>

        <!-- Subselect solo si elige transferencia -->
        <div id="subtransferencia-fields" style="display:none; margin-top:12px;">
            <label>Transferencia por *</label>
            <select name="submetodo_transferencia" id="submetodo_transferencia">
                <option value="">Seleccionar</option>
                <option value="mercadopago">MercadoPago</option>
            </select>
        </div>

        <!-- Campos de tarjeta, ocultos por defecto -->
        <div id="tarjeta-fields" style="display:none; margin-top:16px;">
            <label>Número de tarjeta *</label>
           <input type="text" name="numero_tarjeta" maxlength="19" pattern="(\d{4} ?){3,5}" placeholder="1234 5678 9012 3456">

            <label>Nombre del titular *</label>
            <input type="text" name="titular_tarjeta" maxlength="64" placeholder="Como aparece en la tarjeta">

            <div style="display:flex; gap:10px;">
                <div style="flex:1;">
                    <label>Vencimiento *</label>
                    <input type="text" name="vencimiento" maxlength="5" pattern="\d{2}/\d{2}" placeholder="MM/AA">
                </div>
                <div style="flex:1;">
                    <label>CVV *</label>
                    <input type="text" name="cvv" maxlength="4" pattern="\d{3,4}" placeholder="123">
                </div>
            </div>
        </div>

        <div id="mercadopago-fields" style="display:none; margin-top:10px;">
            <b>Serás redirigido a MercadoPago para completar el pago online.</b>
        </div>
        <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($reserva['id_reserva']) ?>">

        <button type="submit">
            Confirmar compra
        </button>
    </form>
    
    <div class="pago-orden">
        <h2>Tu orden</h2>
        <div>
            <b>Producto:</b> <?= htmlspecialchars($reserva['servicio_titulo']) ?> <br>
            <?= htmlspecialchars($reserva['servicio_descripcion']) ?>
            <div style="margin-top:8px;font-size:.99em;">
                Proveedor: <?= htmlspecialchars($reserva['proveedor_nombre'] . ' ' . $reserva['proveedor_apellido']) ?>
            </div>
            <div style="margin-top:18px;font-size:1.15em;">
                <b>Total: $<?= number_format($reserva['servicio_precio'], 2) ?></b>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campos según método principal y submétodo transferencia
document.getElementById('metodo_pago').addEventListener('change', function() {
    let subtransf = document.getElementById('subtransferencia-fields');
    let tarjetaFields = document.getElementById('tarjeta-fields');
    let mpFields = document.getElementById('mercadopago-fields');
    tarjetaFields.style.display = (this.value === 'tarjeta') ? 'block' : 'none';
    subtransf.style.display = (this.value === 'transferencia') ? 'block' : 'none';
    mpFields.style.display = 'none';
});

document.getElementById('submetodo_transferencia').addEventListener('change', function() {
    let mp = document.getElementById('mercadopago-fields');
    mp.style.display = (this.value === 'mercadopago') ? 'block' : 'none';
});

// Input auto MM/AA
const vencInput = document.querySelector('input[name="vencimiento"]');
vencInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 4) value = value.slice(0, 4);
    if (value.length > 2) {
        value = value.slice(0,2) + '/' + value.slice(2);
    }
    e.target.value = value;
});
// Formateo automático de número de tarjeta (#### #### #### ####)
const tarjetaInput = document.querySelector('input[name="numero_tarjeta"]');
tarjetaInput.addEventListener('input', function(e) {
    // Remueve todo lo que no sea dígito
    let value = e.target.value.replace(/\D/g, '');
    // Limita a 19 dígitos (máximo internacional)
    value = value.substring(0, 19);
    // Agrega un espacio cada 4 dígitos
    let formatted = '';
    for (let i = 0; i < value.length; i += 4) {
        if (formatted) formatted += ' ';
        formatted += value.substring(i, i + 4);
    }
    e.target.value = formatted;
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>