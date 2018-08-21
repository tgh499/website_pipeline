#' Calculates the relevance of this gene in regards to the survival of a patient.
#' @param geneID the gene ID we want to consider
#' @param datasetID the dataset which we want to use to compute this
#' @param cutoff Specify the cutoff to use when segmenting the samples. Default is to use "median". 
#' Other options would be to use "mean" or "cluster". Otherwise, you can specify the numerical cutoff value 
#' yourself and that value will be used.
#' @return a list of statistical information regarding the relevance of this gene to survival.
#' @author Jeffrey D. Allen \email{Jeffrey.Allen@@UTSouthwestern.edu}
#' @author Guanghua Xiao \email{Guanghua.Xiao@@UTSouthwestern.edu}
survivalAnalysisOrig <- function(geneID, datasetID, cutoff){
  library(survival)
  library(lcdb)
  library(probemapper)
  library(Cairo)
  library(png)
  
  PMServer <- SOAPServer(SOAP_SERVER, "ProbeMapperWS/services/ProbeMapperSOAP", SOAP_SERVER_PORT)
  LCDBServer <- lcdb.server(SOAP_SERVER, port=SOAP_SERVER_PORT)
  
  #Right now just take any probe listed from any authority. Eventually, we'll probably want to use consensus or just BLAST
  
  ##### COLLECT ROW NUMBERS????

  vals <- lcdb.getValues(LCDBServer, DatasetID = datasetID, EntrezID = geneID, PMServer = PMServer)
  if (nrow(vals) == 0 || all(is.na(vals))){
    stop("No data found for this gene in this dataset.")
  }
  
  #chomp the prefixed character off of the column names
  valNames <- substr(names(vals), 2, nchar(names(vals)))
  
  ##### GET SAMPLES FROM DATABASE
  ### I CAN START FROM HERE
  samples <- lcdb.getSamples(LCDBServer, SampleID=valNames)
  samples <- samples[as.integer(samples$Tissue)==1,]
  

  ### DELETE UNNECESSARY SAMPLES
  #only keep those samples which have NormID=2
  valsToKeep <- which(valNames%in%samples$ID)
  valNames <- valNames[valsToKeep]
  vals <- vals[,valsToKeep]
  
  pats <- lcdb.getPatients(LCDBServer, PatientID = samples$PatientID);
  
  colnames(samples) <- paste("Sam", colnames(samples), sep=".")
  
  dat.fun <- merge(pats, samples, by.x="ID", by.y="Sam.PatientID")
  dat.fun$expr <- NA
  dat.fun$expr[match(valNames, dat.fun$Sam.ID)] <- unlist(vals)
  
  if(all(is.na(dat.fun$OverallSurvivalMonths))){
    stop("This dataset doesn't appear to have survival information. Please select a dataset with survival information.")
  }
  
  dat_mean <- mean(dat.fun$expr, na.rm=TRUE)
  dat_median <- median(dat.fun$expr, na.rm=TRUE)
  cutoff_type <- ""
  
  ### define high expression vs. low expresion group ###
  if(!missing(cutoff)){
    if (cutoff == "median"){
      cutoff <- dat_median
      cutoff_type <- "median"
    }
    else if (cutoff == "mean"){
      cutoff <- dat_mean
      cutoff_type <- "mean"
    }
    else if (cutoff == "cluster"){
    	library(mclust)
    	mc <- Mclust(as.vector(vals), G=2)
    	
    	min.max <- min(max(vals[mc$classification == 1]), max(vals[mc$classification == 2]))
    	max.min <- max(min(vals[mc$classification == 1]), min(vals[mc$classification == 2]))                       
    	cutoff <- (min.max + max.min)/2
    	cutoff_type <- "cluster"    	
    }
    else if (is.numeric(cutoff)){
      cutoff <- as.numeric(cutoff)
      cutoff_type <- "custom"
    }
    else{
      stop("Invalid cutoff specified.")
    }
  } else{
    cutoff <- dat_median
  }
  
  dat.fun$group <- dat.fun$expr > cutoff
  
  
  fit <- survfit(Surv(OverallSurvivalMonths, Died) ~ group, data = dat.fun )

  ## density plot ##
  dens <- density(na.omit(dat.fun$expr),na.rm = True)
  hist <- list()
  hist$x <- dens$x
  hist$y <- dens$y
  
  
  ### get the p value ###	
  logrank <- survdiff(Surv(OverallSurvivalMonths, Died) ~ group, data = dat.fun )
  pv <- pchisq(logrank$chisq,1, lower.tail=F)

  ### initialize Cairo device so we can write plot to memory ###
  Cairo(file='/dev/null', width=520, height=520, units="px", pointsize=12)
  
  par(mar=c(4.5,4.5,1,12))
  plot(fit, col=1:2, 
  		 mark = 19, 
  		 xlab="Survival Time (months)", 
  		 ylab="Overall Survival", 
  		 cex.lab=1.4, bty="L")
  
  text(50, 0, paste("P-Value:", sprintf("%.3f", pv)));
  par(xpd=TRUE)
  legend("topright", inset=c(-0.5,0),
  			 legend=c("Low", "High"), 
  			 col=1:2, 
  			 lwd=2, xpd=TRUE)
  
  ### grab binary data (byte array) from Cairo ###
  cairoImage = Cairo:::.image(dev.cur())
  cairoRaw   = Cairo:::.ptr.to.raw(cairoImage$ref, 0, cairoImage$width * cairoImage$height * 4)
  dim(cairoRaw) = c(4, cairoImage$width, cairoImage$height) # RGBA planes
  cairoRaw[c(1,3),,] = cairoRaw[c(3,1),,] # swap red & blue components
  pngBytes = writePNG(cairoRaw, raw()) # get data

  dev.off()
  
  coxph.fit <- coxph(Surv(OverallSurvivalMonths, Died) ~ group, data = dat.fun )
  
  res <- lapply(as.list(c(diff.pv=pv, median=dat_median, mean=dat_mean, cutoff=cutoff, cutoff_type=cutoff_type, summary(coxph.fit)$coef[1,-2])), as.character)
  #names(res) <- c("diff.pv","coef","coef.se","z","cox.pv")
  
  toReturn <- list(results=res, density=hist, image=pngBytes)
  
  return(toReturn)
}