# three requirements: geneID, datasetID, groups


param3 = read.csv("param0.csv", header=FALSE)
param2 = read.csv("param1.csv", header=FALSE)

# just in case col dimensions do not match
#param1 <- param2[1:(length(param2)-1)]

param1 <- param2[1:1000]
param0 <- param3[1:1000]


jpeg('boxplot.png')
boxplot(c(param0, param1),
  col=(c("gold","darkgreen")), outline = TRUE,
  main="Age in male vs female", xlab="Age Boxplot")
dev.off()