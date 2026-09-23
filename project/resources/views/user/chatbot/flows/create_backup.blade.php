<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Bot Flow - চ্যাটবট ফ্লও তৈরি করুন</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/drawflow@0.0.60/dist/drawflow.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body { 
        font-family: 'Hind Siliguri', sans-serif; 
        background-color: #f8fafc; 
        overflow: hidden;
        margin: 0;
        padding: 0;
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
        background: #fff;
        border-bottom: 2px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .flow-toolbar .toolbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .flow-toolbar .toolbar-right {
        display: flex;
        gap: 10px;
    }

    .flow-toolbar h5 {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
        font-size: 18px;
    }
    
    .flow-toolbar h5 i {
        color: #667eea;
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

    /* Drawflow Nodes */
    .drawflow .drawflow-node {
        background: #fff;
        border: 2px solid #667eea;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 0;
        width: 220px;
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
</head>
<body>

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
            <div class="node-desc">বাটন দিয়ে অপশন</div>
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
    let editor;
    let nodeId = 1;
    let draggedNode = null;
    let currentEditingNode = null;

    // Initialize Drawflow
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('drawflow');
        editor = new Drawflow(container);
        editor.reroute = true;
        editor.start();

        // Add default start node
        addNode('start', 100, 100, 'start', 'Start', '<div>শুরু করুন</div>', 0, 1);

        // Node click event
        editor.on('nodeSelected', function(id) {
            openEditModal(id);
        });

        // Delete key event
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Delete' || e.key === 'Backspace') {
                const selectedNode = editor.node_selected;
                if (selectedNode && selectedNode !== 'start') {
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
        let title, content, inputs = 1, outputs = 1;

        switch(type) {
            case 'welcome':
                title = 'Welcome';
                content = '<div><strong>Welcome Message</strong><p>স্বাগতম! আমি আপনাকে কিভাবে সাহায্য করতে পারি?</p></div>';
                break;
            case 'text':
                title = 'Text';
                content = '<div><strong>Text Message</strong><p>আপনার টেক্সট মেসেজ লিখুন...</p></div>';
                break;
            case 'image':
                title = 'Image';
                content = '<div><strong>Image</strong><p>Image URL: </p></div>';
                break;
            case 'button':
                title = 'Button';
                content = '<div><strong>Buttons</strong><p>Button 1, Button 2</p></div>';
                outputs = 2;
                break;
            case 'quick_reply':
                title = 'Quick Reply';
                content = '<div><strong>Quick Replies</strong><p>Option 1, Option 2</p></div>';
                outputs = 2;
                break;
            case 'condition':
                title = 'Condition';
                content = '<div><strong>If/Else</strong><p>Condition check</p></div>';
                outputs = 2;
                break;
            case 'delay':
                title = 'Delay';
                content = '<div><strong>Wait</strong><p>5 seconds</p></div>';
                break;
            case 'api':
                title = 'API';
                content = '<div><strong>API Call</strong><p>GET/POST request</p></div>';
                break;
        }

        addNode(type, x, y, type + '_' + nodeId, title, content, inputs, outputs);
        nodeId++;
    }

    // Add Node
    function addNode(type, x, y, id, title, content, inputs, outputs) {
        const deleteBtn = type !== 'start' ? '<div class="node-delete" onclick="deleteNodeByClass(event, this)"><i class="fas fa-times"></i></div>' : '';
        const html = `
            ${deleteBtn}
            <div class="node-header">
                <i class="fas fa-${getNodeIcon(type)}"></i>
                ${title}
            </div>
            <div class="node-content">
                ${content}
            </div>
        `;
        editor.addNode(id, inputs, outputs, x, y, type, {}, html);
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
            api: 'plug'
        };
        return icons[type] || 'circle';
    }

    // Clear Flow
    function clearFlow() {
        if (confirm('সব nodes delete করতে চান?')) {
            editor.clear();
            addNode('start', 100, 100, 'start', 'Start', '<div>শুরু করুন</div>', 0, 1);
            nodeId = 1;
        }
    }

    // Save Flow
    function saveFlow() {
        const flowData = editor.export();
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
        }

        document.getElementById('editNodeForm').innerHTML = formHTML;
        
        // Load existing buttons/replies if any
        if (nodeType === 'button' && data.buttons) {
            data.buttons.forEach(btn => {
                addButton(btn.title, btn.url);
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
    function addButton(title = '', url = '') {
        const container = document.getElementById('buttonsList');
        const id = Date.now();
        const html = `
            <div class="option-item" id="btn-${id}">
                <input type="text" placeholder="Button Name" value="${title}" data-type="title">
                <input type="text" placeholder="URL or Payload" value="${url}" data-type="url">
                <button type="button" class="btn-remove-option" onclick="removeOption('btn-${id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
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
                    const url = item.querySelector('[data-type="url"]').value;
                    if (title) data.buttons.push({ title, url });
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
        }

        // Update node data
        editor.updateNodeDataFromId(currentEditingNode, data);
        
        // Update node HTML
        updateNodeHTML(currentEditingNode, nodeType, data);
        
        closeEditModal();
    }

    // Update Node HTML
    function updateNodeHTML(nodeId, type, data) {
        let content = '';
        
        switch(type) {
            case 'welcome':
            case 'text':
                content = `<p>${data.text || 'Empty message'}</p>`;
                break;
            case 'image':
                content = `<p><strong>Image:</strong> ${data.imageUrl ? '✓' : 'Not set'}</p>`;
                break;
            case 'button':
                content = `<p>${data.text || ''}</p><small>${data.buttons?.length || 0} buttons</small>`;
                break;
            case 'quick_reply':
                content = `<p>${data.text || ''}</p><small>${data.replies?.length || 0} options</small>`;
                break;
            case 'condition':
                content = `<p>${data.variable || 'var'} ${data.operator || '='} ${data.value || 'value'}</p>`;
                break;
            case 'delay':
                content = `<p>Wait ${data.delay || 5}s</p>`;
                break;
            case 'api':
                content = `<p>${data.method || 'GET'}: ${data.apiUrl || 'URL'}</p>`;
                break;
        }

        const deleteBtn = type !== 'start' ? `<div class="node-delete" onclick="deleteNodeById(event, ${nodeId})"><i class="fas fa-times"></i></div>` : '';
        const html = `
            ${deleteBtn}
            <div class="node-header">
                <i class="fas fa-${getNodeIcon(type)}"></i>
                ${type.charAt(0).toUpperCase() + type.slice(1)}
            </div>
            <div class="node-content">
                ${content}
            </div>
        `;
        
        const nodeElement = document.querySelector(`#node-${nodeId}`);
        if (nodeElement) {
            nodeElement.innerHTML = html;
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
