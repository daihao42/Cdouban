#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
	Models 
	'''
__author__ = 'Daihao'

import pymysql
pymysql.install_as_MySQLdb()

from sqlalchemy import Column, String, Integer, create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy import ForeignKey
from sqlalchemy.orm import relationship, backref
import os ,json
import hashlib, time
from config import MyConfig

# 创建对象的基类:
Base = declarative_base()

engine = create_engine('mysql+mysqldb://root:324426@127.0.0.1:3306/mydouban?charset=utf8')

Base.metadata.create_all(engine)
# 创建DBSession类型:
DBSession = sessionmaker(bind=engine)
session = DBSession()

#创建配置信息
myconfig = MyConfig()

class Auth(Base):

	__tablename__ = 'users'
	user_id = Column(Integer, primary_key=True)
	user_name = Column(String(80))
	user_pass = Column(String(47))
	user_email = Column(String(80))
	user_city = Column(String(80))
	user_img = Column(String(80))
	user_about = Column(String(200))
	time = Column(String(200))

	"""
		获取输入并解密，验证是否登陆
		前7位为salt，后面为sha1(salt+passwd)
		"""
	def login(self,email,password):
		####从mysql取出数据######
		c = session.query(Auth).filter_by(user_email=email).first()
		if c is not None:
			#取出salt
			salt = c.user_pass[:7]
			userhash = hashlib.sha1((str(salt)+password).encode('utf-8')).hexdigest()
			if salt+userhash == c.user_pass:
				#return myconfig.Login_OK
				d = {'username':c.user_name,'about':c.user_about,'city':c.user_city}
				#return json.dumps(d)
				return 'username:'+c.user_name+'&about:'+c.user_about
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
		c = session.query(Auth).filter_by(user_email=email).first()
		if c is not None:
			return myconfig.Email_Has_Used
		#用户名存在
		c = session.query(Auth).filter_by(user_name=name).first()
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
				 user_img = '../static/defalut.jpg')
		try:
			session.add(c)
			session.commit()
			return myconfig.Register_OK
		except Exception:
			return myconfig.Something_Wrong
		

