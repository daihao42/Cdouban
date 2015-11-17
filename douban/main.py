#!/usr/bin/python
# -*- coding: utf-8 -*-

__author__ = 'dai'

import time
from movie import Movie
from red import Red
from model import MovieDB, session

while (True):
	print(time.strftime("%H:%M"))
	m = Movie("http://movie.douban.com/")
	m.dumpImg()
	r = Red(0)
	for i in m.arr:
		#如果存在记录，则更新评论人数和评级
		if(session.query(MovieDB).filter_by(title=i['title']).first() is not None):
			d = m.updateInfo(i['info'])
			try:
				s = session.query(MovieDB).filter_by(title=i['title']).first()
				s.average=d['average']
				s.votes=d['votes']
				session.add(s)
				session.commit()
			except Exception:
				continue
		else:
			d = m.getInfo(i['info'],i['title'])
			s = MovieDB(title = i['title'],
					director = d['director'],
					writer = d['writer'],
					actor = d['actor'],
					types = d['types'],
					country = d['country'],
					lang = d['lang'],
					ontime = d['ontime'],
					runtime = d['runtime'],
					another = d['another'],
					summary = d['summary'],
					average = d['average'],
					votes = d['votes']
					)
			session.add(s)
			session.commit()
			#查询刚插入的电影的ID，然后写入redis
			s = session.query(MovieDB).filter_by(title=i['title']).first()
			r.add('全部',s.id)
			for i in d['type']:
				r.add('type',i)
				r.add(i,s.id)
				#print(r.get(i))
			for x in d['ontimes']:
				r.add('ontimes',x)
				r.add(x,s.id)
			for x in d['countrys']:
				r.add('country',x)
				r.add(x,s.id)

	time.sleep(6*60*60)

