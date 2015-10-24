#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''
	Models for palce
	'''
__author__ = 'Daihao'

import pymysql
pymysql.install_as_MySQLdb()

from sqlalchemy import Column, String, Integer, create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy import ForeignKey
from sqlalchemy.orm import relationship, backref
import os

# 创建对象的基类:
Base = declarative_base()

engine = create_engine('mysql+mysqldb://root:324426@127.0.0.1:3306/mydouban?charset=utf8')

Base.metadata.create_all(engine)
# 创建DBSession类型:
DBSession = sessionmaker(bind=engine)
session = DBSession()
'''
class CityId(Base):
	__tablename__ = 'city_id'

	id = Column(Integer, primary_key=True)
	city_id = Column(String(16), unique=True)
	city_name = Column(String(64), ForeignKey('city_sk_info.city'), unique=True)

	def __init__(self,city_id,city_name):
		self.city_id=city_id
		self.city_name=city_name

	def save(self):
		c = CityId(city_id=self.city_id,city_name=self.city_name)
		session.add(c)
		session.commit()

	def update(self):
		c = session.query(CityId).filter_by(city_name=self.city_name).first()
		c.city_id=self.city_id
		session.add(c)
		session.commit()
'''
class CitySkInfo(Base):
	__tablename__ = 'city_sk_info'

	id = Column(Integer, primary_key=True)
	temp = Column(String(16))
	windfrom = Column(String(64))
	winddegree = Column(String(64))
	dampness = Column(String(64))
	njd = Column(String(64))
	qy = Column(String(64))
	updatetime = Column(String(64))
	#city = relationship('city_id', backref='skinfo', lazy='dynamic')
	city = Column(String(64), unique=True)
	city_id = Column(String(16), unique=True)

	def __init__(self,temp,windfrom,winddegree,dampness,njd,qy,updatetime,city,city_id):
		self.temp=temp
		self.windfrom=windfrom
		self.winddegree=winddegree
		self.dampness=dampness
		self.njd=njd
		self.qy=qy
		self.updatetime=updatetime
		self.city=city
		self.city_id=city_id

	def save(self):
		c = session.query(CitySkInfo).filter_by(city=self.city).first()
		if c is not None:
			c.temp=self.temp
			c.windfrom=self.windfrom
			c.winddegree=self.winddegree
			c.dampness=self.dampness
			c.njd=self.njd
			c.qy=self.qy
			c.updatetime=self.updatetime
			c.city_id=self.city_id
			session.add(c)
			session.commit()
		else :
			c = CitySkInfo(temp=self.temp,windfrom=self.windfrom,
						winddegree=self.winddegree,dampness=self.dampness,
						njd=self.njd,qy=self.qy,updatetime=self.updatetime,
						city=self.city,city_id=self.city_id)
			session.add(c)
			session.commit()


Base.metadata.create_all(engine)
