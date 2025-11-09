<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #274c3d;
    min-height: 100vh;
    margin: 0;
}

.container-admin {
    max-width: 1200px;
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

.usuarios-table {
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

.usuarios-table th,
.usuarios-table td {
    padding: 12px 10px;
    text-align: left;
    color: #e1fcef;
    border-bottom: 1px solid #3cfd9e22;
    background: none;
}

.usuarios-table th {
    background: #232a37ed;
    color: #48ecb2;
    font-weight: 800;
    letter-spacing: .01em;
    font-size: 1.05em;
}

.usuarios-table tr:last-child td {
    border-bottom: none;
}

.usuarios-table th.th-acciones {
    width: 160px;
    min-width: 130px;
    max-width: 210px;
    text-align: center;
}

.usuarios-table td.td-acciones {
    min-width: 130px;
    max-width: 210px;
    text-align: center;
}

.badge {
    display: inline-block;
    border-radius: 8px;
    padding: 0 10px;
    font-weight: 700;
    font-size: .97em;
    line-height: 25px;
}

.badge-rol {
    background: #2563eb;
    color: #fff;
}

.badge-cliente {
    background: #67cf8b;
    color: #134726;
}

.badge-proveedor {
    background: #ffb84a;
    color: #7f3705;
}

.badge-admin {
    background: #ff6ad9;
    color: #79236c;
}

.badge-activo {
    background: #30bf61;
    color: #fff;
}

.badge-inactivo {
    background: #9ca3af;
    color: #445;
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

.accion-btn.delete {
    background: #dc2626;
    margin-top: 26px;
}

.accion-btn.delete:hover {
    background: #991b1b;
}

.accion-btn.info {
    background: #64c5ff;
    color: #0b2336;
    text-decoration: none !important;
}

.accion-btn.info:hover {
    background: #3b82f6;
    color: #fff;
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
        margin-top: 20px;
        margin-bottom: 20px;
    }


@media (max-width: 768px) {
    .container-admin {
        padding: 0 5px 18px 5px;
        min-width: 100vw;
        max-width: 100vw;
    }
    .admin-title {
        font-size: 1.07em;
        padding: 12px 0 7px 0;
        margin-bottom: 7px;
        border-radius: 10px 10px 0 0;
    }
    .usuarios-table {
        font-size: 0.92em;
        margin-top: 13px;
        border-radius: 8px;
        box-shadow: 0 1px 6px #0002;
        min-width: 610px;
        width: max-content;
    }
    .usuarios-table th,
    .usuarios-table td {
        padding: 7px 4px;
        font-size: 0.98em;
    }
    .usuarios-table th.th-acciones,
    .usuarios-table td.td-acciones {
        min-width: 108px;
        max-width: 170px;
        font-size: .97em;
        text-align: center;
    }
    .badge {
        font-size: .93em;
        line-height: 20px;
        padding: 0 7px;
    }
    .accion-btn {
        font-size: .93em;
        padding: 5.5px 9px;
        margin-right: 2px;
        margin-bottom: 6px;
    }
    .accion-btn.delete {
        margin-top: 10px;
    }
    .card, .mb-3 {
        padding-right: 2px;
        padding-left: 2px;
    }
    .mb-3 {
        margin-top: 10px !important;
        margin-bottom: 9px !important;
    }
    /* Table horizontal scroll */
    .usuarios-table {
        overflow-x: auto;
        display: block;
    }
}

</style>

<div class="container-admin">
    <h1 class="admin-title">Gestionar Usuarios</h1>
    <table class="usuarios-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Fecha de registro</th>
                <th class="th-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></td>
                <td>
                    <?php
                        $rol = strtolower($usuario['rol'] ?? '');
                        if($rol === 'cliente')    echo '<span class="badge badge-cliente">Cliente</span>';
                        elseif($rol === 'proveedor') echo '<span class="badge badge-proveedor">Proveedor</span>';
                        elseif($rol === 'administrador') echo '<span class="badge badge-admin">Admin</span>';
                        else echo '<span class="badge badge-rol">'.ucfirst($rol).'</span>';
                    ?>
                </td>
                <td>
                    <?php
                        $estado = strtolower($usuario['estado'] ?? '');
                        if($estado === 'activo')    echo '<span class="badge badge-activo">Activo</span>';
                        else echo '<span class="badge badge-inactivo">'.ucfirst($estado).'</span>';
                    ?>
                </td>
                <td><?= htmlspecialchars($usuario['tipo_usuario'] ?? '-') ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($usuario['fecha_registro']))) ?></td>
                <td class="td-acciones">
                    <a href="<?= BASE_URL ?>/perfil?id=<?= $usuario['id_usuario'] ?>" class="accion-btn info"
                        title="Ver perfil" target="_blank">Ver</a>
                    <form action="<?= BASE_URL ?>/admin/eliminarUsuario" method="POST" style="display:inline-block;">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button class="accion-btn delete" title="Eliminar usuario"
                            onclick="return confirm('¿Eliminar permanentemente este usuario y todos sus datos relacionados?\n\nEsta acción NO se puede deshacer.')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>