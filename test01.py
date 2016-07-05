#!/usr/bin/python
# -*- coding: utf-8 -*-
import fpformat
test = '<WHYPO WORD=“さようなら” CLASSID=“さようなら” PHONE=“silB s a y o u n a r a sliE” CM=“0.994"/>'
test01 = test[-8:-3]

test02 = float(test01)

print test02
print test02 + 1