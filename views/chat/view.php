<div class="container-fluid px-4">
    <div class="row">
        <!-- Sidebar - Thông tin nhóm -->
        <div class="col-md-3 mb-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Thông tin nhóm
                    </h5>
                </div>
                <div class="card-body">
                    <h6><?= htmlspecialchars($group['name']) ?></h6>
                    <?php if ($group['type'] === 'tour' && $group['tour_name']): ?>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-map"></i> Tour: <?= htmlspecialchars($group['tour_name']) ?>
                        </p>
                    <?php elseif ($group['type'] === 'department' && $group['department']): ?>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-building"></i> Phòng ban: <?= htmlspecialchars($group['department']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($group['description']): ?>
                        <p class="small mb-3"><?= htmlspecialchars($group['description']) ?></p>
                    <?php endif; ?>

                    <hr>
                    <h6 class="small fw-bold mb-2">Thành viên (<?= count($members) ?>)</h6>
                    <div class="list-group list-group-flush">
                        <?php foreach ($members as $member): ?>
                            <div class="list-group-item px-0 py-2 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 0.875rem;">
                                            <?= strtoupper(mb_substr($member['full_name'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold small"><?= htmlspecialchars($member['full_name']) ?></div>
                                        <small class="text-muted">
                                            <?= $member['role'] === 'admin' ? '<i class="bi bi-shield-check text-primary"></i> Admin' : 'Thành viên' ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-3">
                        <a href="<?= BASE_URL ?>?action=chat/index" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main chat area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-dots"></i> <?= htmlspecialchars($group['name']) ?>
                    </h5>
                </div>

                <!-- Messages area -->
                <div class="card-body p-0" style="height: 500px; overflow-y: auto;" id="messagesContainer">
                    <div class="p-3" id="messagesList">
                        <?php if (empty($messages)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                                <p class="mt-3">Chưa có tin nhắn nào. Hãy bắt đầu cuộc trò chuyện!</p>
                            </div>
                        <?php else: ?>
                            <?php 
                            $currentUser = getCurrentUser();
                            foreach ($messages as $msg): 
                                $isOwn = $msg['user_id'] == $currentUser['id'];
                            ?>
                                <div class="d-flex mb-3 <?= $isOwn ? 'justify-content-end' : 'justify-content-start' ?>">
                                    <div class="message-wrapper" style="max-width: 70%;">
                                        <?php if (!$isOwn): ?>
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                    <?= strtoupper(mb_substr($msg['user_name'], 0, 1)) ?>
                                                </div>
                                                <small class="text-muted fw-bold"><?= htmlspecialchars($msg['user_name']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="message-bubble <?= $isOwn ? 'bg-primary text-white' : 'bg-light' ?> rounded p-2">
                                            <?php if ($msg['message_type'] === 'image'): ?>
                                                <img src="<?= BASE_ASSETS_UPLOADS . $msg['file_path'] ?>" 
                                                     class="img-fluid rounded mb-2" 
                                                     style="max-height: 300px; cursor: pointer;"
                                                     onclick="window.open(this.src, '_blank')">
                                                <div class="small"><?= htmlspecialchars($msg['message']) ?></div>
                                            <?php elseif ($msg['message_type'] === 'file'): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark me-2" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <div class="fw-bold"><?= htmlspecialchars($msg['file_name']) ?></div>
                                                        <small><?= number_format($msg['file_size'] / 1024, 2) ?> KB</small>
                                                    </div>
                                                    <a href="<?= BASE_ASSETS_UPLOADS . $msg['file_path'] ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-light ms-2">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="message-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted d-block mt-1 <?= $isOwn ? 'text-end' : '' ?>">
                                            <?= date('H:i', strtotime($msg['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Input area -->
                <div class="card-footer">
                    <form id="chatForm" enctype="multipart/form-data">
                        <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            <input type="file" id="fileInput" name="file" style="display: none;" accept="image/*,.pdf,.doc,.docx">
                            <input type="text" 
                                   class="form-control" 
                                   id="messageInput" 
                                   name="message" 
                                   placeholder="Nhập tin nhắn..." 
                                   autocomplete="off">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Gửi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-bubble {
    word-wrap: break-word;
}
#messagesContainer {
    scroll-behavior: smooth;
}
</style>

<script>
let lastMessageTime = '<?= !empty($messages) ? end($messages)['created_at'] : date('Y-m-d H:i:s') ?>';
let groupId = <?= $group['id'] ?>;
let pollingInterval;

// Auto scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

// Load new messages
function loadNewMessages() {
    fetch(`<?= BASE_URL ?>?action=chat/get-messages&group_id=${groupId}&after_time=${encodeURIComponent(lastMessageTime)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                const messagesList = document.getElementById('messagesList');
                const currentUser = <?= json_encode(getCurrentUser()) ?>;
                
                data.messages.forEach(msg => {
                    const isOwn = msg.user_id == currentUser.id;
                    const messageHtml = createMessageHtml(msg, isOwn);
                    messagesList.insertAdjacentHTML('beforeend', messageHtml);
                    lastMessageTime = msg.created_at;
                });
                
                scrollToBottom();
            }
        })
        .catch(error => console.error('Error loading messages:', error));
}

// Create message HTML
function createMessageHtml(msg, isOwn) {
    let content = '';
    
    if (msg.message_type === 'image') {
        content = `<img src="<?= BASE_ASSETS_UPLOADS ?>${msg.file_path}" 
                 class="img-fluid rounded mb-2" 
                 style="max-height: 300px; cursor: pointer;"
                 onclick="window.open(this.src, '_blank')">
                 <div class="small">${escapeHtml(msg.message)}</div>`;
    } else if (msg.message_type === 'file') {
        const fileSize = (msg.file_size / 1024).toFixed(2);
        content = `<div class="d-flex align-items-center">
                <i class="bi bi-file-earmark me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <div class="fw-bold">${escapeHtml(msg.file_name)}</div>
                    <small>${fileSize} KB</small>
                </div>
                <a href="<?= BASE_ASSETS_UPLOADS ?>${msg.file_path}" 
                   target="_blank" 
                   class="btn btn-sm btn-outline-light ms-2">
                    <i class="bi bi-download"></i>
                </a>
            </div>`;
    } else {
        content = `<div class="message-text">${escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>`;
    }
    
    const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
    
    return `
        <div class="d-flex mb-3 ${isOwn ? 'justify-content-end' : 'justify-content-start'}">
            <div class="message-wrapper" style="max-width: 70%;">
                ${!isOwn ? `
                    <div class="d-flex align-items-center mb-1">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 28px; height: 28px; font-size: 0.75rem;">
                            ${msg.user_name.charAt(0).toUpperCase()}
                        </div>
                        <small class="text-muted fw-bold">${escapeHtml(msg.user_name)}</small>
                    </div>
                ` : ''}
                <div class="message-bubble ${isOwn ? 'bg-primary text-white' : 'bg-light'} rounded p-2">
                    ${content}
                </div>
                <small class="text-muted d-block mt-1 ${isOwn ? 'text-end' : ''}">${time}</small>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Send message
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');
    const message = messageInput.value.trim();
    const file = fileInput.files[0];
    
    if (!message && !file) {
        return;
    }
    
    const formData = new FormData();
    formData.append('group_id', groupId);
    
    if (file) {
        formData.append('file', file);
    } else {
        formData.append('message', message);
    }
    
    fetch(`<?= BASE_URL ?>?action=chat/${file ? 'upload-file' : 'send-message'}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const messagesList = document.getElementById('messagesList');
            const currentUser = <?= json_encode(getCurrentUser()) ?>;
            const messageHtml = createMessageHtml(data.message, true);
            messagesList.insertAdjacentHTML('beforeend', messageHtml);
            lastMessageTime = data.message.created_at;
            messageInput.value = '';
            fileInput.value = '';
            scrollToBottom();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Có lỗi xảy ra khi gửi tin nhắn');
    });
});

// Start polling for new messages
pollingInterval = setInterval(loadNewMessages, 3000); // Poll every 3 seconds

// Initial scroll
setTimeout(scrollToBottom, 100);
</script>

