library(survminer)
library(RTCGA.clinical)
library(survival)

times = read.csv("surv0.csv", header=FALSE)
times <- as.numeric(unlist(times))
patient.vital_status = read.csv("surv1.csv", header=FALSE)
patient.vital_status <- as.double(unlist(patient.vital_status))
admin.disease_code = read.csv("surv2.csv", header=FALSE)
admin.disease_code <- as.character(unlist(admin.disease_code))


dat <- list(times, patient.vital_status, admin.disease_code)

jpeg('surv.png')
fit <- survfit(Surv(times, patient.vital_status) ~ admin.disease_code)
# Visualize with survminer
#ggsurvplot(fit, data =  dat)

ggsurvplot(
   fit,                     # survfit object with calculated statistics.
   data = BRCAOV.survInfo,  # data used to fit survival curves. 
   risk.table = TRUE,       # show risk table.
   pval = TRUE,             # show p-value of log-rank test.
   conf.int = TRUE,         # show confidence intervals for 
                            # point estimaes of survival curves.
   xlim = c(0,2000),        # present narrower X axis, but not affect
                            # survival estimates.
   break.time.by = 500,     # break X axis in time intervals by 500.
   ggtheme = theme_minimal(), # customize plot and risk table with a theme.
 risk.table.y.text.col = T, # colour risk table text annotations.
  risk.table.y.text = FALSE # show bars instead of names in text annotations
                            # in legend of risk table
)
dev.off()