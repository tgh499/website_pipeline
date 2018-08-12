# three requirements: geneID, datasetID, groups


dat = read.csv("tcga.csv", header = TRUE)

boxplot(dat$diagnosisAge~dat$gender, data=dat, 
  col=(c("gold","darkgreen")),
  main="Age in male vs female", xlab="Age Boxplot")
