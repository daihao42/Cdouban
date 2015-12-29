#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
	Models 
	'''
__author__ = 'Daihao'

'''
import pymysql
pymysql.install_as_MySQLdb()

from sqlalchemy import Column, String, Integer, Float, create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy import ForeignKey
from sqlalchemy.orm import relationship, backref
'''
#用__init___中的flask-sqlalchemy代替原生sqlalchemy
from myapp import db

import os ,json
import hashlib, time
from .config import MyConfig


'''
# 创建对象的基类:
Base = declarative_base()

engine = create_engine('mysql+mysqldb://root:324426@127.0.0.1:3306/android?charset=utf8')

Base.metadata.create_all(engine)
# 创建DBSession类型:
DBSession = sessionmaker(bind=engine)
session = DBSession()
'''

#创建配置信息
myconfig = MyConfig()

class Auth(db.Model):

	__tablename__ = 'users'
	user_id = db.Column(db.Integer, primary_key=True)
	user_name = db.Column(db.String(80))
	user_pass = db.Column(db.String(47))
	user_email = db.Column(db.String(80))
	user_city = db.Column(db.String(80))
	user_img = db.Column(db.String(80))
	user_about = db.Column(db.String(200))
	time = db.Column(db.String(200))

	"""
		获取输入并解密，验证是否登陆
		前7位为salt，后面为sha1(salt+passwd)
		"""
	def login(self,email,password):
		####从mysql取出数据######
		c = Auth.query.filter_by(user_email=email).first()
		if c is not None:
			print (c)
			#取出salt
			salt = c.user_pass[:7]
			userhash = hashlib.sha1((str(salt)+password).encode('utf-8')).hexdigest()
			if salt+userhash == c.user_pass:
				#return myconfig.Login_OK
				d = {'response':'comfirmed',
					 'name':c.user_name,
					 'email':email,
					 'password':password,
					 'about':c.user_about,
					 'city':c.user_city,
					 'image':c.user_img}
				return json.dumps(d)
				#return 'username:'+c.user_name+'&about:'+c.user_about
			else :
				return myconfig.Passwd_Is_Wrong
		else :
			return myconfig.Email_Is_Not_Vaild

	"""
		注册用户，首先确定email和user是否已经使用
		md5(时间,7)生成salt，接用户密码，求sha1()得到用户hash
		"""
	def register(self,email,password,name,city):
		#逻辑判断
		#电子邮箱存在
		c = Auth.query.filter_by(user_email=email).first()
		if c is not None:
			return myconfig.Email_Has_Used
		#用户名存在
		c = Auth.query.filter_by(user_name=name).first()
		if c is not None:
			return myconfig.User_Name_Has_Used
		#生成salt
		salt = hashlib.md5(str(time.time()).encode('utf-8')).hexdigest()[:7]
		userhash = hashlib.sha1((str(salt)+password).encode('utf-8')).hexdigest()
		userhash = salt+userhash
		c = Auth(user_name = name,
				 user_email = email,
				 user_pass = userhash,
				 user_city = city,
				 user_about = '',
				 user_img = '../static/defalut.jpg')
		try:
			db.session.add(c)
			db.session.commit()
			return myconfig.Register_OK
		except Exception:
			return myconfig.Something_Wrong

class MovieDB(db.Model):
	__tablename__ = 'movie_info'

	id = db.Column(db.Integer, primary_key=True)
	title = db.Column(db.String(256))
	director = db.Column(db.String(256))
	writer = db.Column(db.String(256))
	actor = db.Column(db.String(256))
	types = db.Column(db.String(256))
	country = db.Column(db.String(64))
	lang = db.Column(db.String(64))
	ontime = db.Column(db.String(64))
	runtime = db.Column(db.String(64))
	another = db.Column(db.String(256))
	summary = db.Column(db.String(1024))
	average = db.Column(db.Float)
	votes = db.Column(db.Integer)

	def get_movie(page):
		#error_out表示page超过时，True返回404，Flase返回空
		pagination = MovieDB.query.order_by(MovieDB.id.desc()).paginate(
			page, per_page=10,error_out=False)
		movies = pagination.items
		return movies

	#获取单部电影
	def get_a_movie(movie_id):
		movie = MovieDB.query.filter_by(id=movie_id).first()
		return movie

	'''
		序列化一部完整的MovieDB
		'''
	def serize_Moive(self):
		return {'id' : self.id,
				'title' : self.title,
			'director' : self.director,
			'writer' : self.writer,
			'actor' : self.actor,
			'types' : self.types,
			'country' : self.country,
			'lang' : self.lang,
			'ontime' : self.ontime,
			'runtime' : self.runtime,
			'another' : self.another,
			'summary' : self.summary,
			'average' : self.average,
			'votes' : self.votes}

	'''
		序列化简短的电影信息
		'''
	def serize_ShortMovie(self):
		return {'id' : self.id,
				'title' : self.title,
			'director' : self.director,
			#'writer' : self.writer,
			'actor' : self.actor,
			'types' : self.types,
			#'country' : self.country,
			#'lang' : self.lang,
			'ontime' : self.ontime}
			#'runtime' : self.runtime}




