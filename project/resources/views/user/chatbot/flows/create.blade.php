<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>নতুন Bot Flow তৈরি করুন</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/drawflow@0.0.60/dist/drawflow.min.css">
</head>
<body style="margin: 0; padding: 0; overflow: hidden;">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body { 
        font-family: 'Hind Siliguri', sans-serif; 
        background-color: #f0f0f0;
    }
    
    #drawflow-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #f0f0f0;
    }

    #drawflow-canvas {
        position: fixed;
        top: 70px;
        left: 280px;
        right: 0;
        bottom: 0;
        overflow: auto;
    }

    #drawflow {
        width: 100%;
        height: 100%;
        background: #ffffff;
        background-size: 20px 20px;
        background-image: linear-gradient(to right, #f0f0f0 1px, transparent 1px), linear-gradient(to bottom, #f0f0f0 1px, transparent 1px);
    }

    /* Toolbar */
    .flow-toolbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: #ffffff;
        border-bottom: 2px solid #e5e7eb;
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 0 30px;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .toolbar-left {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 20px !important;
        flex-shrink: 0 !important;
    }

    .toolbar-right {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 12px !important;
        flex-shrink: 0 !important;
        margin-left: auto !important;
    }

    .flow-toolbar h5 {
        margin: 0 !important;
        padding: 0 !important;
        font-weight: 700;
        color: #1f2937;
        font-size: 18px;
        white-space: nowrap;
        line-height: 1;
    }
    
    .flow-toolbar h5 i {
        color: #667eea;
        margin-right: 8px;
    }

    /* Sidebar */
    .flow-sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        width: 280px;
        bottom: 0;
        background: #fff;
        border-right: 2px solid #eee;
        overflow-y: auto;
        z-index: 999;
        padding: 20px;
    }

    .flow-sidebar h6 {
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 15px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .node-item {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 12px;
        cursor: move;
        transition: 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .node-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .node-item i {
        margin-right: 10px;
        font-size: 18px;
    }

    .node-item .node-title {
        font-weight: 600;
        font-size: 15px;
    }

    .node-item .node-desc {
        font-size: 11px;
        opacity: 0.8;
        margin-top: 5px;
    }

    /* Drawflow Nodes - কানেকশন বিন্দু যেন কাটা না যায় */
    .drawflow .drawflow-node {
        background: #fff;
        border: 2px solid #667eea;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 0;
        width: 220px;
        overflow: visible !important;
    }
    /* আউটপুট/ইনপুট কানেকশন বিন্দু স্পষ্ট ও বড় দেখাতে */
    .drawflow .drawflow-node .input,
    .drawflow .drawflow-node .output {
        width: 14px !important;
        height: 14px !important;
        min-width: 14px;
        min-height: 14px;
        background: #f59e0b !important;
        border: 2px solid #d97706 !important;
        border-radius: 50%;
        cursor: crosshair;
        z-index: 10;
    }
    .drawflow .drawflow-node .output {
        background: #10b981 !important;
        border-color: #059669 !important;
    }
    .drawflow .drawflow-node .input:hover,
    .drawflow .drawflow-node .output:hover {
        transform: scale(1.3);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.4);
    }

    .drawflow .drawflow-node .node-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 12px 15px;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .drawflow .drawflow-node .node-content {
        padding: 15px;
        color: #2d3436;
        font-size: 13px;
    }

    .drawflow .drawflow-node.selected {
        border-color: #fd5f00;
    }

    /* Canvas Area */
    #drawflow-canvas {
        position: fixed;
        top: 70px;
        left: 280px;
        right: 0;
        bottom: 0;
    }

    /* Buttons */
    .btn-flow {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        font-family: 'Hind Siliguri', sans-serif;
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px;
        text-decoration: none !important;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        line-height: 1.5;
        min-height: 40px;
    }

    .btn-flow-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff !important;
    }

    .btn-flow-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-flow-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff !important;
    }

    .btn-flow-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-flow-secondary {
        background: #6b7280;
        color: #fff !important;
    }

    .btn-flow-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    /* Input Modal */
    .flow-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .flow-modal.show {
        display: flex;
    }

    .flow-modal-content {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .flow-modal-content h5 {
        font-weight: 700;
        margin-bottom: 20px;
        color: #2d3436;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2d3436;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Hind Siliguri', sans-serif;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn-add-option {
        background: #10b981;
        color: #fff;
        padding: 8px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        margin-top: 10px;
    }

    .btn-add-option:hover {
        background: #059669;
    }

    .option-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .option-item input {
        flex: 1;
    }

    .btn-remove-option {
        background: #ef4444;
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    /* Node cursor */
    .drawflow .drawflow-node {
        cursor: pointer;
    }

    .drawflow .drawflow-node:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    /* Node delete button */
    .node-delete {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: #ef4444;
        color: #fff;
        border: 2px solid #fff;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        z-index: 10;
    }

    .drawflow-node:hover .node-delete {
        display: flex;
    }

    .node-delete:hover {
        background: #dc2626;
    }
    
    /* Ensure visibility */
    .flow-toolbar, .flow-sidebar {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Additional utility classes */
    .me-2 {
        margin-right: 8px;
    }
    
    .d-flex {
        display: flex;
    }
    
    .gap-2 {
        gap: 8px;
    }
    
    .flex-grow-1 {
        flex-grow: 1;
    }
    
    .mt-3 {
        margin-top: 16px;
    }
</style>

<div id="drawflow-container">
    {{-- Toolbar --}}
    <div class="flow-toolbar">
        <div class="toolbar-left">
            <a href="{{ route('user.chatbot.flows.index') }}" class="btn-flow btn-flow-secondary">
                <i class="fas fa-arrow-left"></i> ফিরে যান
            </a>
            <h5><i class="fas fa-diagram-project me-2"></i>নতুন Bot Flow</h5>
        </div>
        <div class="toolbar-right">
            <button onclick="clearFlow()" class="btn-flow btn-flow-secondary">
                <i class="fas fa-trash"></i> Clear
            </button>
            <button onclick="saveFlow()" class="btn-flow btn-flow-success">
                <i class="fas fa-save"></i> Save Flow
            </button>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="flow-sidebar">
        <h6><i class="fas fa-cubes me-2"></i>Drag Nodes</h6>
        <div class="alert alert-info py-2 px-3 small mb-3" style="background:#e0f2fe;color:#0369a1;border-radius:8px;border:1px solid #7dd3fc;">
            <strong>কানেক্ট করার নিয়ম:</strong> নোডের <span style="background:#10b981;color:#fff;padding:0 4px;border-radius:4px;">ডান পাশের সবুজ বিন্দু</span> থেকে ক্লিক করে টেনে অন্য নোডের <span style="background:#f59e0b;color:#fff;padding:0 4px;border-radius:4px;">বাম পাশের কমলা বিন্দু</span>তে ছেড়ে দিন।
        </div>
        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="welcome">
            <div class="node-title"><i class="fas fa-comment-dots"></i>Welcome Message</div>
            <div class="node-desc">প্রথম মেসেজ পাঠান</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="text">
            <div class="node-title"><i class="fas fa-font"></i>Text Message</div>
            <div class="node-desc">টেক্সট মেসেজ পাঠান</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="image">
            <div class="node-title"><i class="fas fa-image"></i>Image</div>
            <div class="node-desc">ছবি পাঠান</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="button">
            <div class="node-title"><i class="fas fa-hand-pointer"></i>Button</div>
            <div class="node-desc">বাটন দিয়ে অপশন। ১ বাটন = ১ বিন্দু, ২ বাটন = ২ বিন্দু, ৩ বাটন = ৩ বিন্দু – ডান পাশ থেকে টেনে পরের নোডে কানেক্ট করুন।</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="quick_reply">
            <div class="node-title"><i class="fas fa-reply-all"></i>Quick Reply</div>
            <div class="node-desc">দ্রুত উত্তর</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="condition">
            <div class="node-title"><i class="fas fa-code-branch"></i>Condition</div>
            <div class="node-desc">শর্ত চেক করুন</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="delay">
            <div class="node-title"><i class="fas fa-clock"></i>Delay</div>
            <div class="node-desc">কিছুক্ষণ অপেক্ষা</div>
        </div>

        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="api">
            <div class="node-title"><i class="fas fa-plug"></i>API Call</div>
            <div class="node-desc">API থেকে data নিন</div>
        </div>
        <div class="node-item" draggable="true" ondragstart="drag(event)" data-node="product">
            <div class="node-title"><i class="fas fa-box"></i>প্রোডাক্ট</div>
            <div class="node-desc">প্রোডাক্ট ক্যারোজেল দেখান</div>
        </div>
    </div>

    {{-- Canvas --}}
    <div id="drawflow-canvas" ondrop="drop(event)" ondragover="allowDrop(event)">
        <div id="drawflow"></div>
    </div>
</div>

{{-- Edit Node Modal --}}
<div id="editNodeModal" class="flow-modal">
    <div class="flow-modal-content">
        <h5><i class="fas fa-edit me-2"></i>Edit Node</h5>
        <div id="editNodeForm"></div>
        <div class="d-flex gap-2 mt-3">
            <button onclick="saveNodeData()" class="btn-flow btn-flow-success flex-grow-1">
                <i class="fas fa-check"></i> Update
            </button>
            <button onclick="closeEditModal()" class="btn-flow btn-flow-secondary">
                Cancel
            </button>
        </div>
    </div>
</div>

{{-- Save Flow Modal --}}
<div id="saveModal" class="flow-modal">
    <div class="flow-modal-content">
        <h5><i class="fas fa-save me-2"></i>Save Bot Flow</h5>
        <form id="saveForm" action="{{ route('user.chatbot.flows.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Flow Name *</label>
                <input type="text" name="name" required placeholder="উদা: স্বাগতম বার্তা">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="এই flow টি কি করে..."></textarea>
            </div>
            <div class="form-group">
                <label>Facebook Page</label>
                <select name="facebook_page_id">
                    <option value="">Select Page</option>
                    @foreach($pages as $page)
                    <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Trigger Type *</label>
                <select name="trigger_type" required>
                    <option value="keyword">Keyword</option>
                    <option value="button">Button Click</option>
                    <option value="menu">Menu</option>
                </select>
            </div>
            <div class="form-group">
                <label>Trigger Keywords (comma separated)</label>
                <input type="text" name="trigger_keywords" placeholder="উদা: হাই, হ্যালো, start">
            </div>
            <input type="hidden" name="flow_data" id="flowDataInput">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-flow btn-flow-success flex-grow-1">
                    <i class="fas fa-check"></i> Save
                </button>
                <button type="button" onclick="closeSaveModal()" class="btn-flow btn-flow-secondary">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/drawflow@0.0.60/dist/drawflow.min.js"></script>
<script>
    window.userProductsList = @json($userProducts ?? []);
    let editor;
    let nodeId = 1;
    let draggedNode = null;
    let currentEditingNode = null;

    /**
     * বাটন নোডে যতগুলো বাটন আছে ততগুলো আউটপুট (বিন্দু): ১ বাটন = ১ বিন্দু, ২ বাটন = ২ বিন্দু, ৩ বাটন = ৩ বিন্দু
     */
    function ensureButtonNodesOutputsMatchButtons(flowData) {
        if (!flowData || typeof flowData !== 'object') return flowData;
        const data = flowData.drawflow?.Home?.data || flowData.drawflow?.home?.data || flowData.data || {};
        Object.keys(data).forEach(nid => {
            const node = data[nid];
            if (!node) return;
            const cls = (node.class || node.type || '').toString().toLowerCase();
            if (cls !== 'button') return;
            const buttons = (node.data && node.data.buttons) ? node.data.buttons : [];
            const count = Math.min(3, Math.max(1, buttons.length));
            const oldOutputs = node.outputs || {};
            const useCapital = Object.keys(oldOutputs).some(k => k.startsWith('Output_'));
            const prefix = useCapital ? 'Output_' : 'output_';
            const newOutputs = {};
            for (let i = 1; i <= count; i++) {
                const key = prefix + i;
                newOutputs[key] = oldOutputs[key] || { connections: [] };
            }
            node.outputs = newOutputs;
        });
        return flowData;
    }

    // Initialize Drawflow
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('drawflow');
        editor = new Drawflow(container);
        editor.reroute = true;
        editor.start();

        // Add default start node
        addNode('start', 100, 100, 'start', 'Start', {}, 0, 1);

        // Global click handler to prevent edit modal on delete button click
        let deleteButtonClicked = false;
        document.addEventListener('click', function(e) {
            if (e.target.closest('.node-delete')) {
                deleteButtonClicked = true;
                setTimeout(() => { deleteButtonClicked = false; }, 100);
            }
        }, true);

        // Node click event
        editor.on('nodeSelected', function(id) {
            if (deleteButtonClicked) {
                return; // Don't open edit modal if delete button was just clicked
            }
            openEditModal(id);
        });

        // Delete key event
        document.addEventListener('keydown', function(e) {
            // Don't delete node if user is typing in input/textarea
            const activeElement = document.activeElement;
            const isTyping = activeElement && (
                activeElement.tagName === 'INPUT' || 
                activeElement.tagName === 'TEXTAREA' ||
                activeElement.isContentEditable
            );
            
            if ((e.key === 'Delete' || e.key === 'Backspace') && !isTyping) {
                const selectedNode = editor.node_selected;
                if (selectedNode && selectedNode !== 'start') {
                    e.preventDefault(); // Prevent default browser behavior
                    deleteNode(selectedNode);
                }
            }
        });
    });

    // Drag & Drop
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        const nodeType = ev.target.closest('.node-item');
        if (nodeType) {
            draggedNode = nodeType.dataset.node;
        }
    }

    function drop(ev) {
        ev.preventDefault();
        if (!draggedNode) return;

        const rect = document.getElementById('drawflow').getBoundingClientRect();
        const x = ev.clientX - rect.left;
        const y = ev.clientY - rect.top;

        addNodeByType(draggedNode, x, y);
        draggedNode = null;
    }

    // Prevent node from sticking to mouse
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('dragend', function(e) {
            draggedNode = null;
        });
    });

    // Add Node by Type
    function addNodeByType(type, x, y) {
        let title, inputs = 1, outputs = 1, data = {};

        switch(type) {
            case 'welcome':
                title = 'Welcome';
                data = { text: 'স্বাগতম! আমি আপনাকে কিভাবে সাহায্য করতে পারি?' };
                break;
            case 'text':
                title = 'Text';
                data = { text: '' };
                break;
            case 'image':
                title = 'Image';
                data = { imageUrl: '', caption: '' };
                break;
            case 'button':
                title = 'Button';
                data = { text: '', buttons: [] };
                outputs = 1; // শুরুতে ১ বিন্দু; বাটন যোগ করে সেভ করলে ২/৩ হবে
                break;
            case 'quick_reply':
                title = 'Quick Reply';
                data = { text: '', replies: [] };
                outputs = 2;
                break;
            case 'condition':
                title = 'Condition';
                data = { variable: '', operator: '==', value: '' };
                outputs = 2;
                break;
            case 'delay':
                title = 'Delay';
                data = { delay: 5 };
                break;
            case 'api':
                title = 'API';
                data = { apiUrl: '', method: 'GET' };
                break;
            case 'product':
                title = 'Product';
                data = { product_ids: [] };
                break;
        }

        const newNodeId = type + '_' + nodeId;
        addNode(type, x, y, newNodeId, title, data, inputs, outputs);
        
        // Update HTML with actual data
        setTimeout(() => {
            updateNodeHTML(newNodeId, type, data);
        }, 50);
        
        nodeId++;
    }

    // Add Node
    function addNode(type, x, y, id, title, data, inputs, outputs) {
        const deleteBtn = type !== 'start' ? '<div class="node-delete" onclick="deleteNodeByClass(event, this)"><i class="fas fa-times"></i></div>' : '';
        const html = `
            ${deleteBtn}
            <div class="node-header">
                <i class="fas fa-${getNodeIcon(type)}"></i>
                ${title}
            </div>
            <div class="node-content">
                <p>Loading...</p>
            </div>
        `;
        editor.addNode(id, inputs, outputs, x, y, type, data, html);
    }

    // Delete node by button click
    function deleteNodeByClass(event, element) {
        event.stopPropagation();
        const nodeElement = element.closest('.drawflow-node');
        if (nodeElement && confirm('এই node টি মুছে ফেলবেন?')) {
            const nodeId = nodeElement.id;
            editor.removeNodeId(nodeId);
        }
    }

    // Delete node by keyboard
    function deleteNode(nodeId) {
        if (confirm('এই node টি মুছে ফেলবেন?')) {
            editor.removeNodeId('node-' + nodeId);
        }
    }

    // Get Node Icon
    function getNodeIcon(type) {
        const icons = {
            start: 'play',
            welcome: 'comment-dots',
            text: 'font',
            image: 'image',
            button: 'hand-pointer',
            quick_reply: 'reply-all',
            condition: 'code-branch',
            delay: 'clock',
            api: 'plug',
            product: 'box'
        };
        return icons[type] || 'circle';
    }

    // Clear Flow
    function clearFlow() {
        if (confirm('সব nodes delete করতে চান?')) {
            editor.clear();
            addNode('start', 100, 100, 'start', 'Start', {}, 0, 1);
            nodeId = 1;
        }
    }

    // Save Flow
    function saveFlow() {
        let flowData = editor.export();
        flowData = ensureButtonNodesOutputsMatchButtons(flowData);
        const flowDataJSON = JSON.stringify(flowData);
        
        console.log('Flow Data:', flowData);
        console.log('Flow Data JSON:', flowDataJSON);
        
        document.getElementById('flowDataInput').value = flowDataJSON;
        document.getElementById('saveModal').classList.add('show');
    }

    function closeSaveModal() {
        document.getElementById('saveModal').classList.remove('show');
    }

    // Open Edit Modal
    function openEditModal(nodeId) {
        if (nodeId === 'start') return; // Don't edit start node
        
        currentEditingNode = nodeId;
        const nodeData = editor.getNodeFromId(nodeId);
        const nodeType = nodeData.class;
        
        let formHTML = '';
        const data = nodeData.data || {};

        switch(nodeType) {
            case 'welcome':
            case 'text':
                formHTML = `
                    <div class="form-group">
                        <label>Message Text *</label>
                        <textarea id="nodeText" rows="4" placeholder="আপনার মেসেজ লিখুন...">${data.text || ''}</textarea>
                    </div>
                `;
                break;

            case 'image':
                formHTML = `
                    <div class="form-group">
                        <label>Image URL *</label>
                        <input type="url" id="nodeImageUrl" placeholder="https://example.com/image.jpg" value="${data.imageUrl || ''}">
                    </div>
                    <div class="form-group">
                        <label>Caption (optional)</label>
                        <textarea id="nodeCaption" rows="2" placeholder="ছবির বর্ণনা...">${data.caption || ''}</textarea>
                    </div>
                `;
                break;

            case 'button':
                formHTML = `
                    <div class="form-group">
                        <label>Message Text</label>
                        <textarea id="nodeText" rows="3" placeholder="বার্তা লিখুন...">${data.text || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Buttons</label>
                        <div id="buttonsList"></div>
                        <button type="button" class="btn-add-option" onclick="addButton()">
                            <i class="fas fa-plus"></i> Add Button
                        </button>
                    </div>
                `;
                break;

            case 'quick_reply':
                formHTML = `
                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea id="nodeText" rows="3" placeholder="প্রশ্ন লিখুন...">${data.text || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Quick Replies</label>
                        <div id="repliesList"></div>
                        <button type="button" class="btn-add-option" onclick="addQuickReply()">
                            <i class="fas fa-plus"></i> Add Option
                        </button>
                    </div>
                `;
                break;

            case 'condition':
                formHTML = `
                    <div class="form-group">
                        <label>Condition Variable</label>
                        <input type="text" id="nodeVariable" placeholder="উদা: user_age" value="${data.variable || ''}">
                    </div>
                    <div class="form-group">
                        <label>Operator</label>
                        <select id="nodeOperator">
                            <option value="equals" ${data.operator === 'equals' ? 'selected' : ''}>Equals (=)</option>
                            <option value="not_equals" ${data.operator === 'not_equals' ? 'selected' : ''}>Not Equals (!=)</option>
                            <option value="greater" ${data.operator === 'greater' ? 'selected' : ''}>Greater (>)</option>
                            <option value="less" ${data.operator === 'less' ? 'selected' : ''}>Less (<)</option>
                            <option value="contains" ${data.operator === 'contains' ? 'selected' : ''}>Contains</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" id="nodeValue" placeholder="Compare value" value="${data.value || ''}">
                    </div>
                `;
                break;

            case 'delay':
                formHTML = `
                    <div class="form-group">
                        <label>Delay Time (seconds)</label>
                        <input type="number" id="nodeDelay" min="1" max="300" placeholder="5" value="${data.delay || 5}">
                    </div>
                `;
                break;

            case 'api':
                formHTML = `
                    <div class="form-group">
                        <label>API URL *</label>
                        <input type="url" id="nodeApiUrl" placeholder="https://api.example.com/endpoint" value="${data.apiUrl || ''}">
                    </div>
                    <div class="form-group">
                        <label>Method</label>
                        <select id="nodeApiMethod">
                            <option value="GET" ${data.method === 'GET' ? 'selected' : ''}>GET</option>
                            <option value="POST" ${data.method === 'POST' ? 'selected' : ''}>POST</option>
                        </select>
                    </div>
                `;
                break;

            case 'product':
                const productList = window.userProductsList || [];
                let productOptions = productList.map(p => 
                    `<option value="${p.id}" ${(data.product_ids || []).map(String).includes(String(p.id)) ? 'selected' : ''}>${p.name} - ৳${Number(p.price).toLocaleString()}</option>`
                ).join('');
                if (!productOptions) productOptions = '<option value="">প্রথমে প্রোডাক্ট যোগ করুন</option>';
                formHTML = `
                    <div class="form-group">
                        <label>প্রোডাক্ট নির্বাচন করুন (Ctrl চেপে একাধিক)</label>
                        <select id="nodeProductIds" multiple size="6" class="form-select">
                            ${productOptions}
                        </select>
                        <small class="text-muted">কোনো প্রোডাক্ট নেই? <a href="/user/chatbot/products/create" target="_blank">প্রোডাক্ট যোগ করুন</a></small>
                    </div>
                `;
                break;
        }

        document.getElementById('editNodeForm').innerHTML = formHTML;
        
        // Load existing buttons/replies if any
        if (nodeType === 'button' && data.buttons) {
            data.buttons.forEach(btn => {
                addButton(btn.title, btn.url || '', btn.type || 'postback');
            });
        }
        if (nodeType === 'quick_reply' && data.replies) {
            data.replies.forEach(reply => {
                addQuickReply(reply);
            });
        }

        document.getElementById('editNodeModal').classList.add('show');
    }

    // Add Button
    function addButton(title = '', url = '', type = 'postback') {
        const container = document.getElementById('buttonsList');
        const id = Date.now();
        const html = `
            <div class="option-item" id="btn-${id}" style="display: grid; grid-template-columns: 1fr 100px 1fr auto; gap: 8px; align-items: center;">
                <input type="text" placeholder="বাটনের নাম" value="${escapeHtml(title)}" data-type="title" style="width: 100%;">
                <select data-type="button-type" onchange="toggleButtonUrl(this, 'btn-${id}')" style="width: 100%;">
                    <option value="postback" ${type === 'postback' ? 'selected' : ''}>শুধু বাটন</option>
                    <option value="web_url" ${type === 'web_url' ? 'selected' : ''}>বাটন + URL</option>
                </select>
                <input type="text" placeholder="URL (optional)" value="${escapeHtml(url || '')}" data-type="url" style="width: 100%; ${type === 'postback' ? 'display: none;' : ''}">
                <button type="button" class="btn-remove-option" onclick="removeOption('btn-${id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
    
    // Toggle button URL field based on type
    function toggleButtonUrl(selectElement, btnId) {
        const buttonItem = document.getElementById(btnId);
        const urlInput = buttonItem.querySelector('[data-type="url"]');
        if (selectElement.value === 'postback') {
            urlInput.style.display = 'none';
            urlInput.value = '';
        } else {
            urlInput.style.display = 'block';
        }
    }

    // Add Quick Reply
    function addQuickReply(text = '') {
        const container = document.getElementById('repliesList');
        const id = Date.now();
        const html = `
            <div class="option-item" id="reply-${id}">
                <input type="text" placeholder="Reply Option" value="${text}">
                <button type="button" class="btn-remove-option" onclick="removeOption('reply-${id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    // Remove Option
    function removeOption(id) {
        document.getElementById(id).remove();
    }

    // Save Node Data
    function saveNodeData() {
        const nodeData = editor.getNodeFromId(currentEditingNode);
        const nodeType = nodeData.class;
        let data = {};

        switch(nodeType) {
            case 'welcome':
            case 'text':
                data.text = document.getElementById('nodeText').value;
                break;

            case 'image':
                data.imageUrl = document.getElementById('nodeImageUrl').value;
                data.caption = document.getElementById('nodeCaption').value;
                break;

            case 'button':
                data.text = document.getElementById('nodeText').value;
                data.buttons = [];
                document.querySelectorAll('#buttonsList .option-item').forEach(item => {
                    const title = item.querySelector('[data-type="title"]').value;
                    const btnType = item.querySelector('[data-type="button-type"]').value;
                    const url = item.querySelector('[data-type="url"]').value;
                    if (title) {
                        const btn = { title, type: btnType };
                        if (btnType === 'web_url' && url) {
                            btn.url = url;
                        }
                        data.buttons.push(btn);
                    }
                });
                break;

            case 'quick_reply':
                data.text = document.getElementById('nodeText').value;
                data.replies = [];
                document.querySelectorAll('#repliesList .option-item input').forEach(input => {
                    if (input.value) data.replies.push(input.value);
                });
                break;

            case 'condition':
                data.variable = document.getElementById('nodeVariable').value;
                data.operator = document.getElementById('nodeOperator').value;
                data.value = document.getElementById('nodeValue').value;
                break;

            case 'delay':
                data.delay = document.getElementById('nodeDelay').value;
                break;

            case 'api':
                data.apiUrl = document.getElementById('nodeApiUrl').value;
                data.method = document.getElementById('nodeApiMethod').value;
                break;

            case 'product':
                const selectEl = document.getElementById('nodeProductIds');
                data.product_ids = selectEl ? Array.from(selectEl.selectedOptions).map(o => o.value) : [];
                break;
        }

        // Update node data
        editor.updateNodeDataFromId(currentEditingNode, data);
        
        // Update node HTML
        updateNodeHTML(currentEditingNode, nodeType, data);
        
        closeEditModal();
    }

    // HTML escape helper
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Update Node HTML
    function updateNodeHTML(nodeId, type, data) {
        let content = '';
        
        switch(type) {
            case 'welcome':
            case 'text':
                const displayText = escapeHtml((data.text || 'Empty message').substring(0, 50));
                const hasMore = (data.text || '').length > 50;
                content = `<p class="text-truncate">${displayText}${hasMore ? '...' : ''}</p>`;
                break;
            case 'image':
                const imgUrl = escapeHtml((data.imageUrl || '').substring(0, 30));
                content = `<p><i class="fas fa-image me-1"></i>${imgUrl ? imgUrl + '...' : 'Not set'}</p>`;
                break;
            case 'button':
                const btnText = escapeHtml((data.text || '').substring(0, 40));
                const btnLabels = escapeHtml((data.buttons || []).map(b => b.title).join(', '));
                const btnCount = (data.buttons || []).length;
                content = `<p class="text-truncate mb-1">${btnText || 'Message text...'}</p><small class="text-muted">${btnLabels || 'বাটন যোগ করুন'}</small><p class="mt-1 small text-info">${btnCount ? btnCount + ' বাটন = ' + btnCount + ' বিন্দু।' : 'বাটন যোগ করুন।'} ডান পাশ থেকে টেনে কানেক্ট করুন।</p>`;
                break;
            case 'quick_reply':
                const qrText = escapeHtml((data.text || '').substring(0, 40));
                const qrLabels = escapeHtml((data.replies || []).join(', '));
                content = `<p class="text-truncate mb-1">${qrText || 'Message text...'}</p><small class="text-muted">${qrLabels || 'No options'}</small>`;
                break;
            case 'condition':
                const condVar = escapeHtml(data.variable || 'var');
                const condOp = escapeHtml(data.operator || '=');
                const condVal = escapeHtml(data.value || 'value');
                content = `<p>${condVar} ${condOp} ${condVal}</p>`;
                break;
            case 'delay':
                content = `<p><i class="fas fa-clock me-1"></i>Wait ${data.delay || 5}s</p>`;
                break;
            case 'api':
                const apiUrl = escapeHtml((data.apiUrl || 'URL not set').substring(0, 30));
                content = `<p><strong>${data.method || 'GET'}</strong></p><small class="text-truncate">${apiUrl}</small>`;
                break;
            case 'product':
                const count = (data.product_ids || []).length;
                const productNames = (data.product_ids || []).map(pid => {
                    const p = window.userProductsList.find(prod => String(prod.id) === String(pid));
                    return p ? p.name : '';
                }).filter(n => n).join(', ').substring(0, 40);
                content = `<p><i class="fas fa-box me-1"></i>${count} টি প্রোডাক্ট</p><small class="text-truncate text-muted">${escapeHtml(productNames) || 'Select products'}</small>`;
                break;
        }

        // Get the actual DOM node element
        const nodeElement = document.querySelector(`#node-${nodeId}`);
        if (!nodeElement) return;
        
        // Delete button শুধু start node ছাড়া অন্য সব node-এ থাকবে
        const deleteBtn = type !== 'start' ? '<div class="node-delete" onclick="deleteNodeByClass(event, this)"><i class="fas fa-times"></i></div>' : '';
        
        // Find the drawflow_content_node div (this contains the actual node content)
        const contentNode = nodeElement.querySelector('.drawflow_content_node');
        if (contentNode) {
            contentNode.innerHTML = `
                ${deleteBtn}
                <div class="node-header">
                    <i class="fas fa-${getNodeIcon(type)}"></i>
                    ${type.charAt(0).toUpperCase() + type.slice(1)}
                </div>
                <div class="node-content">
                    ${content}
                </div>
            `;
        }
    }
    
    // Refresh all node HTML with their actual data
    function refreshAllNodeHTML() {
        const flowData = editor.export();
        const nodes = flowData.drawflow.Home.data;
        
        for (const nodeId in nodes) {
            const node = nodes[nodeId];
            const type = node.class || node.data?.type || 'text';
            const data = node.data || {};
            
            if (type !== 'start') {
                updateNodeHTML(nodeId, type, data);
            }
        }
    }

    // Close Edit Modal
    function closeEditModal() {
        document.getElementById('editNodeModal').classList.remove('show');
        currentEditingNode = null;
    }

    // Save form submission
    document.getElementById('saveForm').addEventListener('submit', function(e) {
        // Validate flow has nodes
        const flowData = editor.export();
        const nodeCount = Object.keys(flowData.drawflow.Home.data).length;
        
        if (nodeCount === 0) {
            e.preventDefault();
            alert('অন্তত একটি node add করুন!');
            document.getElementById('saveModal').classList.remove('show');
            return false;
        }
        
        // Update flow data before submit
        document.getElementById('flowDataInput').value = JSON.stringify(flowData);
        
        // Process keywords
        const keywords = document.querySelector('[name="trigger_keywords"]').value;
        if (keywords) {
            const keywordArray = keywords.split(',').map(k => k.trim()).filter(k => k);
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'trigger_values';
            hiddenInput.value = JSON.stringify(keywordArray);
            this.appendChild(hiddenInput);
        }
        
        console.log('Submitting flow data:', flowData);
    });
</script>

</body>
</html>
