<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'vendor') {
    header('Location: /public/login.php');
    exit();
}

$user = Auth::getUser();
$conversations = $data['conversations'] ?? [];
$vendorProfile = $data['vendorProfile'] ?? null;
$unreadCount = 0; // Calculate from conversations data
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens - Fornecedor - Clea Casamentos</title>
    <link rel="stylesheet" href="css/dashboard.css">
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

        .contact-event {
            font-size: 12px;
            color: var(--primary);
            margin-bottom: 2px;
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

        .contact-date {
            font-size: 12px;
            color: var(--gray-500);
        }

        .message-count {
            background: var(--primary);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
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

        .chat-event {
            font-size: 13px;
            color: var(--primary);
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

        .vendor-help {
            padding: 20px;
            border-top: 1px solid var(--gray-200);
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }

        .help-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .help-tips {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.4;
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
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1><i class="fas fa-store"></i> Clea</h1>
                <div class="vendor-badge" style="background: #dbeafe; color: #2563eb; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                    <?= ucfirst($vendorProfile['category'] ?? 'Fornecedor') ?>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="app.php?url=/vendor/dashboard"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="app.php?url=/vendor/events"><i class="fas fa-calendar-alt"></i> Meus Eventos</a>
                <a href="app.php?url=/vendor/financial"><i class="fas fa-dollar-sign"></i> Financeiro</a>
                <a href="app.php?url=/vendor/messages" class="active">
                    <i class="fas fa-comments"></i> Mensagens
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="app.php?url=/vendor/profile"><i class="fas fa-user-edit"></i> Meu Perfil</a>
                <div class="sidebar-divider"></div>
                <a href="auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-comments"></i> Mensagens</h1>
                <p>Converse com seus clientes</p>
            </div>

            <div class="messages-container" id="messagesContainer">
                <!-- Chat Sidebar -->
                <div class="chat-sidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-title">Conversas</div>
                        <div class="sidebar-subtitle"><?= count($conversations) ?> cliente(s)</div>
                    </div>

                    <div class="search-box" style="position: relative;">
                        <input type="text" class="search-input" placeholder="Buscar conversas..." id="searchInput">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    <div class="contacts-list" id="contactsList">
                        <?php if (empty($conversations)): ?>
                            <div style="padding: 40px 20px; text-align: center; color: var(--gray-500);">
                                <i class="fas fa-comments" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                <p>Nenhuma conversa ainda.<br>Suas conversas com clientes aparecerão aqui.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($conversations as $index => $conversation): ?>
                            <div class="contact-item <?= $index === 0 ? 'active' : '' ?>"
                                 onclick="selectConversation(<?= $index ?>, this)"
                                 data-contact="client-<?= $conversation['wedding_id'] ?>">
                                <div class="contact-avatar">
                                    <?= strtoupper(substr($conversation['client_name'], 0, 2)) ?>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-name"><?= htmlspecialchars($conversation['client_name']) ?></div>
                                    <div class="contact-event">
                                        Casamento em <?= date('d/m/Y', strtotime($conversation['wedding_date'])) ?>
                                    </div>
                                    <div class="contact-last-message">
                                        <?= $conversation['message_count'] ?> mensagem(ns)
                                    </div>
                                </div>
                                <div class="contact-meta">
                                    <div class="contact-date">
                                        <?= $conversation['last_message_date'] ? date('d/m', strtotime($conversation['last_message_date'])) : 'Novo' ?>
                                    </div>
                                    <?php if ($conversation['message_count'] > 0): ?>
                                        <div class="message-count"><?= min($conversation['message_count'], 9) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="vendor-help">
                        <div class="help-title">
                            <i class="fas fa-lightbulb"></i>
                            Dicas para Fornecedores
                        </div>
                        <div class="help-tips">
                            • Responda rapidamente às mensagens<br>
                            • Seja profissional e cordial<br>
                            • Use este chat para esclarecer dúvidas<br>
                            • Envie fotos e propostas quando necessário
                        </div>
                    </div>
                </div>

                <!-- Chat Main Area -->
                <div class="chat-main" id="chatMain">
                    <?php if (!empty($conversations)): ?>
                        <?php $firstConversation = $conversations[0]; ?>
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <div class="chat-header-info">
                                <div class="chat-avatar" id="chatAvatar">
                                    <?= strtoupper(substr($firstConversation['client_name'], 0, 2)) ?>
                                </div>
                                <div class="chat-details">
                                    <h3 id="chatName"><?= htmlspecialchars($firstConversation['client_name']) ?></h3>
                                    <div class="chat-event" id="chatEvent">
                                        Casamento em <?= date('d/m/Y', strtotime($firstConversation['wedding_date'])) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-actions">
                                <button class="chat-action-btn" onclick="showEventDetails()">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                                <button class="chat-action-btn" onclick="showClientInfo()">
                                    <i class="fas fa-user"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="chat-messages" id="chatMessages">
                            <div class="message-date-divider">
                                <span class="date-divider-text">Hoje</span>
                            </div>

                            <div class="message-group received">
                                <div class="message received">
                                    Olá! Estou interessada nos seus serviços para o meu casamento.
                                    Gostaria de saber mais detalhes sobre disponibilidade e valores.
                                </div>
                                <div class="message-time">14:30</div>
                            </div>

                            <div class="message-group sent">
                                <div class="message sent">
                                    Olá! Fico muito feliz com o seu interesse! 😊<br><br>
                                    Vou adorar fazer parte do seu grande dia. Quando podemos conversar
                                    para entender melhor suas necessidades?
                                </div>
                                <div class="message-time">14:45</div>
                            </div>

                            <div class="message-group received">
                                <div class="message received">
                                    Perfeito! Podemos marcar uma reunião esta semana? Estou bem flexível com horários.
                                </div>
                                <div class="message-time">15:10</div>
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
                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="empty-chat">
                            <i class="fas fa-comments"></i>
                            <h3>Nenhuma conversa</h3>
                            <p>Quando clientes entrarem em contato,<br>as conversas aparecerão aqui.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        let conversations = <?= json_encode($conversations) ?>;
        let currentConversation = 0;

        function selectConversation(conversationIndex, element) {
            // Remove active class from all contacts
            document.querySelectorAll('.contact-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to selected contact
            element.classList.add('active');

            // Update current conversation
            currentConversation = conversationIndex;
            const conversation = conversations[conversationIndex];

            // Update chat header
            document.getElementById('chatName').textContent = conversation.client_name;
            document.getElementById('chatEvent').textContent =
                `Casamento em ${new Date(conversation.wedding_date).toLocaleDateString('pt-BR')}`;

            const avatarEl = document.getElementById('chatAvatar');
            avatarEl.textContent = conversation.client_name.substring(0, 2).toUpperCase();

            // In a real app, load messages here
            loadConversationMessages(conversation);
        }

        function loadConversationMessages(conversation) {
            const messagesContainer = document.getElementById('chatMessages');

            // For demo purposes, show different messages per conversation
            const demoMessages = [
                `
                    <div class="message-date-divider">
                        <span class="date-divider-text">Hoje</span>
                    </div>
                    <div class="message-group received">
                        <div class="message received">
                            Olá! Vi seu trabalho e adorei. Gostaria de agendar uma conversa para o meu casamento.
                        </div>
                        <div class="message-time">10:30</div>
                    </div>
                `,
                `
                    <div class="message-date-divider">
                        <span class="date-divider-text">Ontem</span>
                    </div>
                    <div class="message-group received">
                        <div class="message received">
                            Preciso urgente de uma proposta. Você teria disponibilidade para março?
                        </div>
                        <div class="message-time">16:20</div>
                    </div>
                    <div class="message-group sent">
                        <div class="message sent">
                            Olá! Sim, tenho disponibilidade. Vou preparar uma proposta personalizada para vocês.
                        </div>
                        <div class="message-time">16:45</div>
                    </div>
                `
            ];

            messagesContainer.innerHTML = demoMessages[currentConversation % 2];
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendMessage(event) {
            event.preventDefault();

            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (!message) return;

            // Add user message to chat
            const messagesContainer = document.getElementById('chatMessages');
            const messageGroup = document.createElement('div');
            messageGroup.className = 'message-group sent';

            const now = new Date();
            const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

            messageGroup.innerHTML = `
                <div class="message sent">
                    ${message}
                </div>
                <div class="message-time">${time}</div>
            `;

            messagesContainer.appendChild(messageGroup);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Clear input
            input.value = '';
            input.style.height = '44px';
            document.getElementById('sendBtn').disabled = true;

            // Simulate response after 2 seconds
            setTimeout(() => {
                const responseGroup = document.createElement('div');
                responseGroup.className = 'message-group received';
                responseGroup.innerHTML = `
                    <div class="message received">
                        Obrigada pela resposta! Vou analisar e retorno em breve.
                    </div>
                    <div class="message-time">${now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })}</div>
                `;
                messagesContainer.appendChild(responseGroup);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 2000);
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

        function showEventDetails() {
            const conversation = conversations[currentConversation];
            alert(`Detalhes do Evento:\n\nCliente: ${conversation.client_name}\nData: ${new Date(conversation.wedding_date).toLocaleDateString('pt-BR')}\n\n(Funcionalidade completa em desenvolvimento)`);
        }

        function showClientInfo() {
            const conversation = conversations[currentConversation];
            alert(`Informações do Cliente:\n\n${conversation.client_name}\n\n(Funcionalidade completa em desenvolvimento)`);
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const contactItems = document.querySelectorAll('.contact-item');

            contactItems.forEach(item => {
                const name = item.querySelector('.contact-name').textContent.toLowerCase();

                if (name.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Initialize first conversation if exists
        <?php if (!empty($conversations)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            loadConversationMessages(conversations[0]);
        });
        <?php endif; ?>
    </script>
</body>
</html>