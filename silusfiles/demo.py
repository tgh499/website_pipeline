#!/usr/bin/python

# OK
import data_parsing as dp
import numpy as np

# NOT OK
from geneSelection import getGeneSigniture
from visualization import reduceDimension


print("\nThis is awesome!")
print("Hi Great~")


X,Y,gene,patient = dp.loadData_RNAseq('coding')
signiture, accuracy, X_sel = getGeneSigniture(X,Y,gene, getMatrix = True)
# For 2D result, set d to 2. For 3D result, set d to 3
reduceDimension(X_sel,Y,d=2,n=5,outputX='mappedX.csv',outputY='Y.csv',outputIndex='Y_index.csv')
# This will generate data in low dimension. For visualization, use matlab code.

print("THE END")
