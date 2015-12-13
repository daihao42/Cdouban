#!/usr/bin/env python
#-*-coding:utf8-*-
from flask import Flask
from flask import request
from models import Auth
import json
import os

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
		a = Auth()
		L = json.loads(request.data[7:].decode())
		email = L['email']
		password = L['password']
		d = {699:'login ok',
			 770:'email is not vaild',
			 771:'password is wrong'}
		r = a.login(email,password)
		try:
			int(r)
			return json.dumps({'response':d[r]})
		except Exception:
			return r
			'''
		print (json.loads(request.data[7:].decode()))
		d = {'response':[{'code':'ok','name':'dai'},{'message':'give a test'}]}
		j = json.dumps(d)
		print(j)
		return j
		'''
	else:
		return 'Get Login~'

"""
	处理注册
	"""
@app.route('/register',methods=['GET','POST'])
def register():
	if request.method == 'POST':
		a = Auth()
		L = json.loads(request.data[7:].decode())
		email = L['email']
		password = L['password']
		name = L['username']
		city = L['city']
		d = {698:'something was wrong on server',
			 772:'register ok',
			 773:'email has used',
			 774:'name has used'}
		r = {'response':d[a.register(email,password,name,city)],'name':name,'about':'','city':city,'image':'../static/defalut.jpg'}
		return json.dumps(r)
		'''
		L = json.loads(request.data[7:].decode())
		print(L['email'])
		d = {'response':"ok"}
		j = json.dumps(d)
		print(j)
		return j
		'''
	else :
		return 'Get Register'

