-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project: Phase E due May 11, 2021 --

-- Removes all procedures
DROP PROCEDURE IF EXISTS Query1;
DROP PROCEDURE IF EXISTS Query2;
DROP PROCEDURE IF EXISTS Query3;
DROP PROCEDURE IF EXISTS Query4;
DROP PROCEDURE IF EXISTS Query5;
DROP PROCEDURE IF EXISTS Query6;
DROP PROCEDURE IF EXISTS ShowCountryGDP;
DROP PROCEDURE IF EXISTS ShowCountryImports;
DROP PROCEDURE IF EXISTS ShowCountryExports;
DROP PROCEDURE IF EXISTS TopPolluter;
DROP PROCEDURE IF EXISTS Top5ExportGrowthSectorsForLargestPolluter;
DROP PROCEDURE IF EXISTS ShowUSTopExportGrowths;
DROP PROCEDURE IF EXISTS ShowUSBottomExportGrowths;
DROP PROCEDURE IF EXISTS TopImportersLessEconomicallyComplex;
DROP PROCEDURE IF EXISTS TopBilateralPartnerSector;
-- Removes all tables
DROP TABLE IF EXISTS GDP;
DROP TABLE IF EXISTS TradeFlows;
DROP TABLE IF EXISTS US_Leaders;
DROP TABLE IF EXISTS Climate;
DROP TABLE IF EXISTS Economic_Complexity;
DROP TABLE IF EXISTS Sectors;
DROP TABLE IF EXISTS Countries;

CREATE TABLE Countries (
  country_name  VARCHAR(50),
  continent_name  VARCHAR(50),
  PRIMARY KEY(country_name)
);

CREATE TABLE Sectors (
  sector    VARCHAR(50),
  PRIMARY KEY(sector)
);

CREATE TABLE GDP (
  country_name  VARCHAR(50),
  year          SMALLINT,
  gdp           BIGINT,
  FOREIGN KEY(country_name) REFERENCES Countries(country_name) ON DELETE CASCADE  ON UPDATE CASCADE,
  PRIMARY KEY(country_name, year)
);

CREATE TABLE TradeFlows (
  country_origin_name  VARCHAR(50),
  country_dest_name    VARCHAR(50),
  export_value         BIGINT,
  sector               VARCHAR(50),
  year                 SMALLINT,
  PRIMARY KEY(country_origin_name, country_dest_name, sector, year),
  FOREIGN KEY(sector) REFERENCES Sectors(sector) ON DELETE CASCADE  ON UPDATE CASCADE,
  FOREIGN KEY(country_origin_name) REFERENCES Countries(country_name) ON DELETE CASCADE  ON UPDATE CASCADE,
  FOREIGN KEY(country_dest_name) REFERENCES Countries(country_name) ON DELETE CASCADE  ON UPDATE CASCADE
);

CREATE TABLE US_Leaders (
  leader_name   VARCHAR(50),
  start_year    SMALLINT,
  end_year      SMALLINT,
  PRIMARY KEY(leader_name),
  CHECK(start_year < end_year)
);

CREATE TABLE Climate (
  country_name  VARCHAR(50),
  year SMALLINT,
  co2_emissions DECIMAL(8,3),
  PRIMARY KEY(country_name, year),
  FOREIGN KEY(country_name) REFERENCES Countries(country_name) ON DELETE CASCADE  ON UPDATE CASCADE
);

CREATE TABLE Economic_Complexity (
  year          SMALLINT,
  country_name  VARCHAR(50),
  econ_comp_index DECIMAL(3,2),
  PRIMARY KEY(country_name, year),
  FOREIGN KEY(country_name) REFERENCES Countries(country_name) ON DELETE CASCADE  ON UPDATE CASCADE
);

LOAD DATA LOCAL INFILE './countries.txt' INTO TABLE Countries
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './sectors.txt' INTO TABLE Sectors
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './gdp_data.txt' INTO TABLE GDP
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './trade_flows.txt' INTO TABLE TradeFlows
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './US_leaders.txt' INTO TABLE US_Leaders
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './climate_data.txt' INTO TABLE Climate
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

LOAD DATA LOCAL INFILE './economic_complexity.txt' INTO TABLE Economic_Complexity
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES;
