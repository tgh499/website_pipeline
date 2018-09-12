# three requirements: geneID, datasetID, groups
library("readr")

param0 = read.csv("param0.csv", header=FALSE)
param2 = read.csv("param1.csv", header=FALSE)

# just in case col dimensions do not match
#param1 <- param2[1:(length(param2)-1)]

param1 <- param2[1:(length(param2)-1)]

param0_name <- read_file("param0.txt")
param1_name <- read_file("param1.txt")

text = paste(param0_name, "vs", param1_name, sep=" ")
xLab_text = paste(param0_name, "Boxplot", sep=" ")

jpeg('boxplot.png')
boxplot(c(param0, param1),
  col=(c("gold","darkgreen")), outline = TRUE,
  main=text, xlab=xLab_text)
dev.off()