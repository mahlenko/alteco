#Import Packages
library(rvest)
## Loading required package: xml2
library(selectr)
library(xml2)
library(dplyr)
##
## Attaching package: 'dplyr'
## The following objects are masked from 'package:stats':
##
##     filter, lag
## The following objects are masked from 'package:base':
##
##     intersect, setdiff, setequal, union
library(stringr)
library(readxl)
library(readr)
##
## Attaching package: 'readr'
## The following object is masked from 'package:rvest':
##
##     guess_encoding
library(PerformanceAnalytics)
## Loading required package: xts
## Loading required package: zoo
##
## Attaching package: 'zoo'
## The following objects are masked from 'package:base':
##
##     as.Date, as.Date.numeric
## Registered S3 method overwritten by 'xts':
##   method     from
##   as.zoo.xts zoo
##
## Attaching package: 'xts'
## The following objects are masked from 'package:dplyr':
##
##     first, last
##
## Attaching package: 'PerformanceAnalytics'
## The following object is masked from 'package:graphics':
##
##     legend
library(dplyr)
library(tidyverse)
## Registered S3 methods overwritten by 'ggplot2':
##   method         from
##   [.quosures     rlang
##   c.quosures     rlang
##   print.quosures rlang
## -- Attaching packages ----------------------------------------------------------------------------------------- tidyverse 1.2.1 --
## v ggplot2 3.1.1     v tidyr   0.8.3
## v tibble  2.1.1     v purrr   0.3.2
## v ggplot2 3.1.1     v forcats 0.4.0
## -- Conflicts -------------------------------------------------------------------------------------------- tidyverse_conflicts() --
## x dplyr::filter()         masks stats::filter()
## x xts::first()            masks dplyr::first()
## x readr::guess_encoding() masks rvest::guess_encoding()
## x dplyr::lag()            masks stats::lag()
## x xts::last()             masks dplyr::last()
## x purrr::pluck()          masks rvest::pluck()
library(lubridate)
##
## Attaching package: 'lubridate'
## The following object is masked from 'package:base':
##
##     date
library(xlsx)
#Parsing CoinMarketCap
OPER <- read_html("https://coinmarketcap.com/all/views/all/")
C<-html_table(OPER)
C1 <- C[[1]]
C1 <- C1[ ,1:10]
C1 = C1 %>%
  filter(C1$`#` < 201)
C1$Price <-gsub("\\$", "", C1$Price)
C1$`Market Cap` <- gsub("\\D", "", C1$`Market Cap`)
C1$`Circulating Supply` <- gsub("\\D", "", C1$`Circulating Supply`)
C1$`Volume (24h)` <- gsub("\\D", "", C1$`Volume (24h)`)
C1$Name <- sub("(.+?)\\w+", "", C1$Name)
C1$Name <- gsub(" ", "-", C1$Name)
C1$Name <- gsub("^.-", "", C1$Name)
C1$Name <- gsub("\n", "",C1$Name)
C1$Name <- gsub("XRP", "Ripple", C1$Name)
C1$Name <- gsub("IOST", "Iostoken", C1$Name)
C1$Name <- gsub("Crypto.com-Chain", "Crypto-com-chain", C1$Name)
C1$Name <- gsub("Basic-Attenti...", "Basic-attention-token", C1$Name)
C1$Name <- gsub("-Ethereum-Classic", "Ethereum-classic", C1$Name)
C1 <- subset(C1, C1$Name != "HedgeTrade")
C1 <- subset(C1, C1$Name != "Bytecoin")
C1 <- subset(C1, C1$Symbol != "R")
C1$Name <- gsub("Paxos-Standar...", "Paxos-standard-token", C1$Name)
C1$Name <- gsub("Metaverse-ETP", "Metaverse", C1$Name)
C1$Name <- gsub("Crypto.com", "Crypto-com", C1$Name)
C1$Name <- gsub("Nebulas", "Nebulas-token", C1$Name)
C1$Name <- gsub("Santiment-Net...", "Santiment", C1$Name)
C1$Name <- gsub("IHT-Real-Esta...", "Iht-real-estate-protocol", C1$Name)
#Benchmark Set
crix <- read_csv("C:/Users/NICEMANBOSS/Desktop/Работа/По криптам/crix.csv",
                 col_types = cols(date = col_date(format = "%Y-%m-%d"),
                                  price = col_number()))
crix <- crix[1600:nrow(crix),]
#Creating result matrixes
NameMatrix <- matrix()
ValueMatrix <- data.frame()
ModMatrix <- data.frame()
TreynorMatrix <- data.frame()
CalmarMatrix <- data.frame()
MatrixJensen <- data.frame()
#Cycle of code with Coeffs
i <- 1
j <- C1$Name[[i]]

for (j in i:100)
{
  crix <- read_csv("C:/Users/NICEMANBOSS/Desktop/Работа/По криптам/crix.csv",
                   col_types = cols(date = col_date(format = "%Y-%m-%d"),
                                    price = col_number()))

 crix <- crix[1600:nrow(crix),]

url <- 'https://coinmarketcap.com/ru/currencies/'
url <- paste0(url,C1$Name[[i]],"/historical-data/?start=20130428&end=20190628")
print(url)
print(C1$Name[i])
OPER2 <- read_html(url)
OPER3 <- html_table(OPER2)
OPER3 <- OPER3[[1]]
OPER3$`Закрытия**` <- as.numeric(OPER3$`Закрытия**`)
OPER3$`Открытия*` <- NULL
OPER3$Максимальная <- NULL
OPER3$Минимальная <- NULL
OPER3$Объем <- NULL
OPER3$`Рыночная капитализация` <- NULL

names(OPER3)[1] <- "Date"
names(OPER3)[2] <- "Close"

OPER3$Date <- as.Date(OPER3$Date, format = "%d.%m.%Y")
OPER3 <- OPER3[nrow(OPER3):1,]

t <- try(crix<-crix[which(crix$date==min(OPER3$Date)):nrow(crix),])
if("try-error" %in% class(t)) {OPER3<-OPER3[which(OPER3$Date==min(crix$date)):nrow(OPER3),]}
OPER3<-OPER3[1:which(OPER3$Date==max(crix$date)),]

OPER3 <- xts(OPER3[, -1], order.by = as.Date(OPER3$Date))
crix<- xts(crix[, -1], order.by = as.Date(crix$date))

R<- Return.calculate(OPER3, method = "discrete")
R <- R[2:nrow(R), ]
RC <- Return.calculate(crix, method = "discrete")
RC <- RC[2:nrow(RC), ]
RCmean <- mean(RC)*30.5
RMean <- mean(R)*30.5
Bez = 9.8/12/100

BetaCov <- (cov(R,RC))
BetaVar <- (StdDev(RC))*(StdDev(RC))
Beta <- BetaCov/BetaVar
JensenIndex <- RMean - (Bez + (RCmean - Bez)*Beta)

###Modelian###

StdDev.annualized(R)
StdDev.annualized(RC)
Modelian = (((RMean - Bez)*StdDev.annualized(RC))/StdDev.annualized(R))+ Bez

###Treinor###

Treynor = (RMean - Bez)/BetaVar/1000

###Calmar###, zhelatelno vishe 3!!!

maxDrawdown(R)
Calmar = RMean*12/maxDrawdown(R)

###Matrix###
NameMatrix <- rbind(NameMatrix,C1$Name[[i]])
ValueMatrix <- rbind(ValueMatrix,JensenIndex)
ModMatrix <- rbind(ModMatrix,Modelian)
TreynorMatrix <- rbind(TreynorMatrix,Treynor)
CalmarMatrix <- rbind(CalmarMatrix,Calmar)

rm(Bez,RCmean,RMean,Beta,BetaCov,BetaVar, R, RC)

i <- i+1}
## [1] "https://coinmarketcap.com/ru/currencies/Bitcoin/historical-data/?start=20130428&end=20190628"
## [1] "Bitcoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ethereum/historical-data/?start=20130428&end=20190628"
## [1] "Ethereum"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ripple/historical-data/?start=20130428&end=20190628"
## [1] "Ripple"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Litecoin/historical-data/?start=20130428&end=20190628"
## [1] "Litecoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Bitcoin-Cash/historical-data/?start=20130428&end=20190628"
## [1] "Bitcoin-Cash"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/EOS/historical-data/?start=20130428&end=20190628"
## [1] "EOS"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Binance-Coin/historical-data/?start=20130428&end=20190628"
## [1] "Binance-Coin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Tether/historical-data/?start=20130428&end=20190628"
## [1] "Tether"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Bitcoin-SV/historical-data/?start=20130428&end=20190628"
## [1] "Bitcoin-SV"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/TRON/historical-data/?start=20130428&end=20190628"
## [1] "TRON"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Cardano/historical-data/?start=20130428&end=20190628"
## [1] "Cardano"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Stellar/historical-data/?start=20130428&end=20190628"
## [1] "Stellar"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/UNUS-SED-LEO/historical-data/?start=20130428&end=20190628"
## [1] "UNUS-SED-LEO"
## [1] "https://coinmarketcap.com/ru/currencies/Monero/historical-data/?start=20130428&end=20190628"
## [1] "Monero"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Dash/historical-data/?start=20130428&end=20190628"
## [1] "Dash"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Chainlink/historical-data/?start=20130428&end=20190628"
## [1] "Chainlink"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/NEO/historical-data/?start=20130428&end=20190628"
## [1] "NEO"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/IOTA/historical-data/?start=20130428&end=20190628"
## [1] "IOTA"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Cosmos/historical-data/?start=20130428&end=20190628"
## [1] "Cosmos"
## [1] "https://coinmarketcap.com/ru/currencies/Ethereum-Classic/historical-data/?start=20130428&end=20190628"
## [1] "Ethereum-Classic"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Tezos/historical-data/?start=20130428&end=20190628"
## [1] "Tezos"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/NEM/historical-data/?start=20130428&end=20190628"
## [1] "NEM"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Zcash/historical-data/?start=20130428&end=20190628"
## [1] "Zcash"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ontology/historical-data/?start=20130428&end=20190628"
## [1] "Ontology"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Maker/historical-data/?start=20130428&end=20190628"
## [1] "Maker"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Crypto-com-chain/historical-data/?start=20130428&end=20190628"
## [1] "Crypto-com-chain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Qtum/historical-data/?start=20130428&end=20190628"
## [1] "Qtum"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Bitcoin-Gold/historical-data/?start=20130428&end=20190628"
## [1] "Bitcoin-Gold"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/VeChain/historical-data/?start=20130428&end=20190628"
## [1] "VeChain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Basic-attention-token/historical-data/?start=20130428&end=20190628"
## [1] "Basic-attention-token"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Dogecoin/historical-data/?start=20130428&end=20190628"
## [1] "Dogecoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/USD-Coin/historical-data/?start=20130428&end=20190628"
## [1] "USD-Coin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/OmiseGO/historical-data/?start=20130428&end=20190628"
## [1] "OmiseGO"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/V-Systems/historical-data/?start=20130428&end=20190628"
## [1] "V-Systems"
## [1] "https://coinmarketcap.com/ru/currencies/Decred/historical-data/?start=20130428&end=20190628"
## [1] "Decred"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/BitTorrent/historical-data/?start=20130428&end=20190628"
## [1] "BitTorrent"
## [1] "https://coinmarketcap.com/ru/currencies/Holo/historical-data/?start=20130428&end=20190628"
## [1] "Holo"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/TrueUSD/historical-data/?start=20130428&end=20190628"
## [1] "TrueUSD"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Huobi-Token/historical-data/?start=20130428&end=20190628"
## [1] "Huobi-Token"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Bitcoin-Diamond/historical-data/?start=20130428&end=20190628"
## [1] "Bitcoin-Diamond"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/HyperCash/historical-data/?start=20130428&end=20190628"
## [1] "HyperCash"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Egretia/historical-data/?start=20130428&end=20190628"
## [1] "Egretia"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ravencoin/historical-data/?start=20130428&end=20190628"
## [1] "Ravencoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Lisk/historical-data/?start=20130428&end=20190628"
## [1] "Lisk"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Pundi-X/historical-data/?start=20130428&end=20190628"
## [1] "Pundi-X"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Waves/historical-data/?start=20130428&end=20190628"
## [1] "Waves"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Aurora/historical-data/?start=20130428&end=20190628"
## [1] "Aurora"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/0x/historical-data/?start=20130428&end=20190628"
## [1] "0x"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Qubitica/historical-data/?start=20130428&end=20190628"
## [1] "Qubitica"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Nano/historical-data/?start=20130428&end=20190628"
## [1] "Nano"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Bytom/historical-data/?start=20130428&end=20190628"
## [1] "Bytom"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/BitShares/historical-data/?start=20130428&end=20190628"
## [1] "BitShares"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Augur/historical-data/?start=20130428&end=20190628"
## [1] "Augur"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/MonaCoin/historical-data/?start=20130428&end=20190628"
## [1] "MonaCoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Paxos-standard-token/historical-data/?start=20130428&end=20190628"
## [1] "Paxos-standard-token"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Komodo/historical-data/?start=20130428&end=20190628"
## [1] "Komodo"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Iostoken/historical-data/?start=20130428&end=20190628"
## [1] "Iostoken"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/ThoreCoin/historical-data/?start=20130428&end=20190628"
## [1] "ThoreCoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/DigiByte/historical-data/?start=20130428&end=20190628"
## [1] "DigiByte"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Zilliqa/historical-data/?start=20130428&end=20190628"
## [1] "Zilliqa"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Energi/historical-data/?start=20130428&end=20190628"
## [1] "Energi"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/KuCoin-Shares/historical-data/?start=20130428&end=20190628"
## [1] "KuCoin-Shares"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Metaverse/historical-data/?start=20130428&end=20190628"
## [1] "Metaverse"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/ICON/historical-data/?start=20130428&end=20190628"
## [1] "ICON"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Mixin/historical-data/?start=20130428&end=20190628"
## [1] "Mixin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/ABBC-Coin/historical-data/?start=20130428&end=20190628"
## [1] "ABBC-Coin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Siacoin/historical-data/?start=20130428&end=20190628"
## [1] "Siacoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/GXChain/historical-data/?start=20130428&end=20190628"
## [1] "GXChain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Aeternity/historical-data/?start=20130428&end=20190628"
## [1] "Aeternity"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Verge/historical-data/?start=20130428&end=20190628"
## [1] "Verge"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Steem/historical-data/?start=20130428&end=20190628"
## [1] "Steem"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ardor/historical-data/?start=20130428&end=20190628"
## [1] "Ardor"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Lambda/historical-data/?start=20130428&end=20190628"
## [1] "Lambda"
## [1] "https://coinmarketcap.com/ru/currencies/VestChain/historical-data/?start=20130428&end=20190628"
## [1] "VestChain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/MaidSafeCoin/historical-data/?start=20130428&end=20190628"
## [1] "MaidSafeCoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Insight-Chain/historical-data/?start=20130428&end=20190628"
## [1] "Insight-Chain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Dent/historical-data/?start=20130428&end=20190628"
## [1] "Dent"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/aelf/historical-data/?start=20130428&end=20190628"
## [1] "aelf"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Nash-Exchange/historical-data/?start=20130428&end=20190628"
## [1] "Nash-Exchange"
## [1] "https://coinmarketcap.com/ru/currencies/THETA/historical-data/?start=20130428&end=20190628"
## [1] "THETA"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Status/historical-data/?start=20130428&end=20190628"
## [1] "Status"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Crypto-com/historical-data/?start=20130428&end=20190628"
## [1] "Crypto-com"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Enjin-Coin/historical-data/?start=20130428&end=20190628"
## [1] "Enjin-Coin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/EDUCare/historical-data/?start=20130428&end=20190628"
## [1] "EDUCare"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Golem/historical-data/?start=20130428&end=20190628"
## [1] "Golem"
## [1] "https://coinmarketcap.com/ru/currencies/SOLVE/historical-data/?start=20130428&end=20190628"
## [1] "SOLVE"
## [1] "https://coinmarketcap.com/ru/currencies/Dai/historical-data/?start=20130428&end=20190628"
## [1] "Dai"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Zcoin/historical-data/?start=20130428&end=20190628"
## [1] "Zcoin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Quant/historical-data/?start=20130428&end=20190628"
## [1] "Quant"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Stratis/historical-data/?start=20130428&end=20190628"
## [1] "Stratis"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Ren/historical-data/?start=20130428&end=20190628"
## [1] "Ren"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Maximine-Coin/historical-data/?start=20130428&end=20190628"
## [1] "Maximine-Coin"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Waltonchain/historical-data/?start=20130428&end=20190628"
## [1] "Waltonchain"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/WAX/historical-data/?start=20130428&end=20190628"
## [1] "WAX"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Nebulas-token/historical-data/?start=20130428&end=20190628"
## [1] "Nebulas-token"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Elastos/historical-data/?start=20130428&end=20190628"
## [1] "Elastos"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/NULS/historical-data/?start=20130428&end=20190628"
## [1] "NULS"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Project-Pai/historical-data/?start=20130428&end=20190628"
## [1] "Project-Pai"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Santiment/historical-data/?start=20130428&end=20190628"
## [1] "Santiment"
## Error in which(crix$date == min(OPER3$Date)):nrow(crix) :
##   аргумент нулевой длины
## [1] "https://coinmarketcap.com/ru/currencies/Grin/historical-data/?start=20130428&end=20190628"
## [1] "Grin"
#MatrixResult
NameMatrix <- NameMatrix[2:nrow(NameMatrix)]
MatrixJensen <- cbind(NameMatrix,ValueMatrix,ModMatrix,TreynorMatrix, CalmarMatrix)

MatrixJensen <- as.data.frame(MatrixJensen)
names(MatrixJensen)[1] <- "Name"
names(MatrixJensen)[2] <- "JensenIndex"
names(MatrixJensen)[3] <- "Modelian"
names(MatrixJensen)[4] <- "Treynor"
names(MatrixJensen)[5] <- "Calmar"
rownames(MatrixJensen) <- NULL

MatrixJensen = MatrixJensen %>%
  mutate(DateStart = index(OPER3[1])) %>%
  mutate(DateEnd = index(OPER3[nrow(OPER3)]))


View(MatrixJensen)
#Tuned MatrixResult
Mat <- MatrixJensen
Mat = Mat %>%
  filter(Calmar > 9.0) %>%
  filter(JensenIndex > 0.44)
View(Mat)
