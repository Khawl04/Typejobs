<?php
// ==========================================
// app/views/admin/dashboard.php
// ==========================================
?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-3">
    <h1>Panel de Administración</h1>
    
    <!-- Estadísticas generales -->
    <div class="grid grid-4">
        <div class="card">
            <h3>Usuarios Totales</h3>
            <p style="font-size: 36px; font-weight: bold; color: #2563eb;">
                <?= $data['totalUsuarios'] ?? 0 ?>
            </p>
        </div>
        
        <div class="card">
            <h3>Servicios Activos</h3>
            <p style="font-size: 36px; font-weight: bold; color: #10b981;">
                <?= $data['totalServicios'] ?? 0 ?>
            </p>
        </div>
        
        <div class="card">
            <h3>Reportes Pendientes</h3>
            <p style="font-size: 36px; font-weight: bold; color: #f59e0b;">
                <?= $data['reportesPendientes'] ?? 0 ?>
            </p>
        </div>
        
        <div class="card">
            <h3>Tickets Abiertos</h3>
            <p style="font-size: 36px; font-weight: bold; color: #ef4444;">
                <?= $data['ticketsAbiertos'] ?? 0 ?>
            </p>
        </div>
    </div>
    
    <!-- Acciones rápidas -->
    <div class="card mt-3">
        <h2>Gestión</h2>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/usuarios" class="btn btn-primary">
                Gestionar Usuarios
            </a>
            <a href="<?= BASE_URL ?>/admin/servicios" class="btn btn-primary">
                Moderar Servicios
            </a>
            <a href="<?= BASE_URL ?>/admin/categorias" class="btn btn-primary">
                Gestionar Categorías
            </a>
            <a href="<?= BASE_URL ?>/admin/reportes" class="btn btn-warning">
                Ver Reportes
            </a>
            <a href="<?= BASE_URL ?>/admin/auditoria" class="btn btn-secondary">
                Log de Auditoría
            </a>
        </div>
    </div>
    
    <!-- Acciones recientes -->
    <?php if (!empty($data['accionesRecientes'])): ?>
    <div class="card mt-3">
        <h2>Acciones Recientes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['accionesRecientes'] as $accion): ?>
                    <tr>
                        <td><?= htmlspecialchars($accion['admin_nombre']) ?></td>
                        <td><span class="badge badge-primary"><?= htmlspecialchars($accion['tipo_accion']) ?></span></td>
                        <td><?= htmlspecialchars($accion['descripcion']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($accion['fecha_accion'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>