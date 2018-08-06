'''
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt


N = 35238
#x = np.random.rand(N)
#y = np.random.rand(N)
colors = np.random.rand(N)
area = 50  # 0 to 15 point radii

gene0 = pd.read_csv('gene0.csv', header=None)
gene0 = gene0.T
gene0 = gene0.as_matrix()


gene1 = pd.read_csv('gene1.csv', header=None)
gene1 = gene1.T
gene1 = gene1.as_matrix()



plt.title('Legend inside')

#print(x)
plt.scatter(gene0[0], gene1[0], s=area, c=colors, alpha=0.4)
plt.savefig('scatter.png')



print("Hello!")

'''

import sys
import re
import pandas as pd
import numpy as np
import json
import matplotlib.pyplot as plt


def main():
	N = 35238
	#x = np.random.rand(N)
	#y = np.random.rand(N)
	colors = np.random.rand(N)
	area = 50  # 0 to 15 point radii

	gene0 = pd.read_csv('gene0.csv', header=None)
	gene0 = gene0.T
	gene0 = gene0.values


	gene1 = pd.read_csv('gene1.csv', header=None)
	gene1 = gene1.T
	gene1 = gene1.values

	dataPoints = 35000

	gene0_curtailed = gene0[0][:dataPoints]
	gene1_curtailed = gene1[0][:dataPoints]

	print(len(gene0_curtailed))


	jsonList = []
	for i in range(0,len(gene0_curtailed)):
	    jsonList.append({"x" : gene0_curtailed[i], "y" : gene1_curtailed[i]})

	#print(json.dumps(jsonList, indent = 1))
	with open('data.json', 'w') as outfile:
		json.dump(jsonList, outfile)

	colors = 2
	area = 10  # 0 to 15 point radii

	plt.scatter(gene0[0], gene1[0])
	plt.savefig('scatter.png')



if __name__ == "__main__":
	main()


