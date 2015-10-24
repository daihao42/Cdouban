# -*- coding: utf-8 -*-

__author__ = 'Daihao'

'''
	解析数据from “http://www.weather.com.cn/adat/sk/101020100.html”
	'''

import urllib.request
import json

class Weatherinfo:
    def __init__(self,place):
        self.url='http://www.weather.com.cn/adat/sk/%s.html' % place
    
    #@asyncio.coroutine
    def decode(self):
        #self.json_decode = json.loads(urllib.request.urlopen(self.url).read().decode('utf-8'))
        req = urllib.request.Request(self.url, headers = {'Connection': 'Keep-Alive',
				'Accept': 'text/html, application/xhtml+xml, */*',
				'Accept-Language': 'en-US,en;q=0.8,zh-Hans-CN;q=0.5,zh-Hans;q=0.3',
				'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko'
				})
        try:
        	oper = urllib.request.urlopen(req,timeout=3)
        	self.json_decode = json.loads(oper.read().decode('utf-8'))
        except Exception:
        	pass
        

