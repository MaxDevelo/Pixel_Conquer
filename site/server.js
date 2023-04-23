const express = require('express');
const cors = require('cors');

const app = express();
const server = require('http').Server(app);
const io = require('socket.io')(server);

app.use(cors()); // permettre toutes les demandes cross-origin

server.listen(3000, () => {
  console.log('Server is running on port 3000');
});
app.get('/socket.io', (req, res) => {
    res.setHeader('Access-Control-Allow-Origin', '*'); // autoriser toutes les demandes cross-origin
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST'); // autoriser les méthodes HTTP GET et POST
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type'); // autoriser le type de contenu
    io.sockets.emit('socketio', 'Hello from Socket.io!'); // envoyer un message à tous les clients connectés via Socket.io
  });
  
