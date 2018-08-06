import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd

temp = [1,2,3,4,5,6,7]

gene0 = pd.read_csv('samp0.csv', header=None)
gene0 = gene0.T
gene0 = gene0.values


gene1 = pd.read_csv('samp1.csv', header=None)
gene1 = gene1.T
gene1 = gene1.values

print(gene0[0])

sns.distplot(gene0[0], hist=True, kde=True, 
           	 color = 'darkblue', 
             hist_kws={'edgecolor':'black'},
             kde_kws={'linewidth': 4})

sns.distplot(gene1[0], hist=True, kde=True, 
             color = 'green', 
             hist_kws={'edgecolor':'red'},
             kde_kws={'linewidth': 4})

plt.title('Density Plot of Samples')
plt.xlabel('range')
plt.ylabel('density')
plt.savefig('density.png')