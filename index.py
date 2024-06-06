from flask import Flask  
from flask import render_template,redirect,request,Response,session
from flask_mysqldb import MySQL,MySQLdb

app = Flask(__name__, template_folder ='templates')

app.config['MYSQL_HOST']='localhost'
app.config['MYSQL_USER']='root'
app.config['MYSQL_PASSWORD']=''
app.config['MYSQL_DB']='goe'
app.config['MYSQL_CURSORCLASS']='DictCursor'
mysql=MySQL(app)

@app.route('/')
def observadores():
    return render_template('observadores.html')

@app.route('/perfil')
def perfil():
    return render_template('perfil.html')

@app.route('/asistencia')
def asistencia():
    return render_template('asistencia.html')


@app.route('/home')
def home():
    return render_template('home.html')

@app.route('/index')
def home():
    return render_template('index.html')

if __name__ == '__main__':
      app.secret_key="jean"
      app.run(debug=True , host='0.0.0.0', port=5000, threaded=True)