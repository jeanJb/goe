const mysql = require("mysql")
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "goe",
})

db.connect((err) => {
  if (err) throw err
  console.log("Base de datos conectada")
})

module.exports = db

