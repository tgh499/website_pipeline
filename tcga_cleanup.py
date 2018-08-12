# -*- coding: utf-8 -*-

import pandas as pd
import numpy as np


df = pd.read_csv('TCGA_BRCA.csv')
headers = df.columns
data = df.values

#print(len(headers))
# rows =  1105
# cols = 140
print(len(data))

'''
maxLen = []
for i,j in enumerate(data):
	temp = []
	for k,l in enumerate(j):
		print(len(l))
		#temp.append(len(l))
	#maxLen.append(temp)
'''

newHeaders = []

for i in headers:
	newHeaders.append(str(i))

for i,j in enumerate(newHeaders):
	if len(j) > 50:
		temp = str(j)
		temp1 = temp[:50]
		newHeaders[i] = temp1

for i in headers:
	print(i)

for i in newHeaders:
	print(i)

data = pd.DataFrame(data)

data.to_csv('tcga.csv', encoding='utf-8', index=False, header=newHeaders)


