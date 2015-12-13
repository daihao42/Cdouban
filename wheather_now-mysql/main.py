#!/usr/bin/env python
#-*- coding: utf-8 -*-  

__author__ = 'Daihao'

'''
    api获取信息存入数据库
    '''

from weatherinfo import Weatherinfo
from models import CitySkInfo
import os,time
import configparser,multiprocessing

config = configparser.ConfigParser()
config.read("cityid.conf")
sections = config.sections()
#print(sections)
options = config.options("cityid")

def fetch_info(str): 
    w=Weatherinfo(str)
    try:
    	w.decode()
    	print(w.json_decode)
    	city = CitySkInfo(temp=w.json_decode["weatherinfo"]["temp"],
    				windfrom=w.json_decode["weatherinfo"]["WD"],
    				winddegree=w.json_decode["weatherinfo"]["WS"],
    				dampness=w.json_decode["weatherinfo"]["SD"],
    				njd=w.json_decode["weatherinfo"]["njd"],
    				qy=w.json_decode["weatherinfo"]["qy"],
    				updatetime=w.json_decode["weatherinfo"]["time"],
    				city=w.json_decode["weatherinfo"]["city"],
    				city_id=w.json_decode["weatherinfo"]["cityid"]
    				)
    	city.save()
    except Exception:
    	return -1 


def fetch_all():
	pool = multiprocessing.Pool(processes = 4)
	for str in options:
		pool.apply_async(fetch_info, (str, ))
	pool.close()
	pool.join()



if __name__=='__main__':
    print('<-----begin update!--------------->')
    fetch_all()
    print('<-----weatherinfo update ok!------>')
