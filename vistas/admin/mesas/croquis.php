<?php
// vistas/admin/mesas/croquis.php
require_once __DIR__ . '/../../layout/headerr.php';
require_once __DIR__ . '/../../layout/sidebar_admin.php';

function getCapClass($cap) {
    $cap = (int)$cap;
    if($cap <= 2) return 'cap-1-2';
    if($cap <= 4) return 'cap-3-4';
    if($cap <= 6) return 'cap-5-6';
    return 'cap-7-plus';
}
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <span class="badge bg-indigo-subtle text-indigo px-3 py-2 rounded-pill fw-800 mb-3 text-uppercase" style="letter-spacing: 2px; font-size: 0.65rem;">Vista en Tiempo Real</span>
            <h1 class="fw-800 text-slate-900 mb-0" style="font-size: 2.8rem; letter-spacing: -1px;">Layout del Salón</h1>
        </div>
        <div class="d-flex gap-3">
            <a href="MesasAdminController.php?accion=index" class="btn btn-white border rounded-20 px-4 py-3 fw-800 shadow-sm">
                <i class="fas fa-list me-2"></i> VISTA TABLA
            </a>
            <a href="MesasAdminController.php?accion=crear" class="btn btn-primary rounded-20 px-4 py-3 fw-800 shadow-pro border-0" style="background: var(--slate-900);">
                <i class="fas fa-plus me-2"></i> NUEVA MESA
            </a>
        </div>
    </div>

    <div class="card-pro overflow-hidden p-0">
        <div class="croquis-header p-4 border-bottom bg-slate-50 d-flex justify-content-between align-items-center">
            <div class="d-flex gap-4">
                <div class="status-legend"><span class="dot bg-success"></span> Libre</div>
                <div class="status-legend"><span class="dot bg-warning"></span> Reservada</div>
                <div class="status-legend"><span class="dot bg-danger"></span> Ocupada</div>
                <div class="status-legend"><span class="dot bg-secondary"></span> Inactiva</div>
            </div>
            <div class="text-muted small fw-600">
                <i class="fas fa-info-circle me-1"></i> Visualización dinámica del salón principal
            </div>
        </div>
        
        <div class="croquis-container p-5">
            <div class="croquis-canvas-pro" id="croquisCanvas">
                <div class="piscina-pro">PISCINA</div>
                <div class="barra-pro">BARRA</div>
                
                <?php foreach($all_mesas as $m): 
                    $capClass = getCapClass($m['capacidad']);
                    $forma = $m['tipo_forma'] ?? 'circular';
                    $estado = (!empty($m['activo']) ? ($m['estado'] ?? 'libre') : 'fuera_servicio');
                    $top = $m['pos_top_num'] ?? 100;
                    $left = $m['pos_left_num'] ?? 100;
                ?>
                <div class="mesa-item <?= $capClass ?> <?= $forma ?> <?= $estado ?> shadow-sm draggable-mesa"
                     id="mesa-<?= $m['id'] ?>"
                     data-id="<?= $m['id'] ?>"
                     style="top: <?= (int)$top ?>px; left: <?= (int)$left ?>px;"
                     title="Doble clic para editar parámetros">
                    <span class="m-num">#<?= (int)$m['numero_mesa'] ?></span>
                    <span class="m-cap"><i class="fas fa-users me-1"></i><?= (int)$m['capacidad'] ?></span>
                    
                    <a href="MesasAdminController.php?accion=editar&id=<?= $m['id'] ?>" class="edit-link">
                        <i class="fas fa-pen"></i>
                    </a>

                    <?php if($estado === 'libre'): ?>
                        <div class="mesa-badge bg-success">LIBRE</div>
                    <?php elseif($estado === 'reservada'): ?>
                        <div class="mesa-badge bg-warning text-dark">RES</div>
                    <?php elseif($estado === 'ocupada'): ?>
                        <div class="mesa-badge bg-danger">OCU</div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div id="saveToast" class="save-toast">
    <i class="fas fa-cloud-upload-alt me-2"></i> Posición guardada
</div>

<style>
    .draggable-mesa { cursor: move !important; }
    .draggable-mesa:active { cursor: grabbing !important; transform: scale(1.05); z-index: 1000; }
    
    .edit-link {
        position: absolute; bottom: -10px; right: -10px;
        width: 30px; height: 30px; background: var(--slate-900);
        color: white; border-radius: 50%; display: flex;
        align-items: center; justify-content: center; font-size: 0.7rem;
        opacity: 0; transition: 0.3s; border: 2px solid #fff;
        text-decoration: none;
    }
    .mesa-item:hover .edit-link { opacity: 1; bottom: 0; right: 0; }
    .edit-link:hover { background: var(--indigo-pro); transform: scale(1.2); color: #fff; }

    .save-toast {
        position: fixed; bottom: 30px; right: 30px;
        background: #0f172a; color: white; padding: 12px 24px;
        border-radius: 12px; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transform: translateY(100px); transition: 0.5s; z-index: 9999;
    }
    .save-toast.show { transform: translateY(0); }

    .status-legend { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.75rem; color: var(--slate-600); text-transform: uppercase; letter-spacing: 1px; }
    .status-legend .dot { width: 10px; height: 10px; border-radius: 50%; }

    .croquis-canvas-pro {
        position: relative;
        width: 100%;
        height: 1000px;
        background: #fff;
        background-image: radial-gradient(var(--slate-200) 1px, transparent 1px);
        background-size: 30px 30px;
        border: 2px solid var(--slate-100);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: inset 0 0 40px rgba(0,0,0,0.02);
    }

    .piscina-pro {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 200px; height: 400px;
        background: linear-gradient(135deg, #e0f2fe 0%, #7dd3fc 100%);
        border: 4px solid #fff;
        box-shadow: 0 10px 30px rgba(125, 211, 252, 0.4);
        border-radius: 60px;
        display: flex; align-items: center; justify-content: center;
        color: #0369a1; font-weight: 900; letter-spacing: 5px; font-size: 1.5rem;
        z-index: 1;
    }

    .barra-pro {
        position: absolute; bottom: 40px; left: 40px;
        width: 150px; height: 50px;
        background: var(--slate-800);
        border: 3px solid #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 0.7rem; letter-spacing: 2px;
    }

    .mesa-item {
        position: absolute;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        border: 3px solid #fff;
    }
    .mesa-item:hover { z-index: 50; box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }

    /* Formas */
    .mesa-item.circular { border-radius: 50%; }
    .mesa-item.rectangular { border-radius: 12px; }

    /* Tamaños */
    .mesa-item.cap-1-2.circular { width: 70px; height: 70px; }
    .mesa-item.cap-3-4.circular { width: 85px; height: 85px; }
    .mesa-item.cap-5-6.circular { width: 100px; height: 100px; }
    .mesa-item.cap-7-plus.circular { width: 120px; height: 120px; }

    .mesa-item.cap-1-2.rectangular { width: 90px; height: 70px; }
    .mesa-item.cap-3-4.rectangular { width: 110px; height: 80px; }
    .mesa-item.cap-5-6.rectangular { width: 130px; height: 90px; }

    /* Estados */
    .mesa-item.libre { background: #10b981; color: white; }
    .mesa-item.reservada { background: #f59e0b; color: white; }
    .mesa-item.ocupada { background: #ef4444; color: white; }
    .mesa-item.fuera_servicio { background: #94a3b8; color: white; opacity: 0.4; }

    .m-num { font-weight: 900; font-size: 1.1rem; line-height: 1; }
    .m-cap { font-size: 0.65rem; font-weight: 700; margin-top: 2px; opacity: 0.9; }

    .mesa-badge {
        position: absolute; top: -10px; right: -10px;
        font-size: 0.6rem; font-weight: 900; padding: 4px 8px;
        border-radius: 8px; border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('croquisCanvas');
    const mesas = document.querySelectorAll('.draggable-mesa');
    let activeMesa = null;
    let offset = { x: 0, y: 0 };

    mesas.forEach(mesa => {
        mesa.addEventListener('mousedown', (e) => {
            if (e.target.closest('.edit-link')) return; // No arrastrar si hace clic en editar
            
            activeMesa = mesa;
            const rect = mesa.getBoundingClientRect();
            offset.x = e.clientX - rect.left;
            offset.y = e.clientY - rect.top;
            
            mesa.style.transition = 'none';
        });

        // Doble clic para editar directamente
        mesa.addEventListener('dblclick', () => {
            const id = mesa.dataset.id;
            window.location.href = `MesasAdminController.php?accion=editar&id=${id}`;
        });
    });

    document.addEventListener('mousemove', (e) => {
        if (!activeMesa) return;

        const canvasRect = canvas.getBoundingClientRect();
        let x = e.clientX - canvasRect.left - offset.x;
        let y = e.clientY - canvasRect.top - offset.y;

        // Limites y Snap
        const gridSize = 10;
        x = Math.round(x / gridSize) * gridSize;
        y = Math.round(y / gridSize) * gridSize;

        const maxX = canvasRect.width - activeMesa.offsetWidth;
        const maxY = canvasRect.height - activeMesa.offsetHeight;

        x = Math.max(0, Math.min(x, maxX));
        y = Math.max(0, Math.min(y, maxY));

        activeMesa.style.left = x + 'px';
        activeMesa.style.top = y + 'px';
    });

    document.addEventListener('mouseup', () => {
        if (!activeMesa) return;

        const id = activeMesa.dataset.id;
        const top = activeMesa.style.top;
        const left = activeMesa.style.left;

        activeMesa.style.transition = 'transform 0.2s cubic-bezier(0.4, 0, 0.2, 1)';
        savePosition(id, top, left);
        
        activeMesa = null;
    });

    function savePosition(id, top, left) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('pos_top', top);
        formData.append('pos_left', left);
        formData.append('ajax', '1');

        fetch('MesasAdminController.php?accion=guardar_posicion', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast();
            }
        })
        .catch(err => console.error('Error saving position:', err));
    }

    function showToast() {
        const toast = document.getElementById('saveToast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    }
});
</script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
