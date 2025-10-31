<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
.reserva-container {
    max-width: 520px;
    margin: 45px auto 0;
    background: #fff;
    border-radius: 17px;
    box-shadow: 0 2px 12px rgba(40, 40, 90, 0.10);
    padding: 38px 28px 32px 28px;
}

.reserva-container h2 {
    margin-top: 0;
    font-size: 1.45em;
    margin-bottom: 26px;
    color: #224;
}

.reserva-container label {
    font-weight: 500;
    color: #4e5e73;
    margin-top: 18px;
    display: block;
}

.reserva-container input[type="date"],
.reserva-container input[type="time"],
.reserva-container textarea {
    width: 100%;
    padding: 8px 21px;
    margin-top: 8px;
    margin-bottom: 15px;
    font-size: 1.06em;
    background: #f5f7fa;
    border-radius: 8px;
    border: 1px solid #c6d3e3;
}

.reserva-container textarea {
    resize: vertical;
    min-height: 65px;
}

.reserva-container button {
    background: #264de4;
    color: #fff;
    font-weight: bold;
    padding: 12px 0;
    font-size: 1.13em;
    border-radius: 8px;
    border: 0;
    margin-top: 17px;
    width: 100%;
    cursor: pointer;
    transition: background 0.18s;
}

.reserva-container button:hover {
    background: #183b9c;
}
</style>

<div class="reserva-container">
    <h2>Reservar servicio: <?= htmlspecialchars($servicio['titulo']) ?></h2>
    <form method="post" action="<?= BASE_URL ?>/reserva">
        <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">

        <label for="fecha_reserva">Fecha de reserva:</label>
        <input type="date" name="fecha_reserva" id="fecha_reserva" required value="<?= date('Y-m-d') ?>">

        <label for="hora_inicio">Hora de inicio:</label>
        <input type="time" name="hora_inicio" id="hora_inicio">

        <label for="hora_fin">Hora de fin:</label>
        <input type="time" name="hora_fin" id="hora_fin">

        <label for="notas">Notas/Comentarios (opcional):</label>
        <textarea name="notas" id="notas"></textarea>

        <button type="submit">Reservar y proceder al pago</button>
    </form>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
