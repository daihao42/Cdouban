#!/usr/bin/env python
#-*-coding:utf8-*-
from flask.ext.script import Manager,Shell
from myapp import app

manager = Manager(app)
#manager.add_command("shell", Shell(make_context=make_shell_context))

if __name__ == '__main__':
	manager.run()