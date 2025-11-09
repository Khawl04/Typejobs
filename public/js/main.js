// Funciones globales de utilidad

// Cerrar alertas automáticamente después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Confirmación antes de eliminar/cancelar
function confirmar(mensaje = '¿Estás seguro de realizar esta acción?') {
    return confirm(mensaje);
}

// Validación de formularios
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let valido = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            valido = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    return valido;
}

// Validar email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validar teléfono (formato uruguayo)
function validarTelefono(telefono) {
    const regex = /^0\d{8}$/;
    return regex.test(telefono.replace(/\s/g, ''));
}

// Preview de imágenes antes de subir
function previewImagen(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Búsqueda en tiempo real
function busquedaEnTiempoReal(inputId, resultadosId, url) {
    const input = document.getElementById(inputId);
    const resultados = document.getElementById(resultadosId);
    
    if (!input || !resultados) return;
    
    let timeoutId;
    
    input.addEventListener('input', function() {
        clearTimeout(timeoutId);
        
        const query = this.value.trim();
        
        if (query.length < 3) {
            resultados.innerHTML = '';
            return;
        }
        
        timeoutId = setTimeout(() => {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    mostrarResultados(data, resultados);
                })
                .catch(error => {
                    console.error('Error en búsqueda:', error);
                });
        }, 300);
    });
}

// Mostrar resultados de búsqueda
function mostrarResultados(data, container) {
    if (!data || data.length === 0) {
        container.innerHTML = '<p class="text-center">No se encontraron resultados</p>';
        return;
    }
    
    let html = '<div class="grid grid-3">';
    
    data.forEach(item => {
        html += `
            <div class="card">
                <h3>${item.nombre}</h3>
                <p>${item.descripcion}</p>
                <a href="/servicio/${item.id}" class="btn btn-primary">Ver detalles</a>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

// Toggle para menú móvil
function toggleMenu() {
    const menu = document.querySelector('.navbar-menu');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// Formatear precio
function formatearPrecio(precio) {
    return new Intl.NumberFormat('es-UY', {
        style: 'currency',
        currency: 'UYU'
    }).format(precio);
}

// Formatear fecha
function formatearFecha(fecha) {
    return new Intl.DateTimeFormat('es-UY', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(fecha));
}

// Sistema de notificaciones (para RF-55, RF-56)
class Notificaciones {
    constructor() {
        this.container = null;
        this.init();
    }
    
    init() {
        // Crear contenedor de notificaciones si no existe
        if (!document.getElementById('notificaciones-container')) {
            const container = document.createElement('div');
            container.id = 'notificaciones-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 350px;
            `;
            document.body.appendChild(container);
            this.container = container;
        }
    }
    
    mostrar(mensaje, tipo = 'info') {
        const notificacion = document.createElement('div');
        notificacion.className = `alert alert-${tipo}`;
        notificacion.textContent = mensaje;
        notificacion.style.cssText = `
            margin-bottom: 10px;
            animation: slideIn 0.3s ease-out;
        `;
        
        this.container.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notificacion.remove();
            }, 300);
        }, 5000);
    }
    
    success(mensaje) {
        this.mostrar(mensaje, 'success');
    }
    
    error(mensaje) {
        this.mostrar(mensaje, 'error');
    }
    
    warning(mensaje) {
        this.mostrar(mensaje, 'warning');
    }
}

// Instancia global de notificaciones
const notificaciones = new Notificaciones();

// Agregar animaciones CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .error {
        border-color: #ef4444 !important;
    }
`;
document.head.appendChild(style);