#!/usr/bin/python

import numpy as np
from sklearn.manifold import TSNE
from sklearn.decomposition import PCA
import math
def correlation(x,y):
	x_mean = np.mean(x)
	y_mean = np.mean(y)
	r = 1 - abs(np.dot((x-x_mean),(y-y_mean)))/math.sqrt(np.dot((x-x_mean),(x-x_mean)) * np.dot((y-y_mean),(y-y_mean)))
	return r
def tsne(X,d):
	shape = X.shape
	d_org = shape[1]
	pca = PCA(n_components=30)
	if d>30:
		X_pca = pca.fit_transform(X)
	else:
		X_pca = X
	model = TSNE(n_components=d, metric = correlation, perplexity=10,learning_rate=100,n_iter=10000,random_state=0)
	mappedX = model.fit_transform(X_pca)
	return mappedX

def groupDataByLabel(X,Y,n):
	shape = X.shape
	count = np.zeros((n,)).astype(int)
	for y in Y:
		for j in range(n):
			if y == j:
				count[j]+=1
				break
	index = np.zeros((n,)).astype(int)
	i=0
	for j in range(n):
		index[j]=i
		i=i+count[j]

	X_new = np.zeros(shape)
	Y_new = np.zeros((len(Y),))
	index_temp = np.array(index)
	for k in range(len(Y)):
		for j in range(n):
			if Y[k] == j:
				X_new[index_temp[j],:]=X[k,:]
				Y_new[index_temp[j]]=Y[k]
				index_temp[j]+=1
	return X_new,Y_new,index

def reduceDimension(X,Y,d,n,outputX='None',outputY='None',outputIndex='None'):
	# d: dimension after mapping
	# n: number of subtypes
	mappedX = tsne(X,d)
	X_g, Y_g, index = groupDataByLabel(mappedX,Y,n)
	if outputX != 'None':
		np.savetxt(outputX,X_g,delimiter=',')
	if outputY != 'None':
		np.savetxt(outputY,Y_g,delimiter=',')
	if outputIndex != 'None':
		np.savetxt(outputIndex,index,delimiter=',')
