<?php

class BusquedaController extends Controller {
    
    private $servicioModel;
    private $categoriaModel;
    
    public function __construct() {
        $this->servicioModel = $this->model('Servicio');
        $this->categoriaModel = $this->model('Categoria');
    }
    
    // RF-17: Vista principal de búsqueda
    public function index() {
        $filtros = $this->obtenerFiltrosRequest();
        
        // Obtener servicios con filtros
        $servicios = $this->servicioModel->buscarServicios($filtros);
        
        // Obtener categorías para el filtro
        $categorias = $this->categoriaModel->obtenerActivas();
        
        // Paginación
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 12;
        $totalResultados = count($servicios);
        $totalPaginas = ceil($totalResultados / $porPagina);
        
        // Aplicar paginación
        $servicios = array_slice($servicios, ($pagina - 1) * $porPagina, $porPagina);
        
        $data = [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'filtros' => $filtros,
            'totalResultados' => $totalResultados,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas
        ];
        
        $this->view('busqueda/index', $data);
    }
    
    // RF-22: Búsqueda por palabras clave (AJAX)
    public function buscarAjax() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 3) {
            $this->json([
                'success' => false,
                'message' => 'Mínimo 3 caracteres'
            ]);
        }
        
        $servicios = $this->servicioModel->buscarServicios(['query' => $query]);
        
        $this->json([
            'success' => true,
            'servicios' => array_map(function($s) {
                return [
                    'id' => $s['id_servicio'],
                    'nombre' => $s['nombre'],
                    'descripcion' => substr($s['descripcion'], 0, 100),
                    'precio' => $s['precio'],
                    'proveedor' => $s['proveedor_nombre'],
                    'imagen' => $s['imagen_principal'] ?? null
                ];
            }, $servicios),
            'total' => count($servicios)
        ]);
    }
    
    // RF-23: Búsqueda por ubicación
    public function porUbicacion() {
        $ubicacion = $_GET['ubicacion'] ?? '';
        
        if (empty($ubicacion)) {
            $_SESSION['error'] = "Por favor ingresa una ubicación";
            $this->redirect('/busqueda.php');
        }
        
        $filtros = $this->obtenerFiltrosRequest();
        $servicios = $this->servicioModel->buscarServicios($filtros);
        
        // Ordenar por proximidad (simulado - en producción usarías geolocalización)
        usort($servicios, function($a, $b) use ($ubicacion) {
            $distA = levenshtein(strtolower($ubicacion), strtolower($a['proveedor_ubicacion']));
            $distB = levenshtein(strtolower($ubicacion), strtolower($b['proveedor_ubicacion']));
            return $distA - $distB;
        });
        
        $categorias = $this->categoriaModel->obtenerActivas();
        
        $this->view('busqueda/index', [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'filtros' => $filtros,
            'totalResultados' => count($servicios)
        ]);
    }
    
    // RF-24: Búsqueda por categoría
    public function porCategoria($idCategoria) {
        $categoria = $this->categoriaModel->find($idCategoria);
        
        if (!$categoria) {
            $_SESSION['error'] = "Categoría no encontrada";
            $this->redirect('/busqueda.php');
        }
        
        $filtros = array_merge(
            $this->obtenerFiltrosRequest(),
            ['categoria' => $idCategoria]
        );
        
        $servicios = $this->servicioModel->buscarServicios($filtros);
        $categorias = $this->categoriaModel->obtenerActivas();
        
        $this->view('busqueda/index', [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'filtros' => $filtros,
            'categoriaActual' => $categoria,
            'totalResultados' => count($servicios)
        ]);
    }
    
    // RF-24: Búsqueda avanzada con todos los filtros
    public function avanzada() {
        $filtros = $this->obtenerFiltrosRequest();
        
        // Validaciones
        if (isset($filtros['precio_min']) && isset($filtros['precio_max'])) {
            if ($filtros['precio_min'] > $filtros['precio_max']) {
                $_SESSION['error'] = "El precio mínimo no puede ser mayor al máximo";
                $this->redirect('/busqueda.php');
            }
        }
        
        if (isset($filtros['calificacion']) && 
            ($filtros['calificacion'] < 1 || $filtros['calificacion'] > 5)) {
            $_SESSION['error'] = "Calificación inválida";
            $this->redirect('/busqueda.php');
        }
        
        $servicios = $this->servicioModel->buscarServicios($filtros);
        
        // Aplicar ordenamiento
        $servicios = $this->aplicarOrdenamiento($servicios, $filtros['ordenar'] ?? 'recientes');
        
        $categorias = $this->categoriaModel->obtenerActivas();
        
        $this->view('busqueda/index', [
            'servicios' => $servicios,
            'categorias' => $categorias,
            'filtros' => $filtros,
            'totalResultados' => count($servicios)
        ]);
    }
    
    // Sugerencias de búsqueda (autocompletado)
    public function sugerencias() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json(['sugerencias' => []]);
        }
        
        $servicios = $this->servicioModel->buscarServicios([
            'query' => $query,
            'limit' => 5
        ]);
        
        $sugerencias = array_map(function($s) {
            return [
                'id' => $s['id_servicio'],
                'texto' => $s['nombre'],
                'tipo' => 'servicio',
                'proveedor' => $s['proveedor_nombre']
            ];
        }, $servicios);
        
        $this->json(['sugerencias' => $sugerencias]);
    }
    
    // Obtener filtros desde el request
    private function obtenerFiltrosRequest() {
        return [
            'query' => trim($_GET['q'] ?? ''),
            'categoria' => isset($_GET['categoria']) ? (int)$_GET['categoria'] : null,
            'precio_min' => isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : null,
            'precio_max' => isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : null,
            'calificacion' => isset($_GET['calificacion']) ? (int)$_GET['calificacion'] : null,
            'ubicacion' => trim($_GET['ubicacion'] ?? ''),
            'ordenar' => $_GET['ordenar'] ?? 'recientes'
        ];
    }
    
    // Aplicar ordenamiento a los resultados
    private function aplicarOrdenamiento($servicios, $orden) {
        switch ($orden) {
            case 'precio_asc':
                usort($servicios, fn($a, $b) => $a['precio'] <=> $b['precio']);
                break;
                
            case 'precio_desc':
                usort($servicios, fn($a, $b) => $b['precio'] <=> $a['precio']);
                break;
                
            case 'calificacion':
                usort($servicios, fn($a, $b) => 
                    $b['calificacion_promedio'] <=> $a['calificacion_promedio']
                );
                break;
                
            case 'nombre':
                usort($servicios, fn($a, $b) => 
                    strcasecmp($a['nombre'], $b['nombre'])
                );
                break;
                
            case 'recientes':
            default:
                usort($servicios, fn($a, $b) => 
                    strtotime($b['fecha_creacion']) <=> strtotime($a['fecha_creacion'])
                );
                break;
        }
        
        return $servicios;
    }
    
    // Exportar resultados (bonus feature)
    public function exportar() {
        $this->requireAuth();
        $this->requireRole('administrador');
        
        $filtros = $this->obtenerFiltrosRequest();
        $servicios = $this->servicioModel->buscarServicios($filtros);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="servicios_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['ID', 'Nombre', 'Categoría', 'Precio', 'Proveedor', 'Calificación']);
        
        // Datos
        foreach ($servicios as $s) {
            fputcsv($output, [
                $s['id_servicio'],
                $s['nombre'],
                $s['categoria_nombre'],
                $s['precio'],
                $s['proveedor_nombre'],
                $s['calificacion_promedio']
            ]);
        }
        
        fclose($output);
        exit();
    }
}