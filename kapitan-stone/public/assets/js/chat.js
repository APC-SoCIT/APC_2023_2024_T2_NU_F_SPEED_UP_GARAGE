// This is for AI chat
// Add the JavaScript code here
let chatWindow = document.getElementById('chatWindow');
let chatMessages = document.getElementById('chatMessages');
let userInput = document.getElementById('userInput');

let minimizeIcon = document.querySelector('.chat-header .minimize-icon');
minimizeIcon.addEventListener('click', toggleChat);

let closeIcon = document.querySelector('.chat-header .close-icon');
closeIcon.addEventListener('click', closeChat);

// Retrieve chat history from localStorage on page load
let chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];

// Display chat history
chatHistory.forEach(entry => {
    appendMessage(entry.sender, entry.message);
});

function toggleChat() {
    chatWindow.style.display = (chatWindow.style.display === 'none') ? 'block' : 'none';

    // Save chat window state to localStorage
    localStorage.setItem('chatWindowVisible', chatWindow.style.display);
}

function closeChat() {
    // Clear chat history
    chatHistory = [];
    localStorage.removeItem('chatHistory');

    // Close the chat window
    chatWindow.style.display = 'none';
    localStorage.setItem('chatWindowVisible', 'none');

    // Clear the chat messages in the UI
    chatMessages.innerHTML = '';
}

function askAI() {
    let userQuestion = userInput.value;
    appendMessage('user', userQuestion);

    // Placeholder AI response (replace with your AI logic)
    let aiResponse = 'I am a placeholder AI. Your question was: ' + userQuestion;
    appendMessage('ai', aiResponse);

    // Save chat history to localStorage
    chatHistory.push({ sender: 'user', message: userQuestion });
    chatHistory.push({ sender: 'ai', message: aiResponse });
    localStorage.setItem('chatHistory', JSON.stringify(chatHistory));

    userInput.value = ''; // Clear input after sending
}

function appendMessage(sender, message) {
    let messageElement = document.createElement('div');
    messageElement.classList.add('message', sender);
    messageElement.innerText = message;
    chatMessages.appendChild(messageElement);
}
