<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
.mensagens-wrapper {
    display: flex;
    height: 70vh;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    background: #fff;
    margin-top: 20px;
}
.painel-esquerdo {
    width: 35%;
    border-right: 1px solid #eee;
    background: #f9f9f9;
    display: flex;
    flex-direction: column;
}
.chat-list {
    overflow-y: auto;
    flex-grow: 1;
}
.chat-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.3s;
}
.chat-item:hover, .chat-item.active {
    background: #eef2ff;
}
.chat-item-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}
.chat-item-title {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 5px;
}
.chat-item-preview {
    font-size: 0.85em;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.painel-direito {
    width: 65%;
    display: flex;
    flex-direction: column;
    background: #fff;
}
.chat-header {
    padding: 13px;
    border-bottom: 1px solid #eee;
    background: #fff;
    font-weight: bold;
    font-size: 1.1em;
}
.chat-messages {
    flex-grow: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f4f6f9;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 15px;
    font-size: 0.95em;
    line-height: 1.4;
    position: relative;
    word-wrap: break-word;
}
.msg-received {
    background: #fff;
    border: 1px solid #e0e0e0;
    align-self: flex-start;
    border-bottom-left-radius: 2px;
}
.msg-sent {
    background: #007bff;
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 2px;
}
.message-time {
    font-size: 0.75em;
    opacity: 0.7;
    margin-top: 5px;
    text-align: right;
}
.chat-input-area {
    padding: 15px;
    background: #fff;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.chat-input-area input {
    flex-grow: 1;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 20px;
    outline: none;
}
.chat-input-area button {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}
.chat-input-area button:hover {
    background: #0056b3;
}
.no-chat-selected {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #888;
    font-size: 1.1em;
}
</style>

<section style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom: 0;">Minhas Mensagens</h2>
    <p style="color: #666; margin-top: 5px;">Acompanhe as mensagens dos seus anúncios e negociações.</p>

    <div class="mensagens-wrapper">
        <!-- Lista de conversas -->
        <div class="painel-esquerdo">
            <div style="padding: 15px; background: #fff; border-bottom: 1px solid #eee; font-weight: bold;">
                Conversas Recentes
            </div>
            <div class="chat-list" id="chatList">
                <div style="padding: 20px; text-align: center; color: #888;">Carregando chats...</div>
            </div>
        </div>

        <!-- Chat Aberto -->
        <div class="painel-direito" id="chatPanel">
            <div class="no-chat-selected">
                Selecione uma conversa ao lado para começar
            </div>
        </div>
    </div>
</section>

<script>
const currentUserId = <?php echo isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0; ?>;
let activeChat = null;

async function loadChats() {
    try {
        const response = await fetch('/index.php?action=api_listar_chats');
        const data = await response.json();
        
        const chatList = document.getElementById('chatList');
        chatList.innerHTML = '';

        if (data.success && data.chats.length > 0) {
            data.chats.forEach(chat => {
                const chatEl = document.createElement('div');
                chatEl.className = 'chat-item';
                chatEl.onclick = () => openChat(chat, chatEl);
                
                chatEl.innerHTML = `
                    <div class="chat-item-header">
                        <span>${chat.nomeUsuario}</span>
                        <span style="font-size: 0.8em; color: #999;">${chat.dataEnvio}</span>
                    </div>
                    <div class="chat-item-title">${chat.tituloAnuncio}</div>
                    <div class="chat-item-preview">${chat.ultimaMensagem || ''}</div>
                `;
                chatList.appendChild(chatEl);
            });
        } else {
            chatList.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Nenhuma conversa encontrada.</div>';
        }
    } catch (error) {
        console.error('Error loading chats:', error);
    }
}

async function openChat(chat, element) {
    activeChat = chat;
    
    document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
    if (element) element.classList.add('active');

    const chatPanel = document.getElementById('chatPanel');
    chatPanel.innerHTML = `
        <div class="chat-header">
            <span style="font-family: Arial; font-size: 0.8em; color: #000000ff; font-weight: bold;"> ${chat.nomeUsuario} </span>
            <span style="font-size: 0.5em; color: #666; font-weight: normal; margin-left: 5px;">Anúncio: ${chat.tituloAnuncio}</span>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div style="text-align: center; color: #888; margin-top: 20px;">Carregando mensagens...</div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="messageInput" placeholder="Digite sua mensagem..." onkeypress="if(event.key === 'Enter') sendMessage()">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    `;

    loadMessages();
}

async function loadMessages() {
    if (!activeChat) return;

    try {
        const response = await fetch(`/index.php?action=listar_mensagens&anuncioId=${activeChat.anuncioId}&outroUsuarioId=${activeChat.usuarioId}`);
        const data = await response.json();

        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.innerHTML = '';

        if (data.success && data.mensagens) {
            data.mensagens.forEach(msg => {
                const isSent = msg.remetente_usuario_id == currentUserId;
                const msgEl = document.createElement('div');
                msgEl.className = `message-bubble ${isSent ? 'msg-sent' : 'msg-received'}`;
                
                msgEl.innerHTML = `
                    <div>${msg.texto}</div>
                    <div class="message-time">${msg.data_envio}</div>
                `;
                messagesContainer.appendChild(msgEl);
            });
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

async function sendMessage() {
    if (!activeChat) return;
    
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    if (!text) return;

    try {
        const response = await fetch('/index.php?action=enviar_mensagem', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                anuncioId: activeChat.anuncioId,
                destinatarioUsuarioId: activeChat.usuarioId,
                texto: text
            })
        });

        const data = await response.json();
        if (data.success) {
            input.value = '';
            await loadMessages();
            loadChats(); // Refresh chat list for preview and order
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

document.addEventListener('DOMContentLoaded', loadChats);
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>