-- Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1) --
-- Project Phase E due May 11, 11 p.m. --

DELIMITER //

DROP PROCEDURE IF EXISTS TopPolluter //

CREATE PROCEDURE TopPolluter(IN Year_Chosen SMALLINT)
BEGIN
   SELECT country_name FROM Climate WHERE co2_emissions = (SELECT MAX(co2_emissions) FROM Climate WHERE year = Year_Chosen);
END; //

DELIMITER ;

DELIMITER //

DROP PROCEDURE IF EXISTS Top5ExportGrowthSectorsForLargestPolluter //

CREATE PROCEDURE Top5ExportGrowthSectorsForLargestPolluter(IN Year_Chosen SMALLINT)
BEGIN
        WITH CurrentYearExportData AS 
                (SELECT sector, SUM(export_value) AS CurrentYearExports
                FROM TradeFlows
                WHERE country_origin_name = (SELECT country_name FROM Climate WHERE co2_emissions = 
                (SELECT MAX(co2_emissions) FROM Climate WHERE year = Year_Chosen)) AND year = Year_Chosen
                GROUP BY sector),
             NextYearExportData AS 
                (SELECT sector, SUM(export_value) AS NextYearExports
                FROM TradeFlows
                WHERE country_origin_name = (SELECT country_name FROM Climate WHERE co2_emissions = 
                (SELECT MAX(co2_emissions) FROM Climate WHERE year = Year_Chosen)) AND year = Year_Chosen + 1
                GROUP BY sector)
        SELECT CurrentYearExportData.sector, CurrentYearExports, NextYearExports, NextYearExports-CurrentYearExports AS NominalGrowth
        FROM CurrentYearExportData, NextYearExportData
        WHERE CurrentYearExportData.sector = NextYearExportData.sector
        ORDER BY NominalGrowth DESC
        LIMIT 5;
END; //

DELIMITER ;
