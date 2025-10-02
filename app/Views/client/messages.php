<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'client') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

$user = Auth::getUser();
$wedding = $data['wedding'] ?? null;
$messages = $data['messages'] ?? [];
$vendors = $data['vendors'] ?? [];
$unreadCount = $data['unread_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens - Clea Casamentos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .messages-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            height: calc(100vh - 200px);
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .chat-sidebar {
            background: var(--gray-50);
            border-right: 1px solid var(--gray-200);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .sidebar-subtitle {
            font-size: 14px;
            color: var(--gray-600);
        }

        .search-box {
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .search-input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            background: var(--white);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .contacts-list {
            padding: 10px 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid var(--gray-100);
        }

        .contact-item:hover,
        .contact-item.active {
            background: var(--white);
        }

        .contact-item.active {
            border-right: 3px solid var(--primary);
        }

        .contact-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-weight: 600;
            color: var(--gray-900);
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-last-message {
            font-size: 13px;
            color: var(--gray-600);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .contact-time {
            font-size: 12px;
            color: var(--gray-500);
        }

        .unread-badge {
            background: var(--primary);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        .chat-main {
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 20px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .chat-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .chat-details h3 {
            margin: 0;
            font-size: 18px;
            color: var(--gray-900);
        }

        .chat-status {
            font-size: 13px;
            color: var(--success);
            margin-top: 2px;
        }

        .chat-actions {
            display: flex;
            gap: 10px;
        }

        .chat-action-btn {
            padding: 8px 12px;
            border: 1px solid var(--gray-300);
            background: var(--white);
            border-radius: 6px;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s;
        }

        .chat-action-btn:hover {
            background: var(--gray-50);
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .message-group.sent {
            align-items: flex-end;
        }

        .message-group.received {
            align-items: flex-start;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message.sent {
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 6px;
        }

        .message.received {
            background: var(--white);
            color: var(--gray-900);
            border: 1px solid var(--gray-200);
            border-bottom-left-radius: 6px;
        }

        .message-time {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 4px;
            padding: 0 8px;
        }

        .message-date-divider {
            text-align: center;
            margin: 20px 0;
        }

        .date-divider-text {
            background: var(--gray-200);
            color: var(--gray-600);
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            display: inline-block;
        }

        .chat-input-container {
            padding: 20px;
            background: var(--white);
            border-top: 1px solid var(--gray-200);
        }

        .chat-input-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .chat-input {
            flex: 1;
            min-height: 44px;
            max-height: 120px;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 22px;
            resize: none;
            font-family: inherit;
            font-size: 14px;
            line-height: 1.4;
        }

        .chat-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--primary);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .send-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .send-btn:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
        }

        .empty-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--gray-500);
            text-align: center;
        }

        .empty-chat i {
            font-size: 64px;
            margin-bottom: 20px;
            color: var(--gray-300);
        }

        .support-section {
            padding: 20px;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .support-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .support-contact {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: var(--white);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .support-contact:hover {
            background: var(--primary-light);
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: var(--white);
            border-radius: 18px;
            border-bottom-left-radius: 6px;
            max-width: 70%;
            border: 1px solid var(--gray-200);
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--gray-400);
            animation: typingAnimation 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typingAnimation {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.4;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .messages-container {
                grid-template-columns: 1fr;
                height: calc(100vh - 140px);
            }

            .chat-sidebar {
                display: none;
            }

            .messages-container.mobile-sidebar .chat-sidebar {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                z-index: 1000;
                background: var(--white);
            }

            .messages-container.mobile-sidebar .chat-main {
                display: none;
            }

            .mobile-back-btn {
                display: block;
                position: absolute;
                top: 20px;
                left: 20px;
                background: none;
                border: none;
                font-size: 20px;
                color: var(--gray-700);
                cursor: pointer;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1><i class="fas fa-heart"></i> Clea</h1>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>/public/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="<?= BASE_URL ?>/public/client/wedding"><i class="fas fa-calendar-alt"></i> Cronograma</a>
                <a href="<?= BASE_URL ?>/public/client/vendors"><i class="fas fa-store"></i> Fornecedores</a>
                <a href="<?= BASE_URL ?>/public/client/contracts"><i class="fas fa-file-contract"></i> Contratos</a>
                <a href="<?= BASE_URL ?>/public/client/financial"><i class="fas fa-credit-card"></i> Financeiro</a>
                <a href="<?= BASE_URL ?>/public/client/guests"><i class="fas fa-users"></i> Convidados</a>
                <a href="<?= BASE_URL ?>/public/client/messages" class="active">
                    <i class="fas fa-comments"></i> Mensagens
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-comments"></i> Mensagens</h1>
                <p>Converse com fornecedores e equipe Clea</p>
            </div>

            <div class="messages-container" id="messagesContainer">
                <!-- Chat Sidebar -->
                <div class="chat-sidebar">
                    <button class="mobile-back-btn" onclick="hideMobileSidebar()" style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                    </button>

                    <div class="sidebar-header">
                        <div class="sidebar-title">Conversas</div>
                        <div class="sidebar-subtitle"><?= count($vendors) ?> contatos</div>
                    </div>

                    <div class="search-box" style="position: relative;">
                        <input type="text" class="search-input" placeholder="Buscar conversas..." id="searchInput">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    <div class="contacts-list" id="contactsList">
                        <!-- Clea Support -->
                        <div class="contact-item active" onclick="selectContact(0, this)" data-contact="clea">
                            <div class="contact-avatar" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name">Suporte Clea</div>
                                <div class="contact-last-message">Olá! Como podemos ajudar?</div>
                            </div>
                            <div class="contact-meta">
                                <div class="contact-time">09:30</div>
                                <div class="unread-badge">1</div>
                            </div>
                        </div>

                        <?php foreach ($vendors as $index => $vendor): ?>
                        <div class="contact-item" onclick="selectContact(<?= $index + 1 ?>, this)" data-contact="vendor-<?= $vendor['id'] ?>">
                            <div class="contact-avatar">
                                <?= strtoupper(substr($vendor['business_name'], 0, 2)) ?>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name"><?= htmlspecialchars($vendor['business_name']) ?></div>
                                <div class="contact-last-message">
                                    <?= htmlspecialchars($vendor['last_message'] ?? 'Nenhuma mensagem ainda') ?>
                                </div>
                            </div>
                            <div class="contact-meta">
                                <div class="contact-time"><?= $vendor['last_message_time'] ?? '' ?></div>
                                <?php if (($vendor['unread_count'] ?? 0) > 0): ?>
                                    <div class="unread-badge"><?= $vendor['unread_count'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="support-section">
                        <div class="support-title">Precisa de ajuda?</div>
                        <div class="support-contact" onclick="openSupport()">
                            <div class="contact-avatar" style="width: 35px; height: 35px; margin-right: 10px;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name">Ligar para Clea</div>
                                <div class="contact-last-message" style="font-size: 12px;">(11) 9999-9999</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Main Area -->
                <div class="chat-main" id="chatMain">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <button class="mobile-back-btn" onclick="showMobileSidebar()" style="display: none;">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="chat-header-info">
                            <div class="chat-avatar" id="chatAvatar">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="chat-details">
                                <h3 id="chatName">Suporte Clea</h3>
                                <div class="chat-status" id="chatStatus">Online agora</div>
                            </div>
                        </div>

                        <div class="chat-actions">
                            <button class="chat-action-btn" onclick="toggleChatInfo()">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button class="chat-action-btn" onclick="archiveChat()">
                                <i class="fas fa-archive"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-messages" id="chatMessages">
                        <!-- Default Clea Support Messages -->
                        <div class="message-date-divider">
                            <span class="date-divider-text">Hoje</span>
                        </div>

                        <div class="message-group received">
                            <div class="message received">
                                Olá <?= htmlspecialchars($user['name']) ?>! 👋<br><br>
                                Bem-vinda ao sistema de mensagens da Clea! Aqui você pode conversar diretamente com seus fornecedores contratados e nossa equipe de suporte.
                            </div>
                            <div class="message-time">09:30</div>
                        </div>

                        <div class="message-group received">
                            <div class="message received">
                                Algumas dicas importantes:<br>
                                • Use este chat para tirar dúvidas sobre serviços<br>
                                • Agende reuniões com fornecedores<br>
                                • Solicite alterações nos contratos<br>
                                • Entre em contato conosco para suporte
                            </div>
                            <div class="message-time">09:31</div>
                        </div>

                        <div class="message-group received">
                            <div class="message received">
                                Como posso ajudar você hoje? 😊
                            </div>
                            <div class="message-time">09:32</div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input-container">
                        <form class="chat-input-form" onsubmit="sendMessage(event)">
                            <textarea
                                class="chat-input"
                                id="messageInput"
                                placeholder="Digite sua mensagem..."
                                rows="1"
                                onkeydown="handleInputKeydown(event)"
                                oninput="autoResize(this)"
                            ></textarea>
                            <button type="submit" class="send-btn" id="sendBtn" disabled>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentContact = 0;
        let contacts = [
            {
                id: 0,
                name: 'Suporte Clea',
                avatar: '<i class="fas fa-headset"></i>',
                status: 'Online agora',
                type: 'support',
                messages: [
                    {
                        id: 1,
                        type: 'received',
                        content: 'Olá <?= htmlspecialchars($user['name']) ?>! 👋<br><br>Bem-vinda ao sistema de mensagens da Clea! Aqui você pode conversar diretamente com seus fornecedores contratados e nossa equipe de suporte.',
                        time: '09:30',
                        date: 'Hoje'
                    },
                    {
                        id: 2,
                        type: 'received',
                        content: 'Algumas dicas importantes:<br>• Use este chat para tirar dúvidas sobre serviços<br>• Agende reuniões com fornecedores<br>• Solicite alterações nos contratos<br>• Entre em contato conosco para suporte',
                        time: '09:31'
                    },
                    {
                        id: 3,
                        type: 'received',
                        content: 'Como posso ajudar você hoje? 😊',
                        time: '09:32'
                    }
                ]
            }
            <?php foreach ($vendors as $index => $vendor): ?>
            ,{
                id: <?= $index + 1 ?>,
                name: '<?= addslashes($vendor['business_name']) ?>',
                avatar: '<?= strtoupper(substr($vendor['business_name'], 0, 2)) ?>',
                status: 'Última vez online há 2h',
                type: 'vendor',
                category: '<?= ucfirst($vendor['category']) ?>',
                messages: [
                    {
                        id: 1,
                        type: 'received',
                        content: 'Olá! Estamos ansiosos para trabalhar no seu casamento. Quando podemos agendar uma reunião para discutir os detalhes?',
                        time: '14:30',
                        date: 'Ontem'
                    }
                ]
            }
            <?php endforeach; ?>
        ];

        function selectContact(contactId, element) {
            // Remove active class from all contacts
            document.querySelectorAll('.contact-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to selected contact
            element.classList.add('active');

            // Update current contact
            currentContact = contactId;
            const contact = contacts[contactId];

            // Update chat header
            document.getElementById('chatName').textContent = contact.name;
            document.getElementById('chatStatus').textContent = contact.status;

            const avatarEl = document.getElementById('chatAvatar');
            if (contact.type === 'support') {
                avatarEl.innerHTML = contact.avatar;
                avatarEl.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
            } else {
                avatarEl.textContent = contact.avatar;
                avatarEl.style.background = 'linear-gradient(135deg, var(--primary), var(--secondary))';
            }

            // Load messages
            loadMessages(contact);

            // Hide mobile sidebar on mobile
            if (window.innerWidth <= 768) {
                hideMobileSidebar();
            }
        }

        function loadMessages(contact) {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.innerHTML = '';

            let currentDate = '';

            contact.messages.forEach(message => {
                // Add date divider if needed
                if (message.date && message.date !== currentDate) {
                    currentDate = message.date;
                    const dateDivider = document.createElement('div');
                    dateDivider.className = 'message-date-divider';
                    dateDivider.innerHTML = `<span class="date-divider-text">${message.date}</span>`;
                    messagesContainer.appendChild(dateDivider);
                }

                // Add message
                const messageGroup = document.createElement('div');
                messageGroup.className = `message-group ${message.type}`;

                messageGroup.innerHTML = `
                    <div class="message ${message.type}">
                        ${message.content}
                    </div>
                    <div class="message-time">${message.time}</div>
                `;

                messagesContainer.appendChild(messageGroup);
            });

            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendMessage(event) {
            event.preventDefault();

            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (!message) return;

            // Add user message
            const contact = contacts[currentContact];
            const now = new Date();
            const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

            contact.messages.push({
                id: Date.now(),
                type: 'sent',
                content: message,
                time: time
            });

            // Clear input
            input.value = '';
            input.style.height = '44px';
            document.getElementById('sendBtn').disabled = true;

            // Reload messages
            loadMessages(contact);

            // Simulate typing indicator for responses
            if (contact.type === 'support') {
                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addAutoResponse(contact, message);
                    }, 2000);
                }, 500);
            }
        }

        function addAutoResponse(contact, userMessage) {
            const responses = {
                support: [
                    'Obrigado pela sua mensagem! Nossa equipe irá responder em breve.',
                    'Recebi sua solicitação. Vou verificar com nossa equipe e retorno rapidamente.',
                    'Perfeito! Vou encaminhar sua dúvida para o setor responsável.',
                    'Anotado! Em breve entraremos em contato com mais detalhes.'
                ],
                vendor: [
                    'Obrigado pelo contato! Vamos verificar sua solicitação.',
                    'Recebido! Em breve respondemos com mais informações.',
                    'Perfeito! Vou preparar uma proposta para você.'
                ]
            };

            const responseList = responses[contact.type] || responses.support;
            const randomResponse = responseList[Math.floor(Math.random() * responseList.length)];

            const now = new Date();
            const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

            contact.messages.push({
                id: Date.now(),
                type: 'received',
                content: randomResponse,
                time: time
            });

            loadMessages(contact);
        }

        function showTypingIndicator() {
            const messagesContainer = document.getElementById('chatMessages');

            const typingDiv = document.createElement('div');
            typingDiv.className = 'message-group received';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="typing-indicator">
                    <div class="typing-dots">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;

            messagesContainer.appendChild(typingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function autoResize(textarea) {
            textarea.style.height = '44px';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';

            // Enable/disable send button
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = !textarea.value.trim();
        }

        function handleInputKeydown(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage(event);
            }
        }

        function showMobileSidebar() {
            document.getElementById('messagesContainer').classList.add('mobile-sidebar');
        }

        function hideMobileSidebar() {
            document.getElementById('messagesContainer').classList.remove('mobile-sidebar');
        }

        function toggleChatInfo() {
            alert('Informações do contato (funcionalidade em desenvolvimento)');
        }

        function archiveChat() {
            if (confirm('Arquivar esta conversa?')) {
                alert('Conversa arquivada com sucesso!');
            }
        }

        function openSupport() {
            window.open('tel:+5511999999999');
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const contactItems = document.querySelectorAll('.contact-item');

            contactItems.forEach(item => {
                const name = item.querySelector('.contact-name').textContent.toLowerCase();
                const message = item.querySelector('.contact-last-message').textContent.toLowerCase();

                if (name.includes(searchTerm) || message.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Mobile responsiveness
        function handleResize() {
            const container = document.getElementById('messagesContainer');
            const backBtns = document.querySelectorAll('.mobile-back-btn');

            if (window.innerWidth <= 768) {
                backBtns.forEach(btn => btn.style.display = 'block');
            } else {
                backBtns.forEach(btn => btn.style.display = 'none');
                container.classList.remove('mobile-sidebar');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Load default contact (Clea Support)
            loadMessages(contacts[0]);

            // Auto-scroll to latest messages
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    </script>
</body>
</html>