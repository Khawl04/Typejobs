<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #274c3d;
    min-height: 100vh;
    margin: 0;
}

.container-admin {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 14px 40px 14px;
    width: 100%;
}

.admin-title {
    background: #232a37ed;
    text-align: center;
    color: #3cfd9e;
    padding: 18px 0 14px 0;
    margin: 0 0 16px 0;
    letter-spacing: 1.2px;
    font-weight: 900;
    font-size: 2em;
    border-radius: 12px 12px 0 0;
    box-shadow: 0 5px 22px #0002;
}

/* --- Tabla Servicios fondo oscuro --- */
.servicios-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #232a37ed;
    border-radius: 13px;
    box-shadow: 0 2px 12px #0001;
    margin-top: 22px;
    font-size: 1em;
    color: #e1fcef;
    overflow: hidden;
    table-layout: fixed;
}

.servicios-table th,
.servicios-table td {
    padding: 12px 10px;
    text-align: left;
    color: #e1fcef;
    border-bottom: 1px solid #3cfd9e22;
    background: none;
}

.servicios-table th {
    background: #232a37ed;
    color: #3cfd9e;
    font-weight: 800;
    font-size: 1.08em;
}

.servicios-table tr:last-child td {
    border-bottom: none;
}

.badge {
    display: inline-block;
    border-radius: 8px;
    padding: 0 10px;
    font-weight: 700;
    font-size: .97em;
    line-height: 25px;
}

.badge-disponible {
    background: #30bf61;
    color: #fff;
}

.badge-pausado {
    background: #f59e0b;
    color: #fff;
}

.badge-eliminado {
    background: #dc2626;
    color: #fff;
}

.accion-btn {
    border: none;
    outline: none;
    border-radius: 8px;
    padding: 7px 14px;
    font-size: .99em;
    font-weight: 600;
    cursor: pointer;
    margin-right: 4px;
    margin-bottom: 3px;
    background: #2563eb;
    color: #fff;
    transition: background .18s;
}

.accion-btn:hover {
    background: #122655;
}

.accion-btn.eliminar {
    background: #dc2626;
}

.accion-btn.eliminar:hover {
    background: #991b1b;
}

.accion-btn.ver {
    background: #64c5ff;
    color: #0b2336;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 26px;
}

.accion-btn.ver:hover {
    background: #3b82f6;
    color: #fff;
}

.img-servicio {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    background: #e5e7eb;
}
.btn-secondary {
    background: #308159 !important;
    color: #eefffc !important;
    font-weight: 700;
    display: inline-block;
    border-radius: 8px;
    text-decoration: none !important;
    border: none;
    font-size: 1em;
    outline: none;
    padding: 10px 21px;
    margin-bottom: 4px;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s, transform .15s;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #266a49 !important;
}
.mb-3 {
        margin-bottom: 50px;
    }

@media (max-width: 768px) {
    .container-admin {
        max-width: 100vw;
        padding: 0 6px 18px 6px;
    }
    .admin-title {
        font-size: 1.12em;
        padding: 10px 0 7px 0;
        margin-bottom: 6px;
        border-radius: 6px 6px 0 0;
    }
    .servicios-table {
        font-size: .92em;
        margin-top: 11px;
        box-shadow: 0 1px 7px #0002;
        min-width: 610px;
        width: max-content;
        border-radius: 8px;
        overflow-x: auto;
        display: block;
    }
    .servicios-table th,
    .servicios-table td {
        padding: 7px 5px;
        font-size: 0.97em;
        word-break: break-word;
    }
    .servicios-table th {
        font-size: .96em;
    }
    .img-servicio {
        width: 40px;
        height: 40px;
        border-radius: 6px;
    }
    .badge {
        font-size: .92em;
        line-height: 20px;
        padding: 0 7px;
    }
    .accion-btn {
        font-size: .92em;
        padding: 5px 8px;
        margin-bottom: 7px;
        margin-right: 1.5px;
    }
    .accion-btn.eliminar {
        margin-top: 9px;
    }
    .mb-3 {
        margin-bottom: 9px !important;
    }
}



</style>

<div class="container-admin">
    <h1 class="admin-title">Gestionar Servicios</h1>

    <?php if (isset($_SESSION['success'])): ?>
    <div style="background:#30bf61;color:#fff;padding:12px 20px;border-radius:8px;margin-bottom:20px;font-weight:600;">
        ✓ <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div style="background:#dc2626;color:#fff;padding:12px 20px;border-radius:8px;margin-bottom:20px;font-weight:600;">
        ✗ <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="servicios-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Título</th>
                <th>Proveedor</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th style="min-width:130px;max-width:185px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($servicios)): ?>
            <?php foreach ($servicios as $servicio): ?>
            <tr>
                <td><?= htmlspecialchars($servicio['id_servicio']) ?></td>
                <td>
                    <?php if (!empty($servicio['imagen_servicio'])): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($servicio['imagen_servicio']) ?>"
                        class="img-servicio" alt="Servicio">
                    <?php else: ?>
                    <div class="img-servicio"
                        style="display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:1.5em;">📷
                    </div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($servicio['titulo']) ?></td>
                <td><?= htmlspecialchars($servicio['proveedor_nombre'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($servicio['categoria'] ?? 'Sin categoría') ?></td>
                <td>$<?= number_format($servicio['precio'], 0) ?></td>
                <td>
                    <?php
                        $estado = strtolower($servicio['estado'] ?? '');
                        if($estado === 'disponible') echo '<span class="badge badge-disponible">Disponible</span>';
                        elseif($estado === 'pausado') echo '<span class="badge badge-pausado">Pausado</span>';
                        else echo '<span class="badge badge-eliminado">'.ucfirst($estado).'</span>';
                    ?>
                </td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($servicio['fecha_creacion'] ?? $servicio['fecha_registro'] ?? 'now'))) ?>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/servicio/detalle?id=<?= $servicio['id_servicio'] ?>" class="accion-btn ver"
                        title="Ver servicio" target="_blank">Ver</a>
                    <form action="<?= BASE_URL ?>/admin/eliminarServicio" method="POST" style="display:inline-block;">
                        <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">
                        <button class="accion-btn eliminar" title="Eliminar servicio"
                            onclick="return confirm('¿Eliminar permanentemente este servicio?\n\nEsta acción NO se puede deshacer.')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="9" style="text-align:center;padding:40px;color:#6b7280;">
                    No hay servicios registrados
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mb-3">
    <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Volver al Panel</a>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>