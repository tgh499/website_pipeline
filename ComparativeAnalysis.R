#' Comparative Expression between two groups of patients with the specified tissue types
#'
#' Compare two groups of patients and plot their expression on the selected gene. Look up the samples associated with these patients using the default Normalization filter on getSamples()
#' @param geneID the Entrez ID of the gene on which we want to do the analysis
#' @param datasetID the ID of the dataset to use for this analysis
#' @param groups a list describing which patients are in which groups. The names of this list should correspond to the names of the group, and the contents should be vectors of the ID of the patients
#' @param tissue a list of the tissue types (numeric) that should be included in the results.
#' @return the p-value of the difference between the two groups' expression of the selected gene.
#' @author Jeffrey D. Allen \email{Jeffrey.Allen@@UTSouthwestern.edu}
#' @edited Jonathan Yoder \email{Jonathan.Yoder@@UTSouthwestern.edu} on 2013-02-08

comparativeExpPatientsTissue <- function(geneID, datasetID, groups){
	library(lcdb)
	# LCDBServer <- lcdb.server(SOAP_SERVER, port=SOAP_SERVER_PORT)
	samGroups <- list()
	for (i in 1:length(groups)){
		samples <- lcdb.getSamples(LCDBServer, PatientID=groups[[i]]$items)
		
		# (temporarily) Remove all normal tissue 
		if (!is.null(groups[[i]]$tissues)){
			samples <- samples[as.integer(samples$Tissue) %in% groups[[i]]$tissues,]
		}
		samGroups[[names(groups[i])]] <- samples
	}
	comparativeExp(geneID, datasetID, lapply(samGroups, "[[", "ID"))
}

#' Comparative Expression between two groups of patients
#'
#' Compare two groups of patients and plot their expression on the selected gene. Look up the samples associated with these patients using the default Normalization filter on getSamples()
#' @param geneID the Entrez ID of the gene on which we want to do the analysis
#' @param datasetID the ID of the dataset to use for this analysis
#' @param groups a list describing which patients are in which groups. The names of this list should correspond to the names of the group, and the contents should be vectors of the ID of the patients
#' @return the p-value of the difference between the two groups' expression of the selected gene.
#' @author Jeffrey D. Allen \email{Jeffrey.Allen@@UTSouthwestern.edu}
comparativeExpPatients <- function(geneID, datasetID, groups){
	library(lcdb)
	LCDBServer <- lcdb.server(SOAP_SERVER, port=SOAP_SERVER_PORT)
	#TODO: consolidate into single request
	samGroups <- list()
	for (i in 1:length(groups)){
		samples <- lcdb.getSamples(LCDBServer, PatientID=groups[[i]])
		
		# (temporarily) Remove all normal tissue 
		samples <- samples[as.integer(samples$Tissue)==1,]
		samGroups[[names(groups[i])]] <- samples
	}
	comparativeExp(geneID, datasetID, lapply(samGroups, "[[", "ID"))
}



#' Comparative Expression between two groups of samples
#'
#' Compare two groups of samples and plot their expression on the selected gene.
#' @param geneID the Entrez ID of the gene on which we want to do the analysis
#' @param datasetID the ID of the dataset to use for this analysis
#' @param groups a list describing which samples are in which groups. The names of this list should correspond to the names of the group, and the contents should be vectors of the ID of the samples
#' @return the p-value of the difference between the two groups' expression of the selected gene.
#' @author Jeffrey D. Allen \email{Jeffrey.Allen@@UTSouthwestern.edu}
comparativeExp <- function(geneID, datasetID, groups){	
	groupExp <- comparativeExpGroups(geneID, datasetID, groups)
	return(groupExp)
}


#' Calculates a string-formatted pvalue
#' @param x ?
#' @param digits the number of digits to be included in the string output
#' @return ?
#' @author Guanghua Xiao \email{Guanghua.Xiao@@UTSouthwestern.edu}
pv.expr <- function(x, digits = 2) {
	if (!x)
		return(0)
	exponent <- floor(log10(x))
	base <- round(x/10^exponent, digits)
	ifelse(x > 1e-06, paste("pv = ", base * (10^exponent), sep = ""),
				 paste("pv = ", base, "E", exponent, sep = ""))
}



#' Comparative Expression between multiple groups
#'
#' Compare multiple groups of patients and plot their expression on the selected gene.
#' @param geneID the Entrez ID of the gene on which we want to do the analysis
#' @param datasetID the ID of the dataset to use for this analysis
#' @param groups a list describing which samples are in which groups. The names of this list should correspond to the names of the group, and the contents should be vectors of the ID of the samples
#' @return the p-value of the difference between the two groups' expression of the selected gene.
#' @author Jeffrey D. Allen \email{Jeffrey.Allen@@UTSouthwestern.edu}
comparativeExpGroups <- function(geneID, datasetID, groups){
	library(lcdb)
	library(probemapper)
    library(Cairo)
    library(png)
	LCDBServer <- lcdb.server(SOAP_SERVER, port=SOAP_SERVER_PORT)
	PMServer <- SOAPServer(SOAP_SERVER, "ProbeMapperWS/services/ProbeMapperSOAP", SOAP_SERVER_PORT)	
	
	group.names <- groupExp <- list()
		
	#TODO: Consolidate into a single request.
	for (groupID in 1:length(groups)){
		sam <- groups[[groupID]]		
		groupExp[[groupID]] <- as.numeric(lcdb.getValues(LCDBServer, EntrezID=geneID, SampleID = sam, DatasetID = datasetID, PMServer=PMServer))
		group.names[[groupID]] <- rep(names(groups)[groupID], length(groupExp[[groupID]]))
	}
	names(groupExp) <- names(groups)
	
	if (length(groups) > 1){
		fit <- anova(lm(unlist(groupExp) ~ as.factor(unlist(group.names))))
		pv <- (fit$"Pr(>F)")[1]	
	}	else{
		pv <- NA
	}

    ### initialize Cairo device so we can write plot to memory ###
    Cairo(file='/dev/null', width=620, height=620, units="px", pointsize=12)
		
	boxplot(groupExp, names=paste(names(groupExp), " - ", sapply(groupExp, function(x){sum(!is.na(x))}), " Samples", sep=""), boxwex = 0.5)
	
    ### grab binary data (byte array) from Cairo ###
    cairoImage = Cairo:::.image(dev.cur())
    cairoRaw   = Cairo:::.ptr.to.raw(cairoImage$ref, 0, cairoImage$width * cairoImage$height * 4)
    dim(cairoRaw) = c(4, cairoImage$width, cairoImage$height) # RGBA planes
    cairoRaw[c(1,3),,] = cairoRaw[c(3,1),,] # swap red & blue components
    pngBytes = writePNG(cairoRaw, raw()) # get data

	dev.off()

	return(list(pv=pv, image=pngBytes))
}

comparativeExpGroups()



