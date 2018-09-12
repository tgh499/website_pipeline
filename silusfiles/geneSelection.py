#!/usr/bin/python

print("foo")
from sklearn.svm import LinearSVC
from sklearn.model_selection import KFold
import numpy as np
print("bar")

# Prameters:
# X: numpy ndarrapy.
#  	 data matrix. Each column is a patient and each row is a gene
# Y: numpy ndarray
# 	 patient subtype label
# geneId: list
#    the name of gene for each row in X
#
# Returns:
# gene_sel: dictionary {gene:subtype_list}
# 	 gene: String
#    subtype_list: list
# 		subtype(s) associated with gene
# test_accuracy: float
#	 average 10-fold cross validation (10 times)
def getGeneSigniture(X,Y,geneId, getMatrix = False):
	X = normalize(X)
	X_sel,geneId_sel,gene_sel,train_acc = recursiveSelection(X,Y,geneId)
	test_accuracy = tenFoldCV(X_sel,Y,1,10)
	if getMatrix == False:
		return gene_sel,test_accuracy
	else:
		return gene_sel, test_accuracy, X_sel


# Helper functions
def normalize(X):
	maxInX = np.max(X)
	X_norm = X/maxInX
	return X_norm

def tenFoldCV(X,Y,c,n):
	CV=KFold(n_splits=10, random_state=None, shuffle=True)
	#c=0.5
	clf=LinearSVC(C=c,max_iter=1e5,dual=False, tol=1e-05,penalty='l2',random_state=10)

	tenFoldAccuracy = np.zeros((n,))
	for i in range(n):

		accuracy = np.zeros((10,))
		j = 0
		for train,test in CV.split(X):
		    clf.fit(X[train],Y[train])
		    accuracy[j] = clf.score(X[test],Y[test])
		    j = j+1
		tenFoldAccuracy[i] = np.mean(accuracy)
		#print "Ten fold accuracy: " + str(tenFoldAccuracy[i])
	avg = np.mean(tenFoldAccuracy)
	error = np.std(tenFoldAccuracy)
	return avg,error

def geneSelection(X,Y,c,geneId):
	clf=LinearSVC(C=c,max_iter=1e5,dual=False, tol=1e-05,penalty='l1',random_state=10)
	clf.fit(X,Y)
	trainning_accuracy = clf.score(X,Y)
	feature={}
	d=X.shape[1]
	for c in range(5):
	    for f in range(d):
	        if clf.coef_[c,f]!=0:
	            if feature.has_key(f):
	                feature[f].append(c)
	            else:
	                feature[f]=[c]

	selInx = feature.keys()
	selInx.sort()
	X_sel = X[:,selInx]
	geneId_sel = []
	gene_sel = {}
	for i in selInx:
		geneId_sel.append(geneId[i])
		gene_sel[geneId[i]]=feature[i]

	return X_sel,geneId_sel,trainning_accuracy,gene_sel

def reduceGeneByC(X,Y,geneId,start,stop,step):
	length = len(np.arange(start,stop,step))
	c_list=np.zeros((length,))
	numberOfGenes=np.zeros((length,)).astype(int)
	trainning_accuracy=np.zeros((length,))
	test_accuracy=np.zeros((length,))
	i=0
	for c in np.arange(start,stop,step):
		X_sel,geneId_sel,trainningAccuracy,g_sel = geneSelection(X,Y,c,geneId)
		c_list[i]=c
		numberOfGenes[i]=len(geneId_sel)
		trainning_accuracy[i]=trainningAccuracy
		testAccuracy, std = tenFoldCV(X_sel,Y,1,10)
		test_accuracy[i]=testAccuracy
		print (str(c)+": "+" number of genes: "+str(len(geneId_sel))+" trainning accuracy: "+str(trainningAccuracy)+" test accuracy: "+str(testAccuracy))
		i=i+1
	return c_list,numberOfGenes,trainning_accuracy,test_accuracy

def findCutOff(c_list,accuracy,cutOff=0.9):
	cutOff_accuracy = np.max(accuracy)*cutOff
	n = len(accuracy)
	i = n-1
	while (True):
		if accuracy[i]<=cutOff_accuracy:
			break
		else:
			i = i-1
		if i<=0:
			break
	return c_list[i]

def recursiveSelection(X,Y,geneId,max_iter=2):
	iteration=1
	start = 0.1
	stop = 1.1
	step = 0.1
	X_sel = X
	geneId_sel = geneId
	while (iteration<max_iter):
		c_list,n_genes,trainning_accuracy,test_accuracy = reduceGeneByC(X,Y,geneId,start,stop,step)
		c = findCutOff(c_list,trainning_accuracy)
		X_sel,geneId_sel,train_acc,gene_sel = geneSelection(X_sel,Y,c,geneId_sel)
		start = 0.1*start
		stop = c
		step = (stop-start)/10
		iteration = iteration+1

	c_list,n_genes,trainning_accuracy,test_accuracy = reduceGeneByC(X_sel,Y,geneId_sel,start,stop,step)

	n = len(n_genes)
	for i in range(n):
		if n_genes[i]<=50 and n_genes[i+1]>50:
			break
	if n_genes[i] != 50:
		start = c_list[i]
		stop = c_list[i+1]
		step = (stop-start)/10
		c_list,n_genes,trainning_accuracy,test_accuracy = reduceGeneByC(X_sel,Y,geneId_sel,start,stop,step)
		n = len(n_genes)
		for i in range(n):
			if n_genes[i]<=50 and n_genes[i+1]>50:
				break
	c = c_list[i]
	X_sel,geneId_sel,train_acc,gene_sel = geneSelection(X_sel,Y,c,geneId_sel)
	return X_sel,geneId_sel,gene_sel,train_acc
