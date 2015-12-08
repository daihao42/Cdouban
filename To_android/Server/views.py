#!/usr/bin/env python
#-*-coding:utf8-*-
from flask import Flask
from flask import request
from models import Auth
import json

app = Flask(__name__)

'''
	test /
	'''
@app.route('/')
def index():
    return 'Index Page'

"""
	处理登陆
	"""
@app.route('/login',methods=['GET','POST'])
def login():
	if request.method == 'POST':
		'''
		a = Auth()
		email = request.form['email']
		print(email)
		password = request.form['password']
		d = {699:'login ok',
			 770:'email is not vaild',
			 771:'password is wrong'}
		r = a.login(email,password)
		try:
			int(r)
			return d[r]
		except Exception:
			return r
			'''
		print (json.loads(request.data[7:].decode()))
		d = {'code':'ok','name':'dai'}
		return json.dumps(d)
	else:
		return 'Get Login~'

"""
	处理注册
	"""
@app.route('/register',methods=['GET','POST'])
def register():
	if request.method == 'POST':
		'''
		a = Auth()
		email = request.form['email']
		password = request.form['password']
		name = request.form['name']
		city = request.form['city']
		d = {698:'something was wrong on server',
			 772:'register ok',
			 773:'email has used',
			 774:'name has used'}
		return d[a.register(email,password,name,city)]
		'''
		L = json.loads(request.data[7:].decode())
		print(L['email'])
		d = {'response':[{'code':'ok','name':'dai'},{'message':'give a test'}]}
		return json.dumps(d)
	else :
		return 'Get Register'
