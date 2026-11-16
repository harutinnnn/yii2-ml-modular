const socket = io('http://asue.local:3001', {
    // transports: ['websocket', 'polling'],
    reconnection: true
});


socket.on('connect', function () {
    console.log('Socket.IO connected');

    if (userId !== null) {
        socket.emit('join', userId);
    }
});

socket.on('broadcast', function (data) {
    console.log('Received:', data);
});

socket.on('notification', function (data) {
    console.log('Received:', data);
});


