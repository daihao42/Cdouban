#!/usr/bin/python
# -*- coding: utf-8 -*-

__author__ = 'dai'

from red import Red

from model import MovieDB, session

"""
	处理types的str
	返回list
	"""
def str2list(str):
	return str.split('/')


def sync(ran):
	r = Red(0)
	for i in range(0,ran):
		try:
			s = session.query(MovieDB).filter_by(id=i).first()
			if(s):
				r.add('全部',i)
				for t in str2list(s.types):
					r.add('type',t)
					r.add(t,i)
				for o in str2list(s.ontime):
					o = o.split('-')[0]
					r.add('ontimes',o)
					r.add(o,i)
				for x in s.country.split(' / '):
					r.add('country',x)
					r.add(x,i)
		except Exception:
			pass



if __name__ == '__main__':
	#print(str2list('剧情/探险/爱情'))
	'''
	for o in str2list('2015-11-20(中国大陆)/2015-07-14(美国)'):
		o = o.split('-')[0]
		print(o)
		'''
	sync(68)