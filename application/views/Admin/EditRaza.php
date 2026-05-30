<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    /* Styling System - Warm and Elegant Gold/Beige Palette */
    body {
        background-color: #faf8f5;
    }
    
    .builder-container {
        display: flex;
        gap: 24px;
        min-height: calc(100vh - 180px);
        margin-top: 20px;
        align-items: stretch;
    }

    @media (max-width: 991px) {
        .builder-container {
            flex-direction: column;
        }
    }

    /* Sidebar - Toolbox */
    .toolbox-panel {
        flex: 0 0 280px;
        background: #ffffff;
        border: 1px solid rgba(226, 230, 234, 0.8);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        height: fit-content;
        position: sticky;
        top: 80px;
    }

    .toolbox-title {
        font-size: 15px;
        font-weight: 600;
        color: #ad7e05;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        border-bottom: 2px solid #fef7e6;
        padding-bottom: 8px;
    }

    .tool-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #faf8f5;
        border: 1px dashed #e2e6ea;
        border-radius: 8px;
        margin-bottom: 12px;
        cursor: grab;
        transition: all 0.2s ease;
        user-select: none;
    }

    .tool-item:hover {
        background: #fef7e6;
        border-color: #ad7e05;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(173, 126, 5, 0.08);
    }

    .tool-item:active {
        cursor: grabbing;
    }

    .tool-icon {
        width: 32px;
        height: 32px;
        background: #ffffff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ad7e05;
        font-size: 14px;
        margin-right: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .tool-label {
        font-weight: 500;
        font-size: 14px;
        color: #333333;
    }

    /* Canvas - Drop Area */
    .canvas-panel {
        flex: 1;
        background: #ffffff;
        border: 1px solid rgba(226, 230, 234, 0.8);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        min-height: 500px;
    }

    .canvas-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f2f2f2;
    }

    .canvas-dropzone {
        flex: 1;
        border: 2px dashed #e2e6ea;
        border-radius: 10px;
        background: #fafafa;
        padding: 20px;
        transition: all 0.2s ease;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .canvas-dropzone.dragover {
        background: #fefcf5;
        border-color: #ad7e05;
    }

    /* Placeholder Empty State */
    .empty-placeholder {
        margin: auto;
        text-align: center;
        color: #8c96a0;
        padding: 40px 20px;
    }

    .empty-placeholder i {
        color: #ad7e05;
        opacity: 0.6;
        margin-bottom: 15px;
    }

    /* Form Field Cards */
    .field-card {
        background: #ffffff;
        border: 1px solid #e2e6ea;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        position: relative;
    }

    .field-card.dragging {
        opacity: 0.5;
        border: 1px dashed #ad7e05;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .field-card:hover {
        border-color: #ad7e05;
        box-shadow: 0 4px 12px rgba(173, 126, 5, 0.06);
    }

    .field-card-header {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #faf8f5;
        border-bottom: 1px solid #e2e6ea;
        border-radius: 10px 10px 0 0;
    }

    .field-drag-handle {
        cursor: grab;
        color: #999;
        margin-right: 12px;
        font-size: 16px;
    }

    .field-drag-handle:active {
        cursor: grabbing;
    }

    .field-type-badge {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #fef7e6;
        color: #ad7e05;
    }

    .field-actions {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .required-toggle {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        user-select: none;
    }

    .required-toggle input {
        cursor: pointer;
        accent-color: #ad7e05;
    }

    .field-delete-btn {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 14px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background 0.15s ease;
    }

    .field-delete-btn:hover {
        background: #feeef0;
    }

    .field-card-body {
        padding: 16px;
    }

    .field-input-group {
        margin-bottom: 0;
    }

    .field-label-input {
        font-weight: 500;
        color: #333;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 8px 12px;
        transition: border-color 0.15s ease;
    }

    .field-label-input:focus {
        border-color: #ad7e05;
        box-shadow: 0 0 0 0.2rem rgba(173, 126, 5, 0.15);
    }

    /* Option Manager for Select Dropdowns */
    .option-manager {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed #e2e6ea;
    }

    .option-manager-title {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .options-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .option-pill {
        display: inline-flex;
        align-items: center;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        color: #495057;
    }

    .option-pill-remove {
        background: none;
        border: none;
        color: #adb5bd;
        margin-left: 6px;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .option-pill-remove:hover {
        color: #dc3545;
    }

    .add-option-wrapper {
        display: flex;
        gap: 8px;
        max-width: 320px;
    }

    /* Buttons */
    .btn-gold {
        background-color: #ad7e05;
        color: #ffffff;
        border: none;
        font-weight: 500;
        padding: 10px 24px;
        border-radius: 8px;
        transition: background-color 0.15s ease;
    }

    .btn-gold:hover {
        background-color: #8c6503;
        color: #ffffff;
    }

    /* Toast Notification */
    .toast-message {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #28a745;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 9999;
        display: none;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s, slideOut 0.3s 2.7s;
    }

    @keyframes slideIn {
        from { transform: translateX(120%); }
        to { transform: translateX(0); }
    }

    @keyframes slideOut {
        from { transform: translateX(0); }
        to { transform: translateX(120%); }
    }
</style>

<div id="toast-message" class="toast-message">Form Layout Saved Successfully</div>

<div class="margintopcontainer">
    <div class="container pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="<?php echo base_url('admin/razalist'); ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Raza List
            </a>
            <button class="btn btn-gold shadow-sm" onclick="saveFormLayout();">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Save Form Layout
            </button>
        </div>

        <div class="text-center mb-4">
            <h3 class="font-weight-bold mb-1" style="color: #ad7e05;"><?php echo htmlspecialchars($raza['name']); ?></h3>
            <p class="text-muted font-weight-500 mb-0">(<?php echo htmlspecialchars($raza['umoor']); ?>)</p>
        </div>

        <div class="builder-container">
            <!-- Sidebar: Field Types Toolbox -->
            <div class="toolbox-panel">
                <div class="toolbox-title">Field Toolbox</div>
                <p class="small text-muted mb-3">Drag tools onto the form area on the right.</p>
                
                <div class="tool-item" draggable="true" data-type="text">
                    <div class="tool-icon"><i class="fa-solid fa-font"></i></div>
                    <div class="tool-label">Short Text</div>
                </div>
                
                <div class="tool-item" draggable="true" data-type="textarea">
                    <div class="tool-icon"><i class="fa-solid fa-paragraph"></i></div>
                    <div class="tool-label">Long Text (Textarea)</div>
                </div>
                
                <div class="tool-item" draggable="true" data-type="number">
                    <div class="tool-icon"><i class="fa-solid fa-hashtag"></i></div>
                    <div class="tool-label">Number Field</div>
                </div>
                
                <div class="tool-item" draggable="true" data-type="date">
                    <div class="tool-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="tool-label">Date Picker</div>
                </div>
                
                <div class="tool-item" draggable="true" data-type="select">
                    <div class="tool-icon"><i class="fa-solid fa-circle-chevron-down"></i></div>
                    <div class="tool-label">Select Dropdown</div>
                </div>
            </div>

            <!-- Canvas: Drop and Edit Area -->
            <div class="canvas-panel">
                <div class="canvas-header">
                    <h5 class="mb-0 font-weight-bold text-dark">Form Layout Preview & Editor</h5>
                    <span class="badge badge-secondary py-1 px-2" id="field-count-badge">0 Fields</span>
                </div>
                
                <div class="canvas-dropzone" id="form-canvas">
                    <!-- Dynamic fields will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load existing form configuration from DB
    const razaConfig = <?php echo json_encode($raza); ?>;
    const propertiesList = <?php echo json_encode(isset($properties) ? $properties : []); ?>;
    let fieldsList = [];

    if (razaConfig && razaConfig.fields && Array.isArray(razaConfig.fields.fields)) {
        fieldsList = razaConfig.fields.fields;
    }

    const canvas = document.getElementById('form-canvas');
    let draggedField = null;
    let dragType = null;
    let dragAction = null;
    let draggedIndex = null;

    // Toast Alert Helper
    function showToast(msg, isError = false) {
        const toast = document.getElementById('toast-message');
        if (!toast) return;
        toast.textContent = msg;
        toast.style.backgroundColor = isError ? '#dc3545' : '#28a745';
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    // Initialize Page
    document.addEventListener('DOMContentLoaded', () => {
        renderFormFields();
        initToolboxDragging();
        initCanvasDropZone();
    });

    // Initialize Toolbox Items
    function initToolboxDragging() {
        const tools = document.querySelectorAll('.tool-item');
        tools.forEach(tool => {
            tool.addEventListener('dragstart', (e) => {
                dragAction = 'add';
                dragType = tool.dataset.type;
                e.dataTransfer.setData('text/plain', ''); // required for Firefox compatibility
                e.dataTransfer.effectAllowed = 'copy';
            });
            tool.addEventListener('dragend', () => {
                dragAction = null;
                dragType = null;
                draggedIndex = null;
            });
        });
    }

    // Initialize Dropzone
    function initCanvasDropZone() {
        canvas.addEventListener('dragover', (e) => {
            e.preventDefault();
            canvas.classList.add('dragover');
            e.dataTransfer.dropEffect = (dragAction === 'add') ? 'copy' : 'move';
            
            // Reorder placeholder calculation
            const afterElement = getDragAfterElement(canvas, e.clientY);
            if (draggedField) {
                if (afterElement == null) {
                    canvas.appendChild(draggedField);
                } else {
                    canvas.insertBefore(draggedField, afterElement);
                }
            }
        });

        canvas.addEventListener('dragleave', () => {
            canvas.classList.remove('dragover');
        });

        canvas.addEventListener('drop', (e) => {
            e.preventDefault();
            canvas.classList.remove('dragover');
            
            if (dragAction === 'add') {
                const type = dragType;
                const defaultLabel = type.charAt(0).toUpperCase() + type.slice(1);
                
                const newField = {
                    name: defaultLabel,
                    type: type,
                    required: false
                };
                if (type === 'select') {
                    newField.options = [{ id: '0', name: 'Select Option 1' }];
                }

                // Determine drop index
                const afterElement = getDragAfterElement(canvas, e.clientY);
                const fieldCard = createFieldCardMarkup(newField, fieldsList.length);
                
                if (afterElement == null) {
                    canvas.appendChild(fieldCard);
                } else {
                    canvas.insertBefore(fieldCard, afterElement);
                }
                
                updateMemoryFromDom();
                renderFormFields();
                showToast('Field Added to Canvas');
            } else {
                updateMemoryFromDom();
                renderFormFields();
            }

            dragAction = null;
            dragType = null;
            draggedIndex = null;
        });
    }

    // Helper to calculate closest placement point during drag
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.field-card:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Render Fields List to DOM
    function renderFormFields() {
        canvas.innerHTML = '';
        if (fieldsList.length === 0) {
            canvas.innerHTML = `
                <div class="empty-placeholder">
                    <i class="fa-solid fa-pencil-ruler fa-3x mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Your Form is Empty</h5>
                    <p class="text-muted small">Drag a field type from the toolbox on the left and drop it here.</p>
                </div>
            `;
            document.getElementById('field-count-badge').textContent = '0 Fields';
            return;
        }

        document.getElementById('field-count-badge').textContent = `${fieldsList.length} Fields`;

        fieldsList.forEach((field, index) => {
            const card = createFieldCardMarkup(field, index);
            canvas.appendChild(card);
        });
    }

    // Create Card HTML
    function createFieldCardMarkup(field, index) {
        const card = document.createElement('div');
        card.className = 'field-card';
        card.draggable = true;
        card.dataset.index = index;
        card.dataset.type = field.type;

        // Custom friendly badges
        let friendlyType = field.type;
        if (friendlyType === 'select') friendlyType = 'Dropdown Select';
        if (friendlyType === 'textarea') friendlyType = 'Long Textarea';

        card.innerHTML = `
            <div class="field-card-header">
                <span class="field-drag-handle" title="Drag to reorder"><i class="fa-solid fa-grip-vertical"></i></span>
                <span class="field-type-badge">${friendlyType}</span>
                
                <div class="field-actions">
                    <label class="required-toggle mb-0">
                        <input type="checkbox" class="required-checkbox" ${field.required ? 'checked' : ''} onchange="updateMemoryFromDom();">
                        <span>Required</span>
                    </label>
                    <button class="field-delete-btn" onclick="deleteField(${index});" title="Delete Field">
                        <i class="fa-solid fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div class="field-card-body">
                <div class="form-group field-input-group mb-0">
                    <input type="text" class="form-control field-label-input" value="${escapeHtml(field.name)}" placeholder="Field Label" oninput="updateMemoryFromDom();" onchange="renderFormFields();">
                </div>
                
                <!-- Option Manager for select lists -->
                ${field.type === 'select' ? createOptionManagerMarkup(field, index) : ''}
            </div>
        `;

        // Card Drag & Drop Events (Reordering inside Canvas)
        card.addEventListener('dragstart', (e) => {
            draggedField = card;
            dragAction = 'reorder';
            draggedIndex = index;
            card.classList.add('dragging');
            e.dataTransfer.setData('text/plain', '');
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            draggedField = null;
            dragAction = null;
            dragType = null;
            draggedIndex = null;
        });

        return card;
    }

    // Option Manager Template
    function createOptionManagerMarkup(field, index) {
        const options = field.options || [];
        
        let pillItems = '';
        options.forEach((opt, optIdx) => {
            pillItems += `
                <span class="option-pill" data-id="${escapeHtml(String(opt.id))}">
                    <span>${escapeHtml(opt.name)}</span>
                    <button type="button" class="option-pill-remove" onclick="removeDropdownOption(${index}, ${optIdx});" title="Remove Option">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </span>
            `;
        });

        const isVenue = (field.name || '').trim().toLowerCase() === 'venue';
        
        if (isVenue) {
            const addedIds = new Set(options.map(opt => String(opt.id)));
            const addedNames = new Set(options.map(opt => (opt.name || '').trim().toLowerCase()));
            const availableProps = propertiesList.filter(p => !addedIds.has(String(p.id)) && !addedNames.has((p.name || '').trim().toLowerCase()));
            
            let selectOptions = '<option value="">-- Select Property --</option>';
            availableProps.forEach(p => {
                selectOptions += `<option value="${escapeHtml(String(p.id))}">${escapeHtml(p.name)}</option>`;
            });

            return `
                <div class="option-manager">
                    <div class="option-manager-title">Configure Venue Properties</div>
                    <div class="options-list" id="options-list-${index}">
                        ${pillItems || '<span class="text-muted small">No properties selected. Add one below.</span>'}
                    </div>
                    <div class="add-option-wrapper">
                        <select class="form-control form-control-sm" id="new-opt-val-${index}">
                            ${selectOptions}
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDropdownOption(${index});">Add</button>
                    </div>
                </div>
            `;
        }

        return `
            <div class="option-manager">
                <div class="option-manager-title">Dropdown Options</div>
                <div class="options-list" id="options-list-${index}">
                    ${pillItems || '<span class="text-muted small">No options. Add one below.</span>'}
                </div>
                <div class="add-option-wrapper">
                    <input type="text" class="form-control form-control-sm" placeholder="New option name..." id="new-opt-val-${index}" onkeypress="handleOptEnter(event, ${index})">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDropdownOption(${index});">Add</button>
                </div>
            </div>
        `;
    }

    // Quick add option on pressing Enter key
    function handleOptEnter(e, fieldIndex) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addDropdownOption(fieldIndex);
        }
    }

    // Add option
    function addDropdownOption(fieldIndex) {
        // Sync DOM state to fieldsList first
        updateMemoryFromDom();

        const field = fieldsList[fieldIndex];
        if (!field) return;

        const isVenue = (field.name || '').trim().toLowerCase() === 'venue';
        
        if (isVenue) {
            const select = document.getElementById(`new-opt-val-${fieldIndex}`);
            if (!select) return;
            const selectedVal = select.value;
            if (!selectedVal) return;
            
            const selectedText = select.options[select.selectedIndex].text;
            
            if (!field.options) field.options = [];
            field.options.push({
                id: String(selectedVal),
                name: selectedText
            });
        } else {
            const input = document.getElementById(`new-opt-val-${fieldIndex}`);
            if (!input) return;
            const optVal = (input.value || '').trim();
            if (!optVal) return;
            
            if (!field.options) field.options = [];
            field.options.push({
                id: String(field.options.length),
                name: optVal
            });
            input.value = '';
        }
        
        renderFormFields();
    }

    // Remove option
    function removeDropdownOption(fieldIndex, optIndex) {
        updateMemoryFromDom();
        
        const field = fieldsList[fieldIndex];
        if (field.options && field.options[optIndex]) {
            field.options.splice(optIndex, 1);
            
            const isVenue = (field.name || '').trim().toLowerCase() === 'venue';
            if (!isVenue) {
                // Re-index option IDs only for non-venue fields
                field.options.forEach((opt, idx) => {
                    opt.id = String(idx);
                });
            }
        }
        
        renderFormFields();
    }

    // Delete field
    function deleteField(index) {
        if (!confirm('Are you sure you want to remove this field?')) return;
        updateMemoryFromDom();
        fieldsList.splice(index, 1);
        renderFormFields();
    }

    // Serialize DOM state back into memory
    function updateMemoryFromDom() {
        const cards = canvas.querySelectorAll('.field-card');
        const updatedList = [];

        cards.forEach((card, idx) => {
            const labelInput = card.querySelector('.field-label-input');
            const reqCheckbox = card.querySelector('.required-checkbox');
            const type = card.dataset.type;

            const fieldObj = {
                name: labelInput.value.trim() || `Field ${idx + 1}`,
                type: type,
                required: reqCheckbox.checked
            };

            // Grab options list if type is select
            if (type === 'select') {
                const pills = card.querySelectorAll('.option-pill');
                const options = [];
                pills.forEach((p, pIdx) => {
                    const span = p.querySelector('span');
                    const spanText = span ? span.textContent.trim() : p.textContent.trim();
                    const pId = p.getAttribute('data-id');
                    options.push({
                        id: (pId !== null && pId !== '') ? String(pId) : String(pIdx),
                        name: spanText
                    });
                });
                fieldObj.options = options;
            }

            updatedList.push(fieldObj);
        });

        fieldsList = updatedList;
        document.getElementById('field-count-badge').textContent = `${fieldsList.length} Fields`;
    }

    // POST entire layout configuration back to controller
    function saveFormLayout() {
        updateMemoryFromDom();

        const payload = {
            id: razaConfig.id,
            name: razaConfig.name,
            fields: fieldsList
        };

        const formData = new FormData();
        formData.append('fields', JSON.stringify(payload));

        const saveUrl = '<?php echo base_url("admin/save_raza_fields/") ?>' + razaConfig.id;

        showToast('Saving layout...', false);

        fetch(saveUrl, {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                showToast('Form Layout Saved Successfully');
                setTimeout(() => { window.location.reload(); }, 800);
            } else {
                alert('Failed to save layout: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('A server error occurred while saving.');
        });
    }

    // Simple HTML escaping helper
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>