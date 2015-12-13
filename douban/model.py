#!/usr/bin/python
# -*- coding: utf-8 -*-

'''
	数据库模型，保存影片信息
	'''
__author__ = 'Daihao'


'''
CREATE DATABASE db_name DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
'''

import pymysql
pymysql.install_as_MySQLdb()

from sqlalchemy import Column, String, Integer, Float, create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy import ForeignKey
from sqlalchemy.orm import relationship, backref
import os

# 创建对象的基类:
Base = declarative_base()

#echo=True 表示显示每条sql语句
#engine = create_engine('mysql+mysqldb://root:324426@127.0.0.1:3306/mydouban?charset=utf8',echo=True)
engine = create_engine('mysql+mysqldb://root:324426@127.0.0.1:3306/mydouban?charset=utf8')

Base.metadata.create_all(engine)
# 创建DBSession类型:
DBSession = sessionmaker(bind=engine)
session = DBSession()


class MovieDB(Base):
	__tablename__ = 'movie_info'

	id = Column(Integer, primary_key=True)
	title = Column(String(256))
	director = Column(String(256))
	writer = Column(String(256))
	actor = Column(String(256))
	types = Column(String(256))
	country = Column(String(64))
	lang = Column(String(64))
	ontime = Column(String(64))
	runtime = Column(String(64))
	another = Column(String(256))
	summary = Column(String(1024))
	average = Column(Float)
	votes = Column(Integer)

Base.metadata.create_all(engine)