from flask import Flask
import pymysql
pymysql.install_as_MySQLdb()
from flask.ext.sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqldb://root:324426@127.0.0.1:3306/android?charset=utf8'
db = SQLAlchemy(app)

import myapp.views