#!/usr/bin/python

import numpy as np

def takeColumn(fileName,column,skipHeader=1,delimiter='\t'):
	input_file = open(fileName,'r')
	item_list = []
	for h in range(skipHeader):
		line = input_file.readline()
	for line in input_file:
		line = line.split(delimiter)
		item = line[column-1]
		item_list.append(item)
	return item_list


def getPatientSubtype(fileName,delimiter,p,s):
	patient_subtype = {}
	input_file = open(fileName,'r')
	line=input_file.readline()
	for line in input_file:
		line = line.replace('\n','').split(delimiter)
		patient = line[p]
		if fileName == 'data/BRCA_clinicalMatrix_Xena_0323.csv':
			patient = patient.replace('-','.')
		subtype = line[s]
		if subtype == 'Normal':
			label = 0
		elif subtype == 'Basal':
			label = 1
		elif subtype == 'Her2':
			label = 2
		elif subtype == 'LumA':
			label = 3
		elif subtype == 'LumB':
			label = 4
		else:
			label = 5
		patient_subtype[patient] = label
	input_file.close()
	return patient_subtype

#'data/BRCA_clinicalMatrix_Xena_0323.csv',',',0,19

def getGeneTypeById():
	geneTypeById = {}
	input_file = open('data/gencode.v23.chr_patch_hapl_scaff.annotation.gtf','r')
	for i in range(5):
		line = input_file.readline()
	for line in input_file:
		i = i+1
		line = line.split('\t')
		annotation = line[8].replace('\"','')
		info = annotation.split(';')
		l = len(info)
		gene_id = info[0]
		gene_id = gene_id.split(' ')
		if (gene_id[0] == 'gene_id'):
			ID = gene_id[1]
		else:
			ID = 'N/A'
			print ('id error at line ' + str(i))

		for j in range(1,l):
			text = info[j].split(' ')
			if (text[1] == 'gene_type'):
				t = text[2]
				break
		if j==l-1:
			t = 'N/A'
			print ('type error at line ' + str(i))

		geneTypeById[ID] = t
	input_file.close()
	return geneTypeById

def get_RNAseq_genes(geneType):
	allGenes = takeColumn('data/probeMap.txt',1)
	sel_genes = []
	gene_type = getGeneTypeById()
	if geneType == 'coding':
		for g in allGenes:
			if gene_type[g] == 'protein_coding':
				sel_genes.append(g)
	else:
		for g in allGenes:
			if gene_type[g] != 'protein_coding':
				sel_genes.append(g)
	return sel_genes


def getGeneIdByName():
	geneIdByName = {}
	input_file = open('data/gencode.v23.chr_patch_hapl_scaff.annotation.gtf','r')
	for i in range(5):
		line = input_file.readline()
	for line in input_file:
		i = i+1
		line = line.split('\t')
		annotation = line[8].replace('\"','')
		info = annotation.split(';')
		l = len(info)
		gene_id = info[0]
		gene_id = gene_id.split(' ')
		if (gene_id[0] == 'gene_id'):
			ID = gene_id[1]
		else:
			ID = 'N/A'
			print ('id error at line ' + str(i))
		for j in range(1,l):
			text = info[j].split(' ')
			if (text[1] == 'gene_name'):
				name = text[2]
				break
		if j==l-1:
			name = 'N/A'
			print ('name error at line ' + str(i))
		if geneIdByName.has_key(name):
			l = geneIdByName[name]
			if ID not in l:
				geneIdByName[name].append(ID)
		else:
			geneIdByName[name] = [ID]
	input_file.close()
	return geneIdByName

def getPAM50_name():
	pam50_name = []
	input_file = open('data/PAM50_gene_list.txt','r')
	for line in input_file:
		pam50_name.append(line.replace('\n',''))
	input_file.close()
	return pam50_name

def getPAM50_id():
	pam50_id = []
	pam50_name = getPAM50_name()
	geneIdByName = getGeneIdByName()
	for name in pam50_name:
		if geneIdByName.has_key(name):
			id_list = geneIdByName[name]
			for gene_id in id_list:
				pam50_id.append(gene_id)
	return pam50_id

def getDifferentialGene():
	input_file = open('data/differential_genes.csv','r')
	header = input_file.readline()
	geneList = []
	for line in input_file:
		gene = line.split(',')[0]
		geneList.append(gene)
	input_file.close()
	return geneList

def getDataByGene(geneList,probeMapFile,dataFile,delimiter,c_off=1):
	if probeMapFile != 'None':
		probeMap = {}
		probe = [' ']
		input_file = open(probeMapFile,'r')
		header = input_file.readline()
		i = 1
		for line in input_file:
			geneId = line.split('\t')[0]
			probeMap[geneId] = i
			probe.append(geneId)
			i = i+1
		input_file.close()
		indexList=[]
		if geneList != 'None':
			for gene in geneList:
				if probeMap.has_key(gene):
					index = probeMap[gene]
					indexList.append(index)
		else:
			indexList=probeMap.values()

		indexList.sort()
		gene_n = len(indexList)
		input_file = open(dataFile,'r')
		header = input_file.readline()
		patient_n = len(header.split(delimiter))-c_off
		data_matrix = np.zeros((gene_n,patient_n))
		i = 1
		j = 0
		for line in input_file:
			if i == indexList[j]:
				line=line.replace('\n','').split(delimiter)[c_off:]
				data_matrix[j] = np.genfromtxt(line)
				j = j+1
			i = i+1
			if j == gene_n:
				break
		input_file.close()
		new_geneList = []
		for index in indexList:
			new_geneList.append(probe[index])
	else:
		input_file = open(dataFile,'r')
		header = input_file.readline()
		patient_n = len(header.split(delimiter))-c_off
		if geneList != 'None':
			gene_n = len(geneList)
		else:
			count=0
			for line in input_file:
				count += 1
			gene_n = count
			input_file.close()
			input_file = open(dataFile,'r')
			header = input_file.readline()

		data_matrix = np.zeros((gene_n,patient_n))
		i=0
		new_geneList=[]
		for line in input_file:
			line=line.replace('\n','').split(delimiter)
			gene = line[0]
			if (geneList == 'None' or gene in geneList) and not missingData(line[c_off:]):
				data_matrix[i] = np.genfromtxt(line[c_off:])
				new_geneList.append(gene)
				i=i+1
				if i==gene_n:
					break
		input_file.close()
		actual_gene_len = len(new_geneList)
		data_matrix = data_matrix[:actual_gene_len,:]

	return data_matrix,new_geneList

def missingData(list):
	missing = False
	for item in list:
		if item == 'NA':
			missing = True
	return missing

def getPatientId(fileName,delimiter,replaceDash=False,c_off=1):
	input_file = open(fileName,'r')
	header = input_file.readline()
	input_file.close()
	if replaceDash:
		header = header.replace('-','.')
	patient = header.replace('\n','').replace('\"','').split(delimiter)[c_off:]
	return patient

def removeNormalSample(data_matrix,fileName,delimiter):
	patient = getPatientId(fileName,delimiter,True)
	tumorIndex = []
	tumorId = []
	i = 0
	for p_id in patient:
		if p_id.split('.')[3] == '01':
			tumorIndex.append(i)
			tumorId.append(p_id)
		i = i+1
	new_matrix = data_matrix[:,tumorIndex]
	return new_matrix, tumorId

def removeUnlabledSample(data_matrix,patient_id_list,fileName):
	delimiter =','
	if fileName == 'data/BRCA_clinicalMatrix_Xena_0323.csv':
		s=19
	else:
		s=20
	patient_subtype = getPatientSubtype(fileName,delimiter,0,s)
	label = []
	index = []
	new_patient_id = []
	i = 0
	for patient in patient_id_list:

		l = patient_subtype[patient]
		if l != 5:
			label.append(l)
			index.append(i)
			new_patient_id.append(patient)
		i = i+1
	new_matrix = data_matrix[:,index]
	label=np.array(label)
	return new_matrix,new_patient_id,label


def loadData_RNAseq_pam50():
	geneList = getPAM50_id()
	data, new_geneList = getDataByGene(geneList,'data/probeMap.txt','data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_normal, patient = removeNormalSample(data,'data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient,'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList

def loadData_RNAseq_differential():
	geneList = getDifferentialGene()
	data, new_geneList = getDataByGene(geneList,'data/probeMap.txt','data/ucsc_BRCA_RNAseq_count.csv',',')
	codingList = []
	codingIndex = []
	non_codingList = []
	non_codingIndex = []
	data_rm_normal, patient = removeNormalSample(data,'data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient,'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	id_type = getGeneTypeById()
	i = 0
	for gene in new_geneList:
		t = id_type[gene]
		if t == 'protein_coding':
			codingList.append(gene)
			codingIndex.append(i)
		else:
			non_codingList.append(gene)
			non_codingIndex.append(i)
		i = i+1
	X_coding = X[:,codingIndex]
	X_non_coding = X[:,non_codingIndex]
	return X,label, new_geneList, X_coding, codingList, X_non_coding, non_codingList

def loadData_RNAseq_pam50AndDf():
	pam50_list = getPAM50_id()
	df_list = getDifferentialGene()
	union_list = list(set().union(pam50_list,df_list))
	data, new_geneList = getDataByGene(union_list,'data/probeMap.txt','data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_normal, patient = removeNormalSample(data,'data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient,'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList

def loadData_RNAseq_pam50AndDf_coding():
	pam50_list = getPAM50_id()
	X_df,Y,df_genes, X_coding, codingGenes, X_non_coding, non_codingGenes = loadData_RNAseq_differential()
	union_list = list(set().union(pam50_list,codingGenes))
	data, new_geneList = getDataByGene(union_list,'data/probeMap.txt','data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_normal, patient = removeNormalSample(data,'data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient, 'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList

def loadData_RNAseq(geneType='None'):
	# geneType can be 'None', 'coding', 'nonCoding'
	if geneType == 'None':
		geneList = 'None'
	else:
		geneList = get_RNAseq_genes(geneType)
	data, new_geneList = getDataByGene(geneList,'data/probeMap.txt','data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_normal, patient = removeNormalSample(data,'data/ucsc_BRCA_RNAseq_count.csv',',')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient, 'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList,patient_id

def loadData_microarray(geneList='None',identifier='name'):
	if identifier == 'id':
		geneName = id2name(geneList)
	else:
		geneName = geneList
	data,new_geneList = getDataByGene(geneName,'None','data/AgilentG4502A_07_3','\t')
	data_rm_normal, patient = removeNormalSample(data,'data/AgilentG4502A_07_3','\t')
	data_rm_unLabel, patient_id, label = removeUnlabledSample(data_rm_normal,patient,'data/BRCA_clinicalMatrix_Xena_0323.csv')
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList,patient_id

def loadData_microarray_pam50():
	pam50 = getPAM50_name()
	X,label,new_geneList,patient_id = loadData_microarray(pam50,'name')
	return X,label,new_geneList,patient_id

def loadData_brca_metabric(geneList='None'):
	data,new_geneList = getDataByGene(geneList,'None','data/brca_metabric/data_expression.txt','\t',2)
	patient = getPatientId('data/brca_metabric/data_expression.txt','\t',c_off=2)
	data_rm_unLabel,patient_id,label = removeUnlabledSample(data,patient,'data/brca_metabric/data_clinical.csv',)
	X = np.transpose(data_rm_unLabel)
	X = normalize(X)
	return X,label,new_geneList

def loadData_TCGA_integrated():
	X_seq,Y_seq,coding_seq,p_seq = loadData_RNAseq('coding')
	X_mic,Y_mic,coding_mic,p_mic = loadData_microarray()
	n_g_seq = X_seq.shape[1]
	n_g_mic = X_mic.shape[1]
	n_g = n_g_seq+n_g_mic
	p_seq_d = {}
	p_mic_d = {}
	for i in range(len(p_seq)):
		p_seq_d[p_seq[i]]=i
	for i in range(len(p_mic)):
		p_mic_d[p_mic[i]]=i
	p_set = set(p_seq).intersection(set(p_mic))
	n_p = len(p_set)
	X = np.zeros((n_p,n_g))
	p_list = []
	i=0
	for p in p_set:
		p_list.append(p)
		s=p_seq_d[p]
		m=p_mic_d[p]
		X[i,:n_g_seq]=X_seq[s,:]
		X[i,n_g_seq:]=X_mic[m,:]
		i=i+1

	patient_subtype = getPatientSubtype('data/BRCA_clinicalMatrix_Xena_0323.csv',',',0,19)
	label = []
	for p in p_list:
		label.append(patient_subtype[p])
	Y=np.array(label)
	geneList = coding_seq+coding_mic

	return X,Y,geneList


def normalize(X):
	maxInX = np.max(X)
	X_norm = X/maxInX
	return X_norm

def getGeneNameById():
	geneNameById = {}
	input_file = open('data/gencode.v23.chr_patch_hapl_scaff.annotation.gtf','r')
	for i in range(5):
		line = input_file.readline()
	for line in input_file:
		i = i+1
		line = line.split('\t')
		annotation = line[8].replace('\"','')
		info = annotation.split(';')
		l = len(info)
		gene_id = info[0]
		gene_id = gene_id.split(' ')
		if (gene_id[0] == 'gene_id'):
			ID = gene_id[1]
		else:
			ID = 'N/A'
			print ('id error at line ' + str(i))
		for j in range(1,l):
			text = info[j].split(' ')
			if (text[1] == 'gene_name'):
				name = text[2]
				break
		if j==l-1:
			name = 'N/A'
			print ('name error at line ' + str(i))
		if geneNameById.has_key(ID):
			if name != geneNameById[ID]:
				print (ID+' exists with name ' + geneNameById[ID] + ', new name: ' + name)
		else:
			geneNameById[ID] = name
	input_file.close()
	return geneNameById

def id2name(id_list):
	name_list=[]
	geneNameById=getGeneNameById()
	for gene in id_list:
		name = geneNameById[gene]
		name_list.append(name)
	return name_list
