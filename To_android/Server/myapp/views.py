#!/usr/bin/env python
#-*-coding:utf8-*-
from flask import Flask
from flask import request
from . import app
from .models import Auth , MovieDB
import json
import os



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

'''
	获取最新的10部电影
	'''
@app.route('/getMovie',methods=['GET'])
def getMovie():
	page = request.args.get('page', 1, type=int)
	movies = MovieDB.get_movie(page)
	#如果获取的分页电影为空，返回empty
	if(len(movies) == 0):
		d = {'response':'empty'}
	#不为空返回数据
	else:
		d = {'response':'ok',
			 'length':len(movies)}
		L = []
		#将电影信息序列化为dict后加入列表
		for i in movies:
			L.append(i.serize_ShortMovie())
		d['data'] = L
	#ensure_ascii=False为了解决json中文乱码
	return json.dumps(d,ensure_ascii=False)


'''
	获取一部电影
	'''
@app.route('/Movie',methods=['GET'])
def get_a_Movie():
	movie_id = request.args.get('id', 1, type=int)
	movie = MovieDB.get_a_movie(movie_id)
	#如果获取的电影为空，返回empty
	if(movie == None):
		d = {'response':'empty'}
	#不为空返回数据
	else:
		d = {'response':'ok',
			 'data':movie.serize_Moive()}
	#ensure_ascii=False为了解决json中文乱码
	return json.dumps(d,ensure_ascii=False)