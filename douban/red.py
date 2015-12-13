#!/usr/bin/python
# -*- coding: utf-8 -*-

__author__ = 'dai'

'''
	封装redis的set的基本操作
	数据库0用于保存type的类型(以set形式保存)及电影ID，
	主要用于电影类型的保存
	'''

from redis import Redis


class Red:
	def __init__(self,db):
		try:
			self.red = Redis(host='localhost',port=6379,db=db)
		except Exception:
			raise Exception

	"""
		Func: 增加一个类型的电影绑定
		Args: mtype 增加的类型
		Args: mid 绑定该类型的电影ID
		"""
	def add(self,mtype,mid):
		try:
			self.red.sadd(mtype,mid)
		except Exception:
			return False
		return True

	def get(self,mtype):
		try:
			return self.red.smembers(mtype)
		except Exception:
			return False


if __name__ == '__main__':
	r = Red(0)
	r.add('act','4')
	print(r.get('act'))


