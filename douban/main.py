#!/usr/bin/python
# -*- coding: utf-8 -*-

__author__ = 'dai'

import time
from movie import Movie
from model import MovieDB, session

while (True):
	print(time.strftime("%H:%M"))
	m = Movie("http://movie.douban.com/")
	m.dumpImg()
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
	time.sleep(6*60*60)

