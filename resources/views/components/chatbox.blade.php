<link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
<div id="chatWindow" class="chat-window">
    <div class="chat-header">
        <span>AI Chat</span>
        <div class="icons-container">
            <span class="minimize-icon">-</span>
            <span class="close-icon" onclick="closeChat()">x</span>
        </div>
    </div>
        <div id="chatMessages" class="chat-body">
        </div>
    <div class="chat-input">
        <input type="text" id="userInput" placeholder="Type your message...">
        <button onclick="askAI()">Send</button>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">