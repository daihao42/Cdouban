#!/usr/bin/env python
#-*-coding:utf8-*-

#电子邮箱不存在
class MyConfig:
	def __init__(self):
		self.Something_Wrong = 698
		'''
			登陆
			'''
		self.Login_OK = 699
		#电子邮箱不存在
		self.Email_Is_Not_Vaild = 770
		#用户密码错误
		self.Passwd_Is_Wrong = 771
		'''
			注册
			'''
		self.Register_OK = 772
		#电子邮箱已经使用
		self.Email_Has_Used = 773
		#用户名已存在
		self.User_Name_Has_Used = 774


