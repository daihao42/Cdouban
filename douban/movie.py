#!/usr/bin/python
# -*- coding: utf-8 -*-

import urllib.request
from bs4 import BeautifulSoup
import os, sys, re


def rph(a, b, c):           #建立显示进度的reporthook函数  
    per = 100.0 * a * b / c  
    if per > 100:  
        per = 100  
    sys.stdout.write( '文件大小：%s byte     下载进度：%.2f%% \r' %(c,per))


'''
	param url 输入解析的地址
	__init__() 将url保存，调用getDecode将首页推荐数据保存
	'''
class Movie:
	def __init__(self,url):
		self.url=url
		self.getDecode()

	#用request获取网页
	def _getHtml(self,inurl=None):
		if(inurl):
			url=inurl
		else :
			url=self.url
		req = urllib.request.Request(url, headers = {'Connection': 'Keep-Alive',
				'Accept': 'text/html, application/xhtml+xml, */*',
				'Accept-Language': 'en-US,en;q=0.8,zh-Hans-CN;q=0.5,zh-Hans;q=0.3',
				'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko'
				})
		try:
			oper = urllib.request.urlopen(req,timeout=3)
			return oper
		except Exception:
			pass

	#用beautifulsoup解析获取到得网页
	#解析出标题，更多信息地址，图片地址存入字典
	#再将字典存入列表中
	def getDecode(self):
		oper = self._getHtml()
		if oper is None:
			return None
		self.soup = BeautifulSoup(oper,"html.parser")
		arr=[]
		for header in self.soup.find_all('li',class_ = 'ui-slide-item'):
			try:
				arr.append(dict(title=header['data-title'],
								info=header.ul.li.a['href'],
								img=header.ul.li.a.img['src']))
			except KeyError:
				continue
		self.arr=arr

	##获取图片标号，用于替换高清图下载地址
	def getPicNum(self,url):
		s = url.split('/')
		return s[-1]



#####下载剧照
	##替换图片url前缀为http://img3.douban.com/view/photo/photo/public/可以获取高清图片
	def dumpMoreImg(self,imgpath,soup):
	#soup findall img and save
		i=0
		for img in soup.find('ul',class_ = 'related-pic-bd narrow').find_all('img',alt='图片'):
			dumppath=os.path.join(imgpath,str(i)+".jpg")
			img_url = 'http://img3.douban.com/view/photo/photo/public/'+self.getPicNum(img['src'])
			urllib.request.urlretrieve(img_url,dumppath,rph)
			i=i+1

	#将图片下载到本地目录中，改名为标题名
	#替换图片url前缀为http://img3.douban.com/view/photo/photo/public/可以获取高清图片
	def dumpImg(self):
		#本地图片save目录
		dumppath=os.path.join(os.path.abspath('.'),'img')
		for i in self.arr:
			
			imgpath=os.path.join(dumppath,i['title']+".jpg")
			#判断本地图片是否已存在,不存在则下载图片并显示进度条
			if not os.path.isfile(imgpath):
				img_url = 'http://img3.douban.com/view/photo/photo/public/'+self.getPicNum(i['img'])
				urllib.request.urlretrieve(img_url,imgpath,rph)
			#存在则pass
			else:
				pass
			


	#获取影片具体信息
	def getInfo(self,url,title=None):
		oper = self._getHtml(url)
		if oper is None:
			return None
		soup = BeautifulSoup(oper,"html.parser")
		d = {}

		#创建更多剧照的文件夹
		if(title):
			dumppath=os.path.join(os.path.abspath('.'),'img')
			morepath = os.path.join(dumppath,title)
			if not os.path.isdir(morepath):
				os.mkdir(morepath)
				self.dumpMoreImg(morepath,soup)

		istr = ''
		for i in soup.find('div',id='info').find_all('a',rel='v:directedBy'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['director'] = istr

		istr = ''
		for i in soup.find('div',id='info').find_all('span',class_='attrs')[1].find_all('a'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['writer'] = istr

		istr=''
		for i in soup.find('div',id='info').find_all('a',rel='v:starring'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['actor'] = istr

		istr=''
		for i in soup.find('div',id='info').find_all('span',property='v:genre'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['types'] = istr

		reg = re.compile(r'制片国家/地区:</span> (.*)<br/>')
		d['country'] = re.findall(reg,str(soup.find('div',id='info')))[0]

		reg = re.compile(r'语言:</span> (.*)<br/>')
		d['lang'] = re.findall(reg,str(soup.find('div',id='info')))[0]

		reg = re.compile(r'又名:</span> (.*)<br/>')
		d['another'] = re.findall(reg,str(soup.find('div',id='info')))[0]

		istr=''
		for i in soup.find('div',id='info').find_all('span',property='v:initialReleaseDate'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['ontime'] = istr

		istr=''
		for i in soup.find('div',id='info').find_all('span',property='v:runtime'):
			istr += i.get_text()
			istr += '/'
		istr = istr[:-1]
		d['runtime'] = istr

		istr=str(soup.find('span',property="v:summary"))
		d['summary'] = istr
		
		istr=soup.find('strong',property="v:average").get_text()
		d['average'] = float(istr)

		istr=soup.find('span',property="v:votes").get_text()
		d['votes'] = int(istr)
		
		return d

	#更新评论人数和评价
	def updateInfo(self,url):
		oper = self._getHtml(url)
		d = {}
		if oper is None:
			return None
		try:
			soup = BeautifulSoup(oper,"html.parser")
		except Exception:
			return d
		istr=soup.find('strong',property="v:average").get_text()
		d['average'] = float(istr)

		istr=soup.find('span',property="v:votes").get_text()
		d['votes'] = int(istr)

		print(d)

		return d








if __name__ == '__main__':
	test = Movie("http://movie.douban.com/")
	#test.dumpImg()
	print(test.getInfo(test.arr[2]['info']))
	#print(test.getPicNum('http://img3.douban.com/view/photo/photo/public/p2218744059.jpg'))


