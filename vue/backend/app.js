// app.js
import express from 'express';
import mysql from 'mysql';
import cors from 'cors';

const app = express();
app.use(cors());
app.use(express.json());

// Configuración de la base de datos
const db = mysql.createConnection({
  host: '172.20.227.241',      // Dirección de tu servidor MySQL
  user: 'grupo3_2425',           // Usuario de MySQL
  password: 'dqwW2[h1v1x)G)6/',   // Contraseña de MySQL
  database: 'grupo3_2425', // Nombre de tu base de datos
});

// Conectar a la base de datos
db.connect((err) => {
  if (err) {
    console.error('Error al conectar a la base de datos:', err);
    return;
  }
  console.log('Conexión exitosa a la base de datos.');
});

// Endpoint para obtener datos de la base de datos
app.get('/api/datos', (req, res) => {
  const sql = 'SELECT * FROM Usuarios'; // Cambia "tabla" por tu tabla en la base de datos
  db.query(sql, (err, results) => {
    if (err) {
      console.error('Error al obtener datos:', err);
      res.status(500).send('Error al obtener datos');
      return;
    }
    res.json(results);
  });
});

// Iniciar el servidor
const PORT = 3000;
app.listen(PORT, () => {
  console.log(`Servidor ejecutándose en http://localhost:${PORT}`);
});
