@extends('layouts.layout')

@vite('resources/js/app.js')

@section('content')
    <style>
        /* CSS Styles untuk Chat */
        #chat-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        #messages {
            height: 250px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            max-width: 70%;
        }

        .sent {
            background-color: #d1e7dd;
            align-self: flex-end;
            margin-left: auto;
        }

        .received {
            background-color: #f8d7da;
            align-self: flex-start;
        }

        #message-form {
            display: flex;
        }

        #message {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #send-btn {
            padding: 10px 15px;
            margin-left: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        #send-btn:hover {
            background-color: #0056b3;
        }

        #connection-status {
            margin-top: 10px;
            font-style: italic;
            color: #555;
        }

        #user-status {
            margin-left: 10px;
            font-size: 14px;
            color: red;
            /* default offline */
        }
    </style>
    <div id="chat-container">
        <h2>Chat dengan {{ $user->name }} <span id="user-status">Offline</span></h2>
        <div id="messages">
            <!-- Pesan akan ditampilkan di sini -->
        </div>
        <form id="message-form">
            <input type="text" id="message" placeholder="Ketik pesan..." required>
            <button type="submit" id="send-btn">Kirim</button>
        </form>
        <div id="connection-status">Connecting...</div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const senderUuid = "{{ auth()->user()->uuid }}"; // UUID pengguna yang sedang login
            const receiverUuid = "{{ $user->uuid }}"; // UUID penerima pesan

            // Generate a unique chat ID by sorting the UUIDs
            const chatId = [senderUuid, receiverUuid].sort().join('_');

            // Mendapatkan pesan yang sudah ada
            fetch(`/dosen/get-message/${receiverUuid}`)
                .then(response => response.json())
                .then(messages => {
                    const messagesContainer = document.getElementById('messages');
                    const authUserUuid = senderUuid; // UUID pengguna yang sedang login

                    if (messages && messages.data && Array.isArray(messages.data)) {
                        messages.data.forEach(message => {
                            const messageElement = document.createElement('div');
                            messageElement.classList.add('message');

                            if (message.sender_uuid === authUserUuid) {
                                messageElement.classList.add('sent');
                                messageElement.innerHTML =
                                    `<span class="text">${message.message}</span>`;
                            } else {
                                messageElement.classList.add('received');
                                messageElement.innerHTML =
                                    `<span class="text">${message.message}</span>`;
                            }

                            messagesContainer.appendChild(messageElement);
                        });
                        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll ke bawah
                    } else {
                        console.error('Format pesan tidak sesuai:', messages);
                    }
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                });

            // Mengirim pesan
            document.getElementById('message-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const messageInput = document.getElementById('message');
                const message = messageInput.value.trim();

                if (message === '') {
                    return; // Jangan kirim pesan kosong
                }

                fetch('/dosen/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            uuid_receiver: receiverUuid,
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hanya mengosongkan input tanpa menampilkan pesan dari respons API
                            messageInput.value = ''; // Mengosongkan input
                        } else {
                            console.error('Failed to send message:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                    });
            });

            // Menangani Presence Channel untuk Status Pengguna
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                // Subscribe to Presence Channel
                window.Echo.join(`chat.${chatId}`)
                    .here((users) => {
                        const userStatusElement = document.getElementById('user-status');

                        // Filter out the sender themselves
                        const otherUsers = users.filter(user => user.uuid !== senderUuid);

                        if (otherUsers.length > 0) {
                            userStatusElement.innerText = 'Online';
                            userStatusElement.style.color = 'green';
                        } else {
                            userStatusElement.innerText = 'Offline';
                            userStatusElement.style.color = 'red';
                        }
                    })
                    .joining((user) => {
                        const userStatusElement = document.getElementById('user-status');
                        if (user.uuid === receiverUuid) {
                            userStatusElement.innerText = 'Online';
                            userStatusElement.style.color = 'green';
                        }
                    })
                    .leaving((user) => {
                        const userStatusElement = document.getElementById('user-status');
                        if (user.uuid === receiverUuid) {
                            userStatusElement.innerText = 'Offline';
                            userStatusElement.style.color = 'red';
                        }
                    })
                    .error((error) => {
                        console.error('Error with presence channel:', error);
                    });

                // Handle connection status
                window.Echo.connector.pusher.connection.bind('state_change', states => {
                    const connectionStatusElement = document.getElementById('connection-status');

                    if (states.current === 'connected') {
                        connectionStatusElement.innerText = 'Connected to chat';
                    } else if (states.current === 'disconnected') {
                        connectionStatusElement.innerText = 'Disconnected from chat';
                    }
                });

                // Menerima pesan dari Presence Channel
                window.Echo.join(`chat.${chatId}`)
                    .listen('.MessageSent', (event) => {
                        const messagesContainer = document.getElementById('messages');
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message');

                        const authUserUuid = senderUuid; // UUID pengguna yang sedang login

                        if (event.sender_uuid === authUserUuid) {
                            messageElement.classList.add('sent');
                            messageElement.innerHTML = `
                                <span class="text">${event.message}</span>
                            `;
                        } else {
                            messageElement.classList.add('received');
                            messageElement.innerHTML = `
                                <span class="text">${event.message}</span>
                            `;
                        }

                        messagesContainer.appendChild(messageElement);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll ke bawah
                    });
            } else {
                console.error('Echo atau Echo.connector tidak diinisialisasi dengan benar.');
                document.getElementById('connection-status').innerText = 'Failed to connect to chat';
            }
        });
    </script>
@endsection
